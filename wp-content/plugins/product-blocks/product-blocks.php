<?php
/**
 * Plugin Name: ProductX
 * Description: <a href="https://www.wpxpo.com/productx/?utm_source=productx_plugin&utm_medium=productx&utm_campaign=productx-dashboard">ProductX</a> is an all-in-one solution for creating visually stunning and conversion-focused WooCommerce stores. The main and attractive features are WooCommerce Builder, Variation Swatches, Wishlist, Comparison, etc.
 * Version:     3.1.17
 * Author:      wpxpo
 * Author URI:  https://www.wpxpo.com/?utm_source=productx_plugin&utm_medium=wpxpo&utm_campaign=productx-dashboard
 * Text Domain: product-blocks
 * License:     GPLv3
 * License URI: http://www.gnu.org/licenses/gpl-3.0.html
**/

defined( 'ABSPATH' ) || exit;

// Define Constants
define( 'WOPB_VER', '3.1.17' );
define( 'WOPB_URL', plugin_dir_url( __FILE__ ) );
define( 'WOPB_BASE', plugin_basename( __FILE__ ) );
define( 'WOPB_PATH', plugin_dir_path( __FILE__ ) );

// Language and Template Load
add_action( 'init', 'wopb_language_n_template_load' );
function wopb_language_n_template_load() {
    // Load Language
    load_plugin_textdomain( 'product-blocks', false, basename( dirname( __FILE__ ) ) . "/languages/" );

    // Template Load
    if ( wopb_function()->is_wc_ready() ) {
        require_once WOPB_PATH . 'classes/Templates.php';
        new \WOPB\Templates();
    }
}

// Common Function
if ( !function_exists( 'wopb_function' ) ) {
    function wopb_function() {
        require_once WOPB_PATH . 'classes/Functions.php';
        return \WOPB\Functions::get_instance();
    }
}

// Plugin Initialization
require_once WOPB_PATH . 'classes/Initialization.php';
new \WOPB\Initialization();