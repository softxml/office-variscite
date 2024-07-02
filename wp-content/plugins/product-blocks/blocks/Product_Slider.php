<?php
namespace WOPB\blocks;

defined('ABSPATH') || exit;

class Product_Slider{
    public function __construct() {
        add_action( 'init', array( $this, 'register' ) );
    }
    
    public function get_attributes() {
        return array(
            'slideAnimation' => 'default',
            'autoPlay' => true,
            'slideSpeed' => '3000',
            'slidesToShow' => (object) array('lg' => '1','sm' => '1','xs' => '1',),
            'showImage' => true,
            'showTitle' => true,
            'showTaxonomy' => true,
            'showDescription' => true,
            'showPrice' => true,
            'showCart' => true,
            'showArrows' => true,
            'showDots' => true,
            'taxonomyUnderTitle' => false,
            'taxonomyType' => 'product_cat',
            'titleTag' => 'h3',
            'titleHoverEffect' => 'none',
            'descriptionLimit' => 190,
            'priceOverDescription' => false,
            'showPriceLabel' => true,
            'priceLabelText' => 'Price: ',
            'showQty' => true,
            'showPlusMinus' => true,
            'plusMinusPosition' => 'right',
            'cartText' => 'Add To Cart',
            'showImgOverlay' => false,
            'showSaleBadge' => true,
            'saleText' => 'Sale!',
            'saleDesign' => 'digit',
            'saleStyle' => 'shape1',
            'arrowStyle' => 'leftAngle2#rightAngle2',
            'queryNumber' => 6,
        );
    }

    public function register() {
        register_block_type( 'product-blocks/product-slider',
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
        $block_key = 'product_slider';
        $blocks_settings = wopb_function()->get_setting( $block_key );
        
        if ( wopb_function()->get_setting() == '' || ! array_key_exists( $block_key, wopb_function()->get_setting() ) || $blocks_settings == 'yes' ) {
            $block_name = 'product-slider';
            $wraper_before = $wraper_after = $post_loop = '';
            $attr = wp_parse_args( $attr, $this->get_attributes() );
            $recent_posts = new \WP_Query( wopb_function()->get_query( $attr ) );

            $slider_attr = wc_implode_html_attributes(
                array(
                    'data-slidestoshow'  => wopb_function()->slider_responsive_split( $attr['slidesToShow'] ),
                    'data-autoplay'      => esc_attr( $attr['autoPlay'] ),
                    'data-slidespeed'    => esc_attr( $attr['slideSpeed'] ),
                    'data-showdots'      => esc_attr( $attr['showDots'] ),
                    'data-showarrows'    => esc_attr( $attr['showArrows'] ),
                    'data-fade'          => $attr['slideAnimation'] == 'fade' ? true : false,
                )
            );

            if ( $recent_posts->have_posts() ) {
                $attr['className'] = !empty($attr['className']) ? preg_replace('/[^A-Za-z0-9_ -]/', '', $attr['className']) : '';
                $attr['align'] = !empty($attr['align']) ? 'align' . preg_replace('/[^A-Za-z0-9_ -]/', '', $attr['align']) : '';
                ob_start();
                echo '<div '.(isset($attr['advanceId'])?'id="'.sanitize_html_class($attr['advanceId']).'" ':'').' class="wp-block-product-blocks-'.esc_attr($block_name).' wopb-block-'.sanitize_html_class($attr["blockId"]).' '. $attr['className'] . $attr['align'] . ' wopb-product-slider-block">';
?>
                        <div class="wopb-block-wrapper">
                            <div class="wopb-slider-section">
                                <div class="wopb-product-blocks-slide" <?php echo wp_kses_post($slider_attr)?>>
                                    <?php
                                        $idx = $noAjax ? 1 : 0;

                                        while ( $recent_posts->have_posts() ): $recent_posts->the_post();
                                            include WOPB_PATH . 'blocks/template/data.php';
                                            if($product) {
                                    ?>
                                                <div class="wopb-block-item">
                                                    <div class="wopb-slide-wrap">
                                                        <?php
                                                            echo $this->content_section($product, $attr); //phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                                                            if($attr['showImage']) {
                                                                echo $this->image_section($product, $_discount, $attr); //phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                                                            }
                                                        ?>
                                                    </div>
                                                </div>
                                    <?php
                                            }
                                            $idx ++;
                                        endwhile;
                                    ?>
                                </div>

                                <?php
                                    if ( $attr['showArrows'] ) {
                                        $nav = explode('#', $attr['arrowStyle']);
                                ?>
                                    <div class="wopb-slick-nav" style="display:none">
                                        <div class="wopb-slick-prev">
                                            <div class="slick-arrow slick-prev">
                                                <?php echo wopb_function()->svg_icon($nav[0]); //phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                                            </div>
                                        </div>
                                        <div class="wopb-slick-next">
                                            <div class="slick-arrow slick-next">
                                                <?php echo wopb_function()->svg_icon($nav[1]); //phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                                            </div>
                                        </div>
                                    </div>
                                <?php
                                    }
                                ?>

                            </div>
                        </div>
                    </div>
            <?php
                return ob_get_clean();
            }
        }
    }

    public function content_section( $product, $attr ) {
        $attr['titleTag'] = in_array($attr['titleTag'],  wopb_function()->allowed_block_tags() ) ? $attr['titleTag'] : 'h3';
?>
        <div class="wopb-content-section">
            <?php
                if(!$attr['taxonomyUnderTitle']) {
                    echo $this->taxonomyContent($product, $attr); //phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                }
                if($attr['showTitle']) {
            ?>
                    <<?php echo $attr['titleTag']; ?> class="wopb-product-title <?php echo $attr['titleHoverEffect'] == 'none' ? '' :  'wopb-title-' . esc_attr($attr['titleHoverEffect']) ?>">
                    <a href="<?php echo esc_url(get_permalink($product->get_id())) ?>">
                        <?php echo wp_kses_post($product->get_title()); ?>
                    </a>
                    </<?php echo $attr['titleTag'] ?>>
            <?php
                }
                if($attr['taxonomyUnderTitle']) {
                    echo $this->taxonomyContent($product, $attr); //phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                }
                if($attr['priceOverDescription']) {
                    echo $this->priceContent($product, $attr); //phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                }
                if($attr['showDescription']) {
            ?>
                <div class="wopb-product-excerpt">
                    <?php
                        $product_excerpt = strlen($product->get_short_description()) > $attr['descriptionLimit'] ? substr($product->get_short_description(), 0, $attr['descriptionLimit']) . '...' : $product->get_short_description();
                        echo $product_excerpt; //phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                    ?>
                </div>
            <?php
                }
                if(!$attr['priceOverDescription']) {
                    echo $this->priceContent($product, $attr); //phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                }
                echo $this->cartContent($product, $attr); //phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
            ?>
        </div>

<?php
    }

    public function image_section($product, $_discount, $attr) {
        $shapeClass = "";
        $shape = "";
        if($attr['saleStyle'] === "shape1") {
            $shapeClass = "wopb-onsale-shape";
            switch ($attr['saleStyle']) {
                case "shape1":
                    $shape = wopb_function()->svg_icon('saleShape1');
                    break;
                default:
                    break;
            }
        }
?>
        <div class="wopb-image-section">
             <span class="wopb-product-image">
                <?php
                    if($attr['showImgOverlay']) {
                        echo '<div class="wopb-image-overlay"></div>';
                    }
                    if($attr['showSaleBadge'] && $product->is_on_sale() && $_discount) {
                ?>
                    <div class="wopb-onsale-hot <?php echo esc_attr($shapeClass); ?>">
                        <?php
                            if($shape !== '') {
                        ?>
                                <span class="wopb-sale-shape">
                                    <?php
                                        echo $shape; //phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                                        echo $this->saleStyle($_discount, $attr); //phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                                    ?>
                                </span>
                        <?php
                            }else {
                                echo $this->saleStyle($_discount, $attr); //phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                            }
                        ?>
                    </div>
                <?php
                    }
                    if($product->get_image_id() && wp_get_attachment_image_src($product->get_image_id(), 'large')[0]) {
                ?>
                        <a href="<?php echo esc_url(get_permalink($product->get_id())) ?>">
                            <img class="wopb-block-image" alt="<?php echo esc_attr($product->get_title()); ?>" src="<?php echo esc_url(wp_get_attachment_image_src($product->get_image_id(), 'large')[0]) ?>"/>
                        </a>
                <?php }else { ?>
                        <a href="<?php echo esc_url(get_permalink($product->get_id())) ?>">
                            <img class="wopb-block-image wopb-fallback-image" alt="<?php echo esc_attr($product->get_title()); ?>" src="<?php echo esc_url(WOPB_URL . 'assets/img/wopb-fallback-img.png'); ?>"/>
                        </a>
                <?php
                    }
                ?>
             </span>
        </div>
<?php
    }

    public function saleStyle($_discount, $attr) {
?>
        <span class="wopb-onsale wopb-onsale-<?php echo esc_attr($attr["saleStyle"]); ?>">
            <?php
                if($attr["saleDesign"] == 'digit') {
                    echo esc_html('-'.$_discount);
                }elseif($attr["saleDesign"] == 'text') {
                    echo isset($attr["saleText"]) ? esc_html($attr["saleText"]) : esc_html__('Sale!', 'product-blocks');
                }elseif($attr["saleDesign"] == 'textDigit') {
                    echo esc_html('-'.$_discount) . esc_html__(' Off', 'product-blocks');
                }
            ?>
        </span>
<?php
    }

    public function taxonomyContent($product, $attr) {
        if($attr['showTaxonomy']) {
?>
            <div class="wopb-product-taxonomies">
                <?php
                    $categories = get_the_terms($product->get_id(), 'product_cat');
                    if($attr['taxonomyType'] == 'product_cat' && $categories) {
                        foreach ($categories as $category) {
                ?>
                            <a href="<?php echo esc_url(get_term_link( $category->term_id )) ?>" class="wopb-taxonomy"><?php echo esc_attr( $category->name ); ?></a>
                <?php
                        }
                    }
                    $tags = get_the_terms($product->get_id(), 'product_tag');
                    if($attr['taxonomyType'] == 'product_tag' && $tags) {
                        foreach ($tags as $tag) {
                ?>
                            <a href="<?php echo esc_url(get_term_link( $tag->term_id )) ?>" class="wopb-taxonomy"><?php echo esc_attr( $tag->name ); ?></a>
                <?php } } ?>
            </div>

<?php

        }
    }

    public function priceContent($product, $attr) {
        if($attr['showPrice'] && $product->get_price_html()) {
?>
            <div class="wopb-product-price-section">
                <span class="wopb-product-price">
                    <?php
                        if($attr['showPriceLabel'] && $attr['priceLabelText']) {
                    ?>
                        <span class="wopb-price-label"><?php echo esc_html($attr['priceLabelText']); ?></span>
                    <?php
                        }
                    ?>
                    <span class="wopb-prices wopb-<?php echo esc_attr( $product->get_type() ); ?>-price">
                        <?php
                            echo $product->get_price_html(); // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                        ?>
                    </span>
                </span>
            </div>
<?php
        }
    }

    public function cartContent($product, $attr) {
        if($attr['showCart']) {
?>
            <div class="wopb-product-cart-section wopb-cart-action">
                <form action="#" class="cart">
                    <?php if($attr['showQty'] && $product->is_type('simple')) { ?>
                        <div class="quantity">
                            <?php if($attr['showPlusMinus'] && $attr['plusMinusPosition'] == 'both') { ?>
                                <span class="wopb-cart-minus wopb-add-to-cart-minus"><?php echo wopb_function()->svg_icon('minus'); //phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
                        <?php } ?>
                            <input type="number" class="input-text qty text" step="1" min="1" max="" name="quantity" value="1" title="Qty" size="4" placeholder="" inputMode="numeric"/>
                        <?php if($attr['showPlusMinus'] && $attr['plusMinusPosition'] == 'both') { ?>
                            <span class="wopb-cart-plus wopb-add-to-cart-plus"><?php echo wopb_function()->svg_icon('plus'); //phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
                        <?php
                            }
                            if($attr['showPlusMinus'] && ($attr['plusMinusPosition'] == 'left' || $attr['plusMinusPosition'] == 'right')) {
                        ?>
                            <span class="wopb-cart-plus-minus-icon">
                                <?php echo $this->plusMinusContent(); //phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
                            </span>
                        <?php
                            }
                        ?>
                        </div>
                    <?php
                        }
                        $cart_btn_class = '';
                        $cart_text = $product->add_to_cart_text();
                        if($product->is_type('simple')) {
                            $cart_btn_class = 'single_add_to_cart_button ajax_add_to_cart';
                            if($attr['cartText'])
                                $cart_text = $attr['cartText'] ? $attr['cartText'] : $cart_text;
                        }
                    ?>
                            <a href="<?php echo esc_url($product->add_to_cart_url()) ?>" class="wopb-product-cart <?php echo esc_html($cart_btn_class); ?>" data-postid="<?php echo esc_attr($product->get_id()) ?>"><?php echo esc_html( $cart_text ); ?></a>
                            <a href="<?php echo esc_url(wc_get_cart_url()) ?>" class="wopb-product-cart wopb-view-cart wopb-d-none"><?php echo esc_html__('View Cart', 'product-blocks'); ?></a>
                </form>
            </div>
<?php
        }
    }

    public function plusMinusContent() {
?>
        <span class="wopb-cart-plus wopb-add-to-cart-plus"><?php echo wopb_function()->svg_icon('plus'); //phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
        <span class="wopb-cart-minus wopb-add-to-cart-minus"><?php echo wopb_function()->svg_icon('minus'); //phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
<?php
    }

}