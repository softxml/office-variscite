<?php
/*
Plugin Name: CPU Search and List
Description: Search for specific CPU names and get a link to their product lists or return all CPU names available in the database.
Version: 1.0
Author: Your Name
*/

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

// Enqueue necessary scripts and styles
function cpu_enqueue_scripts() {
    wp_enqueue_script( 'cpu-script', plugin_dir_url( __FILE__ ) . 'js/cpu-script.js', array( 'jquery' ), filemtime( plugin_dir_path( __FILE__ ) . 'js/cpu-script.js' ), true );
    wp_enqueue_style( 'cpu-style', plugin_dir_url( __FILE__ ) . 'css/cpu-style.css', array(), filemtime( plugin_dir_path( __FILE__ ) . 'css/cpu-style.css' ) );

    // Localize script to pass AJAX URL
    wp_localize_script( 'cpu-script', 'cpu_ajax_object', array(
        'ajax_url' => admin_url( 'admin-ajax.php' )
    ));
}
add_action( 'admin_enqueue_scripts', 'cpu_enqueue_scripts' );

// Add meta box to product edit screens
function cpu_add_meta_box() {
    add_meta_box(
        'cpu_related_products',
        'Related CPUs',
        'cpu_render_meta_box',
        ['product', 'page', 'post'],
        'normal',
        'default'
    );
}
add_action( 'add_meta_boxes', 'cpu_add_meta_box' );

// Render the meta box content
function cpu_render_meta_box( $post ) {
    wp_nonce_field( 'cpu_save_related_products', 'cpu_related_products_nonce' );
    $related_cpus = get_post_meta( $post->ID, '_cpu_related_products', true );
    ?>
    <div id="cpu-container">
        <div id="cpu-search-container">
            <div class="cpu-search-wrapper">
                <input type="text" id="cpu-search" placeholder="Search for CPU names..." />
                <span class="cpu-clear-search">&times;</span>
            </div>
            <ul id="cpu-search-results"></ul>
        </div>
        <div id="cpu-list-container">
            <h3>Selected CPUs</h3>
            <ul id="cpu-list">
                <?php if ( ! empty( $related_cpus ) && is_array( $related_cpus ) ) : ?>
                    <?php foreach ( $related_cpus as $cpu_id ) : ?>
                        <li data-cpu-id="<?php echo esc_attr( $cpu_id ); ?>">
                            <?php echo esc_html( get_term_field( 'name', $cpu_id, 'cpu' ) ); ?>
                            <button class="cpu-remove">Remove</button>
                        </li>
                    <?php endforeach; ?>
                <?php endif; ?>
            </ul>
        </div>
    </div>
    <input type="hidden" id="cpu-related-products" name="cpu_related_products" value="<?php echo esc_attr( implode( ',', (array) $related_cpus ) ); ?>" />
    <?php
}

// Save the related CPUs when the product is saved
function cpu_save_related_products( $post_id ) {
    if ( ! isset( $_POST['cpu_related_products_nonce'] ) || ! wp_verify_nonce( $_POST['cpu_related_products_nonce'], 'cpu_save_related_products' ) ) {
        return;
    }

    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return;
    }

    if ( ! current_user_can( 'edit_post', $post_id ) ) {
        return;
    }

    if ( isset( $_POST['cpu_related_products'] ) && !empty( $_POST['cpu_related_products'] ) ) {
        $related_cpus = array_map( 'intval', explode( ',', $_POST['cpu_related_products'] ) );
        update_post_meta( $post_id, '_cpu_related_products', $related_cpus );
    } else {
        delete_post_meta( $post_id, '_cpu_related_products' );
    }
}
add_action( 'save_post', 'cpu_save_related_products' );

// AJAX handler for searching CPU names
function cpu_search_names() {
    if ( ! isset( $_POST['query'] ) ) {
        wp_send_json_error();
    }

    $query = sanitize_text_field( $_POST['query'] );

    $args = array(
        'taxonomy' => 'cpu',
        'search' => $query,
        'hide_empty' => false,
    );

    $cpu_terms = get_terms( $args );

    $results = array();
    if ( ! is_wp_error( $cpu_terms ) ) {
        foreach ( $cpu_terms as $term ) {
            $results[] = array(
                'id' => $term->term_id,
                'name' => $term->name,
                'link' => get_term_link( $term ),
            );
        }
    }

    wp_send_json_success( $results );
}
add_action( 'wp_ajax_cpu_search_names', 'cpu_search_names' );
add_action( 'wp_ajax_nopriv_cpu_search_names', 'cpu_search_names' );

// AJAX handler for getting all CPU names
function cpu_get_all_names() {
    $args = array(
        'taxonomy' => 'cpu',
        'hide_empty' => false,
    );

    $cpu_terms = get_terms( $args );

    $results = array();
    if ( ! is_wp_error( $cpu_terms ) ) {
        foreach ( $cpu_terms as $term ) {
            $results[] = array(
                'id' => $term->term_id,
                'name' => $term->name,
                'link' => get_term_link( $term ),
            );
        }
    }

    wp_send_json_success( $results );
}
add_action( 'wp_ajax_cpu_get_all_names', 'cpu_get_all_names' );
add_action( 'wp_ajax_nopriv_cpu_get_all_names', 'cpu_get_all_names' );
?>
