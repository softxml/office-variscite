<?php
/**
 * Created
 * User: alan
 * Date: 04/04/18
 * Time: 16:35
 */

namespace Redirect_404_Error_Page_To_Homepage_Or_Custom_Page\Admin;


class Admin_Table_404_Log extends Admin_Tables {


	public function add_table_page() {

		$this->page_heading = __( '404 Log', 'redirect-404-error-page-to-homepage-or-custom-page' );
		$this->hook         = add_submenu_page(
			'redirect-404-error-page-to-homepage-or-custom-page',
			'404 Log',
			'404 Log',
			'manage_options',
			'redirect-404-error-page-to-homepage-or-custom-page-404-log-report',
			array( $this, 'list_page' )
		);

		add_action( "load-{$this->hook}", array( $this, 'screen_option' ) );
	}

	public function screen_option() {

		$option = 'per_page';
		$args   = [
			'label'   => __( '404 Log Entries', 'redirect-404-error-page-to-homepage-or-custom-page' ),
			'default' => 25,
			'option'  => 'logs_per_page'
		];

		add_screen_option( $option, $args );

		$this->table_obj = new List_Table_404_log();
	}

}
