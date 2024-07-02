<?php
namespace WOPB\blocks;

defined('ABSPATH') || exit;

class Product_Search{
    public function __construct() {
        add_action( 'init', array( $this, 'register' ) );
    }

    public function get_attributes() {
        return array (
            'productListLayout' => '1',
            'showSearchCategory' => true,
            'showSearchIcon' => true,
            'showMoreResult' => true,
            'itemCatLabel' => 'Categories',
            'itemProductLabel' => 'Products',
            'showProductImage' => true,
            'showProductTitle' => true,
            'showProductPrice' => true,
            'showProductRating' => true,
        );
    }

    public function register() {
        register_block_type( 'product-blocks/product-search',
            array(
                'editor_script' => 'wopb-blocks-editor-script',
                'editor_style'  => 'wopb-blocks-editor-css',
                'render_callback' =>  array( $this, 'content' )
            )
        );
    }

    /**
     * This
     * @return terminal
     */
    public function content( $attr, $noAjax = false ) {

        $attr = wp_parse_args( $attr, $this->get_attributes() );

        $is_active = wopb_function()->is_lc_active();
        if ( ! $is_active ) { // Expire Date Check
            $start_date = get_option( 'edd_wopb_license_expire' );
            $is_active = ( $start_date && ( $start_date == 'lifetime' || strtotime( $start_date ) ) ) ? true : false;
        }

        if ( $is_active ) {
            global $wpdb;
            $wraper_before = '';
            $block_name = 'product-search';
            $page_post_id = wopb_function()->get_ID();
            $post_meta = $wpdb->get_row($wpdb->prepare("SELECT * FROM " . $wpdb->prefix . "postmeta WHERE meta_value LIKE %s", '%.wopb-block-'.$attr['blockId'].'%')); // phpcs:ignore WordPress.DB.DirectDatabaseQuery.DirectQuery, WordPress.DB.DirectDatabaseQuery.NoCaching

            if ( $post_meta && isset( $post_meta->post_id ) && $post_meta->post_id != $page_post_id ) {
                $page_post_id = $post_meta->post_id;
            }

            $attr['className'] = !empty($attr['className']) ? preg_replace('/[^A-Za-z0-9_ -]/', '', $attr['className']) : '';
            $attr['align'] = !empty($attr['align']) ? 'align' . preg_replace('/[^A-Za-z0-9_ -]/', '', $attr['align']) : '';


            $wraper_before .= '<div '.(isset($attr['advanceId'])?'id="'.sanitize_html_class($attr['advanceId']).'" ':'').' class="wp-block-product-blocks-'.esc_attr($block_name).' wopb-block-'.sanitize_html_class($attr["blockId"]).' '. $attr['className'] . $attr['align'] . '">';
                $wraper_before .= '<div class="wopb-block-wrapper wopb-front-block-wrapper wopb-product-search-block " data-blockid="'.esc_attr($attr['blockId']).'" data-postid = "' . $page_post_id . '" data-blockname="product-blocks_' . esc_attr($block_name) . '">';

                    $wraper_before .= '<div class="wopb-search-section">';
                        $wraper_before .= '<form action="javascript:">';
                            ob_start();
                                $this->search_input_content( $attr );
                                $this->search_category_content( $attr );
                                $this->search_icon_content( $attr );
                            $wraper_before .= ob_get_clean();
                        $wraper_before .= '</form>';
                    $wraper_before .= '</div>';

                    $wraper_before .= '<div class="wopb-search-result wopb-d-none wopb-layout-' . intval($attr['productListLayout']) . '">';
                    $wraper_before .= '</div>';

                $wraper_before .= '</div>';
            $wraper_before .= '</div>';

            return $wraper_before;
        }
    }

    /**
     * Get Search Input Content
     *
     * @param $attr
     * @since v.2.6.8
     */
    public function search_input_content( $attr ) {
        $html = '';
        $html .= '<div class="wopb-input-section">';
            $html .= '<input type="text" class="wopb-search-input" placeholder="' . esc_html__('Search for products...','product-blocks') . '" />';
            $html .= '<span class="dashicons dashicons-no-alt wopb-clear wopb-d-none"></span>';
            $html .= '<span class="wopb-loader-container"></span>';
        $html .= '</div>';
        echo $html; //phpcs:disable WordPress.Security.EscapeOutput.OutputNotEscaped
    }

    /**
     * Get Search Cateogry Content
     *
     * @param $attr
     * @since v.2.6.8
     */
    public function search_category_content( $attr ) {
        $categories = get_terms( ['taxonomy' => 'product_cat', 'hide_empty' => true] );
        
        if ( isset( $attr['showSearchCategory'] ) && $attr['showSearchCategory'] ) {
?>
            <div class="wopb-search-category">
                    <span class="wopb-separator wopb-left-separator"></span>
                    <div class="wopb-dropdown-select">
                    <span class="wopb-selected-item">
                        <span value="" class="wopb-selected-text">
                            <?php echo esc_html__('All Categories','product-blocks') ?>
                        </span>
                        <i class="dashicons dashicons-arrow-down-alt2"></i>
                    </span>
                        <ul class="wopb-select-items">
                                <li value=""><?php echo esc_html__('All Categories', 'product-blocks'); ?></li>
                            <?php
                                foreach($categories as $category) {
                            ?>
                                <li value="<?php echo $category->term_id; ?>"><?php echo esc_html($category->name); ?></li>
                            <?php } ?>
                        </ul>
                    </div>
                    <span class="wopb-separator wopb-right-separator"></span>
                </div>
<?php
        }
    }

    /**
     * Get Search Icon Content
     *
     * @param $attr
     * @since v.2.6.8
     */
    public function search_icon_content( $attr ) {
        if ( isset( $attr['showSearchIcon'] ) && $attr['showSearchIcon'] ) {
?>
        <a class="wopb-search-icon">
            <?php echo wopb_function()->svg_icon('search'); //phpcs:disable WordPress.Security.EscapeOutput.OutputNotEscaped ?>
        </a>
<?php
        }
    }

    /**
     * Get Search Result Item Content
     *
     * @param array $params
     * @since v.2.6.8
     */
    public function search_item_content( $params = [] ) {
        $attr = $params['attr'];
        $products = $params['products'];
        $tax_terms = $params['tax_terms'];
        $params['view_limit'] = 6;

        if ( $tax_terms && count( $tax_terms ) > 0 ) {
            echo '<div class="wopb-search-item-label wopb-search-item">' . esc_html($attr['itemCatLabel']) . '</div>';
            echo '<div class="wopb-tax-term-items wopb-search-items">';
                $i = 0;
                foreach( $tax_terms as $term ) {
                    $i++;
                    $extend_item_class = '';
                    if ( $i > $params['view_limit'] ) {
                        $extend_item_class = ' wopb-extended-item wopb-d-none';
                    }
?>
                     <div class="wopb-search-item<?php echo esc_attr($extend_item_class); ?>">
                         <a href="<?php echo esc_url(get_term_link($term->term_id)); ?>" class="wopb-item-term">
                             <?php echo wopb_function()->highlightSearchKey(esc_html($term->name,'product-blocks'), $params['search']) ?>
                         </a>
                     </div>
<?php
                }
            echo '</div>';
        }
        if ( $products->have_posts() ) {
            $i = 0;
            echo '<div class="wopb-search-item-label wopb-search-item">' . esc_html($attr['itemProductLabel']) . '</div>';
            echo '<div class="wopb-search-items">';
            while ( $products->have_posts() ) {
                $i++;
                $products->the_post();
                $extend_item_class = '';
                if ( $i > $params['view_limit'] && $attr['showMoreResult'] ) {
                  $extend_item_class = ' wopb-extended-item wopb-d-none';
                }
                $product = wc_get_product( get_the_ID() );
                if ( has_post_thumbnail() ) {
                    $image = wp_get_attachment_image_src(get_post_thumbnail_id($product->get_id()), 'large');
                } else {
                    $image[0] = esc_url(WOPB_URL . 'assets/img/wopb-placeholder.jpg');
                }
    ?>
                <div class="wopb-search-item wopb-block-item<?php echo esc_attr($extend_item_class); ?>">
                    <div class="wopb-item-details">
                        <?php if($attr['showProductImage']) { ?>
                            <a href="<?php echo esc_url($product->get_permalink()) ?>"  class="wopb-item-image">
                                <img src="<?php echo esc_url($image[0]); ?>" />
                            </a>
                        <?php } ?>
                        <div class="wopb-item-title-section">
                            <?php if($attr['showProductTitle']) { ?>
                                <a href="<?php echo esc_url($product->get_permalink()) ?>" class="wopb-item-title">
                                    <?php echo wopb_function()->highlightSearchKey(esc_html($product->get_name() ), $params['search']); //phpcs:disable WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                                </a>
                            <?php
                                }

                                echo $this->rating_content($attr, $product); //phpcs:disable WordPress.Security.EscapeOutput.OutputNotEscaped

                                if($attr['productListLayout'] == 2 || $attr['productListLayout'] == 3) {
                                  echo $this->price_content($attr, $product); //phpcs:disable WordPress.Security.EscapeOutput.OutputNotEscaped
                                }
                            ?>
                        </div>
                    </div>
        <?php
                    if ( $attr['productListLayout'] == 1 ) {
                        echo $this->price_content($attr, $product); //phpcs:disable WordPress.Security.EscapeOutput.OutputNotEscaped
                    }
        ?>
                </div>
<?php
            }

        echo '</div>';
        if ( $params['total_product'] > $params['view_limit'] ) {
            echo $this->more_result_content( $attr, $params ); //phpcs:disable WordPress.Security.EscapeOutput.OutputNotEscaped
        }
    }

    if ( ! $tax_terms && !$products->have_posts() ) {
        echo '<div class="wopb-empty-result"><h2> ' . esc_html__('No Result Found', 'product-blocks') . ' </h2></div>';
    }

}

    /**
     * Get Price Content
     *
     * @param $attr
     * @param $product
     * @since v.2.6.8
     */
    public function price_content( $attr, $product ) {
        if ( $attr['showProductPrice'] ) {
?>
            <div class="wopb-item-price">
                <?php echo $product->get_price_html(); //phpcs:disable WordPress.Security.EscapeOutput.OutputNotEscaped ?>
            </div>
<?php
        }
    }

    /**
     * Get Rating Content
     *
     * @param $attr
     * @param $product
     * @since v.2.6.8
     */
    public function rating_content( $attr, $product ) {
        if ( $attr['showProductRating'] ) {
            $rating_average = $product ? $product->get_average_rating() : 0;
?>
            <div class="wopb-rating-section">
                <div class="wopb-star-rating">
                    <span class="wopb-star-fill" style="width: <?php echo esc_attr($rating_average ? (($rating_average / 5 ) * 100) : 0) ?>%">
                        <strong itemprop="ratingValue" class="wopb-rating"><?php echo esc_html($rating_average) ?></strong>
                    </span>
                </div>
            </div>
<?php
        }
    }

    /**
     * Get More Result Content
     *
     * @param $attr
     * @param $params
     * @since v.2.6.8
     */
    public function more_result_content( $attr, $params ) {
        if ( $attr['showMoreResult'] ) {
            $rest_product_count = $params['total_product'] - $params['view_limit']
?>
            <div class="wopb-load-more-section">
                <a class="wopb-load-more">
                    <?php echo esc_html__( 'More results..', 'product-blocks' ) . '(' . $rest_product_count .')'; ?>
                </a>
                <a class="wopb-less-result wopb-d-none">
                    <?php echo esc_html__( 'Less results', 'product-blocks' ); ?>
                </a>
            </div>
<?php
        }
    }
}