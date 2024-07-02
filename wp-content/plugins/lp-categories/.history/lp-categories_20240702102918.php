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
add_action( 'wp_enqueue_scripts', 'cpu_enqueue_scripts' );

// Shortcode to display the search form and results
function cpu_search_shortcode() {
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
            <ul id="cpu-list"></ul>
        </div>
    </div>
    <?php
}
add_shortcode( 'cpu_search', 'cpu_search_shortcode' );

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
