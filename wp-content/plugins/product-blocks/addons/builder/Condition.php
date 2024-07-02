<?php
namespace WOPB;

defined('ABSPATH') || exit;

class Condition {
    public function __construct() {
        add_action('wp', array($this, 'checkfor_header_footer'), 999);
        add_filter('template_include', array($this, 'include_builder_files'), 999);
        add_action('admin_footer', array($this, 'builder_footer_callback'));
        add_action('admin_enqueue_scripts', array($this, 'load_media'));
        add_action('enqueue_block_editor_assets', array($this, 'register_scripts_back_callback'));
    }

    public function checkfor_header_footer() {
        $theme_name = get_template();
        $header_id = wopb_function()->conditions('header');
        $footer_id = wopb_function()->conditions('footer');
        
        if ( $header_id ) {
            if ( wp_is_block_theme() ) {
                add_action('wp_head', function() use ($header_id, $theme_name) {
                    $this->header_builder_template($header_id, $theme_name);
                });
            } else {
                add_action('get_header', function() use ($header_id, $theme_name) {
                    $this->header_builder_template($header_id, $theme_name);
                });
            }
		}
        if ( $footer_id ) {
            if ( wp_is_block_theme() ) {
                add_action('wp_footer', function() use ($footer_id, $theme_name) {
                    $this->footer_builder_template($footer_id, $theme_name);
                });
            } else {
                switch ($theme_name) {
                    case 'astra':
                        remove_all_actions( 'astra_footer' );
                        add_action( 'astra_footer', function() use ($footer_id, $theme_name) {
                            $this->footer_builder_template($footer_id, $theme_name);
                        });
                        break;
                    case 'generatepress':
                        remove_action( 'generate_footer', 'generate_construct_footer_widgets');
                        remove_action( 'generate_footer', 'generate_construct_footer' );
                        add_action( 'generate_footer', function() use ($footer_id, $theme_name) {
                            $this->footer_builder_template($footer_id, $theme_name);
                        });
                        break;
                    default:
                        add_action('get_footer', function() use ($footer_id, $theme_name) {
                            $this->footer_builder_template($footer_id, $theme_name);
                        });
                }
            }
		}
    }

    public function header_builder_template($header_id) {
        if ($header_id) {
            if ( !wp_is_block_theme() ) {
                require_once WOPB_PATH . 'addons/builder/templates/header.php';
                $templates   = [];
                $templates[] = 'header.php';
                remove_all_actions( 'wp_head' );
                ob_start();
                locate_template( $templates, true );
                ob_get_clean();
            }
            wopb_function()->register_scripts_common();
            ?> 
                <header id="wpob-header-template">
                    <?php echo wopb_function()->content($header_id); //phpcs:disable WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                </header> 
            <?php
        }
	}
    public function footer_builder_template($footer_id) {
        if ($footer_id) {
            if ( !wp_is_block_theme() ) {
                require_once WOPB_PATH . 'addons/builder/templates/footer.php';
                $templates   = [];
                $templates[] = 'footer.php';
                remove_all_actions( 'wp_footer' );
                ob_start();
                locate_template( $templates, true );
                ob_get_clean();
            }
            wopb_function()->register_scripts_common();
            ?> 
                <footer id="wpob-footer-template" role="contentinfo">
                    <?php echo wopb_function()->content($footer_id); //phpcs:disable WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                </footer> 
            <?php
        }
	}

    /**
     * Checking Statement of Archive Builder
     *
     * @since v.2.3.1
     * @return BOOLEAN | is Builder or not
     */
    public function is_archive_builder($single = false) {
        if ($single) {
            $type = get_post_meta(get_the_ID(), '_wopb_builder_type', true);
            return $type == 'single-product' ? false : true;
        } else {
            return  get_post_type( get_the_ID() ) == 'wopb_builder' ? true : false;
        }
    }

    public function register_scripts_back_callback(){
        global $pagenow;
        $builder = $this->is_archive_builder();
        $builder_type = get_post_meta( wopb_function()->get_ID(), '_wopb_builder_type', true );
//        if ($builder ) {
            if( $pagenow != 'widgets.php' ) {
                wp_enqueue_script( 'wp-editor' );
            }
            wp_enqueue_script( 'wopb-blocks-builder-script', WOPB_URL.'addons/builder/blocks.min.js', array('wp-i18n', 'wp-element', 'wp-blocks', 'wp-components'), WOPB_VER, true );
//        }
    }

    // Load Media
    public function load_media() {
        if (!$this->is_builder()) {
            return;
        }
        wp_enqueue_script('builder-script', WOPB_URL.'addons/builder/builder.js', array('jquery'), WOPB_VER, true);
        wp_enqueue_style('builder-style', WOPB_URL.'addons/builder/builder.css', array(), WOPB_VER);

        wp_localize_script('builder-script', 'builder_option', array(
            'security' => wp_create_nonce('wopb-nonce'),
            'ajax' => admin_url('admin-ajax.php')
        ));
    }

    public function include_builder_files($template) {
        $includes = wopb_function()->conditions('includes');
        return $includes ? $includes : $template;
    }

    public function is_builder() {
        global $post;
        return isset($_GET['post_type']) ? (sanitize_text_field($_GET['post_type']) == 'wopb_builder') : (isset($post->post_type) ? ($post->post_type == 'wopb_builder') : false); // phpcs:ignore WordPress.Security.NonceVerification.Recommended
    }


    public function builder_footer_callback() {

        if ($this->is_builder()) { ?>
            <form class="wopb-builder" action="">
                <div class="wopb-builder-modal">
                    <div class="wopb-popup-wrap">
                        <input type="hidden" name="action" value="wopb_new_post">
                        <input type="hidden" name="_wpnonce" value="<?php echo esc_attr(wp_create_nonce('wopb-nonce')); ?>">
                        <div class="wopb-builder-wrapper">
                            <div class="wopb-builder-left">
                                <div class="wopb-builder-left-content">
                                    <div class="wopb-builder-left-title">
                                        <label><?php esc_html_e('Name of Your Template', 'product-blocks'); ?></label>
                                        <div>
                                            <input type="text" name="post_title" class="wopb-title" placeholder="<?php esc_attr_e('Template Name', 'product-blocks'); ?>" />
                                        </div>
                                    </div>
                                    <div class="wopb-builder-left-title">
                                        <label><?php esc_html_e('Select Template Type', 'product-blocks'); ?></label>
                                        <div>
                                            <select name="post_filter">
                                                <option value=""><?php esc_html_e('--Select--', 'product-blocks'); ?></option>    
                                                <option value="single-product"><?php esc_html_e('Single Product', 'product-blocks'); ?></option>    
                                                <option value="archive"><?php esc_html_e('Product Archive', 'product-blocks'); ?></option>
                                                <option value="shop"><?php esc_html_e('Shop', 'product-blocks'); ?></option>
                                                <option value="cart"><?php esc_html_e('Cart', 'product-blocks'); ?></option>
                                                <option value="checkout"><?php esc_html_e('Checkout', 'product-blocks'); ?></option>
                                                <option value="thankyou"><?php esc_html_e('Thank You', 'product-blocks'); ?></option>
                                                <option value="my-account"><?php esc_html_e('My Account', 'product-blocks'); ?></option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="wopb-message"></div>
                                    <div class="wopb-builder-button">
                                    <button class="wopb-new-template"><?php esc_html_e('Create Template', 'product-blocks'); ?></button>
                                    <a class="wopb-edit-template" href="<?php echo esc_url(get_edit_post_link(get_the_ID())); ?>"><?php esc_html_e('Save & Edit Template', 'product-blocks'); ?></a>
                                    </div>
                                </div>
                            </div>

                            <div class="wopb-builder-right">
                                <div class="wopb-builder-archive-wrap">
                                    <div class="wopb-builder-right-title">
                                        <label>
                                            <?php esc_html_e('Where You Want to Display Your Template', 'product-blocks'); ?>
                                        </label>
                                        <span>
                                            <input type="checkbox" id="archive" name="archive" value="archive" class="wopb-single-select"/>
                                            <label for="archive"><?php esc_html_e('All Shop Archive Pages', 'product-blocks'); ?></label>
                                        </span>
                                        <span>
                                            <input type="checkbox" id="search" name="search" value="search" class="wopb-single-select"/>
                                            <label for="search"><?php esc_html_e('Shop Search Result', 'product-blocks'); ?></label>
                                        </span>
                                        <?php
                                        $taxonomy_list = wopb_function()->get_taxonomy_list();
                                        foreach ($taxonomy_list as $key => $val) { ?>
                                            <span>
                                                <input type="checkbox" name="<?php echo esc_attr($val); ?>" id="id-<?php echo esc_attr($key); ?>" value="<?php echo esc_attr($val); ?>" class="wopb-single-select"/>
                                                <label for="id-<?php echo esc_attr($key); ?>">
                                                    <?php
                                                        /* translators: %s: is name of page */
                                                        printf( __('All %s Pages', 'product-blocks'),  $val);
                                                    ?>
                                                </label>
                                            </span>
                                        <?php } ?>
                                    </div>
                                    <?php
                                    foreach ($taxonomy_list as $val) { ?>
                                    <div class="wopb-multi-select">
                                        <span class="wopb-multi-select-action">
                                            <?php
                                                /* translators: %s: is specific selection */
                                                printf( __('Specific %s', 'product-blocks'),  $val);
                                            ?>
                                        </span>
                                        <select class="multi-select-data select-<?php echo esc_attr($val); ?> wopb-multi-select-hide" name="<?php echo esc_attr($val); ?>_id[]" multiple="multiple" data-type="<?php echo esc_attr($val); ?>"></select>
                                        <div class="wopb-option-multiselect">
                                            <div class="multi-select-action"><ul></ul></div>
                                            <div class="wopb-search-dropdown">
                                                <input type="text" value="" placeholder="Search..." class="wopb-item-search"/>
                                                <div class="wopb-search-results"></div>
                                            </div>
                                        </div>
                                    </div>
                                    <?php } ?>
                                </div>

                                <div class="wopb-builder-single-wrap">
                                    <div class="wopb-builder-right-title">
                                        <label>
                                            <?php esc_html_e('Where You Want to Display Your Template', 'product-blocks'); ?>
                                        </label>
                                        <span>
                                            <input type="checkbox" id="allsingle" name="allsingle" value="allsingle" class="wopb-single-select"/>
                                            <label for="allsingle"><?php esc_html_e('All Product Single Pages', 'product-blocks'); ?></label>
                                        </span>
                                        <div class="wopb-multi-select">
                                            <span class="wopb-multi-select-action"><?php esc_html_e('Specific Product', 'product-blocks'); ?></span>
                                            <select class="multi-select-data select-single-product wopb-multi-select-hide" name="single_product_id[]" multiple="multiple" data-type="single_product"></select>
                                            <div class="wopb-option-multiselect">
                                                <div class="multi-select-action"><ul></ul></div>
                                                <div class="wopb-search-dropdown">
                                                    <input type="text" value="" placeholder="Search..." class="wopb-item-search"/>
                                                    <div class="wopb-search-results"></div>
                                                </div>
                                            </div>
                                        </div>
                                        <?php foreach ($taxonomy_list as $val) { ?>
                                        <div class="wopb-multi-select">
                                            <span class="wopb-multi-select-action">
                                                <?php
                                                /* translators: %s: is specific selection */
                                                printf( __('Specific %s', 'product-blocks'),  $val);
                                                ?>
                                            </span>
                                            <select class="multi-select-data select-single-<?php echo esc_attr($val); ?> wopb-multi-select-hide" name="single_<?php echo esc_attr($val); ?>_id[]" multiple="multiple" data-type="<?php echo esc_attr($val); ?>"></select>
                                            <div class="wopb-option-multiselect">
                                                <div class="multi-select-action"><ul></ul></div>
                                                <div class="wopb-search-dropdown">
                                                    <input type="text" value="" placeholder="Search..." class="wopb-item-search"/>
                                                    <div class="wopb-search-results"></div>
                                                </div>
                                            </div>
                                        </div>
                                        <?php } ?>
                                    </div>
                                </div>

                            </div>
                        </div>
                        <div class="wopb-builder-close"><span class="dashicons dashicons-no-alt"></span></div>
                    </div>
                </div>
            </form>
        </div>
        <?php    
        }
    }


}
