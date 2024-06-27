<?php


/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 */

namespace Redirect_404_Error_Page_To_Homepage_Or_Custom_Page\Includes;

use Redirect_404_Error_Page_To_Homepage_Or_Custom_Page\Admin\Admin;
use Redirect_404_Error_Page_To_Homepage_Or_Custom_Page\Admin\Admin_Settings;
use Redirect_404_Error_Page_To_Homepage_Or_Custom_Page\Admin\Admin_Table_404_Log;
use Redirect_404_Error_Page_To_Homepage_Or_Custom_Page\Admin\Admin_Cron;
use Redirect_404_Error_Page_To_Homepage_Or_Custom_Page\FrontEnd\FrontEnd;


class Core {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 */
	protected $loader;

	/**
	 * The unique identifier of this plugin.
	 */
	protected $plugin_name;

	/**
	 * The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 *
	 * @since    1.0.0
	 * @access   public
	 *
	 */
	public function __construct(  ) {

		$this->plugin_name = 'redirect-404-error-page-to-homepage-or-custom-page';

		$this->version = REDIRECT_404_ERROR_PAGE_TO_HOMEPAGE_OR_CUSTOM_PAGE_PLUGIN_VERSION;

		$this->loader = new Loader();
		$this->filters();
		$this->set_locale();
		$this->settings_pages();
		$this->define_admin_hooks();
		$this->define_public_hooks();

	}


	/**
	 * @return void
	 */
	private function filters() {
		add_filter(
		/**
		 * Astra changes teh post title to their own string
		 * this filter sets it back to post title
		 *
		 * @param $title
		 *
		 * @return string
		 */
		'astra_the_404_page_title',
			function ( $title ) {
				return get_the_title();
			},
			10,
			1
		);
	}
	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new i18n();
		$this->loader->add_action( 'init', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function settings_pages() {

		$settings = new Admin_Settings( $this->get_plugin_name(), $this->get_version() );
		$this->loader->add_action( 'admin_menu', $settings, 'settings_setup' );

		$activeblocks = new Admin_Table_404_Log( $this->get_plugin_name(), $this->get_version() );
		$this->loader->add_filter( 'set-screen-option', $activeblocks, 'set_screen', 10, 3 );
		$this->loader->add_action( 'admin_menu', $activeblocks, 'add_table_page' );

	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @return    string    The name of the plugin.
	 * @since     1.0.0
	 * @access    public
	 */
	public function get_plugin_name() {
		return $this->plugin_name;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @return    string    The version number of the plugin.
	 * @since     1.0.0
	 * @access    public
	 */
	public function get_version() {
		return $this->version;
	}

	/**
	 * Responsible for defining all actions that occur in the admin area.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_admin = new Admin( $this->get_plugin_name(), $this->get_version() );

		$this->loader->add_action( 'admin_notices', $plugin_admin, 'display_admin_notice' );


		// Cron events
		$admin_cron = new Admin_Cron( $this->get_plugin_name(), $this->get_version() );
		$this->loader->add_action( 'redirect_404_hp_cp', $admin_cron, 'daily' );
	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		$plugin_public = new FrontEnd( $this->get_plugin_name(), $this->get_version());

		$this->loader->add_action( 'template_redirect', $plugin_public, 'redirect', 1001 );

		$this->loader->add_filter( '404_template', $plugin_public, 'redirect_404', 10, 3 );

	}

	/**
	 * handle legacy options  convert slug to page id  - keep for several releases.
	 *
	 * @since    1.3
	 * @access   public
	 */

	public static function get_option( $option ) {

		if ( 'redirect-404-error-page-to-homepage-or-custom-page-settings' == $option ) {
			// handle legacy options  convert slug to page id  - keep for several releases
			$orig         = array();
			$default      = array(
				'pageid' => 0,
				'type'   => 301
			);
			$orig['slug'] = get_option( 'redirect_404_to_homepage_or_custom_page', false );
			$orig['type'] = get_option( 'redirect_404_to_homepage_or_custom_page_type', false );
			if ( false !== $orig['slug'] ) {
				$post = get_page_by_path( $orig['slug'], OBJECT );
				if ( ! empty( $post ) ) {
					$default['pageid'] = $post->ID;
				}
				delete_option( 'redirect_404_to_homepage_or_custom_page' );
			}
			if ( false !== $orig['type'] ) {
				$default['type'] = $orig['type'];
				delete_option( 'redirect_404_to_homepage_or_custom_page_type' );
			}
			if ( false !== $orig['type'] || false !== $orig['slug'] ) {
				update_option( 'redirect-404-error-page-to-homepage-or-custom-page-settings', $default );
			}

			//
			return get_option( 'redirect-404-error-page-to-homepage-or-custom-page-settings', $default );
		}

		return get_option( $option );
	}

	/**
	 * Run the loader to execute all of the hooks with WordPress
	 *
	 * @since    1.0.0
	 * @access   public
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since    1.0.0
	 * @access   public
	 */
	public function get_loader() {
		return $this->loader;
	}

}
