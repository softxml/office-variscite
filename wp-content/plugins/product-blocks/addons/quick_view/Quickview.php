<?php
/**
 * Quickview Addons Core.
 *
 * @package WOPB\Quickview
 * @since v.1.1.0
 */

namespace WOPB;

defined('ABSPATH') || exit;

/**
 * Quickview class.
 */
class Quickview
{

    /**
     * Setup class.
     *
     * @since v.1.1.0
     */
    public function __construct()
    {
        $this->default_settings();
        $quick_view_position = wopb_function()->get_setting('quick_view_position');
        $position_filters = $this->button_position_filters();
        add_action('wp_ajax_wopb_quickview', array($this, 'wopb_quickview_callback'));
        add_action('wp_ajax_nopriv_wopb_quickview', array($this, 'wopb_quickview_callback'));
        add_action('wp_enqueue_scripts', array($this, 'add_quickview_scripts'));

        // Quick view in default woocommerce shop pages
        if( isset( $position_filters[$quick_view_position] ) ) {
            add_filter($position_filters[$quick_view_position], array($this, 'quick_view_in_cart'), 10, 2);
        }
        if( $quick_view_position == 'shortcode' ) {
            add_shortcode('wopb_quick_view_button', array($this, 'quick_view_button'));
        }
    }

    /**
     * Quickview Addons Initial Setup Action
     *
     * @return NULL
     * @since v.2.1.8
     */
    public function add_quickview_scripts()
    {
        add_action('wp_head', array($this, 'header_element'));
        wp_enqueue_script('wc-add-to-cart-variation');
        wp_enqueue_script('wc-single-product');
        wp_enqueue_script('flexslider');

        wp_enqueue_style('wopb-modal-css', WOPB_URL.'assets/css/modal.min.css', array(), WOPB_VER);
        wp_enqueue_style('wopb-animation-css', WOPB_URL.'assets/css/animation.min.css', array(), WOPB_VER);
        wp_enqueue_style('wopb-quickview-style', WOPB_URL . 'addons/quick_view/css/quick_view.min.css', array(), WOPB_VER);
        wp_enqueue_style('wopb-slick-style', WOPB_URL.'assets/css/slick.css', array(), WOPB_VER);
        wp_enqueue_style('wopb-slick-theme-style', WOPB_URL.'assets/css/slick-theme.css', array(), WOPB_VER);
        wp_enqueue_script('wopb-slick-script', WOPB_URL.'assets/js/slick.min.js', array('jquery'), WOPB_VER, true);
//        wp_enqueue_script('wopb-script', WOPB_URL.'assets/js/wopb.js', array('jquery','wopb-flexmenu-script','wp-api-fetch'), WOPB_VER, true);
        wp_enqueue_script('wopb-quickview', WOPB_URL . 'addons/quick_view/js/quickview.js', array('jquery', 'wp-api-fetch'), WOPB_VER, true);

        wp_localize_script('wopb-quickview', 'wopb_quickview', array(
            'ajax' => admin_url('admin-ajax.php'),
            'security' => wp_create_nonce('wopb-nonce'),
            'isVariationSwitchActive' => wopb_function()->get_setting('wopb_variation_swatches')
        ));

    }


    /**
     * Quickview Addons Initial Setup Action
     *
     * @return NULL
     * @since v.1.1.0
     */
    public function initial_setup()
    {

        // Set Default Value
        $initial_data = array(
            'quickview_heading' => 'yes',
            'quickview_mobile_disable' => '',
            'quick_view_text' => __('Quick View', 'product-blocks'),
            'quickview_navigation' => 'yes',
            'quickview_link' => 'yes',
            'quickview_gallery_enable' => 'yes',
            'quickview_cart_redirect' => '',
            'quickview_image_disable' => '',
            'quickview_sales_disable' => '',
            'quickview_rating_disable' => '',
            'quickview_title_disable' => '',
            'quickview_price_disable' => '',
            'quickview_excerpt_disable' => '',
            'quickview_stock_disable' => '',
            'quickview_sku_disable' => '',
            'quickview_cart_disable' => '',
            'quickview_category_disable' => '',
            'quickview_tag_disable' => '',
            'wopb_quickview' => 'true'
        );
        foreach ($initial_data as $key => $val) {
            wopb_function()->set_setting($key, $val);
        }
    }

    /**
     * Quickview Add Action Callback.
     *
     * @return null
     * @since v.1.1.0
     */
    public function wopb_quickview_callback()
    {
        if ( ! ( isset( $_REQUEST['wpnonce'] ) && wp_verify_nonce(sanitize_key( wp_unslash( $_REQUEST['wpnonce'] ) ), 'wopb-nonce') ) ) {
            return;
        }
        if( wopb_function()->get_setting('quick_view_buy_now') ) {
            add_action('woocommerce_after_add_to_cart_button', array($this, 'quick_buy_now_button'), 100);
        }
        $params = [
            'post_id' => isset( $_POST['postid'] ) ? sanitize_text_field( $_POST['postid'] ): '',
            'post_list' => isset($_POST['postList'])? sanitize_text_field($_POST['postList']):''
        ];
        $image_effect = wopb_function()->get_setting('quick_view_image_effect');
        $image_effect_type = wopb_function()->get_setting('quick_view_image_effect_type');
    ?>
        <?php if( wopb_function()->get_setting('quick_view_close_button') == 'yes' ) { ?>
            <div class="wopb-modal-header">
                <a class="wopb-modal-close">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                        <path d="M17.7137 17.7147L6.28516 6.28613M17.7137 6.28613L6.28516 17.7147" stroke="#070C1A" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </a>
            </div>
        <?php } ?>
        <div
            class="wopb-modal-body"
            data-outside_click="<?php echo esc_attr(wopb_function()->get_setting('quick_view_close_outside_click')) ?>"
            data-product_id="<?php echo esc_attr($params['post_id']) ?>"
            data-modal_close_after_cart="<?php echo esc_attr(wopb_function()->get_setting('quick_view_close_add_to_cart')) ?>"
        >
            <div class="woocommerce-message wopb-d-none" role="alert"></div>
            <?php echo $this->quick_view_content($params); //phpcs:disable WordPress.Security.EscapeOutput.OutputNotEscaped ?>
        </div>
        <?php if ( $image_effect == 'yes' && $image_effect_type == 'popup' ) { ?>
            <div class="wopb-quick-view-zoom wopb-zoom-2 wopb-d-none">
                <a class="wopb-zoom-close wopb-modal-close-icon"></a>
                <img alt="Zoom Image" src="">
            </div>
        <?php
            }
            if ( wopb_function()->get_setting('quick_view_product_navigation') == 'yes' && $params['post_list'] ) {
                echo $this->quick_view_navigation($params); //phpcs:disable WordPress.Security.EscapeOutput.OutputNotEscaped
            }
        die();
    }

    /**
     * Quick View Navigation
     *
     * @since v.3.1.5
     * @param $params
     * @return null
     */
    public function quick_view_navigation($params) {
 ?>
        <div class="wopb-quick-view-navigation wopb-d-none">
            <?php
                $p_id = explode(',', $params['post_list']);
                $key = array_search($params['post_id'], $p_id);
                $key = isset($p_id[$key - 1]) ? $p_id[$key - 1] : '';
                if ( $key ) {
                    $thumbnail = get_post_thumbnail_id($key);
            ?>
                <div
                    class="wopb-nav-arrow wopb-quick-view-previous"
                    data-list="<?php echo esc_attr($params['post_list']) ?>"
                    data-postid="<?php echo esc_attr($key) ?>"
                    data-modal-loader="<?php echo esc_attr( wopb_function()->get_setting('quick_view_loader') ) ?>"
                >
                    <span>
                        <svg class="icon" viewBox="0 0 64 64">
                            <path id="arrow-left-1" d="M46.077 55.738c0.858 0.867 0.858 2.266 0 3.133s-2.243 0.867-3.101 0l-25.056-25.302c-0.858-0.867-0.858-2.269 0-3.133l25.056-25.306c0.858-0.867 2.243-0.867 3.101 0s0.858 2.266 0 3.133l-22.848 23.738 22.848 23.738z">
                            </path>
                        </svg>
                    </span>
                    <div class="wopb-quick-view-btn-image">
                        <?php
                            if ( $thumbnail ) {
                                $t_img = wp_get_attachment_image_src($thumbnail, 'thumbnail');
                                if ( isset( $t_img[0] ) ) {
                        ?>
                            <img src="<?php echo esc_attr($t_img[0]); ?>" />
                        <?php } } ?>
                        <h4><?php echo esc_html(get_the_title($key)); ?></h4>
                    </div>
                </div>
            <?php
                }
                $key = array_search($params['post_id'], $p_id);
                $key = isset( $p_id[$key + 1] ) ? $p_id[$key + 1] : '';
                if ( $key ) {
                    $thumbnail = get_post_thumbnail_id($key);
            ?>
                <div
                    class="wopb-nav-arrow wopb-quick-view-next"
                    data-list="<?php echo esc_attr($params['post_list']) ?>"
                    data-postid="<?php echo esc_attr($key) ?>"
                    data-modal-loader="<?php echo esc_attr( wopb_function()->get_setting('quick_view_loader') ) ?>"
                >
                    <span>
                        <svg class="icon" viewBox="0 0 64 64">
                            <path id="arrow-right-1" d="M17.919 55.738c-0.858 0.867-0.858 2.266 0 3.133s2.243 0.867 3.101 0l25.056-25.302c0.858-0.867 0.858-2.269 0-3.133l-25.056-25.306c-0.858-0.867-2.243-0.867-3.101 0s-0.858 2.266 0 3.133l22.848 23.738-22.848 23.738z">
                            </path>
                        </svg>
                    </span>
                    <div class="wopb-quick-view-btn-image">
                        <?php
                            if ( $thumbnail ) {
                                $t_img = wp_get_attachment_image_src($thumbnail, 'thumbnail');
                                if (isset($t_img[0])) {
                        ?>
                            <img src="<?php echo esc_attr($t_img[0]); ?>" />
                        <?php } } ?>
                        <h4><?php echo esc_html(get_the_title($key)); ?></h4>
                    </div>
                </div>
            <?php } ?>
        </div>
    <?php
    }

    /**
     * Quick View Contents
     *
     * @since v.3.1.5
     * @param $params
     * @return null
     */
    public function quick_view_content($params) {
        global $post;
        $post_id = $params['post_id'];
        $post = get_post( $post_id, OBJECT );
        setup_postdata( $post );
        $product = wc_get_product($post_id);
        $content_keys = array_column(wopb_function()->get_setting('quick_view_contents'), 'key');
        $quick_view_layout = wopb_function()->get_setting('quick_view_layout');
        if( $quick_view_layout == 2 ) {
    ?>
        <div class="wopb-product-info">
            <?php
                woocommerce_template_single_title();
                woocommerce_template_single_rating();
            ?>
        </div>
    <?php } ?>
        <div class="wopb-main-section">
            <?php
                if( in_array('image', $content_keys) ) {
                    echo $this->quick_view_image($params, $product); //phpcs:disable WordPress.Security.EscapeOutput.OutputNotEscaped
                }
            ?>
                <div class="wopb-quick-view-content">
                    <?php
                        foreach ( wopb_function()->get_setting('quick_view_contents') as $content ) {
                            switch ( $content['key'] ) {
                                case 'campaign_count':
                                    echo '';
                                    break;
                                case 'title':
                                    if( $quick_view_layout != 2 ) {
                                        woocommerce_template_single_title();
                                    }
                                    break;
                                case 'rating':
                                    if( in_array($quick_view_layout, [1, 4, 5]) ) {
                                        woocommerce_template_single_rating();
                                    }elseif( $quick_view_layout == 3 ) {
                                ?>
                                    <div class="wopb-rating-info">
                                        <?php
                                            woocommerce_template_single_rating();
                                            echo $this->stock_status($product); //phpcs:disable WordPress.Security.EscapeOutput.OutputNotEscaped
                                        ?>
                                    </div>
                                <?php
                                    }
                                    break;
                                case 'price':
                                    if( $product->get_price() ) {
                                        $save_percentage = ($product->get_sale_price() && $product->get_regular_price()) ? round(($product->get_regular_price() - $product->get_sale_price()) / $product->get_regular_price() * 100) . '%' : '';
                                        echo "<div>";
                                        woocommerce_template_single_price();
                                        if ( $save_percentage ) {
                                            echo '<span class="wopb-discount-percentage">' . esc_html( $save_percentage ) . '</span>';
                                        }
                                        echo "</div>";
                                    }
                                    break;
                                case 'description':
                                    woocommerce_template_single_excerpt();
                                    break;
                                case 'stock_status':
                                    if( $quick_view_layout != 3 ) {
                                        echo $this->stock_status($product); //phpcs:disable WordPress.Security.EscapeOutput.OutputNotEscaped
                                    }
                                    break;
                                case 'add_to_cart':
                                    ob_start();
                                        add_action('woocommerce_before_quantity_input_field', [$this, 'quick_view_before_add_to_cart_quantity']);
                                        add_action('woocommerce_after_quantity_input_field', [$this, 'quick_view_after_add_to_cart_quantity']);
                                        woocommerce_template_single_add_to_cart();
                                    echo ob_get_clean(); //phpcs:disable WordPress.Security.EscapeOutput.OutputNotEscaped
                                    break;
                                case 'delivery_info':
                                    echo '';
                                    break;
                                case 'payment_info':
                                    echo "";
                                    break;
                                case 'meta':
                                    woocommerce_template_single_meta();
                                    break;
                                case 'view_details':
                                    echo '<a class="wopb-product-details" href="' . esc_url( $product->get_permalink() ) . '">' . esc_html__( "View Full Product Details", "product-blocks" ) . '</a>';
                                    break;
                                case 'social_share':
                                    echo $this->social_share($product); //phpcs:disable WordPress.Security.EscapeOutput.OutputNotEscaped
                                    break;
                                default;
                                    break;
                            }
                        }
                    ?>
                </div>
        </div>

<?php
        wp_reset_postdata();
    }

    /**
     * Product Stock Status
     *
     * @param $product
     * @return null
     * @since v.3.1.5
     */
    public function stock_status($product) {
?>
        <div class="wopb-product-stock-status">
            <?php
            if( $product->get_stock_status() == 'instock' ) {
                echo '<span class="wopb-product-in-stock">'.esc_html__( "In Stock", "product-blocks" ).'</span>';
            }elseif( $product->get_stock_status() == 'outofstock' ) {
                echo '<span class="wopb-product-out-stock">'.esc_html__( "Out of stock", "product-blocks" ).'</span>';
            }
            ?>
        </div>
<?php
    }

    /**
     * Quick View Buy Now Button
     *
     * @since v.3.1.5
     * @return null
     */
    public function quick_buy_now_button() {
        global $product;
    ?>
        <div>
            <a
                class="wopb-quick-addon-btn wopb-quickview-buy-btn single_add_to_cart_button button alt"
                value="<?php echo esc_attr( $product->get_ID() ); ?>"
                data-cart_type="buy_now"
                data-redirect="<?php echo esc_url( wc_get_checkout_url() ); ?>"
            >
                <?php echo esc_html__('Buy Now', 'product-blocks') ?>
            </a>
        </div>
    <?php
    }

    /**
     * Quick View Image
     *
     * @since v.3.1.5
     * @param $params
     * @return null
     */
    public function quick_view_image($params, $product) {
        $image_effect = wopb_function()->get_setting('quick_view_image_effect');
        $image_effect_type = wopb_function()->get_setting('quick_view_image_effect_type');
        $image_wrapper_class = wopb_function()->get_setting('quick_view_thumbnail_freeze') == 'yes' ? ' wopb-image-sticky' : '';
?>
        <div class="wopb-quick-view-image <?php echo esc_attr($image_wrapper_class); ?>">
            <div class="<?php echo esc_attr($image_wrapper_class); ?>">
            <?php
                if (
                    $image_effect == 'yes' &&
                    $image_effect_type == 'zoom'
                ) {
            ?>
                <div class="wopb-zoom-image-outer wopb-zoom-1">
                    <div class="wopb-zoom-image-inner">
                        <img alt="Zoom Image">
                    </div>
                </div>
            <?php
                }
                $slick_html = function() use( $product ) {
                    $image_type = wopb_function()->get_setting('quick_view_image_type');
                    $quick_image_gallery = 'wopb-' . wopb_function()->get_setting('quick_view_image_gallery');
                    $quick_image_pagination = wopb_function()->get_setting('quick_view_image_pagination');
                    $save_percentage = ($product->get_sale_price() && $product->get_regular_price()) ? round(($product->get_regular_price() - $product->get_sale_price()) / $product->get_regular_price() * 100) . '%' : '';
                    $attachment = $product->get_image_id();
                    $gallery    = $product->get_gallery_image_ids();
                    $all_id = [];
                    if ( !empty( $attachment ) ) {
                        $all_id[] = $attachment;
                    }
                    if ( !empty($gallery) ) {
                        $all_id = array_merge($all_id, $gallery);
                    }
                    $image_full = $image_thumb = '';
                    $gallery_thumbnail = wc_get_image_size( 'gallery_thumbnail' );
                    $thumbnail_size = apply_filters( 'woocommerce_gallery_thumbnail_size', array( $gallery_thumbnail['width'], $gallery_thumbnail['height'] ) );
                    $full_size = apply_filters( 'woocommerce_gallery_full_size', apply_filters( 'woocommerce_product_thumbnails_large_size', 'full' ) );
                    foreach ( $all_id as $key => $attachment_id ) {
                        $thumbnail_src = wp_get_attachment_image_src( $attachment_id, $thumbnail_size );
                        $full_src = wp_get_attachment_image_src( $attachment_id, $full_size );
                        $alt_text = trim( wp_strip_all_tags( get_post_meta( $attachment_id, '_wp_attachment_image_alt', true ) ) );
                        $image_full .= '<div>';
                            $image_full .= '<a>';
                                $image_full .= '<img class="wopb-main-image" src="'.esc_url($full_src[0]).'" alt="'.esc_attr($alt_text).'" data-width="'.esc_attr($full_src[1]).'" data-height="'.esc_attr($full_src[2]).'"/>';
                            $image_full .= '</a>';
                        $image_full .= '</div>';
                        $image_thumb .= '<div>';
                            $image_thumb .= '<img src="'.esc_url($thumbnail_src[0]).'" alt="'.esc_attr($alt_text).'" />';
                        $image_thumb .= '</div>';
                    }
                    $slider_attr = '';
                    $image_class = $image_type;
                    $image_class .= ' wopb-' . wopb_function()->get_setting('quick_view_thumbnail_ratio');
                    if ( count( $all_id) > 1 ) {
                        $nav_html = '';
                        $nav_class = '';
                        $dot = '';
                        $position = '';
                        if( $image_type == 'image_with_gallery' ) {
                            $image_class .= $nav_class .= ' ' . $quick_image_gallery;
                            $position = wopb_function()->get_setting('quick_view_image_gallery');
                            if( $image_thumb ) {
                                $nav_html = wp_kses_post( $image_thumb );
                            }
                        }elseif( $image_type == 'image_with_pagination' ) {
                            $image_class .= $nav_class .= ' ' . $quick_image_pagination;
                            $dot = $quick_image_pagination == 'line' || $quick_image_pagination == 'dot' ? true : $dot;
                        }
                        $slider_attr = wc_implode_html_attributes(
                            array(
                                'data-arrow'  => true,
                                'data-dots'  => $dot,
                            )
                        );
                    }
            ?>
                <div class="wopb-quick-view-gallery <?php echo esc_attr( $image_class ) ?>">
                    <div class="wopb-thumbnail">
                        <div
                            class="<?php echo count( $all_id) > 1 ? 'wopb-quick-slider' : '' ?>"
                            <?php echo wp_kses_post( $slider_attr ) ?>
                        >
                            <?php if( $image_full) { echo wp_kses_post( $image_full );}  ?>
                        </div>
                        <?php if ( $save_percentage ) { ?>
                            <span class="wopb-quick-view-sale">-
                                <span><?php echo esc_html__('Sale!', 'product-blocks'); ?></span>
                            </span>
                        <?php } ?>
                    </div>
                    <?php
                        if ( isset($nav_html) && $nav_html ) {
                    ?>
                        <div
                            class="wopb-quick-slider-nav<?php echo esc_attr( $nav_class ) ?>"
                            data-arrow="true"
                            data-collg="4"
                            data-colmd="4"
                            data-colsm="2"
                            data-colxs="2"
                            data-position=<?php echo esc_attr($position); ?>
                        >
                            <?php echo $nav_html; //phpcs:disable WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                        </div>
                    <?php } ?>
                </div>
            </div>
<?php
        };
        $gallery_classes = function($classes) {
            return $classes;
        };
        add_action('woocommerce_product_thumbnails', $slick_html);
        add_filter('woocommerce_single_product_image_gallery_classes', $gallery_classes);
        add_filter('woocommerce_single_product_image_thumbnail_html', function () {
            return '';
        }, 10, 2);
        echo woocommerce_show_product_images(); //phpcs:disable WordPress.Security.EscapeOutput.OutputNotEscaped
        remove_filter('woocommerce_single_product_image_gallery_classes', $gallery_classes);
        remove_filter('woocommerce_single_product_image_thumbnail_html', function () {
            return '';
        }, 10, 2);
        remove_action('woocommerce_product_thumbnails', $slick_html);
?>
        </div>
<?php
    }

    /**
     * Before Quantity
     *
     * @since v.3.1.5
     * @return null
     */
    public function quick_view_before_add_to_cart_quantity()
    {
        echo '<span class="wopb-add-to-cart-minus"><svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 18 18" fill="none"><path d="M14.25 9.74854H3.75V8.24854H14.25V9.74854Z" fill="#070C1A"/></svg></span>';
    }

    /**
     * After Quantity
     *
     * @since v.3.1.5
     * @param $params
     * @return null
     */
    public function quick_view_after_add_to_cart_quantity()
    {
        echo '<span class="wopb-add-to-cart-plus"><svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 18 18" fill="none"><path d="M14.25 9.74854H9.75V14.2485H8.25V9.74854H3.75V8.24854H8.25V3.74854H9.75V8.24854H14.25V9.74854Z" fill="#070C1A"/></svg></span>';
    }

    /**
     * Product social share icons
     * 
     * @since v.3.1.5
     * @param $product
     * @return null
     * 
     */
    public function social_share($product) {
        $link = $product->get_permalink();
    ?>
        <div class="wopb-product-social-share">
            <span><?php echo esc_html__('Social Share','product-blocks'); ?></span>
            <a href="<?php echo esc_url( 'http://www.facebook.com/sharer.php?u='.$link ) ?>" target="_blank" class="wopb-share-facebook">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M22 12C22 6.48 17.52 2 12 2C6.48 2 2 6.48 2 12C2 16.84 5.44 20.87 10 21.8V15H8V12H10V9.5C10 7.57 11.57 6 13.5 6H16V9H14C13.45 9 13 9.45 13 10V12H16V15H13V21.95C18.05 21.45 22 17.19 22 12Z" fill="white"/>
                </svg>
            </a>
            <a href="<?php echo esc_url( 'https://www.linkedin.com/sharing/share-offsite/?url='.$link ) ?>" target="_blank" class="wopb-share-linkedin">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M19 3C19.5304 3 20.0391 3.21071 20.4142 3.58579C20.7893 3.96086 21 4.46957 21 5V19C21 19.5304 20.7893 20.0391 20.4142 20.4142C20.0391 20.7893 19.5304 21 19 21H5C4.46957 21 3.96086 20.7893 3.58579 20.4142C3.21071 20.0391 3 19.5304 3 19V5C3 4.46957 3.21071 3.96086 3.58579 3.58579C3.96086 3.21071 4.46957 3 5 3H19ZM18.5 18.5V13.2C18.5 12.3354 18.1565 11.5062 17.5452 10.8948C16.9338 10.2835 16.1046 9.94 15.24 9.94C14.39 9.94 13.4 10.46 12.92 11.24V10.13H10.13V18.5H12.92V13.57C12.92 12.8 13.54 12.17 14.31 12.17C14.6813 12.17 15.0374 12.3175 15.2999 12.5801C15.5625 12.8426 15.71 13.1987 15.71 13.57V18.5H18.5ZM6.88 8.56C7.32556 8.56 7.75288 8.383 8.06794 8.06794C8.383 7.75288 8.56 7.32556 8.56 6.88C8.56 5.95 7.81 5.19 6.88 5.19C6.43178 5.19 6.00193 5.36805 5.68499 5.68499C5.36805 6.00193 5.19 6.43178 5.19 6.88C5.19 7.81 5.95 8.56 6.88 8.56ZM8.27 18.5V10.13H5.5V18.5H8.27Z" fill="white"/>
                </svg>
            </a>
            <a href="<?php echo esc_url( 'http://twitter.com/share?url='.$link ) ?>" target="_blank" class="wopb-share-twitter">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M13.7119 10.6217L20.4124 3H18.8243L13.0078 9.61757L8.3599 3H3L10.0274 13.008L3 21H4.58812L10.7315 14.0109L15.6401 21H21L13.7119 10.6217ZM11.5375 13.0954L10.8255 12.099L5.15984 4.17H7.59893L12.1701 10.569L12.8821 11.5654L18.8256 19.884H16.3865L11.5375 13.0954Z" fill="white"/>
                </svg>
            </a>
            <a href="<?php echo esc_url( 'http://pinterest.com/pin/create/link/?url='.$link ) ?>" target="_blank" class="wopb-share-pinterest">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M9.04 21.54C10 21.83 10.97 22 12 22C14.6522 22 17.1957 20.9464 19.0711 19.0711C20.9464 17.1957 22 14.6522 22 12C22 10.6868 21.7413 9.38642 21.2388 8.17317C20.7362 6.95991 19.9997 5.85752 19.0711 4.92893C18.1425 4.00035 17.0401 3.26375 15.8268 2.7612C14.6136 2.25866 13.3132 2 12 2C10.6868 2 9.38642 2.25866 8.17317 2.7612C6.95991 3.26375 5.85752 4.00035 4.92893 4.92893C3.05357 6.8043 2 9.34784 2 12C2 16.25 4.67 19.9 8.44 21.34C8.35 20.56 8.26 19.27 8.44 18.38L9.59 13.44C9.59 13.44 9.3 12.86 9.3 11.94C9.3 10.56 10.16 9.53 11.14 9.53C12 9.53 12.4 10.16 12.4 10.97C12.4 11.83 11.83 13.06 11.54 14.24C11.37 15.22 12.06 16.08 13.06 16.08C14.84 16.08 16.22 14.18 16.22 11.5C16.22 9.1 14.5 7.46 12.03 7.46C9.21 7.46 7.55 9.56 7.55 11.77C7.55 12.63 7.83 13.5 8.29 14.07C8.38 14.13 8.38 14.21 8.35 14.36L8.06 15.45C8.06 15.62 7.95 15.68 7.78 15.56C6.5 15 5.76 13.18 5.76 11.71C5.76 8.55 8 5.68 12.32 5.68C15.76 5.68 18.44 8.15 18.44 11.43C18.44 14.87 16.31 17.63 13.26 17.63C12.29 17.63 11.34 17.11 11 16.5L10.33 18.87C10.1 19.73 9.47 20.88 9.04 21.57V21.54Z" fill="white"/>
                </svg>
            </a>
            <a href="<?php echo esc_url( 'https://web.skype.com/share?url='.$link ) ?>" target="_blank" class="wopb-share-skype">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M18 6C20.07 8.04 20.85 10.89 20.36 13.55C20.77 14.27 21 15.11 21 16C21 17.3261 20.4732 18.5979 19.5355 19.5355C18.5979 20.4732 17.3261 21 16 21C15.11 21 14.27 20.77 13.55 20.36C10.89 20.85 8.04 20.07 6 18C3.93 15.96 3.15 13.11 3.64 10.45C3.23 9.73 3 8.89 3 8C3 6.67392 3.52678 5.40215 4.46447 4.46447C5.40215 3.52678 6.67392 3 8 3C8.89 3 9.73 3.23 10.45 3.64C13.11 3.15 15.96 3.93 18 6ZM12.04 17.16C14.91 17.16 16.34 15.78 16.34 13.92C16.34 12.73 15.78 11.46 13.61 10.97L11.62 10.53C10.86 10.36 10 10.13 10 9.42C10 8.7 10.6 8.2 11.7 8.2C13.93 8.2 13.72 9.73 14.83 9.73C15.41 9.73 15.91 9.39 15.91 8.8C15.91 7.43 13.72 6.4 11.86 6.4C9.85 6.4 7.7 7.26 7.7 9.54C7.7 10.64 8.09 11.81 10.25 12.35L12.94 13.03C13.75 13.23 13.95 13.68 13.95 14.1C13.95 14.78 13.27 15.45 12.04 15.45C9.63 15.45 9.96 13.6 8.67 13.6C8.09 13.6 7.67 14 7.67 14.57C7.67 15.68 9 17.16 12.04 17.16Z" fill="white"/>
                </svg>
            </a>
        </div>
    <?php
    }

    /**
     * Quick View Button
     *
     * @return null
     * @since v.3.1.5
     */
    public function quick_view_button() {
        global $wp_query;
        $params = array(
            'source' => 'default',
            'post' => new \WP_Query( $wp_query->query_vars ),
            'post_id' => get_the_ID(),
        );
        echo wopb_function()->get_quick_view($params); //phpcs:disable WordPress.Security.EscapeOutput.OutputNotEscaped
    }

    /**
     * Quick View Button in Default Shop Page
     *
     * @param $content
     * @param $args
     * @return null
     * @since v.3.1.5
     */
    public function quick_view_in_cart( $content, $args ) {
        if( !wopb_function()->is_builder() &&
            (
                ( wopb_function()->get_setting('quick_view_shop_enable') == 'yes' && is_shop() )  ||
                ( wopb_function()->get_setting('quick_view_archive_enable') == 'yes' && is_archive() )
            ) )
        {
            global $product;
            ob_start();
            $this->quick_view_button();
            return $content . ob_get_clean();
        }
        return $content;
    }

    /**
     * Quick Button Position Filters
     *
     * @since v.3.1.5
     * @return array
     */
    public function button_position_filters() {
        return array(
           'top_cart' => 'wopb_top_add_to_cart_loop',
           'before_cart' => 'wopb_before_add_to_cart_loop',
           'after_cart' => 'wopb_after_add_to_cart_loop',
           'bottom_cart' => 'wopb_bottom_add_to_cart_loop',
           'above_image' => 'wopb_before_shop_loop_title',
        );
    }

    /**
     * CSS Push To Header
     *
     * @since v.3.1.5
     * @return null
     */
    public function header_element() {
        $quick_view_css = $this->get_css();
        if ( $quick_view_css ) {
            $placeholderRegex = '/\{\{(\w+)\}\}/';
            $quick_view_css = preg_replace_callback($placeholderRegex, function ( $matches ) {
                $key = $matches[1];
                return wopb_function()->get_setting($key);
            }, $quick_view_css);
            if( wopb_function()->get_setting('quick_view_image_effect') == 'yes' ) {
                $zoom_icon = base64_encode($this->zoom_icons(wopb_function()->get_setting('quick_view_image_hover_icon')));
                $quick_view_css .= '.wopb-quick-view-image .wopb-thumbnail a {cursor:url("data:image/svg+xml;base64,' . $zoom_icon . '"), pointer;}';
            }
            echo '<style id="wopb-quickview-internal-style">' . $quick_view_css . '</style>'; //phpcs:disable WordPress.Security.EscapeOutput.OutputNotEscaped
        }
    }

    /**
     * Quick View Content Item
     *
     * @since v.3.1.5
     * @param $default
     * @return array
     */
    public function quick_view_contents($default = '') {
        $default_options = array(
            ['key' => 'image','label' => __( 'Image','product-blocks' )],
            ['key' => 'title','label' => __( 'Title','product-blocks' )],
            ['key' => 'rating','label' => __( 'Rating','product-blocks' )],
            ['key' => 'price','label' => __( 'Price','product-blocks' )],
            ['key' => 'description','label' => __( 'Description','product-blocks' )],
            ['key' => 'stock_status','label' => __( 'Stock Status','product-blocks' )],
            ['key' => 'add_to_cart','label' => __( 'Add To Cart','product-blocks' )],
            ['key' => 'meta','label' => __( 'Meta','product-blocks' )],
            ['key' => 'view_details','label' => __( 'View Details','product-blocks' )],
            ['key' => 'social_share','label' => __( 'Social Share','product-blocks' )],
        );
        $options = array(
            ['key' => '','label' => __( 'Select Column','product-blocks' )],
            ...$default_options,
        );
        return $default && $default == 'default' ? $default_options : $options;
    }

    /**
     * Quick View Layouts
     *
     * @since v.3.1.5
     * @param $key
     * @return array
     */
    public function quick_view_layouts($key = '') {
        $layouts = array(
            (object)['key' => 1, 'image' => WOPB_URL.'assets/img/addons/quick_view/layout_1.png', 'pro' => false],
            (object)['key' => 2, 'image' => WOPB_URL.'assets/img/addons/quick_view/layout_2.png', 'pro' => false],
            (object)['key' => 3, 'image' => WOPB_URL.'assets/img/addons/quick_view/layout_3.png', 'pro' => false],
            (object)['key' => 4, 'image' => WOPB_URL.'assets/img/addons/quick_view/layout_4.png', 'pro' => false],
            (object)['key' => 5, 'image' => WOPB_URL.'assets/img/addons/quick_view/layout_5.png', 'pro' => false],
        );
        if($key) {
            return isset($layouts[$key]) ? $layouts[$key] : '';
        }else {
            return $layouts;
        }
    }

    /**
     * Quick View Icons
     *
     * @since v.3.1.5
     * @param $key
     * @return array
     */
    public function quick_view_icons($key='') {
        $icons = array(
            'quick_view_1' => wopb_function()->svg_icon('quick_view_1'),
            'quick_view_2' => wopb_function()->svg_icon('quick_view_2'),
            'quick_view_3' => wopb_function()->svg_icon('quick_view_3'),
            'quick_view_4' => wopb_function()->svg_icon('quick_view_4'),
            'quick_view_5' => wopb_function()->svg_icon('quick_view_5'),
            'quick_view_6' => wopb_function()->svg_icon('quick_view_6'),
        );
        return $key ? $icons[$key] : $icons;
    }

    /**
     * Quick View Zoom Icons
     *
     * @since v.3.1.5
     * @param $key
     * @return array
     */
    public function zoom_icons($key='') {
        global $wp_filesystem;
        if (! $wp_filesystem ) {
            require_once ABSPATH . 'wp-admin/includes/file.php';
            WP_Filesystem();
        }
        $icons = array(
            'zoom_1' => $wp_filesystem->get_contents( WOPB_PATH.'assets/img/addons/quick_view/cursor_zoom_1.svg'),
            'zoom_2' => $wp_filesystem->get_contents( WOPB_PATH.'assets/img/addons/quick_view/cursor_zoom_2.svg'),
        );
        return $key ? $icons[$key] : $icons;
    }

    /**
     * Dynamic CSS
     *
     * @since v.3.1.5
     * @return string
     */
    public function get_css() {
        return ".wopb-quick-view-wrapper .wopb-modal-content, .wopb-popup-body:has(.wopb-quick-view-wrapper), .wopb-quick-view-wrapper .wopb-zoom-image-outer.wopb-zoom-1 {
                    background: {{quick_view_modal_background}} !important;
                }
                .wopb-quick-view-wrapper {
                    color: {{quick_view_text_color}};
                    font-size: {{quick_view_text_font_size}}px;
                    font-weight: {{quick_view_text_font_weight}};
                }
                .wopb-quick-view-wrapper .wopb-rating-info>div {
                    border-color: {{quick_view_border_color}}33;
                }
                .wopb-quick-view-image div.wopb-image-sticky, .wopb-quick-view-image.wopb-image-sticky {
                    background: {{quick_view_modal_background}};
                }
                .wopb-quick-view-gallery.wopb-custom {
                    max-width: {{quick_view_thumbnail_width}}px;
                }
                .wopb-quick-view-gallery.wopb-custom .wopb-thumbnail .wopb-main-image  {
                    height: {{quick_view_thumbnail_height}}px;
                }
                .wopb-quick-view-wrapper .wopb-quick-view-content {
                    gap: {{quick_view_content_inner_gap}}px;
                }
                .wopb-quick-view-wrapper .wopb-product-info {
                    gap: {{quick_view_content_inner_gap}}px;
                    border-color: {{quick_view_border_color}}33;
                }
                .wopb-quick-view-wrapper .product_title {
                    color: {{quick_view_title_color}};
                    font-size: {{quick_view_title_font_size}}px;
                    font-weight: {{quick_view_title_font_weight}};
                    text-transform: {{quick_view_title_font_case}};
                }
                .wopb-quick-view-wrapper .price del {
                    color: {{quick_view_text_color}};
                    opacity: 0.5;
                }
                .wopb-quick-view-wrapper .price del span bdi {
                    color: {{quick_view_text_color}};
                }
                .wopb-quick-view-wrapper form.cart .quantity {
                    color: {{quick_view_text_color}};
                    margin-bottom: {{quick_view_content_inner_gap}}px;
                    padding-bottom: {{quick_view_content_inner_gap}}px;
                    border-color: {{quick_view_border_color}}33;
                }
                .wopb-quick-view-wrapper form.cart .quantity:before {
                    color: {{quick_view_text_color}};
                }
                .wopb-quick-view-wrapper form.cart .quantity svg path {
                    fill: {{quick_view_text_color}};
                }
                .wopb-quick-view-wrapper .wopb-add-to-cart-minus, .wopb-quick-view-wrapper .wopb-add-to-cart-plus, .wopb-quick-view-wrapper input.qty {
                    border: {{quick_view_border_width}}px solid {{quick_view_border_color}} !important;
                }
                .wopb-quick-view-wrapper form.cart .quantity input.qty {
                    color: {{quick_view_text_color}};
                }
                .wopb-quick-view-wrapper form.cart .quantity span:hover {
                    background: {{quick_view_button_hover_color}} 
                }
                .wopb-quick-view-wrapper .single_add_to_cart_button.wopb-quickview-buy-btn {
                    margin-top: calc({{quick_view_content_inner_gap}}px - ({{quick_view_content_inner_gap}}px * 0.6));
                    color: {{quick_view_button_color}};
                }
                .wopb-quick-view-wrapper .product_meta {
                    padding-top: {{quick_view_content_inner_gap}}px;
                    border-color: {{quick_view_border_color}}33;
                }
                .wopb-quick-view-wrapper .wopb-product-details {
                    padding-top: {{quick_view_content_inner_gap}}px;
                    color: {{quick_view_link_color}};
                    border-color: {{quick_view_border_color}}33;
                }
                .wopb-quick-view-wrapper .wopb-product-details:hover {
                    color: {{quick_view_button_hover_color}};
                }
                .wopb-quick-view-wrapper .wopb-product-social-share {
                    padding-top: {{quick_view_content_inner_gap}}px;
                    border-color: {{quick_view_border_color}}33;
                }
                .wopb-quick-view-wrapper .wopb-product-social-share a:hover {
                    background: {{quick_view_button_hover_color}};
                }
                .wopb-quick-addon-btn {
                    font-size: {{quick_view_button_font_size}}px;
                    font-weight: {{quick_view_button_font_weight}};
                    text-transform: {{quick_view_button_font_case}};
                }
                .wopb-quick-addon-btn:not(.wopb-link), .wopb-quick-view-content .single_add_to_cart_button {
                    background: {{quick_view_button_background}} !important;
                    color: {{quick_view_button_color}};
                    padding: {{quick_view_button_padding_y}}px {{quick_view_button_padding_x}}px;
                    border-radius: {{quick_view_button_radius}}px;
                }
                .wopb-quick-addon-btn:not(.wopb-link):hover, .wopb-quick-view-content .single_add_to_cart_button:hover {
                    background: {{quick_view_button_hover_color}} !important;
                }
                .wopb-quick-view-wrapper .product_meta span {
                    color: {{quick_view_text_color}}9c;
                }
                .wopb-quick-view-wrapper .product_meta span span {
                    color: {{quick_view_text_color}};
                }
                .wopb-quick-view-wrapper .product_meta span a {
                    color: {{quick_view_link_color}};
                }
                .wopb-quick-view-wrapper .product_meta span a:hover {
                    color: {{quick_view_button_hover_color}};
                }
                .wopb-quick-addon-btn:not(.wopb-link) svg path {
                    stroke: {{quick_view_button_color}};
                }
                .wopb-quick-addon-btn:not(.wopb-link) svg path:not([stroke]) {
                    stroke: none;
                    fill: {{quick_view_button_color}};
                }
                .wopb-quick-addon-btn.wopb-link {
                    color: {{quick_view_link_color}};
                }
                .wopb-quick-addon-btn.wopb-link svg path {
                    stroke: {{quick_view_link_color}};
                }
                .wopb-quick-addon-btn.wopb-link svg path:not([stroke]) {
                    stroke: none;
                    fill: {{quick_view_link_color}};
                }
                .wopb-quick-addon-btn.wopb-link:hover {
                    color: {{quick_view_button_hover_color}};
                }
                .wopb-quick-addon-btn.wopb-link:hover svg path {
                    stroke: {{quick_view_button_hover_color}};
                }
                .wopb-quick-addon-btn.wopb-link:hover svg path:not([stroke]) {
                    stroke: none;
                    fill: {{quick_view_button_hover_color}};
                }
        ";
    }

    /**
     * Default settings for quick view
     *
     * @since v.3.1.5
     * @return array
     */
    public function default_settings() {
        global $wopb_default_settings;
        $quick_view_settings = [
            'quick_view_mobile_enable' => array(
                'type' => 'toggle',
                'label' => __('Enable Quick View on Mobile Devices', 'product-blocks'),
                'default' => '',
                'desc' => __('Enable if you want to show quick view on mobile devices', 'product-blocks')
            ),
            'quick_view_shop_enable' => array(
                'type' => 'toggle',
                'label' => __('Show in Shop Page', 'product-blocks'),
                'default' => 'yes',
                'desc' => __('Enable if you want to show quick view on shop page', 'product-blocks')
            ),
            'quick_view_archive_enable' => array(
                'type' => 'toggle',
                'label' => __('Show in Archive Page', 'product-blocks'),
                'default' => 'yes',
                'desc' => __('Enable if you want to show quick view on archive page', 'product-blocks')
            ),
            'quick_view_click_action' => array(
                'type' => 'radio',
                'label' => __('Select option for what will happens when you click quick view', 'product-blocks'),
                'display' => 'inline-box',
                'options' => array(
                    'popup' => 'Popup',
                    'right_sidebar' => 'Right Sidebar',
                    'left_sidebar' => 'Left Sidebar',
                ),
                'default' => 'popup',
                'variations' => [
                    'popup' => [
                        'quick_view_layout' => 1,
                    ],
                    'right_sidebar' => [
                        'quick_view_layout' => 4,
                    ],
                    'left_sidebar' => [
                        'quick_view_layout' => 5,
                    ],
                ],
            ),
            'quick_view_loader' => array(
                'type' => 'radio',
                'label' => __('Quick View Display Loading', 'product-blocks'),
                'display' => 'inline-box',
                'options' => wopb_function()->modal_loaders(),
                'default' => 'loader_1',
                'desc' => __("Select loading icon to show before open quick view.", 'product-blocks'),
            ),
            'quick_view_open_animation' => array(
                'type' => 'select',
                'label' => __('Quick View Opening Animation', 'product-blocks'),
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
            'quick_view_close_animation' => array(
                'type' => 'select',
                'label' => __('Quick View Closing Animation', 'product-blocks'),
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
            'quick_view_button_type' => array(
                'type' => 'radio',
                'label' => __('Button Type', 'product-blocks'),
                'display' => 'inline-box',
                'options' => array(
                    'button' => 'Button',
                    'link' => 'Link',
                ),
                'default' => 'button',
            ),
            'quick_view_text' => array(
                'type' => 'text',
                'label' => __('Button Text', 'product-blocks'),
                'default' => __('Quick View', 'product-blocks'),
                'desc' => __('Write your preferable text to show  on quick view button', 'product-blocks')
            ),
            'quick_view_button_icon_enable' => array(
                'type' => 'toggle',
                'label' => __('Button Icon', 'product-blocks'),
                'default' => 'yes',
                'value' => 'yes',
                'desc' => __("Enable button icon to display icon on quick view button.", 'product-blocks')
            ),
            'quick_view_button_only_icon' => array(
                'type' => 'toggle',
                'label' => __('Use Only Icon', 'product-blocks'),
                'default' => 'yes',
                'value' => 'yes',
                'depends' => ['key' =>'quick_view_button_icon_enable', 'condition' => '==', 'value' => 'yes'],
                'desc' => __("Enable if you want to only icon on quick view button.", 'product-blocks')
            ),
            'quick_view_button_icon' => array(
                'type' => 'radio',
                'label' => __('Choose Icon', 'product-blocks'),
                'display' => 'inline-box',
                'depends' => ['key' =>'quick_view_button_icon_enable', 'condition' => '==', 'value' => 'yes'],
                'options' => $this->quick_view_icons(),
                'default' => 'quick_view_3',
                'desc' => __("Choose icon display on quick view button.", 'product-blocks')
            ),
            'quick_view_button_icon_position' => array(
                'type' => 'radio',
                'label' => __('Icon Position', 'product-blocks'),
                'display' => 'inline-box',
                'depends' => [
                    ['key' =>'quick_view_button_icon_enable', 'condition' => '==', 'value' => 'yes'],
                    ['key' =>'quick_view_button_only_icon', 'condition' => '==', 'value' => ''],
                ],
                'options' => array(
                    'before_text' => __('Before Text', 'product-blocks'),
                    'after_text' => __('After Text', 'product-blocks'),
                ),
                'default' => 'before_text',
            ),
            'quick_view_position' => array(
                'type' => 'select',
                'label' => __('Button Position', 'product-blocks'),
                'desc' => __("Choose where will place quick view button.", 'product-blocks'),
                'options' => array(
                    'after_cart' => __( 'After Add to Cart','product-blocks' ),
                    'bottom_cart' => __( 'Bottom Add to Cart','product-blocks' ),
                    'top_cart' => __( 'Top Add to Cart','product-blocks' ),
                    'before_cart' => __( 'Before Add to Cart','product-blocks' ),
                    'above_image' => __( 'Above Image','product-blocks' ),
                    'shortcode' => __( 'Use Shortcode','product-blocks' ),
                ),
                'default' => 'before_cart',
                'note' => (object)[
                    'text' => __('Use this shortcode [wopb_quick_view_button] where you want to show quick view button.', 'product-blocks'),
                    'depends' => ['key' =>'quick_view_position', 'condition' => '==', 'value' => 'shortcode'],
                ]
            ),
            'quick_view_contents' => array(
                'type' => 'select_item',
                'label' => __('Select Content to Show on Quick View Modal Box', 'product-blocks'),
                'desc' => __('Select the content you want to display in the quick view modal box. To rearrange these fields, you can also drag and drop. You can also customize the label name according to your preferences.', 'product-blocks'),
                'default' => $this->quick_view_contents('default'),
                'options' => $this->quick_view_contents(),
            ),
            'quick_view_image_type' => array(
                'type' => 'radio',
                'label' => __('Product Thumbnail', 'product-blocks'),
                'desc' => __('Select option for what you want to show on modal box', 'product-blocks'),
                'default' => 'image_with_gallery',
                'display' => 'inline-box',
                'options' => array(
                    'image_with_gallery' => 'Product Image & Gallery Images',
                    'image_with_pagination' => 'Product Gallery Images',
                    'image_only' => 'Product Image Only',
                ),
            ),
            'quick_view_image_gallery' => array(
                'type' => 'layout',
                'label' => __('Thumbnail Style', 'product-blocks'),
                'default' => 'bottom',
                'depends' => ['key' =>'quick_view_image_type', 'condition' => '==', 'value' => 'image_with_gallery'],
                'options' => array(
                    (object)['key' => 'bottom', 'image' => WOPB_URL.'assets/img/addons/quick_view/quick_gallery_bottom.svg'],
                    (object)['key' => 'right', 'image' => WOPB_URL.'assets/img/addons/quick_view/quick_gallery_right.svg'],
                    (object)['key' => 'left', 'image' => WOPB_URL.'assets/img/addons/quick_view/quick_gallery_left.svg'],
                ),
                'hr_line' => true,
            ),
            'quick_view_image_pagination' => array(
                'type' => 'layout',
                'label' => __('Thumbnail Style', 'product-blocks'),
                'default' => 'line',
                'depends' => ['key' =>'quick_view_image_type', 'condition' => '==', 'value' => 'image_with_pagination'],
                'options' => array(
                    (object)['key' => 'line', 'image' => WOPB_URL.'assets/img/addons/quick_view/quick_slide_line.svg'],
                    (object)['key' => 'dot', 'image' => WOPB_URL.'assets/img/addons/quick_view/quick_slide_dot.svg'],
                    (object)['key' => 'right_arrow', 'image' => WOPB_URL.'assets/img/addons/quick_view/quick_slide_arrow_right.svg'],
                ),
                'hr_line' => true,
            ),
            'quick_view_image_effect' => array(
                'type' => 'toggle',
                'label' => __('Product Image Effect', 'product-blocks'),
                'default' => 'yes',
                'desc' => __('Enable lightbox on click image', 'product-blocks'),
            ),
            'quick_view_image_effect_type' => array(
                'type' => 'radio',
                'label' => __('Effect Type', 'product-blocks'),
                'default' => 'zoom',
                'display' => 'inline-box',
                'depends' => ['key' =>'quick_view_image_effect', 'condition' => '==', 'value' => 'yes'],
                'options' => array(
                    'zoom' => 'Zoom',
                    'popup' => 'Click to Popup',
                ),
                'hr_line' => true,
            ),
            'quick_view_image_hover_icon' => array(
                'type' => 'radio',
                'label' => __('Hover Effect Icon', 'product-blocks'),
                'default' => 'zoom_1',
                'display' => 'inline-box',
                'depends' => ['key' =>'quick_view_image_effect', 'condition' => '==', 'value' => 'yes'],
                'options' => $this->zoom_icons(),
            ),
            'quick_view_buy_now' => array(
                'type' => 'toggle',
                'label' => __('Enable Buy Now Button', 'product-blocks'),
                'default' => 'yes',
                'desc' => __('Enable to display buy now button on modal box', 'product-blocks'),
            ),
            'quick_view_thumbnail_freeze' => array(
                'type' => 'toggle',
                'label' => __('Freeze Product Thumbnail', 'product-blocks'),
                'default' => 'yes',
                'desc' => __('Enable freeze the product thumbnail when scrolling', 'product-blocks'),
            ),
            'quick_view_product_navigation' => array(
                'type' => 'toggle',
                'label' => __('Product Navigation(Next/Previous)', 'product-blocks'),
                'default' => 'yes',
                'desc' => __('Enable navigation to quick view next/previous product without closing modal box', 'product-blocks'),
            ),
            'quick_view_close_button' => array(
                'type' => 'toggle',
                'label' => __('Close Button on Quick View Modal', 'product-blocks'),
                'default' => 'yes',
                'desc' => __('Enable close button at top right corner on quick view modal', 'product-blocks'),
            ),
            'quick_view_close_outside_click' => array(
                'type' => 'toggle',
                'label' => __('Close Modal if Clicked Outside', 'product-blocks'),
                'default' => 'yes',
                'desc' => __('Enable to close modal box if clicked outside', 'product-blocks'),
            ),
            'quick_view_close_add_to_cart' => array(
                'type' => 'toggle',
                'label' => __('Close Modal Box After Add To Cart', 'product-blocks'),
                'default' => 'yes',
                'desc' => __('Auto close the modal box after adding a product to the cart', 'product-blocks'),
            ),
            'quick_view_layout' => array(
                'type' => 'layout',
                'label' => __('Choose Quick View Modal Layout', 'product-blocks'),
                'default' => 1,
                'options' => $this->quick_view_layouts(),
                'preview' => true,
                'variations' => [
                    1 => [
                        'quick_view_click_action' => 'popup',
                    ],
                    2 => [
                        'quick_view_click_action' => 'popup',
                    ],
                    3 => [
                        'quick_view_click_action' => 'popup',
                    ],
                    4 => [
                        'quick_view_click_action' => 'right_sidebar',
                    ],
                    5 => [
                        'quick_view_click_action' => 'left_sidebar',
                    ],
                ],
            ),
            'quick_view_preset' => array(
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
                        'quick_view_button_background' => '#FF5845',
                        'quick_view_link_color' => '#FF5845',
                        'quick_view_title_color' => '#070C1A',
                        'quick_view_text_color' => '#5A5A5A',
                        'quick_view_border_color' => '#E5E5E5',
                        'quick_view_modal_background' => '#FFFFFF',
                    ],
                    '2' => [
                        'quick_view_button_background' => '#4558FF',
                        'quick_view_link_color' => '#4558FF',
                        'quick_view_title_color' => '#1B233A',
                        'quick_view_text_color' => '#5A5A5A',
                        'quick_view_border_color' => '#E5E5E5',
                        'quick_view_modal_background' => '#FFFFFF',
                    ],
                    '3' => [
                        'quick_view_button_background' => '#FF9B26',
                        'quick_view_link_color' => '#FF9B26',
                        'quick_view_title_color' => '#070C1A',
                        'quick_view_text_color' => '#5A5A5A',
                        'quick_view_border_color' => '#E5E5E5',
                        'quick_view_modal_background' => '#FFFFFF',
                    ],
                    '4' => [
                        'quick_view_button_background' => '#FF4319',
                        'quick_view_link_color' => '#FF4319',
                        'quick_view_title_color' => '#383838',
                        'quick_view_text_color' => '#5A5A5A',
                        'quick_view_border_color' => '#E5E5E5',
                        'quick_view_modal_background' => '#FFFFFF',
                    ],
                    '5' => [
                        'quick_view_button_background' => '#2AAB6F',
                        'quick_view_link_color' => '#2AAB6F',
                        'quick_view_title_color' => '#101010',
                        'quick_view_text_color' => '#5A5A5A',
                        'quick_view_border_color' => '#E5E5E5',
                        'quick_view_modal_background' => '#FFFFFF',
                    ],
                ],
            ),
            'quick_view_modal_background' => [
                'type'=> 'color',
                'label'=> __('Modal Background', 'product-blocks'),
                'default' => '#FFFFFF'
            ],
            'quick_view_title_color' => [
                'type'=> 'color',
                'label'=> __('Title Color', 'product-blocks'),
                'default' => '#070C1A'
            ],
            'quick_view_border_color' => [
                'type'=> 'color',
                'label'=> __('Border Color', 'product-blocks'),
                'default' => '#E5E5E5'
            ],
            'quick_view_text_color' => [
                'type'=> 'color',
                'label'=> __('Text Color', 'product-blocks'),
                'default' => '#5A5A5A'
            ],
            'quick_view_button_background' => [
                'type'=> 'color',
                'label'=> __('Button Background', 'product-blocks'),
                'default' => '#FF5845'
            ],
            'quick_view_button_color' => [
                'type'=> 'color',
                'label'=> __('Button Text Color', 'product-blocks'),
                'default' => '#ffffff'
            ],
            'quick_view_link_color' => [
                'type'=> 'color',
                'label'=> __('Link Color', 'product-blocks'),
                'default' => '#FF5845'
            ],
            'quick_view_button_hover_color' => [
                'type'=> 'color',
                'label'=> __('Button/Link Hover Color', 'product-blocks'),
                'default' => '#333'
            ],
            'quick_view_title_font_size' => [
                'type'=> 'number',
                'plus_minus'=> true,
                'label'=> __('Font Size', 'product-blocks'),
                'default' => '32'
            ],
            'quick_view_title_font_weight' => [
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
            'quick_view_title_font_case' => [
                'type'=> 'tag',
                'label'=> __('Font Case', 'product-blocks'),
                'options' => [
                    'uppercase' => 'AB',
                    'capitalize' => 'Ab',
                    'lowercase' => 'ab',
                ],
                'default' => 'capitalize'
            ],
            'quick_view_button_font_size' => [
                'type'=> 'number',
                'plus_minus'=> true,
                'label'=> __('Font Size', 'product-blocks'),
                'default' => '16'
            ],
            'quick_view_button_font_weight' => [
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
            'quick_view_button_font_case' => [
                'type'=> 'tag',
                'label'=> __('Font Case', 'product-blocks'),
                'options' => [
                    'uppercase' => 'AB',
                    'capitalize' => 'Ab',
                    'lowercase' => 'ab',
                ],
                'default' => 'capitalize'
            ],
            'quick_view_text_font_size' => [
                'type'=> 'number',
                'plus_minus'=> true,
                'label'=> __('Font Size', 'product-blocks'),
                'default' => '16'
            ],
            'quick_view_text_font_weight' => [
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
            'quick_view_button_padding_y' => [
                'type'=> 'number',
                'plus_minus'=> true,
                'label'=> __('Padding (Top, Bottom)', 'product-blocks'),
                'default' => '6'
            ],
            'quick_view_button_padding_x' => [
                'type'=> 'number',
                'plus_minus'=> true,
                'label'=> __('Padding (Left, Right)', 'product-blocks'),
                'default' => '12'
            ],
            'quick_view_button_radius' => [
                'type'=> 'number',
                'plus_minus'=> true,
                'label'=> __('Corner Radius', 'product-blocks'),
                'default' => '4'
            ],
            'quick_view_content_inner_gap' => [
                'type'=> 'number',
                'plus_minus'=> true,
                'label'=> __('Inner Column Gap', 'product-blocks'),
                'default' => '15'
            ],
            'quick_view_thumbnail_ratio' => [
                'type'=> 'select',
                'label'=> __('Thumbnail Ratio', 'product-blocks'),
                'options' => [
                    'default' => __('Default', 'product-blocks'),
                    'custom' => __('Custom', 'product-blocks'),
                ],
                'fields' => [
                    'quick_view_thumbnail_height' => array (
                        'type'=> 'number',
                        'label'=> __('Height', 'product-blocks'),
                        'default' => '350',
                        'depends' => ['key' =>'quick_view_thumbnail_ratio', 'condition' => '==', 'value' => 'custom'],
                    ),
                    'quick_view_thumbnail_width' => array (
                        'type'=> 'number',
                        'label'=> __('Width', 'product-blocks'),
                        'default' => '400',
                        'depends' => ['key' =>'quick_view_thumbnail_ratio', 'condition' => '==', 'value' => 'custom'],
                    ),
                ],
                'default' => 'default'
            ],
            'quick_view_border_width' => [
                'type'=> 'number',
                'plus_minus'=> true,
                'label'=> __('Border Width', 'product-blocks'),
                'default' => '1'
            ],
        ];
        $wopb_default_settings = array_merge($wopb_default_settings, $quick_view_settings);
        return $quick_view_settings;
    }
}
