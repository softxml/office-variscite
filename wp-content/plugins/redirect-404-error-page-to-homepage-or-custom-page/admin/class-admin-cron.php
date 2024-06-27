<?php


/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 */

namespace Redirect_404_Error_Page_To_Homepage_Or_Custom_Page\Admin;

use Redirect_404_Error_Page_To_Homepage_Or_Custom_Page\Includes\Core;


class Admin_Cron {

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

	public function daily() {
		set_time_limit( 0 );
		$this->purge_404_log();
		if ( $l = ini_get( 'max_execution_time' ) ) {
			set_time_limit( $l );
		}
	}


	private function purge_404_log() {
		global $wpdb;
		$options    = Core::get_option( 'redirect-404-error-page-to-homepage-or-custom-page-log' );
		$table_name = $wpdb->prefix . 'redirect_404_hp_cp_log';
		$sql        = $wpdb->prepare( "DELETE FROM $table_name 
WHERE logdate < CURRENT_DATE - INTERVAL %s DAY"
			, absint( $options['days'] ) );

		$result = $wpdb->query( $sql );

	}
}
