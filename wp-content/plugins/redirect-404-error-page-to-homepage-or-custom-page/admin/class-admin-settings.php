<?php
/**
 * Created
 * User: alan
 * Date: 04/04/18
 * Time: 13:45
 */

namespace Redirect_404_Error_Page_To_Homepage_Or_Custom_Page\Admin;


use Redirect_404_Error_Page_To_Homepage_Or_Custom_Page\Includes\Core;
use AlanEFPluginDonation\PluginDonation;


class Admin_Settings extends Admin_Pages {

	protected $settings_page;
	protected $settings_page_id = 'toplevel_page_redirect-404-error-page-to-homepage-or-custom-page';
	// protected $settings_page_id = 'settings_page_redirect-404-error-page-to-homepage-or-custom-page-settings';
	protected $option_group = 'redirect-404-error-page-to-homepage-or-custom-page';
	protected $settings_title;

	/**
	 * Settings constructor.
	 *
	 * @param string $plugin_name
	 * @param string $version plugin version.
	 */

	public function __construct( $plugin_name, $version) {
		$this->plugin_name = $plugin_name;
		$this->version     = $version;


		$this->settings_title = esc_html__( 'Redirect 404', 'redirect-404-error-page-to-homepage-or-custom-page' );
		// $this->donation       = new PluginDonation(
		// 	'redirect-404-error-page-to-homepage-or-custom-page',
		// 	'toplevel_page_redirect-404-error-page-to-homepage-or-custom-page',
		// 	'redirect-404-error-page-to-homepage-or-custom-page/redirect-404-error-page-to-homepage-or-custom-page.php',
		// 	admin_url( 'admin.php?page=redirect-404-error-page-to-homepage-or-custom-page' ),
		// 	$this->settings_title
		// );
		//add_filter( 'plugindonation_lib_strings_redirect-404-error-page-to-homepage-or-custom-page', array( $this, 'set_strings' ) );
		parent::__construct();
	}

	public function register_settings() {
		/* Register our setting. */
		register_setting(
			$this->option_group,                         /* Option Group */
			'redirect-404-error-page-to-homepage-or-custom-page-settings',                   /* Option Name */
			array( $this, 'sanitize_settings' )          /* Sanitize Callback */
		);
		register_setting(
			$this->option_group,                         /* Option Group */
			'redirect-404-error-page-to-homepage-or-custom-page-log',                   /* Option Name */
			array( $this, 'sanitize_log' )          /* Sanitize Callback */
		);


		/* Add settings menu page */

		$this->settings_page = add_submenu_page(
			'redirect-404-error-page-to-homepage-or-custom-page',
			'Settings', /* Page Title */
			'Settings',                       /* Menu Title */
			'manage_options',                 /* Capability */
			'redirect-404-error-page-to-homepage-or-custom-page',                         /* Page Slug */
			array( $this, 'settings_page' )          /* Settings Page Function Callback */
		);

		register_setting(
			$this->option_group,                         /* Option Group */
			"{$this->option_group}-reset",                   /* Option Name */
			array( $this, 'reset_sanitize' )          /* Sanitize Callback */
		);

	}


	public function delete_options() {
		update_option( 'redirect-404-error-page-to-homepage-or-custom-page-settings', self::option_defaults( 'redirect-404-error-page-to-homepage-or-custom-page-settings' ) );
		update_option( 'redirect-404-error-page-to-homepage-or-custom-page-log', self::option_defaults( 'redirect-404-error-page-to-homepage-or-custom-page-log' ) );
		$options = Core::get_option( 'redirect-404-error-page-to-homepage-or-custom-page-log' );
	}

	public static function option_defaults( $option ) {
		switch ( $option ) {
			case 'redirect-404-error-page-to-homepage-or-custom-page-settings':
				return array(
					// set defaults
					'pageid' => 0,
					'type'   => '301',
				);
			case 'redirect-404-error-page-to-homepage-or-custom-page-log':
				return array(
					// set defaults
					'log'            => 0,
					'days'           => 60,
					'ignoreredirect' => 0,
				);
			default:
				return false;
		}
	}

	public function add_meta_boxes() {
		add_meta_box(
			'settings-1',                  /* Meta Box ID */
			__( 'Information', 'redirect-404-error-page-to-homepage-or-custom-page' ),               /* Title */
			array( $this, 'meta_box_information' ),  /* Function Callback */
			$this->settings_page_id,               /* Screen: Our Settings Page */
			'normal',                 /* Context */
			'default'                 /* Priority */
		);
		add_meta_box(
			'settings-2',                  /* Meta Box ID */
			__( 'Redirect Settings', 'redirect-404-error-page-to-homepage-or-custom-page' ),               /* Title */
			array( $this, 'meta_box_settings' ),  /* Function Callback */
			$this->settings_page_id,               /* Screen: Our Settings Page */
			'normal',                 /* Context */
			'default'                 /* Priority */
		);
		add_meta_box(
			'settings-3',                  /* Meta Box ID */
			__( '404 Log Settings', 'redirect-404-error-page-to-homepage-or-custom-page' ),               /* Title */
			array( $this, 'meta_box_logs' ),  /* Function Callback */
			$this->settings_page_id,               /* Screen: Our Settings Page */
			'normal',                 /* Context */
			'default'                 /* Priority */
		);


	}

	public function meta_box_information() {
		?>
        <table class="form-table">
            <tbody>
            <?php
			// Removed Donation Display 
			// $this->donation->display(); 
			?>
            <tr valign="top" class="alternate">
                <th scope="row"><?php esc_html_e( 'Welcome', 'redirect-404-error-page-to-homepage-or-custom-page' ); ?></th>

                <td>
                    <p>
						<?php esc_html_e( 'This plugin can be configured to redirect missing pages (404) to either the home page or a custom page as required', 'redirect-404-error-page-to-homepage-or-custom-page' ); ?>
                    </p>
                    <p>
						<?php esc_html_e( 'This is very useful if your site has been undergoing changes and Google still have pages that have gone in the index, this way you visitors will not get the standard 404 page, and educate Google that the missing pages are now the home page or custom page as required.', 'redirect-404-error-page-to-homepage-or-custom-page' ); ?>
                    </p>
                    <p>
						<?php esc_html_e( 'Or, if you have an ugly 404 page, you can now add a page as a custom error page and set the return code to 404 instead.', 'redirect-404-error-page-to-homepage-or-custom-page' ); ?>
                    </p>

                    <p>
						<?php printf( esc_html__( 'If you turn on logging below - your 404 logs are %shere%s', 'redirect-404-error-page-to-homepage-or-custom-page' ), '<a href="' . menu_page_url( 'redirect-404-error-page-to-homepage-or-custom-page-404-log-report', false ) . '">', '</a>' ); ?>
                    </p>

                </td>
            </tr>
            </tbody>
        </table>
		<?php
	}

	public function sanitize_settings( $settings ) {

		if ( isset( $settings['pageid'] ) && ( 0 == $settings['pageid'] ) && ( isset( $settings['type'] ) && 404 == $settings['type'] ) ) {
			add_settings_error( 'redirect-404-error-page-to-homepage-or-custom-page-settings',
				'redirect-404-error-page-to-homepage-or-custom-page-settings',
				__( 'Cant set home page to return a 404 code that would be a bad idea', 'redirect-404-error-page-to-homepage-or-custom-page' ),
				'error' );
			$settings['type'] = 301;
		}

		return $settings;
	}

	public function sanitize_log( $settings ) {

		if ( ! isset( $settings['log'] ) ) {
			$settings['log'] = 0;
		}
		if ( ! isset( $settings['ignoreredirect'] ) ) {
			$settings['ignoreredirect'] = 0;
		}

		return $settings;
	}


	public function meta_box_settings() {
		?>
		<?php
		$options = Core::get_option( 'redirect-404-error-page-to-homepage-or-custom-page-settings' );
		?>
        <table class="form-table">
            <tbody>
            <tr valign="top">
                <th scope="row"><?php esc_html_e( 'Redirect to', 'redirect-404-error-page-to-homepage-or-custom-page' ); ?></th>
                <td>
                    <label for="redirect-404-error-page-to-homepage-or-custom-page-settings[pageid]">
						<?php wp_dropdown_pages( array(
							'name'              => 'redirect-404-error-page-to-homepage-or-custom-page-settings[pageid]',
							'selected'          => $options['pageid'],
							'show_option_none'  => '- Home Page -',
							'option_none_value' => 0
						) ); ?>

						<?php esc_html_e( ' Page', 'redirect-404-error-page-to-homepage-or-custom-page' ); ?></label>
                </td>
            </tr>
            <tr valign="top" class="alternate">
                <th scope="row"><?php esc_html_e( 'Redirect Type', 'redirect-404-error-page-to-homepage-or-custom-page' ); ?></th>
                <td>
                    <input type="radio" name="redirect-404-error-page-to-homepage-or-custom-page-settings[type]"
                           value="301"<?php checked( '301' == $options['type'] ); ?> /> 301
                    <input type="radio" name="redirect-404-error-page-to-homepage-or-custom-page-settings[type]"
                           value="302"<?php checked( '302' == $options['type'] ); ?> /> 302
                    <input type="radio" name="redirect-404-error-page-to-homepage-or-custom-page-settings[type]"
                           value="404"<?php checked( '404' == $options['type'] ); ?> /> 404
                    <p>
                        <span class="description">
		                <?php printf( esc_html__( 'Only use 404 if you have written a custom page specifically for 404s. If you need more info on 301 vs 302 redirect, read the %s SEO blog %s article.', 'redirect-404-error-page-to-homepage-or-custom-page' ), '<a href="https://www.seoblog.com/2018/02/difference-301-302-redirect/" target="_blank">', '</a>' ); ?>
                        </span>
                    </p>
                </td>
            </tr>


            </tbody>
        </table>
		<?php
	}

	public function meta_box_logs() {

		$disabled = '';
		$options  = Core::get_option( 'redirect-404-error-page-to-homepage-or-custom-page-log' );
		?>

        <table class="form-table">
            <tbody>

            <tr valign="top">
                <th scope="row"><?php esc_html_e( 'Log', 'redirect-404-error-page-to-homepage-or-custom-page' ); ?></th>
                <td>
                    <label for="redirect-404-error-page-to-homepage-or-custom-page-log[log]"><input type="checkbox"
                                                                                                    name="redirect-404-error-page-to-homepage-or-custom-page-log[log]"
                                                                                                    id="redirect-404-error-page-to-homepage-or-custom-page-log[log]"
                                                                                                    value="1"
							<?php checked( '1', $options['log'] );
							echo esc_attr( $disabled ); ?>>
						<?php esc_html_e( 'Enable logging of 404 errors', 'redirect-404-error-page-to-homepage-or-custom-page' ); ?>
                    </label>
                </td>
            </tr>
            <tr valign="top">
                <th scope="row"><?php esc_html_e( 'Do not redirect', 'redirect-404-error-page-to-homepage-or-custom-page' ); ?></th>
                <td>
                    <label for="redirect-404-error-page-to-homepage-or-custom-page-log[ignoreredirect]"><input
                                type="checkbox"
                                name="redirect-404-error-page-to-homepage-or-custom-page-log[ignoreredirect]"
                                id="redirect-404-error-page-to-homepage-or-custom-page-log[ignoreredirect]"
                                value="1"
							<?php checked( '1', $options['ignoreredirect'] );
							echo esc_attr( $disabled ); ?>>
						<?php esc_html_e( 'Tick this if you want the plugin redirect settings to be IGNORED, any only log 404s', 'redirect-404-error-page-to-homepage-or-custom-page' ); ?>
                    </label>
                </td>
            </tr>
            <tr valign="top">
                <th scope="row"><?php esc_html_e( 'Log history', 'redirect-404-error-page-to-homepage-or-custom-page' ); ?></th>
                <td>
                    <label for="redirect-404-error-page-to-homepage-or-custom-page-log[days]"><input type="number"
                                                                                                     name="redirect-404-error-page-to-homepage-or-custom-page-log[days]"
                                                                                                     id="redirect-404-error-page-to-homepage-or-custom-page-log[days]"
                                                                                                     class="small-text"
                                                                                                     value="<?php echo $options['days']; ?>"
                                                                                                     min="0"
							<?php echo esc_attr( $disabled ); ?>
                        >
						<?php esc_html_e( 'Days', 'redirect-404-error-page-to-homepage-or-custom-page' ); ?></label>
                    <p>
                        <span class="description"><?php esc_html_e( 'Select the number of days to keep 404 log history', 'redirect-404-error-page-to-homepage-or-custom-page' ); ?></span>
                    </p>
                </td>
            </tr>


            </tbody>
        </table>
		<?php
	}

	public function set_strings( $strings ) {
		$strings = array(
			esc_html__( 'Gift a Donation', 'redirect-404-error-page-to-homepage-or-custom-page' ),
			// 0
			esc_html__( 'Hi, I\'m Alan and I built this free plugin to solve problems I had, and I hope it solves your problem too.', 'redirect-404-error-page-to-homepage-or-custom-page' ),
			// 1
			esc_html__( 'It would really help me know that others find it useful and a great way of doing this is to gift me a small donation', 'redirect-404-error-page-to-homepage-or-custom-page' ),
			// 2
			esc_html__( 'Gift a donation: select your desired option', 'redirect-404-error-page-to-homepage-or-custom-page' ),
			// 3
			esc_html__( 'My Bitcoin donation wallet', 'redirect-404-error-page-to-homepage-or-custom-page' ),
			// 4
			esc_html__( 'Gift a donation via PayPal', 'redirect-404-error-page-to-homepage-or-custom-page' ),
			// 5
			esc_html__( 'My Bitcoin Cash address', 'redirect-404-error-page-to-homepage-or-custom-page' ),
			// 6
			esc_html__( 'My Ethereum address', 'redirect-404-error-page-to-homepage-or-custom-page' ),
			// 7
			esc_html__( 'My Dogecoin address', 'redirect-404-error-page-to-homepage-or-custom-page' ),
			// 8
			esc_html__( 'Contribute', 'redirect-404-error-page-to-homepage-or-custom-page' ),
			// 9
			esc_html__( 'Contribute to the Open Source Project in other ways', 'redirect-404-error-page-to-homepage-or-custom-page' ),
			// 10
			esc_html__( 'Submit a review', 'redirect-404-error-page-to-homepage-or-custom-page' ),
			// 11
			esc_html__( 'Translate to your language', 'redirect-404-error-page-to-homepage-or-custom-page' ),
			// 12
			esc_html__( 'SUBMIT A REVIEW', 'redirect-404-error-page-to-homepage-or-custom-page' ),
			// 13
			esc_html__( 'If you are happy with the plugin then we would love a review. Even if you are not so happy feedback is always useful, but if you have issues we would love you to make a support request first so we can try and help.', 'redirect-404-error-page-to-homepage-or-custom-page' ),
			// 14
			esc_html__( 'SUPPORT FORUM', 'redirect-404-error-page-to-homepage-or-custom-page' ),
			// 15
			esc_html__( 'Providing some translations for a plugin is very easy and can be done via the WordPress system. You can easily contribute to the community and you don\'t need to translate it all.', 'redirect-404-error-page-to-homepage-or-custom-page' ),
			// 16
			esc_html__( 'TRANSLATE INTO YOUR LANGUAGE', 'redirect-404-error-page-to-homepage-or-custom-page' ),
			// 17
			esc_html__( 'As an open source project you are welcome to contribute to the development of the software if you can. The development plugin is hosted on GitHub.', 'redirect-404-error-page-to-homepage-or-custom-page' ),
			// 18
			esc_html__( 'CONTRIBUTE ON GITHUB', 'redirect-404-error-page-to-homepage-or-custom-page' ),
			// 19
			esc_html__( 'Get Support', 'redirect-404-error-page-to-homepage-or-custom-page' ),
			// 20
			esc_html__( 'WordPress SUPPORT FORUM', 'redirect-404-error-page-to-homepage-or-custom-page' ),
			// 21
			esc_html__( 'Hi I\'m Alan and I support the free plugin', 'redirect-404-error-page-to-homepage-or-custom-page' ),
			// 22
			esc_html__( 'for you.  You have been using the plugin for a while now and WordPress has probably been through several updates by now. So I\'m asking if you can help keep this plugin free, by donating a very small amount of cash. If you can that would be a fantastic help to keeping this plugin updated.', 'redirect-404-error-page-to-homepage-or-custom-page' ),
			// 23
			esc_html__( 'Donate via this page', 'redirect-404-error-page-to-homepage-or-custom-page' ),
			// 24
			esc_html__( 'Remind me later', 'redirect-404-error-page-to-homepage-or-custom-page' ),
			// 25
			esc_html__( 'I have already donated', 'redirect-404-error-page-to-homepage-or-custom-page' ),
			// 26
			esc_html__( 'I don\'t want to donate, dismiss this notice permanently', 'redirect-404-error-page-to-homepage-or-custom-page' ),
			// 27
			esc_html__( 'Hi I\'m Alan and you have been using this plugin', 'redirect-404-error-page-to-homepage-or-custom-page' ),
			// 28
			esc_html__( 'for a while - that is awesome! Could you please do me a BIG favor and give it a 5-star rating on WordPress? Just to help spread the word and boost my motivation..', 'redirect-404-error-page-to-homepage-or-custom-page' ),
			// 29
			esc_html__( 'OK, you deserve it', 'redirect-404-error-page-to-homepage-or-custom-page' ),
			// 30
			esc_html__( 'Maybe later', 'redirect-404-error-page-to-homepage-or-custom-page' ),
			// 31
			esc_html__( 'Already done', 'redirect-404-error-page-to-homepage-or-custom-page' ),
			// 32
			esc_html__( 'No thanks, dismiss this request', 'redirect-404-error-page-to-homepage-or-custom-page' ),
			// 33
			esc_html__( 'Donate to Support', 'redirect-404-error-page-to-homepage-or-custom-page' ),
			// 34
			esc_html__( 'Settings', 'redirect-404-error-page-to-homepage-or-custom-page' ),
			// 35
			esc_html__( 'Help Develop', 'redirect-404-error-page-to-homepage-or-custom-page' ),
			// 36
			esc_html__( 'Buy Me a Coffee makes supporting fun and easy. In just a couple of taps, you can donate (buy me a coffee) and leave a message. You don’t even have to create an account!', 'plugin-donation-lib' ),
			// 37
		);

		return $strings;
	}
}

