<?php
/**
 * FlipImage Addons Core.
 * 
 * @package WOPB\FlipImage
 * @since v.1.1.0
 */

namespace WOPB;

defined('ABSPATH') || exit;

class FlipImage {
    public function __construct() {
        $settings = wopb_function()->get_setting();
        add_action('wp_enqueue_scripts', array($this, 'add_flip_image_scripts'));
        if ( isset( $settings['flip_image_source'] ) && $settings['flip_image_source'] == 'feature' ) {
            add_action('add_meta_boxes', array($this, 'feature_image_add_metabox'));
            add_action('save_post', array($this, 'feature_image_save'), 10, 1);
        }
        if( ! wopb_function()->is_builder() && ! is_admin() ) {
            add_filter('wp_get_attachment_image', array($this, 'change_image_on_hover'), 10, 1);
        }
    }

    /**
     * Flip Image Script
     *
     * @return NULL
     * @since v.3.1.5
     */
    public function add_flip_image_scripts() {
        wp_enqueue_style('wopb-animation-css', WOPB_URL.'assets/css/animation.min.css', array(), WOPB_VER);
        wp_enqueue_style('wopb-flip-image-style', WOPB_URL . 'addons/flip_image/css/flip_image.min.css', array(), WOPB_VER);
        wp_enqueue_script('wopb-flip-image-script', WOPB_URL . 'addons/flip_image/js/flip_image.js', array('jquery', 'wp-api-fetch'), WOPB_VER, true);
    }


    /**
     * Default Shop Page Image Flip
     *
     * @return null
     * @since v.3.1.5
     */
     public function change_image_on_hover($html) {
         global $product;
         global $woocommerce_loop;
         if( !is_product() && $product && $woocommerce_loop ) {
             $html .= wopb_function()->get_flip_image($product->get_id(), $product->get_title());
             return $html;
         }
         return $html;
    }


    /**
	 * Flip Image Meta Box Register
     * 
	 * @return NULL
     * @since v.1.1.0
	 */
    function feature_image_add_metabox () {
        add_meta_box( 'flipimage-feature-image', __( 'Flip Image', 'text-domain' ), array($this, 'feature_image_metabox'), 'product', 'side', 'low');
    }

    /**
     * Flip Image Meta Box
     *
     * @param $post
     * @return NULL
     * @since v.1.1.0
     */
    function feature_image_metabox ( $post ) {
        global $content_width, $_wp_additional_image_sizes;
        $image_id = get_post_meta( $post->ID, '_flip_image_id', true );
        $old_content_width = $content_width;
        $content_width = 254;
        if ( $image_id && get_post( $image_id ) ) {
            if ( ! isset( $_wp_additional_image_sizes['post-thumbnail'] ) ) {
                $thumbnail_html = wp_get_attachment_image( $image_id, array( $content_width, $content_width ) );
            } else {
                $thumbnail_html = wp_get_attachment_image( $image_id, 'post-thumbnail' );
            }
            if ( ! empty( $thumbnail_html ) ) {
                echo wp_kses_post($thumbnail_html);
                echo '<p class="hide-if-no-js"><a href="javascript:;" id="remove_feature_image_button" >' . esc_html__( 'Remove Flip Image', 'product-blocks' ) . '</a></p>';
                echo '<input type="hidden" id="upload_feature_image" name="_flip_image" value="' . esc_attr( $image_id ) . '" />';
            }
            $content_width = $old_content_width;
        } else {
            echo '<p class="hide-if-no-js"><a title="' . esc_attr__( 'Set Flip Image Source', 'product-blocks' ) . '" href="javascript:;" id="upload_feature_image_button" id="set-listing-image" data-uploader_title="' . esc_attr__( 'Select Flip Image Source', 'product-blocks' ) . '" data-uploader_button_text="' . esc_attr__( 'Set Flip Image Source', 'product-blocks' ) . '">' . esc_html__( 'Set Flip Image Source', 'product-blocks' ) . '</a></p>';
            echo '<input type="hidden" id="upload_feature_image" name="_flip_image" value="" />';
        }
    }

    /**
     * Flip Image Save From Meta Box
     *
     * @param $post_id
     * @return NULL
     * @since v.1.1.0
     */
    function feature_image_save ( $post_id ) {
        if( isset( $_POST['_flip_image'] ) ) { //phpcs:disable WordPress.Security.NonceVerification.Missing
            $image_id = (int) sanitize_text_field($_POST['_flip_image']);  //phpcs:disable WordPress.Security.NonceVerification.Missing
            update_post_meta( $post_id, '_flip_image_id', $image_id );
        }
    }

    /**
	 * FlipImage Addons Initial Setup Action
     * 
	 * @return NULL
     * @since v.1.1.0
	 */
    public function initial_setup(){
        // Set Default Value
        $initial_data = array(
            'flipimage_heading' => 'yes',
            'flip_image_source' => 'gallery',
            'flip_animation_type' => 'fade_in',
            'flip_group_variable_disable' => '',
            'flip_mobile_device_disable' => '',
        );
        foreach ($initial_data as $key => $val) {
            wopb_function()->set_setting($key, $val);
        }
    }
}