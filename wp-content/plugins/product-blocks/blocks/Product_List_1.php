<?php
namespace WOPB\blocks;

defined('ABSPATH') || exit;

class Product_List_1{

    public function __construct() {
        add_action( 'init', array( $this, 'register' ) );
    }

    public function get_attributes() {
        return array (
            'layout' => '1',
            'sortSection' => '["title","description","price","review","cart"]',
            'productView' => 'grid',
            'columns' => array('lg' => '2','sm' => '1'),
            'slidesToShow' => (object) array('lg' => '4','sm' => '2','xs' => '1'),
            'autoPlay' => true,
            'showDots' => true,
            'showArrows' => true,
            'slideSpeed' => '3000',
            'showPrice' => true,
            'showReview' => true,
            'showWishList' => true,
            'showCompare' => false,
            'quickView' => true,
            'showCart' => false,
            'filterShow' => false,
            'paginationShow' => false,
            'headingShow' => true,
            'titleShow' => true,
            'showShortDesc' => false,
            'showImage' => true,
            'disableFlip' => false,
            'queryTax' => 'product_cat',
            'arrowStyle' => 'leftAngle2#rightAngle2',
            'headingText' => 'Product List #1',
            'headingURL' => '',
            'headingBtnText' => 'View More',
            'headingStyle' => 'style1',
            'headingTag' => 'h2',
            'headingAlign' => 'left',
            'subHeadingShow' => false,
            'subHeadingText' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Integer ut sem augue. Sed at felis ut enim dignissim sodales.',
            'shortDescLimit' => 7,
            'titleTag' => 'h3',
            'cartText' => '',
            'cartActive' => 'View Cart',
            'imgFlip' => false,
            'imgCrop' => 'full',
            'imgAnimation' => 'none',
            'filterType' => 'product_cat',
            'filterText' => 'all',
            'filterCat' => '[]',
            'filterTag' => '["all"]',
            'filterAction' => '[]',
            'filterActionText' => 'Top Sale|Popular|On Sale|Most Rated|Top Rated|Featured|New Arrival',
            'filterMobile' => true,
            'filterMobileText' => 'More',
            'paginationType' => 'pagination',
            'loadMoreText' => 'Load More',
            'paginationText' => 'Previous|Next',
            'paginationNav' => 'textArrow',
            'paginationAjax' => true,
            'queryNumber' => 6,
        );
    }

    public function register() {
        register_block_type( 'product-blocks/product-list-1',
            array(
                'editor_script' => 'wopb-blocks-editor-script',
                'editor_style'  => 'wopb-blocks-editor-css',
                'render_callback' =>  array( $this, 'content' )
            )
        );
    }

    public function content( $attr, $noAjax = false ) {
        $attr = wp_parse_args( $attr, $this->get_attributes() );

        if ( ! $noAjax ) {
            $paged = is_front_page() ? get_query_var('page') : get_query_var('paged');
            $attr['paged'] = $paged ? $paged : 1;
        }

        $block_name = 'product-list-1';
        $page_post_id = wopb_function()->get_ID();
        $wraper_before = $wraper_after = $post_loop = '';
        $wrapper_main_content = '';
        $recent_posts = new \WP_Query( wopb_function()->get_query( $attr ) );
        $pageNum = wopb_function()->get_page_number($attr, $recent_posts->found_posts);

        $wishlist = wopb_function()->get_setting('wopb_wishlist') == 'true' ? true : false;
        $wishlist_data = $wishlist ? wopb_function()->get_wishlist_id() : array();
        $compare = wopb_function()->get_setting('wopb_compare') == 'true' ? true : false;
        $compare_data = $compare ? wopb_function()->get_compare_id() : array();
        $quickview = wopb_function()->get_setting('wopb_quickview') == 'true' ? true : false;

        $slider_attr = wc_implode_html_attributes(
            array(
                'data-slidestoshow'  => wopb_function()->slider_responsive_split($attr['slidesToShow']),
                'data-autoplay'      => esc_attr($attr['autoPlay']),
                'data-slidespeed'    => esc_attr($attr['slideSpeed']),
                'data-showdots'      => esc_attr($attr['showDots']),
                'data-showarrows'    => esc_attr($attr['showArrows'])
            )
        );

    
        if ( $recent_posts->have_posts() ) {
            $attr['className'] = !empty($attr['className']) ? preg_replace('/[^A-Za-z0-9_ -]/', '', $attr['className']) : '';
            $attr['align'] = !empty($attr['align']) ? 'align' . preg_replace('/[^A-Za-z0-9_ -]/', '', $attr['align']) : '';

            $wraper_before .= '<div '.(isset($attr['advanceId'])?'id="'.sanitize_html_class($attr['advanceId']).'" ':'').' class="wp-block-product-blocks-'.esc_attr($block_name).' wopb-block-'.sanitize_html_class($attr["blockId"]).' '. $attr['className'] . $attr['align'] . '">';
                $wraper_before .= '<div class="wopb-block-wrapper">';

                    if ($attr['headingShow'] || $attr['filterShow'] ) {
                        $wraper_before .= '<div class="wopb-heading-filter">';
                            $wraper_before .= '<div class="wopb-heading-filter-in">';
                                
                                // Heading
                                include WOPB_PATH . 'blocks/template/heading.php';
                                
                                if ( $attr['filterShow'] && $attr['productView'] == 'grid' ) {
                                    $wraper_before .= '<div class="wopb-filter-navigation">';

                                        // Filter
                                        if ($attr['filterShow']){
                                            include WOPB_PATH . 'blocks/template/filter.php';
                                        }
                                    $wraper_before .= '</div>';
                                }
                            $wraper_before .= '</div>';
                        $wraper_before .= '</div>';
                    }

                    $wraper_before .= '<div class="wopb-wrapper-main-content">';
                        if ( $attr['productView']=='slide' ) {
                            $wrapper_main_content .= '<div class="wopb-product-blocks-slide" '.wp_kses_post($slider_attr).'>';
                        } else {
                            $wrapper_main_content .= '<div class="wopb-block-items-wrap wopb-block-row wopb-block-column-'.(! empty( $attr['columns']['lg'] ) ? intval($attr['columns']['lg']) : 4).' wopb-block-content-'.esc_attr($attr['imgFlip']).'">';
                        }
                            $is_show = json_decode( $attr['sortSection'] );
                            $idx = $noAjax ? 1 : 0;
                            while ( $recent_posts->have_posts() ): $recent_posts->the_post();

                                $title_data = $price_data = $review_data = $cart_data = $description_data = '';

                                include WOPB_PATH . 'blocks/template/data.php';
                                if($product) {
                                    $post_loop .= '<div class="wopb-block-item wopb-block-media">';
                                        $post_loop .= '<div class="wopb-block-content-wrap '.($attr['layout']?'wopb-pl1-l'.esc_attr($attr['layout']):'').'">';

                                            if ($attr['showImage']) {
                                                $post_loop .= '<div class="wopb-block-image wopb-block-image-'.esc_attr($attr['imgAnimation']).'">';

                                                    if ( has_post_thumbnail() ) {
                                                        $post_loop .= '<a href="'.esc_url($titlelink).'">';
                                                        $post_loop .= '<img alt="'.esc_attr($title).'" src="'.esc_url(wp_get_attachment_image_url( $post_thumb_id, ($attr['imgCrop'] ? $attr['imgCrop'] : 'full') )).'" />';
                                                            if ( ! $attr['disableFlip'] ) {
                                                                $post_loop .= wopb_function()->get_flip_image($post_id, $title, $attr['imgCrop']);
                                                            }
                                                        $post_loop .= '</a>';
                                                    } else {
                                                        $post_loop .='<div class="empty-image">';
                                                            $post_loop .= '<a href="'.esc_url($titlelink).'">';
                                                                $post_loop .='<img alt='.esc_attr($title).' src="'.esc_url(wc_placeholder_img_src(($attr['imgCrop'] ? $attr['imgCrop'] : 'full'))).'"/>';
                                                            $post_loop .= '</a>';
                                                        $post_loop .='</div>';
                                                    }


                                                    if( $attr["layout"] != '1' ) {
                                                        if ( $attr["quickView"] || $attr["showCompare"] || $attr["showWishList"] ) {
                                                        $post_loop .= '<div class="wopb-product-meta">';
                                                            if( $wishlist ) {
                                                                if( $attr["showWishList"] ) {
                                                                    $post_loop .= wopb_function()->get_wishlist_html($post_id, $wishlist_active, $attr["layout"], array(1, 2, 3, 4));
                                                                }
                                                            }
                                                            if( $attr["quickView"] && $quickview ) {
                                                                $quick_params = array(
                                                                    'post' => $recent_posts,
                                                                    'post_id' => $post_id,
                                                                    'layout' => $attr['layout'],
                                                                    'position' => array(1, 2, 3, 4),
                                                                    'tooltip' => true,
                                                                );
                                                                $post_loop .= wopb_function()->get_quick_view($quick_params);
                                                            }
                                                            if( $attr["showCompare"] && $compare ) {
                                                                $post_loop .= wopb_function()->get_compare($post_id,['layout' => $attr["layout"], 'position' => array(1, 2, 3, 4)]);
                                                            }
                                                        $post_loop .= '</div>';
                                                        }

                                                    }

                                                $post_loop .= '</div>';
                                            }

                                            $post_loop .= '<div class="wopb-block-content">';

                                                // Title
                                                if($attr['titleShow'] && in_array('title',$is_show)){
                                                    include WOPB_PATH . 'blocks/template/title.php';
                                                }

                                                if($attr['showShortDesc']){
                                                    $description_data .= '<div class="wopb-short-description">'. wopb_function()->excerpt($post_id, $attr['shortDescLimit']) .'</div>';
                                                }

                                                // Review
                                                if($attr['showReview'] && in_array('review',$is_show)){
                                                    include WOPB_PATH . 'blocks/template/review.php';
                                                }

                                                // Price
                                                if($attr['showPrice'] && in_array('price',$is_show)){
                                                    $price_data .= '<div class="wopb-product-price">'.$product->get_price_html().'</div>';
                                                }

                                                // Add to Cart URL
                                                if($attr['showCart'] && in_array('cart',$is_show)){
                                                    $cart_data .= wopb_function()->get_add_to_cart($product, $attr['cartText'], $attr['cartActive'], $attr['layout'], array(), false, $attr);
                                                }

                                                foreach ($is_show as $value) {
                                                    $post_loop .= ${$value."_data"};
                                                }

                                            $post_loop .= '</div>';
                                        $post_loop .= '</div>';
                                    $post_loop .= '</div>';
                                }
                                $idx ++;
                            endwhile;

                            $wrapper_main_content .= $post_loop;

                            if ( $attr['paginationShow'] && $attr['productView'] == 'grid' && $attr['paginationType'] == 'loadMore' ) {
                                $wrapper_main_content .= '<span class="wopb-loadmore-insert-before"></span>';
                            }

                        $wrapper_main_content .= '</div>';//wopb-block-items-wrap

                        // Load More
                        if ( $attr['paginationShow'] && $attr['productView'] == 'grid' && $attr['paginationType'] == 'loadMore' ) {
                            include WOPB_PATH . 'blocks/template/loadmore.php';
                        }

                        // Pagination
                        if ( $attr['paginationShow'] && $attr['productView'] == 'grid' && $attr['paginationType'] == 'pagination' ) {
                            include WOPB_PATH . 'blocks/template/pagination.php';
                        }

                        if ( $attr['productView'] == 'slide' && $attr['showArrows'] ) {
                            include WOPB_PATH . 'blocks/template/arrow.php';
                        }

                    $wraper_after .= '</div>';//wopb-wrapper-main-content
                $wraper_after .= '</div>';
            $wraper_after .= '</div>';

            wp_reset_query();
        }

        if ( $noAjax && $attr['ajax_source'] == 'filter' ) {
            if ( $post_loop === '' ) {
                $wrapper_main_content .= '<span class="wopb-no-product-found">' . __('No products were found of your matching selection', 'product-blocks') . '</span>';
            }
            return $wrapper_main_content;
        }

        return $noAjax ? $post_loop : $wraper_before.$wrapper_main_content.$wraper_after;
    }

}