<?php


/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, hooks & filters
 *
 */

namespace Redirect_404_Error_Page_To_Homepage_Or_Custom_Page\FrontEnd;

use Redirect_404_Error_Page_To_Homepage_Or_Custom_Page\Includes\Core;

class FrontEnd {

	/**
	 * The ID of this plugin.
	 *
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 */
	public function __construct( $plugin_name, $version ) {
		$this->plugin_name = $plugin_name;
		$this->version     = $version;
	}

	public function redirect( $slug = null, $name = null ) {
		$options = Core::get_option( 'redirect-404-error-page-to-homepage-or-custom-page-settings' );

		if ( ( ! is_404() ) || 404 == $options['type'] ) {
			return;
		}
		if ( 0 != $options['pageid'] ) {
			$id = get_permalink( $options['pageid'] );
			$this->do_redirect( $id, $options['type'] );
		} else {
			$this->do_redirect( home_url(), $options['type'] );
		}
	}

	private function do_redirect( $target, $type ) {
		if ( ! $this->log_it() ) {   // log it will return false if not requiring redirect
			return;
		}
		wp_redirect( $target, $type );
		exit;
	}

	private function log_it() {
		$log_options = Core::get_option( 'redirect-404-error-page-to-homepage-or-custom-page-log' );

		$referer = ( isset( $_SERVER['HTTP_REFERER'] ) ) ? esc_url( $_SERVER['HTTP_REFERER'] ) : '';

		if ( 1 == $log_options['log'] ) {
			global $wpdb;
			$table_name = $wpdb->prefix . 'redirect_404_hp_cp_log';
			$sql        = $wpdb->prepare( "INSERT INTO $table_name 
( url, referer ) 
values ( %s, %s )"
				, esc_url( $_SERVER['REQUEST_URI'] ), $referer );

			$result = $wpdb->query( $sql );
		}
		if ( 1 == $log_options['ignoreredirect'] ) {
			return false;
		}

		return true;
	}

	public function redirect_404( $template, $type, $templates ) {
		global $post;
		global $posts;
		global $wp_query;

		$options = Core::get_option( 'redirect-404-error-page-to-homepage-or-custom-page-settings' );
		if ( $options['type'] != 404 ) {
			return $template;
		}
		if ( ! $this->log_it() ) {  // log it will return false if no redirection required
			return $template;
		}

		$id = $options['pageid'];
		if ( 0 == $id ) {
			$id = get_option( 'page_on_front' );
		}
		$posts = get_posts( array( 'page_id' => $id, 'post_type' => 'page' ) );
		foreach ( $posts as $post ) {
			setup_postdata( $post );
		}
		$wp_query->posts      = $posts;
		$wp_query->post_count = count( $posts );
		$template             = get_page_template();

		return $template;
	}
}
