<?php
/*
Plugin Name: Specs Related Products
Description: Search for specs products and add them as related products for regular products, pages, and posts.
Version: 1.2
Author: Your Name
*/

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

// Enqueue necessary scripts and styles
if (!function_exists('srp_enqueue_scripts')) {
    function srp_enqueue_scripts() {
        wp_enqueue_script( 'srp-script', plugin_dir_url( __FILE__ ) . 'js/srp-script.js', array( 'jquery' ), filemtime( plugin_dir_path( __FILE__ ) . 'js/srp-script.js' ), true );
        wp_enqueue_style( 'srp-style', plugin_dir_url( __FILE__ ) . 'css/srp-style.css', array(), filemtime( plugin_dir_path( __FILE__ ) . 'css/srp-style.css' ) );

        // Localize script to pass AJAX URL
        wp_localize_script( 'srp-script', 'srp_ajax_object', array(
            'ajax_url' => admin_url( 'admin-ajax.php' )
        ));
    }
}
add_action( 'admin_enqueue_scripts', 'srp_enqueue_scripts' );

// Function to render the plugin content
if (!function_exists('srp_render_plugin_content')) {
    function srp_render_plugin_content() {
        ob_start(); // Start output buffering
        ?>
        <div id="srp-container">
            <div id="srp-search-container">
                <div class="srp-search-wrapper">
                    <input type="text" id="srp-search" placeholder="Search for specs products..." />
                    <span class="srp-clear-search">&times;</span>
                </div>
                <ul id="srp-search-results"></ul>
            </div>
            <div id="srp-related-products-list">
                <h3>Selected Products</h3>
                <ul>
                    <?php 
                    $post_id = get_the_ID();
                    $related_products = get_post_meta( $post_id, '_srp_related_products', true );
                    if ( ! empty( $related_products ) && is_array( $related_products ) ) : 
                        foreach ( $related_products as $product_id ) : 
                            if ( get_post_status( $product_id ) ) : // Ensure the product exists 
                                ?>
                                <li data-product-id="<?php echo esc_attr( $product_id ); ?>">
                                    <?php echo esc_html( get_the_title( $product_id ) ); ?>
                                    <button class="srp-remove-product">Remove</button>
                                </li>
                            <?php 
                            endif; 
                        endforeach; 
                    endif; 
                    ?>
                </ul>
            </div>
        </div>
        <input type="hidden" id="srp-related-products" name="srp_related_products" value="<?php echo esc_attr( implode( ',', (array) $related_products ) ); ?>" />
        <?php
        return ob_get_clean(); // Return the buffered content
    }
}

// Save the related products when the product or page is saved
if (!function_exists('srp_save_related_products')) {
    function srp_save_related_products( $post_id ) {
        if ( ! isset( $_POST['srp_related_products_nonce'] ) || ! wp_verify_nonce( $_POST['srp_related_products_nonce'], 'srp_save_related_products' ) ) {
            return;
        }

        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
            return;
        }

        // Check the user's permissions.
        if (isset($_POST['post_type'])) {
            if ($_POST['post_type'] == 'page') {
                if (!current_user_can('edit_page', $post_id)) {
                    return;
                }
            } else {
                if (!current_user_can('edit_post', $post_id)) {
                    return;
                }
            }
        }

        if ( isset( $_POST['srp_related_products'] ) && !empty( $_POST['srp_related_products'] ) ) {
            $related_products = array_map( 'intval', explode( ',', $_POST['srp_related_products'] ) );
            update_post_meta( $post_id, '_srp_related_products', $related_products );
        } else {
            // If no related products are left, delete the post meta
            delete_post_meta( $post_id, '_srp_related_products' );
        }
    }
}
add_action( 'save_post', 'srp_save_related_products' );

// AJAX handler for searching specs products
if (!function_exists('srp_search_specs_products')) {
    function srp_search_specs_products() {
        if ( ! isset( $_POST['query'] ) ) {
            wp_send_json_error();
        }

        $query = sanitize_text_field( $_POST['query'] );

        $args = array(
            'post_type' => 'specs',
            's' => $query,
            'posts_per_page' => 10,
        );

        $search_results = new WP_Query( $args );

        $results = array();
        if ( $search_results->have_posts() ) {
            while ( $search_results->have_posts() ) {
                $search_results->the_post();
                $results[] = array(
                    'id' => get_the_ID(),
                    'title' => get_the_title(),
                );
            }
            wp_reset_postdata();
        }

        // Clear the output buffer before sending the JSON response
        if (ob_get_length()) {
            ob_clean();
        }

        wp_send_json_success( $results );
    }
}
add_action( 'wp_ajax_srp_search_specs_products', 'srp_search_specs_products' );
add_action( 'wp_ajax_nopriv_srp_search_specs_products', 'srp_search_specs_products' );
