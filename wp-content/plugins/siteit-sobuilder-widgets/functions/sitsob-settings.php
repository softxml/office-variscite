<?php
/*

UNDER CONSTRUCTION - TO DO
- Include PHP Less to Css: https://github.com/leafo/lessphp
- create dynamic css file: https://github.com/leafo/lessphp
- get field data from "css file"
*/

// REGISTER SETTINGS PAGE MENU ITEM
add_action('admin_menu', function() {
	add_options_page( __('Siteit Widgets Settings', 'siteitsob'), __('Siteit Widgets Settings', 'siteitsob'), 'manage_options', 'sitsob-plugin-settings', 'sitsob_plugin_settings' );
});



add_action( 'admin_init', function() {
    register_setting( 'sitsob-plugin-settings', 'sitsob_css' );
});



// SETTINGS PAGE STRUCTURE
function sitsob_plugin_settings() {
	?>
	<div class="wrap">
		<form action="options.php" method="post">

			<?php
			// register page in options array
			settings_fields( 'sitsob-plugin-settings' );
			do_settings_sections( 'sitsob-plugin-settings' );
			?>

			<h2><?php _e('Siteit Widgets Settings', 'siteitsob'); ?></h2>

			<div id="sitsob-settings-tabs">
				<ul class="nav-tab-wrapper">
					<li><a href="#tabs-1">General</a></li>
					<li><a href="#tabs-2">Css</a></li>
				</ul>

				<div id="tabs-1">
					<p>Plugin Information:</p>
					<ul>
						<li>Name: <?php _e('Siteit Widgets Settings', 'siteitsob'); ?></li>
						<li>Version: <strong>1</strong></li>
						<li>Author: <a href="http://www.siteit.co.il" target="_blank"><strong>SiteIT</strong></a></li>
					</ul>
				</div>
				<div id="tabs-2">
					<p>This is the css used in the <strong>front end (live website)</strong> to display the widget and includes default settings.</p>
					<textarea name="sitsob_css" id="sitsob_css" class="widefat" cols="30" rows="30"><?php echo esc_attr( get_option( 'sitsob_css' ) ); ?></textarea>
				</div>
			</div>


			<p><?php submit_button(); ?></p>
		</form>

	</div>
	<?php
   }

?>