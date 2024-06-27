<?php
/**
 * Created
 * User: alan
 * Date: 03/04/18
 * Time: 16:45
 */

namespace Redirect_404_Error_Page_To_Homepage_Or_Custom_Page\Admin;

use WP_List_Table;


class List_Table_404_log extends WP_List_Table {

	/** Class constructor */
	public function __construct() {


		parent::__construct( [
			'singular' => __( '404 Log Entry', 'redirect-404-error-page-to-homepage-or-custom-page' ),
			//singular name of the listed records
			'plural'   => __( '404 Log Entries', 'redirect-404-error-page-to-homepage-or-custom-page' ),
			//plural name of the listed records
			'ajax'     => false
			//should this table support ajax?

		] );
	}

	public function no_items() {
		_e( 'No current 404 log entries', 'redirect-404-error-page-to-homepage-or-custom-page' );
	}

	function column_url( $item ) {

		// create a nonce
		$delete_nonce = wp_create_nonce( 'redirect_404_hp_cp_delete_log' );
		
		$title = '<strong>' . $item['url'] . '</strong>';

		$actions = array(
			'delete' => sprintf( '<a href="?page=%s&action=%s&logentries=%s&_wpnonce=%s">' . __( 'Delete', 'redirect-404-error-page-to-homepage-or-custom-page' ) . '</a>', esc_attr( $_REQUEST['page'] ), 'delete', absint( $item['ID'] ), $delete_nonce )
		);

		return $title . $this->row_actions( $actions );
	}

	/*
		function column_state( $item ) {


			$title = '<strong>' . $item['state'] . '</strong>';

			$actions = array(
				'link' => '<a href="#">Secure with Full Security Plugin?</a>'
			);

			return $title . $this->row_actions( $actions );
		}
	*/

	public function column_default( $item, $column_name ) {
		switch ( $column_name ) {
			case 'state':
			case 'url':
			case 'referer':
			case 'time':
			default:
				//			return print_r( $item, true ); //Show the whole array for troubleshooting purposes
		}

		return $item[ $column_name ];
	}

	function column_cb( $item ) {
		return sprintf(
			'<input type="checkbox" name="bulk-delete[]" value="%s" />', $item['ID']
		);
	}

	function get_columns() {
		$columns = [
			'cb'      => '<input type="checkbox" />',
			'url'     => __( 'URL that returned 404', 'redirect-404-error-page-to-homepage-or-custom-page' ),
			'state'   => '',
			'referer' => __( 'Referring URL if known', 'redirect-404-error-page-to-homepage-or-custom-page' ),
			'time'    => __( 'Date/Time', 'redirect-404-error-page-to-homepage-or-custom-page' ),
		];

		return $columns;
	}

	public function get_sortable_columns() {
		$sortable_columns = array(
			'time'    => array( 'time', true ),
			'url'     => array( 'url', true ),
			'referer' => array( 'referer', true )
		);

		return $sortable_columns;
	}

	public function get_bulk_actions() {
		$actions = [
			'bulk-delete' => 'Delete'
		];

		return $actions;
	}

	public function prepare_items() {

		/** Process bulk action */
		$this->process_bulk_action();

		$this->_column_headers = $this->get_column_info();

		$per_page     = $this->get_items_per_page( 'logs_per_page', 25 );
		$current_page = $this->get_pagenum();
		$total_items  = self::record_count();

		$this->set_pagination_args( [
			'total_items' => $total_items, //WE have to calculate the total number of items
			'per_page'    => $per_page //WE have to determine how many items to show on a page
		] );


		$this->items = self::get_log_entries( $per_page, $current_page );
	}

	public function process_bulk_action() {
		if ( isset( $_REQUEST['_wpnonce'] ) ) {
			$nonce = esc_attr( $_REQUEST['_wpnonce'] );
		} else {
			return;
		}


		//Detect when a bulk action is being triggered...
		if ( 'delete' === $this->current_action() ) {
			
			// In our file that handles the request, verify the nonce.

			if ( ! wp_verify_nonce( $nonce, 'redirect_404_hp_cp_delete_log' ) ) {
				// @TODO think about this message
				die( 'Go get a life script kiddies' );
				// die('ind delete');
			} else {
				self::delete_log_entries( absint( $_GET['logentries'] ) );
			}
		}
		// If the delete bulk action is triggered
		if ( ( isset( $_POST['action'] ) && $_POST['action'] == 'bulk-delete' )
		     || ( isset( $_POST['action2'] ) && $_POST['action2'] == 'bulk-delete' )
		) {
			if ( ! wp_verify_nonce( $nonce, 'bulk-' . $this->_args['plural'] ) ) {
				die( 'Go get a life script kiddies' );
			} else {
				$delete_ids = esc_sql( $_POST['bulk-delete'] );

				// loop over the array of record IDs and delete them
				foreach ( $delete_ids as $id ) {
					self::delete_log_entries( $id );
				}
			}

		}
	}


	public static function delete_log_entries( $id ) {
		// echo $id;
		// die('fasdf');
		global $wpdb;
		$table_name = $wpdb->prefix . 'redirect_404_hp_cp_log';

		$wpdb->delete(
			$table_name,
			[ 'ID' => $id ],
			[ '%d' ]
		);
	}

	public static function record_count() {
		global $wpdb;
		$table_name = $wpdb->prefix . 'redirect_404_hp_cp_log';
		$sql        = "SELECT COUNT(*) FROM $table_name";

		return $wpdb->get_var( $sql );
	}

	public static function get_log_entries( $per_page = 25, $page_number = 1 ) {

		global $wpdb;
		$table_name = $wpdb->prefix . 'redirect_404_hp_cp_log';
		$allowed_orderby = array(
			'time',
			'url',
			'referer'
		);

		$allowed_order = array(
			'ASC',
			'DESC'
		);
		// @TODO  only active
		$sql = "SELECT ID, url, referer, logdate as time FROM $table_name WHERE 1";

		if ( ! empty( $_REQUEST['orderby'] ) && in_array( $_REQUEST['orderby'], $allowed_orderby ) && ! empty( $_REQUEST['order'] ) && in_array( $_REQUEST['order'], $allowed_order ) ) {
			$sql .= ' ORDER BY ' . esc_sql( $_REQUEST['orderby'] );
			$sql .= ! empty( $_REQUEST['order'] ) ? ' ' . esc_sql( $_REQUEST['order'] ) : ' ASC';
		} else {
			$sql .= ' ORDER BY ID DESC';
		}

		$sql .= " LIMIT $per_page";
		$sql .= ' OFFSET ' . ( $page_number - 1 ) * $per_page;

		$results = $wpdb->get_results( $sql, 'ARRAY_A' );

		$return = array();
		if ( $results ) {
		}
		foreach ( $results as $result ) {
			$state = '';
			$url   = esc_url( $result['url'] );
			if ( self::ends_with( $url, ".php" ) ) {
				$url   = "<div style='border-left-color: #dc3232; border-left-style: solid; border-left-width: 4px;'>" . $url . "</div>";
				$state = __( 'Direct attempt on php code', 'redirect-404-error-page-to-homepage-or-custom-page' );
			} elseif ( self::ends_with( $url, ".html" ) ) {
				$url   = $state = "<div style='border-left-color: #ffb900; border-left-style: solid; border-left-width: 4px;'>" . $url . "</div>";
				$state = __( 'Direct attempt on php code', 'redirect-404-error-page-to-homepage-or-custom-page' );
			}
			$return[] = array(
				'ID'      => $result['ID'],
				'state'   => $state,
				'time'    => date_i18n( get_option( 'date_format' ) . ' ' . get_option( 'time_format' ), strtotime( $result['time'] ) ),
				'url'     => $url,
				'referer' => esc_url( $result['referer'] )
			);
		}

		return $return;
	}

	private static function ends_with( $haystack, $needle ) {
		$length = strlen( $needle );
		$x      = substr( $haystack, - $length );
		$y      = ( substr( $haystack, - $length ) === $needle );

		return $length === 0 ||
		       ( substr( $haystack, - $length ) === $needle );
	}
}
