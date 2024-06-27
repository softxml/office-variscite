<?php
// if uninstall.php is not called by WordPress, die
if ( ! defined( 'WP_UNINSTALL_PLUGIN' ) ) {
	die;
}
global $wpdb;
$table_name = $wpdb->prefix . 'redirect_404_hp_cp_log';
$sql        = "DROP TABLE IF EXISTS " . $table_name;
$result     = $wpdb->query( $sql );

delete_option( "redirect-404-error-page-to-homepage-or-custom-page_db_version" );
// Cron
wp_clear_scheduled_hook( 'redirect_404_hp_cp' );

delete_option( 'redirect-404-error-page-to-homepage-or-custom-page-settings' );
delete_option( 'redirect-404-error-page-to-homepage-or-custom-page-log' );