<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 */

namespace Redirect_404_Error_Page_To_Homepage_Or_Custom_Page\Admin;

class Admin {

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

	/**
	 * Display admin notices to help users solve requirements for getting events.
	 *
	 * @access public
	 */
	public function display_admin_notice() {
		// Don't display notices to users that can't do anything about it.
		if ( ! current_user_can( 'manage_options' ) ) {
			return;
		}
		// Notices are only displayed on the dashboard, plugins, tools, and settings admin pages.
		$page             = get_current_screen()->base;
		$display_on_pages = array(
			'dashboard',
			'plugins',
			'tools',
			'options-general',
			'toplevel_page_redirect-404-error-page-to-homepage-or-custom-page',
			'redirect-404_page_redirect-404-error-page-to-homepage-or-custom-page-404-log-report',
		);
		if ( ! in_array( $page, $display_on_pages, true ) ) {
			return;
		}
		$notice = "";

		// Permalinks must be active.
		if ( ! get_option( 'permalink_structure' ) ) {
			$notice = sprintf( __( '%sPermalinks%s have to be enabled in order for the redirect to work. ( Redirect 404 Error Page to Homepage or Custom Page Plugin )', 'redirect-404-error-page-to-homepage-or-custom-page' ),
				'<a href="' . admin_url( 'options-permalink.php' ) . '">',
				'</a>' );
		}

		// Output notice HTML.
		if ( ! empty( $notice ) ) {
			printf( '<div id="message" class="notice notice-error"><p>%s</p></div>', wp_kses_post( $notice ) );
		}
	}
}
