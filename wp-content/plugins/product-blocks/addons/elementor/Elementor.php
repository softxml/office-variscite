<?php
defined('ABSPATH') || exit;

final class Elementor_WOPB_Extension {

    private static $_instance = null;

    public static function instance() {
        if (is_null( self::$_instance ) ) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    public function __construct() {
        $this->init();
    }

    public function init() {
        add_action( 'elementor/widgets/register', [ $this, 'register_widgets' ] );
        add_action( 'elementor/frontend/after_register_scripts', [ $this, 'widget_scripts' ] );
        add_action( 'elementor/frontend/after_enqueue_styles', [ $this, 'widget_styles' ] );
    }

    public function widget_styles() {
        wp_enqueue_style('dashicons');
        wp_enqueue_style('wopb-slick-style', WOPB_URL.'assets/css/slick.css', array(), WOPB_VER);
        wp_enqueue_style('wopb-slick-theme-style', WOPB_URL.'assets/css/slick-theme.css', array(), WOPB_VER);
        if(is_rtl()){ 
            wp_enqueue_style('wopb-blocks-rtl-css', WOPB_URL.'assets/css/rtl.css', array(), WOPB_VER); 
        }
        wp_enqueue_style('wopb-style', WOPB_URL.'assets/css/blocks.style.css', array(), WOPB_VER );
        wp_enqueue_style('wopb-css', WOPB_URL.'assets/css/wopb.css', array(), WOPB_VER );
    }

    public function widget_scripts() {
        global $post;
        wp_enqueue_script('wopb-slick-script', WOPB_URL.'assets/js/slick.min.js', array('jquery'), WOPB_VER, true);
        wp_enqueue_script('wopb-flexmenu-script', WOPB_URL.'assets/js/flexmenu.min.js', array('jquery'), WOPB_VER, true);
        if (has_block('product-blocks/cart-total', $post)) {
            wp_enqueue_script('wc-cart');
        }
        if (has_block('product-blocks/checkout-order-review', $post)) {
            wp_enqueue_script('wc-checkout');
        }
        wp_enqueue_script('wopb-script', WOPB_URL.'assets/js/wopb.js', array('jquery','wopb-flexmenu-script','wp-api-fetch'), WOPB_VER, true);
        $wopb_core_localize = array(
            'url' => WOPB_URL,
            'ajax' => admin_url('admin-ajax.php'),
            'security' => wp_create_nonce('wopb-nonce'),
            'isActive' => wopb_function()->isActive(),
            'currency_symbol' => class_exists( 'WooCommerce' ) && is_plugin_active( 'woocommerce/woocommerce.php' ) ? get_woocommerce_currency_symbol() : '' ,
            'currency_position' => get_option( 'woocommerce_currency_pos' ),
            'errorElementGroup' => [
                'errorElement' => '<div class="wopb-error-element"></div>'
            ],
            'taxonomyCatUrl' => admin_url( 'edit-tags.php?taxonomy=category' )
        );
        $wopb_core_localize = array_merge($wopb_core_localize, wopb_function()->get_endpoint_urls());
        wp_localize_script('wopb-script', 'wopb_core', $wopb_core_localize);
    }

    public function includes() {
        require_once WOPB_PATH . 'addons/elementor/Elementor_Widget.php';
    }

    public function register_widgets() {
        $this->includes();
        \Elementor\Plugin::instance()->widgets_manager->register( new \ProductX_Widget() );
    }
}