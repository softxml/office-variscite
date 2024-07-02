<?php
/**
 * Compare Addons Core.
 *
 * @package WOPB\Compare
 * @since v.1.1.0
 */

namespace WOPB;

defined('ABSPATH') || exit;

/**
 * Compare class.
 */
class Compare {

    /**
	 * Setup class.
	 *
	 * @since v.1.1.0
	 */
    public $demo_column;
    public $my_account_compare_end_point;
    public $default_settings;
    public function __construct() {
        $this->demo_column = 3;
        $this->my_account_compare_end_point = 'my-compare';
        $this->default_settings = $this->default_settings();
        $compare_position = wopb_function()->get_setting('compare_position');
        $compare_position_shop = wopb_function()->get_setting('compare_position_shop_page');

        add_action('wp_enqueue_scripts', array($this, 'add_compare_scripts'));
        add_action( 'template_redirect', array($this, 'compare_clear') );
        add_action('wp_ajax_wopb_compare', array($this, 'wopb_compare_callback'));
        add_action('wp_ajax_nopriv_wopb_compare', array($this, 'wopb_compare_callback'));
        add_action('wc_ajax_wopb_product_list', array($this, 'wopb_product_list_callback'));
        add_action('wp_ajax_wopb_product_list', array($this, 'wopb_product_list_callback'));
        add_action('wp_ajax_nopriv_wopb_product_list', array($this, 'wopb_product_list_callback'));

        if ( wopb_function()->get_setting('compare_nav_menu_enable') == 'yes' ) {
            if ( wopb_function()->get_setting('compare_nav_menu_shortcode') == 'yes' ) {
                add_shortcode('wopb_compare_nav', array($this, 'compare_nav_menu'));
            }else {
                add_filter('wp_nav_menu_items', array($this, 'nav_menu_item'), 10, 2);
            }
        }
        add_shortcode('wopb_compare_button', array($this, 'compare_btn_html'));
        add_shortcode('wopb_compare', array($this, 'compare_wrapper'));

        if ( wopb_function()->get_setting('compare_single_enable') == 'yes' ) {
            $position_filters = $this->button_position_filters();
            if( isset( $position_filters[$compare_position] ) ) {
                add_filter($position_filters[$compare_position], array($this, 'compare_button_in_single_product'), 10, 1);
            }
        }
        if ( wopb_function()->get_setting('compare_shop_enable') == 'yes') {
            $position_filters = $this->button_position_shop_filters();
            if( isset( $position_filters[$compare_position_shop] ) ) {
                add_filter($position_filters[$compare_position_shop], array($this, 'compare_button_in_cart'), 10, 2);
            }
        }
        if ( wopb_function()->get_setting('compare_my_account_enable') == 'yes' ) {
            $this->my_account_compare_endpoint();
            add_filter('woocommerce_account_menu_items', array($this, 'compare_my_account_menu_items'), 10, );
            add_filter( 'woocommerce_get_query_vars', array( $this, 'woocommerce_query_vars' ) );
            add_action( 'woocommerce_account_' . $this->my_account_compare_end_point . '_endpoint', function () {
                echo $this->compare_wrapper(); //phpcs:disable WordPress.Security.EscapeOutput.OutputNotEscaped
            });
        }
    }

    /**
     * Compare JS Script Add
     *
     * @since v.1.1.0
     * @return null
     */
    public function add_compare_scripts() {
        add_action('wp_head', array($this, 'header_element'));
        wp_enqueue_style('wopb-modal-css', WOPB_URL.'assets/css/modal.min.css', array(), WOPB_VER);
        wp_enqueue_style('wopb-animation-css', WOPB_URL.'assets/css/animation.min.css', array(), WOPB_VER);
        wp_enqueue_style('wopb-compare-style', WOPB_URL.'addons/compare/css/compare.min.css', array(), WOPB_VER );
//        wp_enqueue_script('wopb-script', WOPB_URL.'assets/js/wopb.js', array('jquery','wopb-flexmenu-script','wp-api-fetch'), WOPB_VER, true);
        wp_enqueue_script('wopb-compare', WOPB_URL.'addons/compare/js/compare.js', array('jquery'), WOPB_VER, true);
        $wopb_compare_localize = array(
            'ajax' => admin_url('admin-ajax.php'),
            'security' => wp_create_nonce('wopb-nonce')
        );
        $wopb_compare_localize = array_merge($wopb_compare_localize, wopb_function()->get_endpoint_urls());
        wp_localize_script('wopb-compare', 'wopb_compare', $wopb_compare_localize);
    }

    /**
     * Compare Addons Intitial Setup Action
     *
     * @since v.1.1.0
     * @return null
     */
    public function initial_setup() {

        // Set Default Value
        $initial_data = array(
            'compare_heading'       => 'yes',
            'compare_page'          => '',
            'compare_text'          => __('Compare', 'product-blocks'),
            'compare_added_text'    => __('Added', 'product-blocks'),
            'compare_single_enable' => 'yes',
            'compare_position'      => 'after_cart',
            'compare_action_added'  => 'popup',
            'wopb_compare'          => 'true',
        );
        foreach ($initial_data as $key => $val) {
            wopb_function()->set_setting($key, $val);
        }

        // Insert Compare Page
        $compare_arr  = array(
            'post_title'     => 'Compare',
            'post_type'      => 'page',
            'post_content'   => '<!-- wp:shortcode -->[wopb_compare]<!-- /wp:shortcode -->',
            'post_status'    => 'publish',
            'comment_status' => 'closed',
            'ping_status'    => 'closed',
            'post_author'    => get_current_user_id(),
            'menu_order'     => 0,
        );
        $compare_id = wp_insert_post( $compare_arr, false );
        if ( $compare_id ) {
            wopb_function()->set_setting('compare_page', $compare_id);
        }
    }

    /**
     * Clear Compare List.
     *
     * @since v.3.1.1
     */
    public function compare_clear() {
        if ( isset($_GET['wopb_compare_clear']) && isset($_COOKIE['wopb_compare']) ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
            wopb_function()->clear_compare_cookie();
        }
    }

    /**
     * Compare Add Action Callback.
     *
     * @since v.1.1.0
     * @return array | With Custom Message
     */
    public function wopb_compare_callback() {
        if ( !wp_verify_nonce($_REQUEST['wpnonce'], 'wopb-nonce') ) {
            return ;
        }

        $postId = sanitize_text_field($_POST['postid']);
        $action_type = sanitize_text_field($_POST['type']);
        $params = [
            'source' => 'ajax',
            'postid' => $postId,
            'added_action' => sanitize_text_field($_POST['added_action']),
            'action_type' => $action_type,
        ];

        if ( $postId && $action_type ) {
            $message = '';
            $data_id = wopb_function()->get_compare_id();
            if ( $action_type == 'add' ) {
                if ( !in_array($postId, $data_id) ) {
                    $data_id[] = $postId;
                    $message = esc_html__('Compare Added.', 'product-blocks');
                }
            } elseif ( $action_type == 'clear' ) {
                wopb_function()->clear_compare_cookie();
                $data_id = [];
                $message = esc_html__('Compare Item Clear.', 'product-blocks');
            }elseif($action_type != 'nav_popup') {
                if ( false !== $key = array_search($postId, $data_id) ) {
                    unset($data_id[$key]);
                    $message = esc_html__('Compare Removed.', 'product-blocks');
                }
            }
            setcookie('wopb_compare', wp_json_encode($data_id), time()+604800, '/'); // 7 Days
            $params['data_id'] = $data_id;
            return wp_send_json_success(
                array(
                    'html' => $this->compare_wrapper($params),
                    'compare_count' => count($data_id),
                    'demo_column' => $this->demo_column,
                    'message' => $message)
            );
        } else {
            return wp_send_json_error( __('Compare Not Added', 'product-blocks') );
        }
        die();
    }

    /**
     * Compare Wrapper
     *
     * @since v.3.1.7
     * @param $params
     * @return null
     */
    public function compare_wrapper($params = []) {
        $added_action = isset( $params['added_action'] ) ? $params['added_action'] : '';
        $content = '';
        $wrapper_class = '';
        if( $added_action == 'sidebar' ) {
            $content = $this->modal_header() . $this->compare_sidebar_content($params) . $this->modal_footer($params);
        }elseif ( $added_action == 'message' ) {
            $content = $this->compare_toast_message($params);
            $wrapper_class .= 'wopb-modal-toaster wopb-right-top';
        }else{
            ob_start();
             echo $this->modal_header() . $this->compare_modal_content($params) . $this->modal_footer($params) . $this->product_list_modal($params);
            $content = ob_get_clean();
            $wrapper_class .= 'wopb-compare-layout-' . wopb_function()->get_setting('compare_layout');
        }
        ob_start();
?>
        <div class="wopb-compare-wrapper <?php echo esc_attr($wrapper_class); ?>">
            <?php echo $content; ?>
        </div>
<?php
        return ob_get_clean();
    }

    /**
     * Modal Header
     *
     * @return string
     *@since v.3.1.7
     */
    public function modal_header() {
        $html = '<div class="wopb-modal-header">';
            $html .= '<span class="wopb-header-title">';
                $html .= __('Compare Products', 'product-blocks');
            $html .= '</span>';
            if ( wopb_function()->get_setting('compare_close_button') == 'yes' ) {
                $html .= '<a class="wopb-modal-close">';
                    $html .= '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                        <path d="M17.7137 17.7147L6.28516 6.28613M17.7137 6.28613L6.28516 17.7147" stroke="#070C1A" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>';
                $html .= '</a>';
            }
        $html .= '</div>';
        return $html;
    }

    /**
     * Modal Footer
     *
     * @param $params
     * @return string
     * @since v.3.1.7
     */
    public function modal_footer($params) {
        $post_id = isset($params['postid']) ? $params['postid'] : esc_attr(get_the_ID());
        $compare_data = isset($params['source']) && $params['source'] == 'ajax' ? $params['data_id'] : wopb_function()->get_compare_id();
        $data_redirect = !wp_doing_ajax() || (wp_doing_ajax() || isset($params['action_type']) && $params['action_type'] == 'add') ? 'data-redirect="' . get_permalink( get_the_ID() ) . '"' : '';
        $added_action = isset( $params['added_action'] ) ? $params['added_action'] : wopb_function()->get_setting('compare_action_added');
        $added_action = $added_action == 'message' ? 'clear_all' : $added_action;
        $html = '<div class="wopb-modal-footer">';
        if ( wopb_function()->get_setting('compare_clear') == 'yes' && count($compare_data) > 0 ) {
            $html .= '<a class="wopb-compare-clear-btn" data-action="clear" data-added-action="' . esc_attr($added_action) . '" data-postid="' . $post_id . '">';
                $html .= __('Clear All', 'product-blocks');
            $html .= '</a>';
        }
        if( $added_action == 'sidebar' ) {
            $html .= '<a class="wopb-lets-compare-btn wopb-compare-addon-btn"';
                $html .= 'data-postid="' . $post_id .'"';
                $html .= 'data-action="nav_popup" ';
                $html .= 'data-added-action="nav_popup"';
                $html .= 'data-open-animation="wopb-' . esc_attr( wopb_function()->get_setting('compare_modal_open_animation') ) .'"';
                $html .= 'data-close-animation="wopb-' . esc_attr( wopb_function()->get_setting('compare_modal_close_animation') ) . '"';
                $html .= 'data-modal-loader="' . esc_attr( wopb_function()->get_setting('compare_modal_loading') ) . '"';
            $html .= '>';
                $html .= __("Let's Compare", 'product-blocks');
            $html .= '</a>';
        }
        $html .= '</div>';
        return $html;
    }

    /**
     * Product List Callback.
     *
     * @since v.3.1.1
     * @return array
     */
    public function wopb_product_list_callback() {
        if ( !wp_verify_nonce($_REQUEST['wpnonce'], 'wopb-nonce') ) {
            return ;
        }
        $params = ['s' => isset($_POST['search']) ? sanitize_text_field($_POST['search']) : ''];
        $html = $this->product_list($params);
        return wp_send_json_success( array('html' => $html) );
        die();
    }

    /**
     * Get Product List.
     *
     * @since v.3.1.1
     * @param array
     * @return html
     */
    public function product_list($params = []) {
        $compare_data = isset($params['source']) && $params['source'] == 'ajax' ? $params['data_id'] : wopb_function()->get_compare_id();
        $query_args = array(
            'posts_per_page'    => 10,
            'post_type'         => 'product',
            'post_status'       => 'publish',
            'no_found_rows' => true,
            's' => isset($params['s']) && $params['s'] != '' ? $params['s'] : '',
        );
        $products = wc_get_products($query_args);
        if ( $products && count($products) > 0 ) {
            ob_start();
            foreach ( $products as $product ) {
                if( $product && ! in_array( $product->get_id(), $compare_data ) ) {
?>
                    <div class="wopb-compare-item wopb-compare-item-<?php echo esc_attr($product->get_id()) ?>">
                        <div class="wopb-compare-product-details">
                            <a href="<?php echo esc_url($product->get_permalink()) ?>" class="wopb-product-image">
                                <?php echo $product->get_image('shop_thumbnail') ?>
                            </a>
                            <div class="wopb-compare-product-content">
                                <div class="wopb-compare-product-name"><?php echo $product->get_title(); ?></div>
                                <div class="wopb-compare-product-review">
                                    <div class="wopb-star-rating" aria-label="product review">
                                        <span style="width:<?php echo esc_attr($product->get_average_rating() ? ($product->get_average_rating() / 5) * 100 : 0) ?>%"></span>
                                    </div>
                                    <span class="wopb-review-count">
                                       <?php echo esc_html($product->get_rating_count() . ' customer review', 'product-blocks');?>
                                    </span>
                                </div>
                                <div class="wopb-compare-product-price">
                                    <?php echo wp_kses_post($product->get_price_html()) ?>
                                </div>
                            </div>
                        </div>
                        <a class="wopb-add-to-compare-btn" data-action="add" data-added-action="product_list" data-postid="<?php echo esc_attr($product->get_id())?>">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 18 18" fill="none">
                                <path d="M18 9C18 9.19891 17.921 9.38968 17.7803 9.53033C17.6397 9.67098 17.4489 9.75 17.25 9.75H9.75V17.25C9.75 17.4489 9.67098 17.6397 9.53033 17.7803C9.38968 17.921 9.19891 18 9 18C8.80109 18 8.61032 17.921 8.46967 17.7803C8.32902 17.6397 8.25 17.4489 8.25 17.25V9.75H0.75C0.551088 9.75 0.360322 9.67098 0.21967 9.53033C0.0790178 9.38968 0 9.19891 0 9C0 8.80109 0.0790178 8.61032 0.21967 8.46967C0.360322 8.32902 0.551088 8.25 0.75 8.25H8.25V0.75C8.25 0.551088 8.32902 0.360322 8.46967 0.21967C8.61032 0.0790178 8.80109 0 9 0C9.19891 0 9.38968 0.0790178 9.53033 0.21967C9.67098 0.360322 9.75 0.551088 9.75 0.75V8.25H17.25C17.4489 8.25 17.6397 8.32902 17.7803 8.46967C17.921 8.61032 18 8.80109 18 9Z" fill="white"/>
                            </svg>
                        </a>
                    </div>
<?php
                }
            }
            return ob_get_clean();
        }
    }

    /**
     * Compare Nav Menu To Specific Location.
     *
     * @since v.3.1.1
     * @return html
     */
    public function nav_menu_item($items, $args) {
        $nav_location = wopb_function()->get_setting('compare_nav_menu_location');
        if ( $nav_location )  {
            $nav_menu = '<li class="menu-item">' . $this->compare_nav_menu() . '</li>';
            if ( $args->theme_location ) {
                if ( $args->theme_location == $nav_location ) {
                    $items .= $nav_menu;
                }
            }else {
                $items .= $nav_menu;
            }
        }
        return $items;
    }

    /**
     * Compare Nav Menu.
     *
     * @since v.3.1.1
     * @return html
     */
    public function compare_nav_menu() {
        add_filter('wopb_active_modal', '__return_true');
        $content = '';
        $compare_text = wopb_function()->get_setting('compare_nav_menu_type') == 'with_text' ?  '<span>' . __('Compare', 'product-blocks') . '</span>' : '';
        $nav_class = wopb_function()->get_setting('compare_nav_icon_position') == 'top_text' ? ' wopb-flex-column-dir' : '';
        $nav_click_action = wopb_function()->get_setting('compare_nav_click_action');
        ob_start();
?>
        <a
            class="wopb-compare-nav-item<?php echo $nav_class ?>"
            data-action="<?php echo $nav_click_action == 'popup' ? 'nav_popup' : 'redirect'?>"
            data-added-action="<?php echo $nav_click_action == 'popup' ? 'nav_popup' : ''?>"
            data-postid="<?php echo esc_attr(get_the_ID()) ?>"
            data-open-animation="wopb-<?php echo esc_attr( wopb_function()->get_setting('compare_modal_open_animation') ) ?>"
            data-close-animation="wopb-<?php echo esc_attr( wopb_function()->get_setting('compare_modal_close_animation') ) ?>"
            data-modal-loader="<?php echo esc_attr( wopb_function()->get_setting('compare_modal_loading') ) ?>"
            <?php echo $nav_click_action == 'redirect' ? 'data-redirect="' . esc_url(get_permalink(wopb_function()->get_setting('compare_page'))) .'"' : '' ?>
        >
            <?php if ( wopb_function()->get_setting('compare_nav_icon_position') == 'after_text' ) { echo $compare_text;} ?>
            <span class="wopb-compare-icon">
                <?php echo $this->compare_icons(wopb_function()->get_setting('compare_nav_icon'))?>
                <span class="wopb-compare-count"><?php echo esc_html(count(wopb_function()->get_compare_id())) ?></span>
            </span>
            <?php if ( wopb_function()->get_setting('compare_nav_icon_position') == 'before_text' || wopb_function()->get_setting('compare_nav_icon_position') == 'top_text' ) { echo $compare_text;} ?>
        </a>

    <?php
        return ob_get_clean();
    }

    /**
     * Compare HTML for Cart Button
     *
     * @param array $params
     * @return null
     * @since v.1.1.0
     */
    public function compare_btn_html($params = []) {
        $params['source'] = 'default';
        return wopb_function()->get_compare(get_the_ID(), $params);
    }


    /**
     * Compare Table Content
     *
     * @param array $params
     * @return html
     * @since v.3.1.1
     */
    public function compare_modal_content($params = []) {
        $compare_data = isset($params['source']) && $params['source'] == 'ajax' ? $params['data_id'] : wopb_function()->get_compare_id();
        global $wp_filesystem;
        if (! $wp_filesystem ) {
            require_once ABSPATH . 'wp-admin/includes/file.php';
            WP_Filesystem();
        }
        ob_start();
?>
        <div
            class="wopb-modal-body"
            data-outside_click="yes"
        >
            <?php
                if ( count($compare_data) == 0 && wopb_function()->get_setting('compare_hide_empty_table') == 'yes' ) {
                    echo $this->empty_product_message($params);
                }
                if ( !(wopb_function()->get_setting('compare_hide_empty_table') == 'yes' && count($compare_data) == 0) ) {
                    $demo_column = count($compare_data) < $this->demo_column ? $this->demo_column - count($compare_data) : 0;
                    $row_class = wopb_function()->get_setting('compare_first_row_sticky') ? 'wopb-sticky-row' : '';
                    $column_class = wopb_function()->get_setting('compare_first_column_sticky') ? 'wopb-sticky-column' : '';
                    $compare_add_product_button = wopb_function()->get_setting('compare_add_product_button');
            ?>
                    <table class="wopb-compare-table">
                        <thead>
                            <tr class="<?php echo $row_class ?>">
                                <th class="<?php echo $column_class ?>"><?php echo __('Action' ,' product-blocks'); ?></th>
                                <?php
                                    foreach ($compare_data as $key => $val) {
                                        $product = wc_get_product($val);
                                ?>
                                        <td class="wopb-compare-item wopb-compare-item-<?php echo esc_attr($product->get_id()) ?>">
                                            <a class="wopb-compare-remove" data-action="remove" data-added-action="popup" data-postid="<?php echo esc_attr($product->get_id()) ?>">
                                                <?php echo wopb_function()->svg_icon('delete') ?>
                                                <span><?php echo __('Delete' ,' product-blocks'); ?></span>
                                            </a>
                                        </td>
                                <?php
                                    }
                                    for ($i = 0; $i < $demo_column; $i++) {
                                        echo '<td class="wopb-demo-column"><span></span></td>';
                                    }
                                    if ( $compare_add_product_button == 'yes' ) {
                                ?>
                                    <td class="wopb-action-add-btn">
                                        <a class="wopb-compare-add-btn">
                                            <?php echo wopb_function()->svg_icon('plus_3')  ?>
                                            <span class="wopb-tooltip"><?php echo __('Add Product', 'product-blocks'); ?></span>
                                        </a>
                                    </td>
                                <?php } ?>
                            </tr>
                        </thead>
                        <tbody>
                    <?php
                        $table_columns = wopb_function()->get_setting('compare_table_columns');
                        $table_columns = !$table_columns ? $this->default_settings['compare_table_columns']['default'] : $table_columns;
                        foreach ($table_columns as $table_column) {
                            $row_class = 'wopb-' . $table_column['key'] . '-row';
                            $column_class = wopb_function()->get_setting('compare_first_column_sticky') ? 'wopb-sticky-column' : '';
                    ?>
                            <tr class="<?php echo $row_class ?>">
                                <th class="<?php echo $column_class ?>"><?php echo esc_html($table_column['label'], 'product-blocks') ?></th>
                                <?php
                                    foreach ($compare_data as $key => $val) {
                                        $product = wc_get_product($val);
                                ?>
                                        <td class="wopb-compare-item-<?php echo esc_attr($product->get_id()) ?>">
                                            <?php
                                                switch ($table_column['key']) {
                                                    case 'image':
                                                    case 'title':
                                            ?>
                                                        <a href="<?php echo esc_url($product->get_permalink()) ?>">
                                                            <?php
                                                                echo $table_column['key'] == 'image' ? $product->get_image('woocommerce_thumbnail') : $product->get_title()
                                                            ?>

                                                        </a>
                                            <?php
                                                        break;
                                                    case 'quantity':
                                            ?>
                                                        <div class="wopb-quantity-wrapper quantity">
                                                            <a class="wopb-add-to-cart-minus"><?php echo wopb_function()->svg_icon('minus_2') ?></a>
                                                            <input type="number" class="input-text qty text" value="1">
                                                            <a class="wopb-add-to-cart-plus"><?php echo wopb_function()->svg_icon('plus_2') ?></a>
                                                        </div>
                                            <?php
                                                    break;
                                                    case 'price':
                                                        echo $product->get_price_html() ? wp_kses_post($product->get_price_html()) : 'N/A';
                                                        break;
                                                    case 'description':
                                            ?>
                                                        <span class="wopb-description"><?php echo $product->get_short_description() ? wp_kses_post($product->get_short_description()) : 'N/A'; ?></span>
                                            <?php
                                                        break;
                                                    case 'stock_status':
                                                        if ( $product->is_purchasable() && $product->is_in_stock() ) {
                                                            echo $product->get_stock_quantity().' '.esc_html__('in stock', 'product-blocks');
                                                        }
                                                    break;
                                                    case 'add_to_cart':
                                                        $cart_btn_class = '';
                                                        $cart_text = $product->add_to_cart_text();
                                                        if ( $product->is_type('simple') && $product->is_in_stock() ) {
                                                            $cart_btn_class = 'ajax_add_to_cart';
                                                        }
                                            ?>
                                                    <span class="wopb-cart-action">
                                                        <a
                                                            href="<?php echo esc_url($product->add_to_cart_url()) ?>"
                                                            class="wopb-add-to-cart wopb-compare-addon-btn <?php echo esc_attr($cart_btn_class) ?>"
                                                            data-postid="<?php echo esc_attr($product->get_id()) ?>"
                                                        >
                                                            <?php echo esc_html($cart_text, 'product-blocks'); ?>
                                                        </a>
                                                        <a
                                                            href="<?php echo esc_url(wc_get_cart_url()) ?>"
                                                            class="wopb-add-to-cart wopb-compare-addon-btn wopb-view-cart"
                                                        >
                                                            <?php esc_html_e('View Cart', 'product-blocks'); ?>
                                                        </a>
                                                    </span>
                                            <?php
                                                    break;
                                                    case 'review':
                                            ?>
                                                        <div class="wopb-review-content">
                                                            <div class="wopb-star-rating" aria-label=""><span style="width:<?php echo esc_attr($product->get_average_rating() ? ($product->get_average_rating() / 5) * 100 : 0) ?>%"></span></div>
                                                            <span class="wopb-review-count">
                                                                <?php echo esc_html($product->get_rating_count() . ' customer review', 'product-blocks');?>
                                                            </span>
                                                        </div>
                                            <?php
                                                    break;
                                                    case 'additional':
                                                        ob_start();
                                                            wc_display_product_attributes( $product );
                                                        $additional = ob_get_clean();
                                            ?>
                                                    <span class="wopb-additional">
                                                        <?php echo $additional ? $additional : 'N/A' ?>
                                                    </span>
                                            <?php
                                                        break;
                                                    case 'weight':
                                                        $weight = $product->get_weight();
                                                        $weight = $weight ? ( wc_format_localized_decimal( $weight ) . ' ' . esc_attr( get_option( 'woocommerce_weight_unit' ) ) ) : 'N/A';
                                                        echo $weight;
                                                        break;
                                                    case 'sku':
                                                        echo esc_html($product->get_sku() ? $product->get_sku() : 'N/A');
                                                        break;
                                                    case 'dimensions':
                                                        echo $product->get_dimensions(false) ? esc_html(wc_format_dimensions($product->get_dimensions(false))) : 'N/A';
                                                        break;
                                            ?>

                                            <?php
                                                default:
                                                    break;
                                                }
                                            ?>
                                        </td>
                                <?php
                                    }
                                    for ($i = 0; $i < $demo_column; $i++) {
                                        $demo_content = '';
                                        $demo_column_class = '';
                                        if ( $table_column['key'] == 'image' ) {
                                            $demo_column_class = ' image';
                                            $demo_content = $wp_filesystem->get_contents( WOPB_PATH . 'assets/img/svg/placeholder.svg' );
                                        }
                                        echo '<td class="wopb-demo-column' . $demo_column_class . '"><span>' . $demo_content . '</span></td>';
                                    }
                                    if ( $compare_add_product_button == 'yes' ) {
                                ?>
                                    <td class="wopb-action-add-btn"></td>
                                <?php } ?>
                            </tr>
                    <?php } ?>
                        </tbody>
                    </table>
            <?php } ?>
        </div>
<?php
        return ob_get_clean();
    }

    /**
     * Compare Product List Modal
     *
     * @since v.3.1.1
     * @return null
     */
    public function product_list_modal($params) {
        $class = '';
        if( ( isset($params['added_action']) && $params['added_action'] != 'product_list' && $params['action_type'] == 'add' ) || !isset($params['added_action']) ) {
            $class = ' wopb-d-none';
        }
    ?>
        <div class="wopb-compare-product-list-modal wopb-d-none">
            <div class="wopb-product-list-content">
                <a class="wopb-product-list-close">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                        <path d="M17.7137 17.7147L6.28516 6.28613M17.7137 6.28613L6.28516 17.7147" stroke="#070C1A" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </a>
                <div class="wopb-product-list-body">
                    <div class="wopb-product-search">
                        <input type="text" class="wopb-search-input" placeholder="<?php echo __('Search for products by name...', 'product-blocks'); ?>">
                        <a class="wopb-search-icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none">
                                <path fill-rule="evenodd" clip-rule="evenodd" d="M11.2443 10.1741L15.2793 14.2091C15.4211 14.351 15.5008 14.5434 15.5007 14.7441C15.5006 14.9447 15.4209 15.1371 15.2789 15.2789C15.137 15.4208 14.9446 15.5004 14.7439 15.5003C14.5433 15.5003 14.3509 15.4205 14.2091 15.2786L10.1741 11.2436C8.96784 12.1778 7.45102 12.6175 5.93215 12.4731C4.41329 12.3287 3.00648 11.6111 1.99792 10.4663C0.989358 9.32145 0.454801 7.83542 0.502997 6.31047C0.551193 4.78552 1.17852 3.3362 2.25736 2.25736C3.3362 1.17852 4.78552 0.551193 6.31047 0.502997C7.83542 0.454801 9.32145 0.989358 10.4663 1.99792C11.6111 3.00648 12.3287 4.41329 12.4731 5.93215C12.6175 7.45102 12.1778 8.96784 11.2436 10.1741H11.2443ZM6.50056 10.9998C7.69403 10.9998 8.83862 10.5257 9.68254 9.68179C10.5265 8.83788 11.0006 7.69328 11.0006 6.49981C11.0006 5.30633 10.5265 4.16174 9.68254 3.31783C8.83862 2.47391 7.69403 1.99981 6.50056 1.99981C5.30708 1.99981 4.16249 2.47391 3.31858 3.31783C2.47466 4.16174 2.00056 5.30633 2.00056 6.49981C2.00056 7.69328 2.47466 8.83788 3.31858 9.68179C4.16249 10.5257 5.30708 10.9998 6.50056 10.9998Z" fill="#5A5A5A"/>
                            </svg>
                        </a>
                    </div>
                    <div class="wopb-product-list">
                        <?php echo $this->product_list($params) ?>
                    </div>
                </div>
            </div>
        </div>
    <?php
    }

    /**
     * Comparison Sidebar Content
     *
     * @since v.3.1.1
     * @param $params
     * @return html
     */
    public function compare_sidebar_content($params = []) {
        $compare_data = isset($params['source']) && $params['source'] == 'ajax' ? $params['data_id'] : wopb_function()->get_compare_id();
        ob_start();
    ?>
        <div
            class="wopb-modal-body"
            data-outside_click="yes"
        >
            <?php if ( count($compare_data) == 0 ) { echo $this->empty_product_message($params); } else{ ?>
                <div class="wopb-product-list">
                    <?php
                    foreach ($compare_data as $key => $val) {
                        $product = wc_get_product($val);
                        ?>
                        <div class="wopb-compare-item wopb-compare-item-<?php echo esc_attr($product->get_id()) ?>">
                            <div class="wopb-compare-product-details">
                                <a href="<?php echo esc_url($product->get_permalink()) ?>" class="wopb-product-image">
                                    <?php echo $product->get_image('woocommerce_thumbnail') ?>
                                </a>
                                <a class="wopb-compare-product-name" href="<?php echo esc_url($product->get_permalink()) ?>" data-action="remove">
                                    <?php echo $product->get_title() ?>
                                </a>
                            </div>
                            <a class="wopb-compare-remove" data-action="remove" data-added-action="sidebar" data-postid="<?php echo esc_attr($product->get_id()) ?>">
                                <?php echo wopb_function()->svg_icon('delete') ?>
                            </a>
                        </div>
                        <?php
                    }
                    ?>
                </div>
            <?php } ?>
        </div>
    <?php
        return ob_get_clean();
    }

    /**
     * Message When There Is No Compare Product
     *
     * @since v.3.1.1
     * @return null
     */
    public function empty_product_message($params = []) {
        $class = isset($params['added_action']) ? ' wopb-'.$params['added_action'] : '';
?>
        <div class="wopb-no-product<?php echo esc_attr($class); ?>">
            <div class="wopb-no-product-text"><?php esc_html_e('No products were added to compare list', 'product-blocks') ?></div>
            <a href="<?php echo wc_get_page_permalink( 'shop' )?>" class="wopb-retun-shop"><?php esc_html_e('Return to Shop', 'product-blocks') ?></a>
        </div>
<?php
    }

    /**
     * Comparison Sidebar Content
     *
     * @since v.3.1.1
     * @param $product_id
     * @return html
     */
    public function compare_toast_message($params) {
        $nav_click_action = wopb_function()->get_setting('compare_nav_click_action');
        $post_id = isset($params['postid']) ? $params['postid'] : esc_attr(get_the_ID());
        ob_start();
?>
        <?php
            if ( $post_id ) {
                $product = wc_get_product($post_id);
        ?>
                <div class="wopb-compare-item wopb-compare-item-<?php echo esc_attr($product->get_id()) ?>">
                    <span class="wopb-compare-image">
                        <?php echo $product->get_image('shop_thumbnail') ?>
                    </span>
                    <div class="wopb-compare-product-name"><span><?php echo $product->get_title(); ?></span> <?php echo __('has been added on compare list', 'product-blocks'); ?>.</div>
                </div>
        <?php } ?>
            <a
                class="wopb-compare-view-btn"
                data-action="redirect"
                data-postid="<?php echo $post_id; ?>"
                data-redirect="<?php echo esc_url(get_permalink(wopb_function()->get_setting('compare_page'))); ?>"
            >
                <?php echo __('View Compare List', 'product-blocks'); ?>
            </a>
<?php
        return ob_get_clean();
    }

    /**
     * Compare Button In Single Product Page
     *
     * @since v.3.1.5
     * @return null
     */
    public function compare_button_in_single_product($content) {
        return $content . $this->compare_btn_html();
    }

    /**
     * Compare Button In Shop Page
     *
     * @since v.3.1.1
     * @param $content
     * @param $args
     * @return html
     */
    public function compare_button_in_cart( $content, $args ) {
        if ( !wopb_function()->wopb_shop_builder_check() && is_shop() ) {
            return $content . $this->compare_btn_html();
        }
    }

    /**
     * Compare Button Position Filters in Shop Page
     *
     * @since v.3.1.5
     * @return array
     */
    public function button_position_shop_filters() {
        return array(
            'top_cart' => 'wopb_top_add_to_cart_loop',
            'before_cart' => 'wopb_before_add_to_cart_loop',
            'after_cart' => 'wopb_after_add_to_cart_loop',
            'bottom_cart' => 'wopb_bottom_add_to_cart_loop',
            'above_image' => 'wopb_before_shop_loop_title',
        );
    }

    /**
     * Compare Button Position Filters in Single Page
     *
     * @since v.3.1.5
     * @return array
     */
    public function button_position_filters() {
        return array(
            'top_cart' => 'wopb_top_add_to_cart',
            'before_cart' => 'wopb_before_add_to_cart',
            'after_cart' => 'wopb_after_add_to_cart',
            'bottom_cart' => 'wopb_bottom_add_to_cart',
        );
    }

    /**
     * Compare End Point Register For My Account
     *
     * @since v.3.1.1
     * @return null
     */
    public function my_account_compare_endpoint() {
        add_rewrite_endpoint( $this->my_account_compare_end_point, EP_ROOT | EP_PAGES );
        if ( !wopb_function()->get_setting($this->my_account_compare_end_point . '-endpoint') ) {
            flush_rewrite_rules();
            wopb_function()->set_setting($this->my_account_compare_end_point . '-endpoint', 'yes');
        }
    }

    /**
     * Compare Menu In My Account Menubar
     *
     * @since v.3.1.1
     * @param $menu_links
     * @return array
     */
    public function compare_my_account_menu_items($menu_links) {
        $menu_links[$this->my_account_compare_end_point] = __( 'Compare', 'text-domain' );
        return $menu_links;
    }

    /**
     * WooCommerce Query Var For Compare
     *
     * @since v.3.1.1
     * @param $query
     * @return array
     */
    public function woocommerce_query_vars($query) {
        $query[$this->my_account_compare_end_point] = $this->my_account_compare_end_point;
        return $query;
    }

    /**
     * CSS Push To Header
     *
     * @since v.3.1.1
     * @return null
     */
    public function header_element() {
        $compare_css = $this->get_compare_css();
        if ( $compare_css ) {
            $placeholderRegex = '/\{\{(\w+)\}\}/';
            $compare_css = preg_replace_callback($placeholderRegex, function ($matches) {
                $key = $matches[1];
                return wopb_function()->get_setting($key);
            }, $compare_css);
            echo '<style id="wopb-compare-internal-style">' . $compare_css . '</style>';
        }
    }

    /**
     * Compare Table Columns
     *
     * @since v.3.1.1
     * @param $default
     * @return array
     */
    public function compare_table_columns($default = '') {
        $default_options = array(
            ['key' => 'image','label' => __( 'Image','product-blocks' )],
            ['key' => 'title','label' => __( 'Title','product-blocks' )],
            ['key' => 'price','label' => __( 'Price','product-blocks' )],
            ['key' => 'stock_status','label' => __( 'Stock Status','product-blocks' )],
            ['key' => 'quantity','label' => __( 'Quantity','product-blocks' )],
            ['key' => 'add_to_cart','label' => __( 'Add To Cart','product-blocks' )],
            ['key' => 'review','label' => __( 'Review','product-blocks' )],
        );
        $options = array(
            ['key' => '','label' => __( 'Select Column','product-blocks' )],
            ...$default_options,
            ['key' => 'additional','label' => __( 'Additional','product-blocks' )],
            ['key' => 'description','label' => __( 'Description','product-blocks' )],
            ['key' => 'weight','label' => __( 'Weight','product-blocks' )],
            ['key' => 'dimensions','label' => __( 'Dimensions','product-blocks' )],
            ['key' => 'sku','label' => __( 'SKU','product-blocks' )],
        );
        return $default && $default == 'default' ? $default_options : $options;
    }

    /**
     * Compare LAyouts
     *
     * @since v.3.1.1
     * @param $key
     * @return array
     */
    public function compare_layouts($key = '') {
        $layouts = array(
            (object)['key' => 1, 'image' => WOPB_URL.'assets/img/addons/compare/layout_1.png', 'pro' => false],
            (object)['key' => 2, 'image' => WOPB_URL.'assets/img/addons/compare/layout_2.png', 'pro' => false],
            (object)['key' => 3, 'image' => WOPB_URL.'assets/img/addons/compare/layout_3.png', 'pro' => false],
            (object)['key' => 4, 'image' => WOPB_URL.'assets/img/addons/compare/layout_4.png', 'pro' => false],
            (object)['key' => 5, 'image' => WOPB_URL.'assets/img/addons/compare/layout_5.png', 'pro' => false],
        );
        if($key) {
            return isset($layouts[$key]) ? $layouts[$key] : '';
        }else {
            return $layouts;
        }
    }

    /**
     * Compare Icons
     *
     * @since v.3.1.1
     * @param $key
     * @return array
     */
    public function compare_icons($key='') {
        $icons = array(
            'compare_1' => wopb_function()->svg_icon('compare_1'),
            'compare_2' => wopb_function()->svg_icon('compare_2'),
            'compare_3' => wopb_function()->svg_icon('compare_3'),
            'compare_4' => wopb_function()->svg_icon('compare_4'),
            'compare_5' => wopb_function()->svg_icon('compare_5'),
            'compare_6' => wopb_function()->svg_icon('compare_6'),
            'compare_7' => wopb_function()->svg_icon('compare_7'),
            'compare_8' => wopb_function()->svg_icon('compare_8'),
        );
        return $key ? $icons[$key] : $icons;
    }

    /**
     * Dynamic CSS
     *
     * @since v.3.1.1
     * @return string
     */
    public function get_compare_css() {
        return ".wopb-compare-wrapper, .wopb-compare-table td p, .wopb-modal-toaster {
                    color: {{compare_text_color}};
                    font-size: {{compare_text_font_size}}px;
                    font-weight: {{compare_text_font_weight}};
                }
                .wopb-compare-wrapper .wopb-compare-table {
                    background: {{compare_table_background}};
                }
                .wopb-compare-table tr th {
                    color: {{compare_table_heading_color}} !important;
                    font-size: {{compare_heading_font_size}}px;
                    font-weight: {{compare_heading_font_weight}};
                    text-transform: {{compare_heading_font_case}};
                }
                .wopb-compare-table th, .wopb-compare-table td {
                    border: {{compare_layout_border_width}}px solid {{compare_border_color}} !important;
                    padding: {{compare_layout_column_padding_y}}px {{compare_layout_column_padding_x}}px !important;;
                }
                .wopb-compare-table tbody tr:nth-child(odd) td, .wopb-compare-table tbody tr:nth-child(odd) th {
                    background: {{compare_odd_column_background}} !important;
                }
                .wopb-compare-table tbody tr:nth-child(even) td, .wopb-compare-table tbody tr:nth-child(even) th {
                    background: {{compare_even_column_background}} !important;
                }
                .wopb-compare-wrapper a:not(.wopb-compare-addon-btn), a.wopb-compare-btn.wopb-compare-addon-btn.wopb-link {
                    color: {{compare_link_color}};
                }
                .wopb-compare-wrapper a:not(.wopb-compare-addon-btn):hover, a.wopb-compare-btn.wopb-compare-addon-btn.wopb-link:hover {
                    color: {{compare_button_hover_color}};
                }
                a.wopb-compare-btn.wopb-compare-addon-btn.wopb-link svg path {
                    stroke: {{compare_link_color}};
                }
                a.wopb-compare-btn.wopb-compare-addon-btn.wopb-link:hover svg path {
                    stroke: {{compare_button_hover_color}};
                }
                .wopb-compare-wrapper a:hover, .wopb-compare-addon-btn:hover, a.wopb-compare-btn.wopb-compare-addon-btn.wopb-link:hover {
                    color: {{compare_button_hover_color}};
                }
                a.wopb-compare-btn.wopb-compare-addon-btn.wopb-link:hover svg path {
                    stroke: {{compare_button_hover_color}};
                }
                .wopb-compare-addon-btn {
                    font-size: {{compare_button_font_size}}px;
                    font-weight: {{compare_button_font_weight}};
                    text-transform: {{compare_button_font_case}};
                }
                .wopb-compare-addon-btn:not(.wopb-link) {
                    background: {{compare_button_background}} !important;
                    padding: {{compare_layout_button_padding_y}}px {{compare_layout_button_padding_x}}px;
                    border-radius: {{compare_layout_button_radius}}px;
                }
                .wopb-compare-addon-btn:not(.wopb-link):hover {
                    background: {{compare_button_hover_color}} !important;
                }
                .wopb-compare-addon-btn:hover svg path {
                    stroke: {{compare_button_hover_color}};
                }
                .wopb-title-row td a {
                    font-size: {{compare_title_font_size}}px;
                    font-weight: {{compare_title_font_weight}};
                }
        ";
    }

    /**
     * Default settings for compare
     *
     * @since v.3.1.3
     * @return array
     */
    public function default_settings() {
        global $wopb_default_settings;
        $compare_settings = [
            'compare_page' => array(
                'type' => 'select',
                'label' => __('Select Compare Page', 'product-blocks'),
                'options' => wopb_function()->all_pages(true),
                'desc' => __('Select the page containing the ', 'product-blocks') . '[wopb_compare] ' . __('shortcode.', 'product-blocks')
            ),
            'compare_shop_enable' => array(
                'type' => 'toggle',
                'label' => __('Show Compare In Shop Page', 'product-blocks'),
                'default' => 'yes',
                'value' => 'yes',
                'desc' => __('Enable compare on your shop page.', 'product-blocks')
            ),
            'compare_single_enable' => array(
                'type' => 'toggle',
                'label' => __('Show Compare In Single Product Page', 'product-blocks'),
                'default' => 'yes',
                'value' => 'yes',
                'desc' => __('Enable compare on your single product page.', 'product-blocks')
            ),
            'compare_my_account_enable' => array(
                'type' => 'toggle',
                'label' => __('Add Compare Page To My Account', 'product-blocks'),
                'default' => 'yes',
                'value' => 'yes',
                'desc' => __('Enable compare page to my account.', 'product-blocks')
            ),
            'compare_nav_menu_enable' => array(
                'type' => 'toggle',
                'label' => __('Add Compare Menu In Navbar', 'product-blocks'),
                'default' => 'yes',
                'value' => 'yes',
                'desc' => __('Enable compare menu in navbar.', 'product-blocks'),
            ),
            'compare_nav_menu_location' => array(
                'type' => 'select',
                'label' => __('Nav Menu Location', 'product-blocks'),
                'options' => wopb_function()->wopb_nav_menu_location(),
                'default' => '',
            ),
            'compare_nav_menu_type' => array(
                'type' => 'radio',
                'label' => __('Type of Menu', 'product-blocks'),
                'default' => 'only_icon',
                'display' => 'inline-box',
                'depends' => ['key' =>'compare_nav_menu_enable', 'condition' => '==', 'value' => 'yes'],
                'options' => array(
                    'only_icon' => 'Only Icon',
                    'with_text' => 'With Text',
                ),
                'hr_line' => true,
                'hr_line2' => true,
            ),
            'compare_nav_icon' => array(
                'type' => 'radio',
                'label' => __('Choose Icon', 'product-blocks'),
                'display' => 'inline-box',
                'depends' => ['key' =>'compare_nav_menu_enable', 'condition' => '==', 'value' => 'yes'],
                'options' => $this->compare_icons(),
                'default' => 'compare_1',
            ),
            'compare_nav_icon_position' => array(
                'type' => 'radio',
                'label' => __('Icon Position', 'product-blocks'),
                'display' => 'inline-box',
                'depends' => [
                    ['key' =>'compare_nav_menu_enable', 'condition' => '==', 'value' => 'yes'],
                    ['key' =>'compare_nav_menu_type', 'condition' => '==', 'value' => 'with_text'],
                ],
                'options' => array(
                    'before_text' => 'Before Text',
                    'after_text' => 'After Text',
                    'top_text' => 'Top of Text',
                ),
                'default' => 'before_text',
            ),
            'compare_nav_click_action' => array(
                'type' => 'radio',
                'label' => __('Action After Click Menu', 'product-blocks'),
                'display' => 'inline-box',
                'depends' => ['key' =>'compare_nav_menu_enable', 'condition' => '==', 'value' => 'yes'],
                'options' => array(
                    'popup' => 'Popup',
                    'redirect' => 'Redirect',
                ),
                'default' => 'popup',
            ),
            'compare_nav_menu_shortcode' => array(
                'type' => 'toggle',
                'label' => __('Compare Menu Custom Position', 'product-blocks'),
                'default' => '',
                'value' => 'yes',
                'desc' => __('Enable shortcode for custom position.', 'product-blocks'),
                'depends' => ['key' =>'compare_nav_menu_enable', 'condition' => '==', 'value' => 'yes'],
                'note' => (object)[
                    'text' => __('Use this shortcode [wopb_compare_nav] for custom position of comparison menu on navbar.', 'product-blocks'),
                    'depends' => ['key' =>'compare_nav_menu_shortcode', 'condition' => '==', 'value' => 'yes'],
                ],
                'hr_line' => true
            ),
            'compare_first_column_sticky' => array(
                'type' => 'toggle',
                'label' => __('Freeze Table First Column', 'product-blocks'),
                'default' => 'yes',
                'value' => 'yes',
                'desc' => __('Enable freeze the compare table first column when scrolling horizontally.', 'product-blocks')
            ),
            'compare_first_row_sticky' => array(
                'type' => 'toggle',
                'label' => __('Freeze Table First Row', 'product-blocks'),
                'default' => 'yes',
                'value' => 'yes',
                'desc' => __('Enable freeze the compare table first row when scrolling vertically.', 'product-blocks')
            ),
            'compare_add_product_button' => array(
                'type' => 'toggle',
                'label' => __('Add New Product Button on Table', 'product-blocks'),
                'default' => 'yes',
                'value' => 'yes',
                'desc' => __('Enable add new product button on compare table.', 'product-blocks')
            ),
            'compare_close_button' => array(
                'type' => 'toggle',
                'label' => __('Close Button on Table', 'product-blocks'),
                'default' => 'yes',
                'value' => 'yes',
                'desc' => __('Enable close button at top right corner on compare table.', 'product-blocks')
            ),'compare_clear' => array(
                'type' => 'toggle',
                'label' => __('Clear Compare Product List', 'product-blocks'),
                'default' => 'yes',
                'value' => 'yes',
                'desc' => __('Enable clear all button at bottom right corner on compare table.', 'product-blocks')
            ),
            'compare_hide_empty_table' => array(
                'type' => 'toggle',
                'label' => __('Hide Compare Table If Empty', 'product-blocks'),
                'default' => '',
                'value' => 'yes',
                'desc' => __("Hide compare table if haven't any product.", 'product-blocks')
            ),
            'compare_button_type' => array(
                'type' => 'radio',
                'label' => __('Button Type', 'product-blocks'),
                'display' => 'inline-box',
                'options' => array(
                    'button' => 'Button',
                    'link' => 'Link',
                ),
                'default' => 'button',
            ),
            'compare_text' => array(
                'type' => 'text',
                'label' => __('Compare Button Text', 'product-blocks'),
                'default' => __('Add to Compare', 'product-blocks'),
                'desc' => __('Write your preferable text to show  on compare button', 'product-blocks')
            ),
            'compare_added_text' => array(
                'type' => 'text',
                'label' => __('Button Text After Add To Compare', 'product-blocks'),
                'default' => __('Added', 'product-blocks'),
                'desc' => __('Write your preferable text after clicking add to compare', 'product-blocks')
            ),
            'compare_position_shop_page' => array(
                'type' => 'select',
                'label' => __('Button Position on Shop Page', 'product-blocks'),
                'desc' => __("Choose where will place compare button on shop page.", 'product-blocks'),
                'options' => array(
                    'after_cart' => __( 'After Add to Cart','product-blocks' ),
                    'bottom_cart' => __( 'Bottom Add to Cart','product-blocks' ),
                    'top_cart' => __( 'Top Add to Cart','product-blocks' ),
                    'before_cart' => __( 'Before Add to Cart','product-blocks' ),
                    'above_image' => __( 'Above Image','product-blocks' ),
                    'shortcode' => __( 'Use Shortcode','product-blocks' ),
                ),
                'default' => 'after_cart',
                'note' => (object)[
                    'text' => __('Use this shortcode [wopb_compare_button] where you want to show compare button.', 'product-blocks'),
                    'depends' => ['key' =>'compare_position_shop_page', 'condition' => '==', 'value' => 'shortcode'],
                ]
            ),
            'compare_position' => array(
                'type' => 'select',
                'label' => __('Button Position on Single Page', 'product-blocks'),
                'desc' => __("Choose where will place compare button on single page.", 'product-blocks'),
                'options' => array(
                    'after_cart' => __( 'After Add to Cart','product-blocks' ),
                    'bottom_cart' => __( 'Bottom Add to Cart','product-blocks' ),
                    'top_cart' => __( 'Top Add to Cart','product-blocks' ),
                    'before_cart' => __( 'Before Add to Cart','product-blocks' ),
                    'shortcode' => __( 'Use Shortcode','product-blocks' ),
                ),
                'default' => 'after_cart',
                'note' => (object)[
                    'text' => __('Use this shortcode [wopb_compare_button] where you want to show compare button.', 'product-blocks'),
                    'depends' => ['key' =>'compare_position', 'condition' => '==', 'value' => 'shortcode'],
                ]
            ),
            'compare_button_icon_enable' => array(
                'type' => 'toggle',
                'label' => __('Button Icon', 'product-blocks'),
                'default' => 'yes',
                'value' => 'yes',
                'desc' => __("Enable button icon to display icon on compare button.", 'product-blocks')
            ),
            'compare_button_only_icon' => array(
                'type' => 'toggle',
                'label' => __('Use Only Icon', 'product-blocks'),
                'default' => 'yes',
                'value' => 'yes',
                'depends' => ['key' =>'compare_button_icon_enable', 'condition' => '==', 'value' => 'yes'],
                'desc' => __("Enable if you want to only icon on compare button.", 'product-blocks')
            ),
            'compare_button_icon' => array(
                'type' => 'radio',
                'label' => __('Choose Icon', 'product-blocks'),
                'display' => 'inline-box',
                'depends' => ['key' =>'compare_button_icon_enable', 'condition' => '==', 'value' => 'yes'],
                'options' => $this->compare_icons(),
                'default' => 'compare_1',
                'desc' => __("Choose icon display on compare button.", 'product-blocks')
            ),
            'compare_button_icon_position' => array(
                'type' => 'radio',
                'label' => __('Icon Position', 'product-blocks'),
                'display' => 'inline-box',
                'depends' => [
                    ['key' =>'compare_button_icon_enable', 'condition' => '==', 'value' => 'yes'],
                    ['key' =>'compare_button_only_icon', 'condition' => '==', 'value' => ''],
                ],
                'options' => array(
                    'before_text' => __('Before Text', 'product-blocks'),
                    'after_text' => __('After Text', 'product-blocks'),
                ),
                'default' => 'before_text',
            ),
            'compare_action_added' => array(
                'type' => 'radio',
                'label' => __('Action After Add to Compare', 'product-blocks'),
                'display' => 'inline-box',
                'options' => array(
                    'popup' => __( 'Popup','product-blocks' ),
                    'redirect' => __( 'Redirect to Page','product-blocks' ),
                    'sidebar' => __( 'Sidebar','product-blocks' ),
                    'message' => __( 'Show Message','product-blocks' ),
                ),
                'default' => 'popup',
                'desc' => __("Select option for what will happen after clicking on the compare button.", 'product-blocks')
            ),
            'compare_modal_loading' => array(
                'type' => 'radio',
                'label' => __('Compare Modal Loading', 'product-blocks'),
                'display' => 'inline-box',
                'depends' => ['key' =>'compare_action_added', 'condition' => '==', 'value' => ['popup','sidebar']],
                'options' => wopb_function()->modal_loaders(),
                'default' => 'loader_1',
                'desc' => __("Select loading icon to show before open compare modal.", 'product-blocks'),
            ),
            'compare_modal_open_animation' => array(
                'type' => 'select',
                'label' => __('Modal Opening Animation', 'product-blocks'),
                'depends' => ['key' =>'compare_action_added', 'condition' => '==', 'value' => ['popup','sidebar']],
                'options' => array(
                    '' => __( 'Select Animation','product-blocks' ),
                    'zoom_in' => __( 'Zoom In','product-blocks' ),
                    'shrink_in' => __( 'Shrink In','product-blocks' ),
                    'fade_in' => __( 'Fade In','product-blocks' ),
                    'flip_in' => __( 'Flip In','product-blocks' ),
                    'slide_up_in' => __( 'Slide Up','product-blocks' ),
                    'slide_down_in' => __( 'Slide Down','product-blocks' ),
                    'slide_left_in' => __( 'Slide Left','product-blocks' ),
                    'slide_right_in' => __( 'Slide Right','product-blocks' ),
                    'unfold' => __( 'Unfolding','product-blocks' ),
                    'blow_up' => __( 'Blow Up','product-blocks' ),
                ),
                'default' => 'zoom_in',
                'hr_line' => true
            ),
            'compare_modal_close_animation' => array(
                'type' => 'select',
                'label' => __('Modal Closing Animation', 'product-blocks'),
                'depends' => ['key' =>'compare_action_added', 'condition' => '==', 'value' => ['popup','sidebar']],
                'options' => array(
                    '' => __( 'Select Animation','product-blocks' ),
                    'zoom_out' => __( 'Zoom Out','product-blocks' ),
                    'shrink_out' => __( 'Shrink Out','product-blocks' ),
                    'fade_out' => __( 'Fade Out','product-blocks' ),
                    'flip_out' => __( 'Flip Out','product-blocks' ),
                    'slide_up_out' => __( 'Slide Up','product-blocks' ),
                    'slide_down_out' => __( 'Slide Down','product-blocks' ),
                    'slide_left_out' => __( 'Slide Left','product-blocks' ),
                    'slide_right_out' => __( 'Slide Right','product-blocks' ),
                    'fold' => __( 'Folding','product-blocks' ),
                    'blow_down' => __( 'Blow Down','product-blocks' ),
                ),
                'default' => 'zoom_out',
            ),
            'compare_table_columns' => array(
                'type' => 'select_item',
                'label' => __('Select Fields To Show In Comparison Table', 'product-blocks'),
                'desc' => __('Select the fields you want to include in the comparison table. To rearrange these fields, you can also drag and drop. You can also customize the label name according to your preferences.', 'product-blocks'),
                'default' => $this->compare_table_columns('default'),
                'options' => $this->compare_table_columns(),
            ),
            'compare_layout' => array(
                'type' => 'layout',
                'label' => __('Choose Comparison Table Layout', 'product-blocks'),
                'default' => 1,
                'options' => $this->compare_layouts(),
                'preview' => true,
                'variations' => [
                    1 => [
                        'compare_odd_column_background' => '',
                        'compare_even_column_background' => '',
                    ],
                    2 => [
                        'compare_odd_column_background' => '',
                        'compare_even_column_background' => '',
                    ],
                    3 => [
                        'compare_odd_column_background' => '',
                        'compare_even_column_background' => '',
                    ],
                    4 => [
                        'compare_odd_column_background' => '',
                        'compare_even_column_background' => '',
                    ],
                    5 => [
                        'compare_odd_column_background' => '',
                        'compare_even_column_background' => '',
                    ],
                ],
            ),
            'compare_preset' => array(
                'type' => 'preset_color',
                'label' => __('Browse Presets', 'product-blocks'),
                'default' => '1',
                'options' => [
                    '1' => ['#FF5845', '#070C1A', '#5A5A5A', '#E5E5E5', '#FFFFFF'],
                    '2' => ['#4558FF', '#1B233A', '#5A5A5A', '#E5E5E5', '#FFFFFF'],
                    '3' => ['#FF9B26', '#070C1A', '#5A5A5A', '#E5E5E5', '#FFFFFF'],
                    '4' => ['#FF4319', '#383838', '#5A5A5A', '#E5E5E5', '#FFFFFF'],
                    '5' => ['#2AAB6F', '#101010', '#5A5A5A', '#E5E5E5', '#FFFFFF'],
                ],
                'variations' => [
                    '1' => [
                        'compare_button_background' => '#FF5845',
                        'compare_link_color' => '#FF5845',
                        'compare_table_heading_color' => '#070C1A',
                        'compare_text_color' => '#5A5A5A',
                        'compare_border_color' => '#E5E5E5',
                        'compare_table_background' => '#FFFFFF',
                    ],
                    '2' => [
                        'compare_button_background' => '#4558FF',
                        'compare_link_color' => '#4558FF',
                        'compare_table_heading_color' => '#1B233A',
                        'compare_text_color' => '#5A5A5A',
                        'compare_border_color' => '#E5E5E5',
                        'compare_table_background' => '#FFFFFF',
                    ],
                    '3' => [
                        'compare_button_background' => '#FF9B26',
                        'compare_link_color' => '#FF9B26',
                        'compare_table_heading_color' => '#070C1A',
                        'compare_text_color' => '#5A5A5A',
                        'compare_border_color' => '#E5E5E5',
                        'compare_table_background' => '#FFFFFF',
                    ],
                    '4' => [
                        'compare_button_background' => '#FF4319',
                        'compare_link_color' => '#FF4319',
                        'compare_table_heading_color' => '#383838',
                        'compare_text_color' => '#5A5A5A',
                        'compare_border_color' => '#E5E5E5',
                        'compare_table_background' => '#FFFFFF',
                    ],
                    '5' => [
                        'compare_button_background' => '#2AAB6F',
                        'compare_link_color' => '#2AAB6F',
                        'compare_table_heading_color' => '#101010',
                        'compare_text_color' => '#5A5A5A',
                        'compare_border_color' => '#E5E5E5',
                        'compare_table_background' => '#FFFFFF',
                    ],
                ],
            ),
            'compare_table_background' => [
                'type'=> 'color',
                'label'=> __('Table Background', 'product-blocks'),
                'default' => '#FFFFFF'
            ],
            'compare_table_heading_color' => [
                'type'=> 'color',
                'label'=> __('Table Heading Color', 'product-blocks'),
                'default' => '#070C1A'
            ],
            'compare_border_color' => [
                'type'=> 'color',
                'label'=> __('Border Color', 'product-blocks'),
                'default' => '#E5E5E5'
            ],
            'compare_odd_column_background' => [
                'type'=> 'color',
                'label'=> __('Column(Odd) Background', 'product-blocks'),
                'default' => ''
            ],
            'compare_even_column_background' => [
                'type'=> 'color',
                'label'=> __('Column(Even) Background', 'product-blocks'),
                'default' => ''
            ],
            'compare_text_color' => [
                'type'=> 'color',
                'label'=> __('Text Color', 'product-blocks'),
                'default' => '#5A5A5A'
            ],
            'compare_button_background' => [
                'type'=> 'color',
                'label'=> __('Button Background', 'product-blocks'),
                'default' => '#FF5845'
            ],
            'compare_link_color' => [
                'type'=> 'color',
                'label'=> __('Link Color', 'product-blocks'),
                'default' => '#FF5845'
            ],
            'compare_button_hover_color' => [
                'type'=> 'color',
                'label'=> __('Button/Link Hover Color', 'product-blocks'),
                'default' => '#333'
            ],
            'compare_heading_font_size' => [
                'type'=> 'number',
                'plus_minus'=> true,
                'label'=> __('Font Size', 'product-blocks'),
                'default' => '16'
            ],
            'compare_heading_font_weight' => [
                'type'=> 'select',
                'label'=> __('Font Weight', 'product-blocks'),
                'options' => [
                    '400' => __('Normal', 'product-blocks'),
                    '500' => __('Medium', 'product-blocks'),
                    '600' => __('Semi Bold', 'product-blocks'),
                    '700' => __('Bold', 'product-blocks'),
                ],
                'default' => '400'
            ],
            'compare_heading_font_case' => [
                'type'=> 'tag',
                'label'=> __('Font Case', 'product-blocks'),
                'options' => [
                    'uppercase' => 'AB',
                    'capitalize' => 'Ab',
                    'lowercase' => 'ab',
                ],
                'default' => 'capitalize'
            ],
            'compare_button_font_size' => [
                'type'=> 'number',
                'plus_minus'=> true,
                'label'=> __('Font Size', 'product-blocks'),
                'default' => '16'
            ],
            'compare_button_font_weight' => [
                'type'=> 'select',
                'label'=> __('Font Weight', 'product-blocks'),
                'options' => [
                    '400' => __('Normal', 'product-blocks'),
                    '500' => __('Medium', 'product-blocks'),
                    '600' => __('Semi Bold', 'product-blocks'),
                    '700' => __('Bold', 'product-blocks'),
                ],
                'default' => '400'
            ],
            'compare_button_font_case' => [
                'type'=> 'tag',
                'label'=> __('Font Case', 'product-blocks'),
                'options' => [
                    'uppercase' => 'AB',
                    'capitalize' => 'Ab',
                    'lowercase' => 'ab',
                ],
                'default' => 'capitalize'
            ],
            'compare_title_font_size' => [
                'type'=> 'number',
                'plus_minus'=> true,
                'label'=> __('Font Size', 'product-blocks'),
                'default' => '14'
            ],
            'compare_title_font_weight' => [
                'type'=> 'select',
                'label'=> __('Font Weight', 'product-blocks'),
                'options' => [
                    '400' => __('Normal', 'product-blocks'),
                    '500' => __('Medium', 'product-blocks'),
                    '600' => __('Semi Bold', 'product-blocks'),
                    '700' => __('Bold', 'product-blocks'),
                ],
                'default' => '400'
            ],
            'compare_text_font_size' => [
                'type'=> 'number',
                'plus_minus'=> true,
                'label'=> __('Font Size', 'product-blocks'),
                'default' => '16'
            ],
            'compare_text_font_weight' => [
                'type'=> 'select',
                'label'=> __('Font Weight', 'product-blocks'),
                'options' => [
                    '400' => __('Normal', 'product-blocks'),
                    '500' => __('Medium', 'product-blocks'),
                    '600' => __('Semi Bold', 'product-blocks'),
                    '700' => __('Bold', 'product-blocks'),
                ],
                'default' => '400'
            ],
            'compare_layout_button_padding_y' => [
                'type'=> 'number',
                'plus_minus'=> true,
                'label'=> __('Padding (Top, Bottom)', 'product-blocks'),
                'default' => '6'
            ],
            'compare_layout_button_padding_x' => [
                'type'=> 'number',
                'plus_minus'=> true,
                'label'=> __('Padding (Left, Right)', 'product-blocks'),
                'default' => '12'
            ],
            'compare_layout_button_radius' => [
                'type'=> 'number',
                'plus_minus'=> true,
                'label'=> __('Corner Radius', 'product-blocks'),
                'default' => '4'
            ],
            'compare_layout_column_padding_y' => [
                'type'=> 'number',
                'plus_minus'=> true,
                'label'=> __('Padding (Top, Bottom)', 'product-blocks'),
                'default' => '12'
            ],
            'compare_layout_column_padding_x' => [
                'type'=> 'number',
                'plus_minus'=> true,
                'label'=> __('Padding (Left, Right)', 'product-blocks'),
                'default' => '12'
            ],
            'compare_layout_border_width' => [
                'type'=> 'number',
                'plus_minus'=> true,
                'label'=> __('Border Width', 'product-blocks'),
                'default' => '1'
            ],
        ];
        $wopb_default_settings = array_merge($wopb_default_settings, $compare_settings);
        return $compare_settings;
    }
}