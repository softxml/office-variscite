<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       http://example.com
 * @since      1.0.0
 *
 * @package    Redirect_404_Error_Page_To_Homepage_Or_Custom_Page
 * @subpackage Redirect_404_Error_Page_To_Homepage_Or_Custom_Page/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 */

namespace Redirect_404_Error_Page_To_Homepage_Or_Custom_Page\Includes;

class i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'redirect-404-error-page-to-homepage-or-custom-page',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}


}
