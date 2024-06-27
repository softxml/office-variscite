<?php
/*
Plugin Name: Siteit Widgets - SiteOrigin Page Builder 
Plugin URI:  http://www.siteit.co.il
Description: A bundle created to be used in SiteOrigin Page Builder - <strong><u>REQUIRES BOOTSTRAP</u></strong>
Version:     1.0.0
Author:      Imri Sgive
Author URI:  http://www.siteit.co.il
License:     GPL2 etc
License URI: http://www.siteit.co.il


Copyrights SiteIT 2017 Imri Sagive

This plugin is owned by SiteIT - any usage must show a written permission 
to use it and any parts of it. Contact us to learn more at www.siteit.co.il
*/

$version 		= '1.0.0';
$prefix 		= 'sitpbldr_';
$translation	= 'siteitsob';




/*********************************************
** CUSTOM FUNC
*********************************************/
// include( plugin_dir_path( __FILE__ ) . 'functions/sitsob-settings.php' );
include( plugin_dir_path( __FILE__ ) . 'functions/sitsob-misc-func.php' );
include( plugin_dir_path( __FILE__ ) . 'functions/slanted-borders-pagebuilder.php' );



/*********************************************
** BACKEND: GRAB SCRIPTS & CSS
*********************************************/
add_action( 'admin_enqueue_scripts', 'sitpbldr_admin_enqueue_stuff' );
function sitpbldr_admin_enqueue_stuff(){

	global $version;
	global $prefix;
	

	// // styles
	wp_enqueue_style('wp-color-picker'); 
	wp_enqueue_style('sitpbldr_css', plugin_dir_url( __FILE__ ) . 'lib/backend/styles.css', array(), $version, false);
    wp_enqueue_style('sitpbldr_trumbowyg_css', plugin_dir_url( __FILE__ ) . 'lib/trumbowyg/ui/trumbowyg.min.css', array(), $version, false);
    wp_enqueue_style('sitpbldr_trumbowyg_colors_css', plugin_dir_url( __FILE__ ) . 'lib/trumbowyg/plugins/colors/ui/trumbowyg.colors.min.css', array(), $version, false);
    wp_enqueue_style('font-awesome', 'https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css', array(), false, false);

	// scripts
	wp_enqueue_script( 'jquery-ui-tabs' );
	wp_enqueue_script( 'wp-color-picker' );
	wp_enqueue_script('sitpbldr_js', plugin_dir_url( __FILE__ ) . 'lib/backend/scripts.js', array('jquery', 'jquery-ui-droppable', 'jquery-ui-draggable', 'jquery-ui-sortable'), $version, true);
	wp_enqueue_script('sitpbldr_trumbowygjs', plugin_dir_url( __FILE__ ) . 'lib/trumbowyg/trumbowyg.min.js', array('jquery'), false, true);
	wp_enqueue_script('sitpbldr_trumbowyg_colors_js', plugin_dir_url( __FILE__ ) . 'lib/trumbowyg/plugins/colors/trumbowyg.colors.min.js', array($prefix.'trumbowygjs'), false, true);
	wp_enqueue_script('sitpbldr_trumbowyg_fontsize_js', plugin_dir_url( __FILE__ ) . 'lib/trumbowyg/plugins/fontsize/trumbowyg.fontsize.js', array($prefix.'trumbowygjs'), false, true);
}



/*********************************************
** FRONTEND: GRAB SCRIPTS & CSS
*********************************************/
add_action( 'wp_enqueue_scripts', 'sitpbldr_enqueue_stuff' );
function sitpbldr_enqueue_stuff(){

	global $version, $prefix;

	// styles
	wp_enqueue_style($prefix.'front_css', plugin_dir_url( __FILE__ ) . 'lib/front/sitpbldr-front.css', $version, false);

	// scripts
	wp_enqueue_script($prefix.'front_js', plugin_dir_url( __FILE__ ) . 'lib/front/scripts.min.js', array('jquery'), $version, true);
}



/*********************************************
** INCLUDE WIDGETS
*********************************************/
include( plugin_dir_path( __FILE__ ) . 'widgets/widgets-group-settings.php' );
include( plugin_dir_path( __FILE__ ) . 'widgets/button-widget.php' );
include( plugin_dir_path( __FILE__ ) . 'widgets/text-widget.php' );
include( plugin_dir_path( __FILE__ ) . 'widgets/spacer-widget.php' );
include( plugin_dir_path( __FILE__ ) . 'widgets/recent-posts-widget.php' );
include( plugin_dir_path( __FILE__ ) . 'widgets/post-isotope-widget.php' );
include( plugin_dir_path( __FILE__ ) . 'widgets/image-widget.php' );
include( plugin_dir_path( __FILE__ ) . 'widgets/image-text-widget.php' );
include( plugin_dir_path( __FILE__ ) . 'widgets/bgimg-overlay-widget.php' );
include( plugin_dir_path( __FILE__ ) . 'widgets/expending-data-widget.php' );
include( plugin_dir_path( __FILE__ ) . 'widgets/social-icons-list-widget.php' );
include( plugin_dir_path( __FILE__ ) . 'widgets/cf7-selector-widget.php' );
include( plugin_dir_path( __FILE__ ) . 'widgets/list-widget.php' );
include( plugin_dir_path( __FILE__ ) . 'widgets/clean-js-widget.php' );