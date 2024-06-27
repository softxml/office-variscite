<?php

/**
 * Fired during plugin activation
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    Redirect_404_Error_Page_To_Homepage_Or_Custom_Page
 * @subpackage Redirect_404_Error_Page_To_Homepage_Or_Custom_Page/includes
 */

/**
 * Fired during plugin activation.
 */

namespace Redirect_404_Error_Page_To_Homepage_Or_Custom_Page\Includes;

use Redirect_404_Error_Page_To_Homepage_Or_Custom_Page\Admin\Admin_Settings;

class Activator {

	/**
	 * set default settings
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
		// database set up
		global $wpdb;

		require_once ABSPATH . 'wp-admin/includes/upgrade.php';
		$charset_collate = $wpdb->get_charset_collate();

		$table_name = $wpdb->prefix . 'redirect_404_hp_cp_log';
		$sql        = "CREATE TABLE $table_name (
        ID int NOT NULL AUTO_INCREMENT,
		url varchar(2500),
		referer varchar(2500),
		logdate timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
		PRIMARY KEY  (ID)
	) $charset_collate;";
		dbDelta( $sql );


		add_option( 'redirect-404-error-page-to-homepage-or-custom-page_db_version', '1.0' );

		if ( ! wp_next_scheduled( 'redirect_404_hp_cp' ) ) {
			wp_schedule_event( time() - 30, 'daily', 'redirect_404_hp_cp' );
		}


		// options set up
		if ( ! Core::get_option( 'redirect-404-error-page-to-homepage-or-custom-page-settings' ) ) {
			update_option( 'redirect-404-error-page-to-homepage-or-custom-page-settings', Admin_Settings::option_defaults( 'redirect-404-error-page-to-homepage-or-custom-page-settings' ) );
		}
		if ( ! Core::get_option( 'redirect-404-error-page-to-homepage-or-custom-page-log' ) ) {
			update_option( 'redirect-404-error-page-to-homepage-or-custom-page-log', Admin_Settings::option_defaults( 'redirect-404-error-page-to-homepage-or-custom-page-log' ) );
		}
	}
}
