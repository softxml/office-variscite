<?php
namespace WOPB\blocks;

defined('ABSPATH') || exit;

class Product_Cart{

    public function __construct() {
        add_action( 'init', array( $this, 'register' ) );
    }

    public function get_attributes() {
        return array(
            'showQuantity' => true,
            'quantityBtnPosition' => 'right',
            'cartText' => 'Add to Cart',
            'showQuantityBtn' => false,
        );
    }

    public function register() {
        register_block_type( 'product-blocks/product-cart',
            array(
                'editor_script' => 'wopb-blocks-builder-script',
                'editor_style'  => 'wopb-blocks-editor-css',
                'render_callback' =>  array($this, 'content')
            )
        );
    }

    public function content( $attr ) {
        global $product;
        $product = wc_get_product();
        $block_name = 'product-cart';
        $wraper_before = $wraper_after = $content = '';
        $attr = wp_parse_args( $attr, $this->get_attributes() );

        if ( ! empty( $product ) ) {
            global $productx_cart;
            $productx_cart = $attr['cartText'];
            
            if ( wopb_function()->isPro() ) {
                $methods = get_class_methods( wopb_pro_function() );
                if ( in_array( 'is_simple_preorder', $methods ) ) {
                    if ( wopb_pro_function()->is_simple_preorder() && wopb_function()->get_setting('preorder_add_to_cart_button_text') ) {
                        $productx_cart = wopb_function()->get_setting( 'preorder_add_to_cart_button_text' );
                    }
                }
                if ( in_array( 'is_simple_backorder', $methods ) ) {
                    if ( wopb_pro_function()->is_simple_backorder() && wopb_function()->get_setting('backorder_add_to_cart_button_text') ) {
                        $productx_cart = wopb_function()->get_setting( 'backorder_add_to_cart_button_text' );
                    }
                }
            }

            $cart_text = function() {
                global $productx_cart;
                return $productx_cart;
            };
            $attr['className'] = !empty($attr['className']) ? preg_replace('/[^A-Za-z0-9_ -]/', '', $attr['className']) : '';

            $wraper_before .= '<div '.(isset($attr['advanceId']) ? 'id="' . sanitize_html_class($attr['advanceId']) . '" ':'') . ' class="wp-block-product-blocks-' . esc_attr($block_name) . ' wopb-block-' . sanitize_html_class($attr["blockId"]) . ' ' . $attr['className'] . '">';


            add_filter( 'woocommerce_product_single_add_to_cart_text', $cart_text );

            if ( ! $attr['showQuantity'] ) {
                add_filter( 'woocommerce_is_sold_individually', [ $this, 'remove_quantity_fields'], 10, 2 );
            }

            ob_start();
            echo '<div class="wopb-product-wrapper wopb-builder-cart" data-type="'. ( $attr['showQuantity'] && $attr['showQuantityBtn'] ? esc_attr($attr['quantityBtnPosition']) : '' ).'">';
            $this->remove_qty_element();
            woocommerce_template_single_add_to_cart();
            do_action('wopb_after_builder_add_cart_button');
            echo '</div>';
            $form = ob_get_clean();
		    $content .= str_replace( 'single_add_to_cart_button', 'single_add_to_cart_button wopb-cart-button', $form );

            remove_filter( 'woocommerce_product_single_add_to_cart_text', $cart_text );

            $wraper_after .= '</div>';
        }

        return $wraper_before.$content.$wraper_after;
    }

    public function remove_qty_element() {
        remove_action( 'woocommerce_before_quantity_input_field', 'botiga_woocommerce_before_quantity_input_field' );
        remove_action( 'woocommerce_before_quantity_input_field', 'baseket_minus_btn' );
        remove_action( 'woocommerce_before_quantity_input_field', 'plant_minus_btn' );

        remove_action( 'woocommerce_before_add_to_cart_quantity', 'big_store_display_quantity_minus' );

        remove_action( 'woocommerce_after_quantity_input_field', 'botiga_woocommerce_after_quantity_input_field' );
        remove_action( 'woocommerce_after_quantity_input_field', 'baseket_plus_btn' );
        remove_action( 'woocommerce_after_quantity_input_field', 'plant_plus_btn' );

        remove_action( 'woocommerce_after_add_to_cart_quantity', 'big_store_display_quantity_plus' );
    }

    public function remove_quantity_fields( $return, $product ) {
        return true;
    }
}