<?php
/**
 * Initialization Action.
 * 
 * @package WOPB\Notice
 * @since v.1.1.0
 */
namespace WOPB;

defined('ABSPATH') || exit;

/**
 * Initialization class.
 */
class Initialization{

    /**
	 * Setup class.
	 *
	 * @since v.1.1.0
	 */
    public function __construct(){
        $this->compatibility_check();
        $this->requires();
        $this->include_addons(); // Include Addons

        add_filter( 'block_categories_all', array( $this, 'register_category_callback' ), 10, 1 ); // Block Category Register
        add_action( 'enqueue_block_editor_assets', array( $this, 'register_scripts_back_callback' ) ); // Only editor
        add_action( 'admin_enqueue_scripts', array( $this, 'register_scripts_option_panel_callback' ) ); // Option Panel
        add_action( 'wp_enqueue_scripts', array( $this, 'register_scripts_front_callback' ) ); // Both frontend
        add_action( 'wp_footer', array( $this, 'footer_modal_callback' ) ); // Footer Text Added
//        add_action( 'in_plugin_update_message-' . WOPB_BASE, array( $this, 'plugin_settings_update_message' ) ); // Show changelog in Plugin
        add_filter( 'woocommerce_available_variation', array( $this, 'available_variation' ), 100, 3 );
        add_action( 'template_redirect', array( $this, 'popular_posts_tracker_callback' ) ); // Popular Post Callback

        register_activation_hook( WOPB_PATH . 'product-blocks.php', array( $this, 'install_hook' ) ); // Initial Activation Call

        add_action( 'init', array($this, 'init_site') );
        add_action( 'admin_init',                    array($this, 'admin_init_functionalities'));
    }

    /**
	 * Theme Switch Callback
     * 
     * @since v.3.1.16
	 * @return NULL
	 */
    public function admin_init_functionalities () {
        $this->handle_wizard_redirect(); // redirect to either home or wizard page
    }

    /**
	 * Theme Switch Callback
     * 
     * @since v.3.1.16
	 * @return NULL
	 */
    public function handle_wizard_redirect () {
        if ( !get_transient('wopb_activation_redirect') ) {  // set transient to show wizard
            return ;
        }
        if ( wp_doing_ajax() ) {
            return;
        }
        delete_transient( 'wopb_activation_redirect' );
        
        if ( is_network_admin() || isset($_GET['activate-multi']) ) {
            return ;
        }

        if ( get_option('wopb_setup_wizard_data', '') != 'yes' ) {
            update_option('wopb_setup_wizard_data', 'yes');
            exit( wp_safe_redirect( admin_url( 'admin.php?page=wopb-initial-setup-wizard' ) ) ); //phpcs:ignore
        }
    }

    /**
     * Filter Hook for Available Variation
     *
     * @since v.2.7.1
     * @param $variation_data
     * @param $product
     * @param $variation
     * @return ARRAY
     */
    public function available_variation($variation_data, $product, $variation) {
        $variation_data['wopb_deal'] = wopb_function()->get_deals($variation, 'Days|Hours|Minutes|Seconds');
        return $variation_data;
    }

    /**
	 * Include Addons Main File
     * 
     * @since v.1.1.0
	 * @return NULL
	 */
	public function include_addons() {
        if ( wopb_function()->is_wc_ready() ) {
            global $wopb_default_settings; // This is global variable for default settings
            $wopb_default_settings = array(); // This is global settings array, should not change this array
            $addons_dir = array_filter( glob( WOPB_PATH . 'addons/*' ), 'is_dir' );
            if ( count( $addons_dir ) > 0 ) {
                foreach ( $addons_dir as $key => $value ) {
                    $addon_dir_name = str_replace( dirname( $value ) . '/', '', $value );
                    $file_name = WOPB_PATH . 'addons/' . $addon_dir_name . '/init.php';
                    if ( file_exists( $file_name ) ) {
                        include_once $file_name;
                    }
                }
            }
        }
    }

    /**
	 * Footer Modal Callback
     * 
     * @since v.1.1.0
	 * @return NULL
	 */
    public function footer_modal_callback(){
        if( apply_filters('wopb_active_modal', false) ) {
?>
        <div class="wopb-modal-wrap">
            <div class="wopb-modal-overlay"></div>
            <div class="wopb-modal-content"></div>
            <div class="wopb-modal-loading">
                <div class="wopb-loading">
                    <?php
                        $modal_loaders = wopb_function()->modal_loaders();
                        foreach ( $modal_loaders as $key => $loader ) {
                            echo '<span class="wopb-loader wopb-d-none ' . esc_attr( $key ) . '">';
                            if(($key == 'loader_2') || ($key == 'loader_3')) {
                                for ($i=0;$i<=11;$i++) {
                                    echo '<div></div>';
                                }
                            }else if ($key == 'loader_4') {
                                ?>
                                <span class="dot_line"></span>
                                <svg width="100" height="100" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M6 7V5.5C6 4.67157 6.67157 4 7.5 4H16.5C17.3284 4 18 4.67157 18 5.5V8.15071C18 8.67761 17.7236 9.16587 17.2717 9.43695L13.7146 11.5713C13.3909 11.7655 13.3909 12.2345 13.7146 12.4287L17.2717 14.563C17.7236 14.8341 18 15.3224 18 15.8493V18.5C18 19.3284 17.3284 20 16.5 20H7.5C6.67157 20 6 19.3284 6 18.5V15.8493C6 15.3224 6.27645 14.8341 6.72826 14.563L10.2854 12.4287C10.6091 12.2345 10.6091 11.7655 10.2854 11.5713L6.72826 9.43695C6.27645 9.16587 6 8.67761 6 8.15071V8" stroke="white" />
                                </svg>
                                <?php
                            }else if($key == 'loader_5') {
                                for ($i=0;$i<=14;$i++) {
                                    echo '<div style="--index:' . esc_attr( $i ) . '"></div>';
                                }
                            }
                            echo '</span>';
                        }
                    ?>
                </div>
            </div>
            <div class="wopb-after-modal-content"></div>
        </div>
<?php
        }

    }

    /**
	 * Option Panel Enqueue Script 
     * 
     * @since v.1.0.0
	 * @return NULL
	 */
    public function register_scripts_option_panel_callback( $screen ) {
        global $post;
        global $wopb_default_settings;
        $is_active = wopb_function()->is_lc_active();
        $post_id = isset( $post->ID ) ? $post->ID : '';
        $post_type = isset( $post->post_type ) ? $post->post_type : '';
        $_page = isset( $_GET['page'] ) ? sanitize_text_field( $_GET['page'] ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
        $license_key = get_option( 'edd_wopb_license_key' );

        // Custom Font Support Added
        $font_settings = wopb_function()->get_setting('wopb_custom_font');
        $custom_fonts = array();
        if ( $font_settings == 'true' ) {
            $args = array(
                'post_type'              => 'wopb_custom_font',
                'post_status'            => 'publish',
                'numberposts'            => 333,
                'order'                  => 'ASC'
            );
            $posts = get_posts( $args );
            if ( $posts ) {
                foreach ( $posts as $post_data ) {
                    setup_postdata( $post );
                    $font = get_post_meta( $post_data->ID , '__font_settings', true );
                    if ( $font ) {
                        array_push( $custom_fonts, array(
                            'title' => $post_data->post_title,
                            'font' => $font
                        ));
                    }
                }
                wp_reset_postdata();
            }
        }

        wp_enqueue_media();

        $taxonomy = isset( $_GET['taxonomy'] ) ? sanitize_text_field( $_GET['taxonomy'] ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
        if ( $taxonomy == 'pa_color' ) {
            wp_enqueue_style('wp-color-picker');
            wp_enqueue_script('wp-color-picker');
        }

        wp_enqueue_script('wopb-option-script', WOPB_URL.'assets/js/wopb-option.js', array('jquery'), WOPB_VER, true);
        wp_enqueue_style('wopb-option-style', WOPB_URL.'assets/css/wopb-option.css', array(), WOPB_VER);
        wp_localize_script( 'wopb-option-script', 'wopb_option', array(
            'url' => WOPB_URL,
            'version' => WOPB_VER,
            'active' => $is_active,
            'width' => wopb_function()->get_setting('editor_container'),
            'security' => wp_create_nonce('wopb-nonce'),
            'ajax' => admin_url('admin-ajax.php'),
            'settings' => wopb_function()->get_setting(),
            'affiliate_id' => apply_filters( 'wopb_affiliate_id', false ),
            'post_type' => $post_type,
            'saved_template_url' => admin_url('admin.php?page=wopb-settings#saved-templates'),
            'custom_fonts' => $custom_fonts
        ));

        if ( $_page == 'wopb-settings' || $post_type == 'wopb_builder' ) { // Conditions JS
            wp_enqueue_script('wopb-conditions-script', WOPB_URL . 'addons/builder/assets/js/conditions.min.js', array('wp-api-fetch', 'wp-components', 'wp-i18n', 'wp-blocks'), WOPB_VER, true);
            wp_localize_script('wopb-conditions-script', 'wopb_condition', array(
                'url' => WOPB_URL,
                'active' => $is_active,
                'license' => $is_active ? get_option('edd_wopb_license_key') : '',
                'builder_url' => admin_url('admin.php?page=wopb-settings#builder'),
                'builder_type' => $post_id ? get_post_meta( $post_id, '_wopb_builder_type', true ) : ''
            ));
        } else if ( $_page == 'wopb-initial-setup-wizard' ) { // Installation Wizard
            wp_enqueue_script('wopb-initial-setup-script', WOPB_URL . 'assets/js/wopb_initial_setup_min.js', array('wp-i18n', 'wp-api-fetch', 'wp-api-request', 'wp-components', 'wp-blocks'), WOPB_VER, true);
            wp_localize_script('wopb-initial-setup-script', 'initial_local_data', array(
                'url' => WOPB_URL,
                'active' => $is_active,
                'addons' => apply_filters('wopb_addons_config', []),
            ) );
            wp_set_script_translations( 'wopb-initial-setup-script', 'product-blocks', WOPB_URL . 'languages/' );
        }

        /* === Dashboard === */
        if ( $_page == 'wopb-settings' ) {
            $query_args = array(
                'posts_per_page'    => 3,
                'post_type'         => 'product',
                'post_status'       => 'publish',
            );
            wp_enqueue_script('wopb-dashboard-script', WOPB_URL.'assets/js/wopb_dashboard_min.js', array('wp-i18n', 'wp-api-fetch', 'wp-api-request', 'wp-components','wp-blocks'), WOPB_VER, true);
            wp_localize_script('wopb-dashboard-script', 'wopb_dashboard_pannel', array(
                'url' => WOPB_URL,
                'active' => $is_active,
                'license' => $license_key,
                'settings' => wopb_function()->get_setting(),
                'addons' => apply_filters('wopb_addons_config', []),
                'addons_settings' => apply_filters('wopb_settings', []),
                'default_settings' => $wopb_default_settings,
                'affiliate_id' => apply_filters( 'wopb_affiliate_id', false ),
                'version' => WOPB_VER,
                'setup_wizard_link' => admin_url('admin.php?page=wopb-initial-setup-wizard'),
                'helloBar' => get_transient('wopb_helloBar'),
                'status' => get_option( 'edd_wopb_license_status' ),
                'expire' => get_option( 'edd_wopb_license_expire' ),
                'products' => wopb_function()->is_wc_ready() ? wopb_function()->product_format(['products'=> new \WP_Query($query_args), 'size' => 'medium']) : [],
            ));
            wp_set_script_translations( 'wopb-dashboard-script', 'product-blocks', WOPB_PATH . 'languages/' );
        }
    }


    /**
	 * Enqueue Common Script for Both Frontend and Backend
     * 
     * @since v.1.0.0
	 * @return NULL
	 */
    public function register_scripts_common() {
        wp_enqueue_style('dashicons');
        wp_enqueue_style('wopb-slick-style', WOPB_URL.'assets/css/slick.css', array(), WOPB_VER);
        wp_enqueue_style('wopb-slick-theme-style', WOPB_URL.'assets/css/slick-theme.css', array(), WOPB_VER);
        if ( is_rtl() ) {
            wp_enqueue_style('wopb-blocks-rtl-css', WOPB_URL.'assets/css/rtl.css', array(), WOPB_VER); 
        }
        wp_enqueue_script('wopb-slick-script', WOPB_URL.'assets/js/slick.min.js', array('jquery'), WOPB_VER, true);
        $this->register_main_scripts();
    }

    public function register_main_scripts() {
        global $post;
        wp_enqueue_style('wopb-style', WOPB_URL.'assets/css/blocks.style.css', array(), WOPB_VER );
        wp_enqueue_style('wopb-css', WOPB_URL.'assets/css/wopb.css', array(), WOPB_VER );
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
            'currency_symbol' => class_exists( 'WooCommerce' ) && is_plugin_active( 'woocommerce/woocommerce.php' ) ? get_woocommerce_currency_symbol() : '' ,
            'currency_position' => get_option( 'woocommerce_currency_pos' ),
            'errorElementGroup' => [
                'errorElement' => '<div class="wopb-error-element"></div>'
            ],
            'taxonomyCatUrl' => admin_url( 'edit-tags.php?taxonomy=category' ),
        );
        $wopb_core_localize = array_merge($wopb_core_localize, wopb_function()->get_endpoint_urls());
        wp_localize_script('wopb-script', 'wopb_core', $wopb_core_localize);
    }

    /**
	 * Checking if Our Blocks Used or Not
     * 
     * @since v.1.0.0
	 * @return NULL
	 */
    public function register_scripts_front_callback() {
        $call_common = false;
        $isWC = function_exists('is_shop');
        if ('yes' == get_post_meta((($isWC && is_shop()) ? wc_get_page_id('shop') : get_the_ID()), '_wopb_active', true)) {
            $call_common = true;
            $this->register_scripts_common();
        }  else if (apply_filters ('productx_common_script', false)) {
            $call_common = true;
            $this->register_scripts_common();
        } else if ($isWC && wopb_function()->is_builder()) {
            $call_common = true;
            $this->register_scripts_common();
            add_filter( 'productx_common_script', '__return_true' );
        } else if ($isWC && (is_product() || is_archive())) {
            $this->register_main_scripts();
        } else if (isset($_GET['et_fb'])) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended, Divi Backend Builder
            $call_common = true;
            $this->register_scripts_common();
        }

        // For WidgetWidget
        $has_block = false;
        $widget_blocks = array();
        global $wp_registered_sidebars, $sidebars_widgets;
        foreach ($wp_registered_sidebars as $key => $value) {
            if (is_active_sidebar($key)) {
                foreach ($sidebars_widgets[$key] as $val) {
                    if (strpos($val, 'block-') !== false) {
                        if (empty($widget_blocks)) { 
                            $widget_blocks = get_option( 'widget_block' );
                        }
                        foreach ( (array) $widget_blocks as $block ) {
                            if ( isset( $block['content'] ) && strpos($block['content'], 'wp:product-blocks') !== false ) {
                                $has_block = true;
                                break;
                            }
                        }
                        if ($has_block) {
                            break;
                        }
                    }
                }
            }
        }
        if ($has_block) {
            if (!$call_common) {
                $this->register_scripts_common();
            }
            $css = get_option('wopb-widget', true);
            if ($css) {
                echo  wopb_function()->esc_inline($css); //phpcs:ignore
            }
        }
    }

    /**
	 * Only Backend Enqueue Scripts
     * 
     * @since v.1.0.0
	 * @return NULL
	 */
    public function register_scripts_back_callback() {
        global $post;
        global $pagenow;
        global $wopb_default_settings;
        $this->register_scripts_common();
        if (wopb_function()->is_wc_ready()) {
            $is_active = wopb_function()->is_lc_active();
            $is_builder = (isset($post->post_type) && $post->post_type == "wopb_builder") ? true : false;
            if( $pagenow != 'widgets.php' ) {
                wp_enqueue_script('wp-editor');
            }
            wp_enqueue_script( 'wopb-blocks-editor-script', WOPB_URL.'assets/js/editor.blocks.min.js', array('wp-i18n', 'wp-element', 'wp-blocks', 'wp-components'), WOPB_VER, true );
            wp_enqueue_style('wopb-blocks-editor-css', WOPB_URL.'assets/css/blocks.editor.css', array(), WOPB_VER);
            
            wp_localize_script('wopb-blocks-editor-script', 'wopb_data', array(
                'url' => WOPB_URL,
                'ajax' => admin_url('admin-ajax.php'),
                'security' => wp_create_nonce('wopb-nonce'),
                'hide_import_btn' => wopb_function()->get_setting('hide_import_btn'),
                'premium_link' => wopb_function()->get_premium_link(),
                'license' => $is_active ? get_option('edd_wopb_license_key') : '',
                'active' => $is_active,
                'isBuilder' => $is_builder,
                'isVariationSwitchActive' => wopb_function()->get_setting('wopb_variation_swatches'),
                'post_type' => get_post_type(),
                'settings' => wopb_function()->get_setting(),
                'default_settings' => $wopb_default_settings,
                'productTaxonomyList' => wopb_function()->get_product_taxonomies(['term_limit' => 10]),
                'product_category' => get_terms( ['taxonomy' => 'product_cat', 'hide_empty' => true, 'number' => 10] ),
                'builder_type' => $is_builder ? get_post_meta( $post->ID, '_wopb_builder_type', true ) : '',
                'is_builder_css' => $is_builder ? file_exists(WP_CONTENT_DIR . '/uploads/product-blocks/wopb-css-' . $post->ID . '.css') : false,
                'builder_status' => $is_builder ? get_post_status($post->ID) : '',
                'affiliate_id' => apply_filters( 'wopb_affiliate_id', false ),
            ));

            wp_set_script_translations( 'wopb-blocks-editor-script', 'product-blocks', WOPB_PATH . 'languages/' );
        }
    }

    /**
	 * Fire When Plugin First Installs
     * 
     * @since v.1.0.0
	 * @return NULL
	 */
    public function install_hook() {
        $data = get_option( 'wopb_options', array() );
        if ( ! empty( $data ) ) {
            $init_data = array(
                'css_save_as'           => 'wp_head',
                'preloader_style'       => 'style1',
                'preloader_color'       => '#ff5845',
                'container_width'       => '1140',
                'hide_import_btn'       => '',
                'editor_container'      => 'theme_default',
                'wopb_compare'          => 'true',
                'wopb_flipimage'        => 'true',
                'wopb_quickview'        => 'true',
                'wopb_templates'        => 'true',
                'wopb_wishlist'         => 'true',
                'wopb_builder'          => 'true',
                'wopb_variation_swatches'=> 'true',
                'wopb_oxygen'           => 'true',
                'wopb_elementor'        => 'true',
                'wopb_divi'             => 'true',
                'wopb_beaver_builder'   => 'true',
                'save_version'          => wp_rand(1, 1000),
                'disable_google_font'   => '',
                'wopb_custom_font'      => 'true',
            );
            if ( empty( $data ) ) {
                update_option( 'wopb_options', $init_data );
                $GLOBALS['wopb_settings'] = $init_data;
            } else {
                foreach ( $init_data as $key => $single ) {
                    if ( ! isset( $data[$key] ) ) {
                        $data[$key] = $single;
                    }
                }
                update_option( 'wopb_options', $data );
                $GLOBALS['wopb_settings'] = $data;
            }

            if(!get_option('wopb_activation')) {
                update_option('wopb_activation',gmdate('U'));
            }

            // if ( get_transient( 'wopb_initial_user_notice' ) ) {
            //     set_transient( 'wopb_initial_user_notice', 'off', 5 * DAY_IN_SECONDS ); // 5 days notice
            // }
        }
        if ( !get_transient('wopb_activation_redirect') ) {  // set transient to show wizard
            set_transient('wopb_activation_redirect', true, MINUTE_IN_SECONDS);
        }
    }

    /**
	 * Compatibility Check Require
     * 
     * @since v.1.0.0
	 * @return NULL
	 */
    public function compatibility_check() {
        require_once WOPB_PATH . 'classes/Compatibility.php';
        new \WOPB\Compatibility();
    }

    /**
	 * Require Necessary Libraries
     * 
     * @since v.1.0.0
	 * @return NULL
	 */
    public function requires() {
        require_once WOPB_PATH . 'classes/ProPlugins.php';
        require_once WOPB_PATH . 'classes/InitialSetup.php';
        require_once WOPB_PATH . 'classes/Notice.php';
        require_once WOPB_PATH . 'classes/Options.php';
        require_once WOPB_PATH . 'classes/Dashboard.php';
        new \WOPB\ProPlugins();
        new \WOPB\InitialSetup();
        new \WOPB\Notice();
        new \WOPB\Options();
        new \WOPB\Dashboard();
        if ( wopb_function()->is_wc_ready() ) {
            require_once WOPB_PATH . 'classes/REST_API.php';
            require_once WOPB_PATH . 'classes/Blocks.php';
            require_once WOPB_PATH . 'classes/Styles.php';
            require_once WOPB_PATH . 'classes/Caches.php';
            new \WOPB\REST_API();
            new \WOPB\Styles();
            new \WOPB\Blocks();
            new \WOPB\Caches();

            require_once WOPB_PATH . 'classes/Deactive.php';
            new \WOPB\Deactive();
        }
    }

    /**
	 * Block Categories Initialization
     * 
     * @since v.1.0.0
	 * @return NULL
	 */
    public function register_category_callback( $categories ) {
        $attr = array(
            array(
                'slug' => 'product-blocks',
                'title' => __( 'WooCommerce Blocks (ProductX)', 'product-blocks' )
            ),
            array(
                'slug' => 'single-product', 
                'title' => __( 'Single Product (ProductX)', 'product-blocks' ) 
            ),
            array(
                'slug' => 'single-cart', 
                'title' => __( 'Cart (ProductX)', 'product-blocks' ) 
            ),
            array(
                'slug' => 'single-checkout',
                'title' => __( 'Checkout (ProductX)', 'product-blocks' )
            ),
            array(
                'slug' => 'thank-you',
                'title' => __( 'Thank You (ProductX)', 'product-blocks' )
            ),
            array(
                'slug' => 'my-account',
                'title' => __( 'My Account (ProductX)', 'product-blocks' )
            )
        );
        return array_merge( $attr, $categories );
    }

    /**
	 * Post View Counter for Every Post
     * 
     * @since v.1.0.0
	 * @return NULL
	 */
    public function popular_posts_tracker_callback() {
        if ( ! is_singular( 'product' ) ) {
            return;
        }
        global $post;

        // View Product Count
        $count = (int)get_post_meta( $post->ID, '__product_views_count', true );
        update_post_meta( $post->ID, '__product_views_count', $count ? (int)$count + 1 : 1 );

        // Recently View Products
        $viewed_products = empty( $_COOKIE['__wopb_recently_viewed'] ) ? [] : (array) explode( '|', sanitize_text_field( $_COOKIE['__wopb_recently_viewed'] ) );
        if ( ! in_array( $post->ID, $viewed_products ) ) {
            $viewed_products[] = $post->ID;
        }
        if ( sizeof( $viewed_products ) > 30 ) {
            array_shift( $viewed_products );
        }
        wc_setcookie( '__wopb_recently_viewed', implode( '|', $viewed_products ) );
    }

    /**
     * Show Changelog in Plugin
     * 
     *  @return NULL
    */
    public function plugin_settings_update_message() {
        $response = wp_remote_get(
            'https://plugins.svn.wordpress.org/product-blocks/trunk/readme.txt', array(
            'method' => 'GET'
        ));
        
        if ( is_wp_error( $response ) || $response['response']['code'] != 200 ) {
            return;
        }
        
        $changelog_lines = preg_split( "/(\r\n|\n|\r)/", $response['body'] );

        $is_copy = false;
        $current_tag = '';
        $tag_text = 'Stable tag:';
        if ( ! empty( $changelog_lines ) ) {
            echo '<hr style="border-color:#dba617;"/>';
            echo '<div style="color:#cc0303;font-size:14px;font-weight:bold;"> <span style="color:#f56e28;" class="dashicons dashicons-warning"></span> ' . esc_html( 'ProductX is ready for the next update. Here is the changelog:-' ) . '</div>';
            echo '<hr style="border-color:#dba617;"/>';
            echo '<ul style="max-height:200px;overflow:scroll;">';
            foreach ( $changelog_lines as $key => $line ) {
                // Get Current Vesion
                if ( $current_tag == '' ) {
                    if ( strpos( $line, $tag_text ) !== false ) {
                        $current_tag = trim( str_replace( $tag_text, '', $line ) );
                    }
                } else {
                    if ( $is_copy ) {
                        if ( strpos( $line, '= ' . WOPB_VER ) !== false ) {
                            break;
                        }
                        if ( ! empty( $line ) ) {
                            if ( strpos( $line, '= ' ) !== false ) {
                                echo '<li style="color:#50575e;font-weight:bold;"><br/>' . esc_html( $line ) . '</li>';
                            } else {
                                echo '<li>' . esc_html( $line ) . '</li>';
                            }
                        }
                    } else {
                        if ( strpos( $line, '= '.$current_tag )  !== false ) { // Current Version
                            $is_copy = true;
                            echo '<li style="color:#50575e;font-weight:bold;">' . esc_html( $line ) . '</li>';
                        }
                    }
                }
            }
            echo '</ul>';
        }
    }

    /**
     * Init after Site Load
     *
     * @since v.todo
     *
     * @return void
     */
    public function init_site() {
        add_filter('woocommerce_loop_add_to_cart_link', array($this, 'add_to_cart_filter'), 100, 3);
        add_action('woocommerce_before_shop_loop_item_title', array($this, 'before_shop_loop_item'), 10);

        add_action('woocommerce_before_add_to_cart_button', array($this, 'before_add_to_cart_button'));
        add_action('woocommerce_after_add_to_cart_button', array($this, 'after_add_to_cart_button'));
    }

    /**
     * Add To Cart Button Filter for Loop Product
     *
     * @since v.3.1.5
     * @param $add_to_cart_html
     * @param $product
     * @param $products
     *
     * @return html
     */
    public function add_to_cart_filter($add_to_cart_html, $product, $args) {
        $cart_top_filters = apply_filters('wopb_top_add_to_cart_loop', $content = '', $args);
        $cart_before_filters = apply_filters('wopb_before_add_to_cart_loop', $content = '', $args);
        $cart_after_filters = apply_filters('wopb_after_add_to_cart_loop', $content = '', $args);
        $cart_bottom_filters = apply_filters('wopb_bottom_add_to_cart_loop', $content = '', $args);
        $cart_before_content = '';
        $cart_after_content = '';
        if( $cart_top_filters ) {
            $cart_before_content .= "<div class='wopb-cart-top'>";
            $cart_before_content .= $cart_top_filters;
            $cart_before_content .= "</div>";
        }
        if( $cart_before_filters ) {
            $cart_before_content .= "<span class='wopb-cart-before'>";
            $cart_before_content .= $cart_before_filters;
            $cart_before_content .= "</span>";
        }
        if( $cart_after_filters ) {
            $cart_after_content .= "<span class='wopb-cart-after'>";
            $cart_after_content .= $cart_after_filters;
            $cart_after_content .= "</span>";
        }
        if( $cart_bottom_filters ) {
            $cart_after_content .= "<div class='wopb-cart-bottom'>";
            $cart_after_content .= $cart_bottom_filters;
            $cart_after_content .= "</div>";
        }

        return $cart_before_content . $add_to_cart_html . $cart_after_content;
    }

    /**
     * Various Addons Before Shop Loop Title
     *
     * @since v.3.1.5
     * @return null
     */
    public function before_shop_loop_item() {
        $args = array();
        $before_shop_loop_title = apply_filters('wopb_before_shop_loop_title', $content = '', $args);
        if( $before_shop_loop_title ) {
            $content = "<div class='wopb-loop-image-top'>";
            $content .= $before_shop_loop_title;
            $content .= "</div>";
            echo $content; //phpcs:disable WordPress.Security.EscapeOutput.OutputNotEscaped
        }
    }

    /**
     * Button In Single Product Page Before Cart Button
     *
     * @since v.3.1.5
     * @return null
     */
    public function before_add_to_cart_button() {
        $cart_top_filters = apply_filters('wopb_top_add_to_cart', $content = '');
        $cart_before_filters = apply_filters('wopb_before_add_to_cart', $content = '');
        $cart_before_content = '';
        if( $cart_top_filters ) {
            $cart_before_content .= "<div class='wopb-cart-top'>";
            $cart_before_content .= $cart_top_filters;
            $cart_before_content .= "</div>";
        }
        if( $cart_before_filters ) {
            $cart_before_content .= "<span class='wopb-cart-before'>";
            $cart_before_content .= $cart_before_filters;
            $cart_before_content .= "</span>";
        }

        echo $cart_before_content; //phpcs:disable WordPress.Security.EscapeOutput.OutputNotEscaped
    }

    /**
     * Button In Single Product Page After Cart Button
     *
     * @since v.3.1.5
     * @return null
     */
    public function after_add_to_cart_button() {
        $cart_after_filters = apply_filters('wopb_after_add_to_cart', $content = '');
        $cart_bottom_filters = apply_filters('wopb_bottom_add_to_cart', $content = '');
        $cart_after_content = '';
        if( $cart_after_filters ) {
            $cart_after_content .= "<span class='wopb-cart-after'>";
            $cart_after_content .= $cart_after_filters;
            $cart_after_content .= "</span>";
        }
        if( $cart_bottom_filters ) {
            $cart_after_content .= "<div class='wopb-cart-bottom'>";
            $cart_after_content .= $cart_bottom_filters;
            $cart_after_content .= "</div>";
        }

        echo $cart_after_content; //phpcs:disable WordPress.Security.EscapeOutput.OutputNotEscaped
    }
}