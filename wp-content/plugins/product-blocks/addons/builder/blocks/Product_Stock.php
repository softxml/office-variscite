<?php
namespace WOPB\blocks;

defined('ABSPATH') || exit;

class Product_Stock{

    public function __construct() {
        add_action( 'init', array( $this, 'register' ) );
    }

    public function register() {
        register_block_type( 'product-blocks/product-stock',
            array(
                'editor_script' => 'wopb-blocks-builder-script',
                'editor_style'  => 'wopb-blocks-editor-css',
                'render_callback' =>  array( $this, 'content' )
            )
        );
    }

    public function content( $attr ) {
        global $product;
        $product = wc_get_product();
        $block_name = 'product-stock';
        $wraper_before = $wraper_after = $content = '';
       
        if ( ! empty( $product ) ) {
            if ( $product->get_manage_stock() ) {
                $attr['className'] = !empty($attr['className']) ? preg_replace('/[^A-Za-z0-9_ -]/', '', $attr['className']) : '';

                $wraper_before .= '<div ' . (isset($attr['advanceId']) ? 'id="' . sanitize_html_class($attr['advanceId']) . '" ':'') . ' class="wp-block-product-blocks-' . esc_attr($block_name) . ' wopb-block-' . sanitize_html_class($attr["blockId"]) . ' ' . $attr['className'] . '">';
                    $wraper_before .= '<div class="wopb-product-wrapper">';

                    $content .= wc_get_stock_html( $product );

                    $wraper_after .= '</div>';
                $wraper_after .= '</div>';
            }
		}
        
        return $wraper_before.$content.$wraper_after;
    }

}