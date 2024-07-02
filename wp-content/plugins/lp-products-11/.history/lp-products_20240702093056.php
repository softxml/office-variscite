<?php
/*
Plugin Name: Specs Related Products
Description: Search for specs products and add them as related products for regular products, pages, and posts.
Version: 1.0
Author: Your Name
*/

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

// Enqueue necessary scripts and styles
function srp_enqueue_scripts() {
    wp_enqueue_script( 'srp-script', plugin_dir_url( __FILE__ ) . 'js/srp-script.js', array( 'jquery' ), filemtime( plugin_dir_path( __FILE__ ) . 'js/srp-script.js' ), true );
    wp_enqueue_style( 'srp-style', plugin_dir_url( __FILE__ ) . 'css/srp-style.css', array(), filemtime( plugin_dir_path( __FILE__ ) . 'css/srp-style.css' ) );

    // Localize script to pass AJAX URL
    wp_localize_script( 'srp-script', 'srp_ajax_object', array(
        'ajax_url' => admin_url( 'admin-ajax.php' )
    ));
}
add_action( 'admin_enqueue_scripts', 'srp_enqueue_scripts' );

// Add meta box to product, page, and post edit screens
function srp_add_meta_box() {
    add_meta_box(
        'srp_related_products',
        'Related Specs Products',
        'srp_render_meta_box',
        ['product', 'page', 'post'],
        'normal',
        'default'
    );
}
add_action( 'add_meta_boxes', 'srp_add_meta_box' );

// Render the meta box content
function srp_render_meta_box( $post ) {
    wp_nonce_field( 'srp_save_related_products', 'srp_related_products_nonce' );
    $related_products = get_post_meta( $post->ID, '_srp_related_products', true );
    ?>
    <div id="srp-search-container">
        <input type="text" id="srp-search" placeholder="Search for specs products..." />
        <ul id="srp-search-results"></ul>
    </div>
    <div id="srp-related-products-list">
        <ul>
            <?php if ( ! empty( $related_products ) && is_array( $related_products ) ) : ?>
                <?php foreach ( $related_products as $product_id ) : ?>
                    <?php if ( get_post_status( $product_id ) ) : // Ensure the product exists ?>
                        <li data-product-id="<?php echo esc_attr( $product_id ); ?>">
                            <?php echo esc_html( get_the_title( $product_id ) ); ?>
                            <button class="srp-remove-product">Remove</button>
                        </li>
                    <?php endif; ?>
                <?php endforeach; ?>
            <?php endif; ?>
        </ul>
    </div>
    <input type="hidden" id="srp-related-products" name="srp_related_products" value="<?php echo esc_attr( implode( ',', (array) $related_products ) ); ?>" />
    <?php
}

// Save the related products when the product or page is saved
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
add_action( 'save_post', 'srp_save_related_products' );

// AJAX handler for searching specs products
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

    wp_send_json_success( $results );
}
add_action( 'wp_ajax_srp_search_specs_products', 'srp_search_specs_products' );
add_action( 'wp_ajax_nopriv_srp_search_specs_products', 'srp_search_specs_products' );


// JavaScript
?>

<script>
jQuery(document).ready(function($) {
    // Handle the search input
    $('#srp-search').on('input', function() {
        var query = $(this).val();
        console.log('Search query:', query); // Debugging line

        if (query.length < 3) {
            $('#srp-search-results').empty();
            return;
        }

        // Show loading indicator
        $('#srp-search-results').html('<li class="loading">Loading...</li>');
        console.log('Sending AJAX request to:', srp_ajax_object.ajax_url); // Debugging line

        $.ajax({
            url: srp_ajax_object.ajax_url,
            method: 'POST',
            data: {
                action: 'srp_search_specs_products',
                query: query
            },
            success: function(response) {
                console.log('AJAX response:', response); // Debugging line

                if (response.success) {
                    var results = response.data;
                    $('#srp-search-results').empty();
                    results.forEach(function(product) {
                        if (!isProductAdded(product.id)) {
                            $('#srp-search-results').append(
                                '<li data-product-id="' + product.id + '">' +
                                product.title +
                                '<button class="srp-add-product">Add</button>' +
                                '</li>'
                            );
                        }
                    });
                } else {
                    $('#srp-search-results').html('<li>No results found.</li>');
                }
            },
            error: function(error) {
                console.log('AJAX error:', error); // Debugging line
                $('#srp-search-results').html('<li>Error loading results.</li>');
            }
        });
    });

    // Handle adding a product
    $('#srp-search-results').on('click', '.srp-add-product', function() {
        var productId = $(this).parent().data('product-id');
        var productTitle = $(this).parent().text().replace('Add', '');

        if (!isProductAdded(productId)) {
            $('#srp-related-products-list ul').append(
                '<li data-product-id="' + productId + '">' +
                productTitle +
                '<button class="srp-remove-product">Remove</button>' +
                '</li>'
            );

            // Remove product from search results
            $(this).parent().remove();
            updateRelatedProducts();
        } else {
            alert('This product is already added.');
        }
    });

    // Handle removing a product
    $('#srp-related-products-list').on('click', '.srp-remove-product', function() {
        $(this).parent().remove();
        updateRelatedProducts();
    });

    // Update the hidden input field with the list of related product IDs
    function updateRelatedProducts() {
        var productIds = [];
        $('#srp-related-products-list ul li').each(function() {
            productIds.push($(this).data('product-id'));
        });
        $('#srp-related-products').val(productIds.join(','));
        console.log('Updated related products:', productIds); // Debugging line
    }

    // Check if a product is already added
    function isProductAdded(productId) {
        var isAdded = false;
        $('#srp-related-products-list ul li').each(function() {
            if ($(this).data('product-id') == productId) {
                isAdded = true;
                return false; // Exit each loop
            }
        });
        return isAdded;
    }
});
</script>
