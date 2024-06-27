<?php


namespace Redirect_404_Error_Page_To_Homepage_Or_Custom_Page\Admin;


/**
 * Class Settings
 * @package Redirect_404_Error_Page_To_Homepage_Or_Custom_Page\Admin
 */
class Admin_Tables {

	public $table_obj;
	protected $hook;
	protected $page_heading;
	/**
	 * Settings constructor.
	 *
	 * @param string $plugin_name
	 * @param string $version plugin version.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version     = $version;

	}


	public static function set_screen( $status, $option, $value ) {
		return $value;
	}




	public function add_table_page() {

	}

	public function screen_option() {

	}

	public function list_page() {
		?>
        <div class="wrap">
            <h2><?php echo esc_html__($this->page_heading); ?></h2>

            <div id="poststuff">
                <div id="post-body" class="metabox-holder columns-1">
                    <div id="post-body-content">
                        <div class="meta-box-sortables ui-sortable">
                            <form method="post">
								<?php
								$this->table_obj->prepare_items();
								$this->table_obj->display(); ?>
                            </form>
                        </div>
                    </div>
                </div>
                <br class="clear">
            </div>
        </div>
		<?php
	}

}
