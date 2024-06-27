<?php
get_header();

// GET SOME PREDEFINED DATA
$term               = get_queried_object();
$ctax               = array();
$ctax['id']         = $term->term_id;
$ctax['title']      = get_field('produtcscat_title', $term);
$ctax['titleclr']   = get_field('produtcscat_titleclr', $term);
$ctax['desc']       = get_field('produtcscat_desc', $term);
$ctax['bgimg']      = get_field('produtcscat_bgimg', $term);
$ctax['rmlink']     = get_field('produtcscat_rmlink', $term);
$ctax['longtxt']    = get_field('produtcscat_long_text', $term);


if( empty($ctax['title']) ) {$ctax['title'] = $term->name;}
if( empty($ctax['desc']) ) {$ctax['desc'] = $term->description;}
if( empty($ctax['bgimg']) ) {$ctax['bgimg'] = get_field('optage_defaults_catheadimg', 'option');}

if(!empty($ctax['titleclr'])) {
    $colorSettings  = 'style="color:'.$ctax['titleclr'].';" ';
    $btnColor       = 'style="color:'.$ctax['titleclr'].'; border: 1px solid '.$ctax['titleclr'].';" ';
}
else {$colorSettings = ''; $btnColor = '';}
?>


    <input type="hidden" id="page_id" value="<?php the_ID(); ?>">


    <div class="page-wrap filter-page">
    

        <!--===STR========= PAGE HEADER =================-->
        <div class="page-header" style="background: url('<?php echo $ctax['bgimg']; ?>') no-repeat center top;">
            <div class="container-wrap">

                <div class="container">
                    <h1 class="page-title" <?php echo $colorSettings; ?> ><?php echo $ctax['title']; ?></h1>
                    <div class="row">
                        <div class="col-md-8 col-xs-12 clearfix">
                            <div class="row">
                                <div class="col-md-9 sub-title" <?php echo $colorSettings; ?> >
                                    <p><?php echo $ctax['desc']; ?></p>
                                </div>
                                <div class="col-md-3 rmlink">
                                    <button data-toggle="modal" data-target="#catLongTextModal" <?php echo $btnColor; ?>><span class="text"><?php _e('Read More'); ?></span> <img src="<?php echo IMG_URL.'/button-arrow-mini.png'; ?>" alt="Arrow"></button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="clearfix"></div>
            </div>
        </div>
        <!--===END========= PAGE HEADER =================-->



        <!--===STR========= PAGE BODY =================-->
        <div class="category-wrap page-body">

            <div class="container-wrap">
                <div class="container">

                    <div class="filter-products-wrap" data-viewstate="row">

                        <input type="hidden" id="page" value="1">
                        <input type="hidden" id="taxonomy" value="product_cat">
                        <input type="hidden" id="cats" value="<?php echo $ctax['id']; ?>">

                        <?php
                        $pppage         = 10;
                        $paged 		    = (get_query_var('paged')) ? get_query_var('paged') : 1;
                        $data           = '';
                        
                        // The Query
                        $args = array(
                            'posts_per_page'=> $pppage,
                            'post_type'     => array('specs'),
                            'order'         => 'ASC',
                            'orderby'       => 'menu_order',
                            'paged'         => $paged,
                            'tax_query'     => array(
                                array(
                                    'taxonomy' => 'product_cat',
                                    'field'    => 'term_id',
                                    'terms'    => array($ctax['id']),
                                ),
                            ),
                        );
                        $query = new WP_Query( $args );
                        $total = $query->found_posts;


                        // RESULTS STRING
                        $resCount = $paged * $pppage;
                        if($resCount > $total) {$resCount = $total;}
                    
                        // The Loop
                        if ( $query->have_posts() ) {
                            while ( $query->have_posts() ) {
                                $query->the_post();
                                
                    
                                // item id
                                $pid            = get_the_ID();
                                $clntitle       = get_the_title();
                                $title          = is_tablet() ? content_to_excerpt( get_the_title(), 55 ) : content_to_excerpt( get_the_title(), 35 );
                                $title          = explode(':', $title);
                                $plink          = get_permalink();

                                $thumb          = smart_thumbnail($pid, 225, 125, '', $clntitle, get_field('optage_defaults_blog_image', 'option'));
                    
                    
                                // EXCERPT SPECS
                                $xcrSpecArr     = post_tabs_specs_array($pid);
                                $xcrSpecKeys    = array('CPU Name', 'RAM', 'CPU Type', 'Wi-Fi', 'Ethernet', 'Parallel RGB' );
                                $xcerptSpcs     = '';
                                $exCls          = 'even';

                                $label_results = __('Results:', THEME_NAME);
                                $label_of = __('of', THEME_NAME);
                                $label_products = __('products', THEME_NAME);
                    
                    
                                $i = 0;
                                foreach($xcrSpecKeys as $item) {
                                    
                                    $xcerptSpcs .= '
                                    <div class="col-md-6 col-sm-12 col-xs-12 excerpt-product-spec '.$exCls.' '.($i > 2 ? 'grid-hide' : '').'">
                                        <div class="row spec-row">
                                            <div class="col-md-4 col-sm-5 col-xs-5"><strong>'.$item.'</strong></div>
                                            <div class="col-md-8 col-sm-7 col-xs-7">'.( !empty($xcrSpecArr[$item][0]) ? $xcrSpecArr[$item][0] : '').'</div>
                                        </div>
                                    </div>
                                    ';
                    
                                    $i++;

                                    if($exCls == 'even') {$exCls = 'odd';} else {$exCls = 'even';}
                                }
                    
                    
                                // FIX TITLE
                                if(count($title) == 1) { $title = $title[0]; }
                                elseif( count($title) == 2) { $title = '<span class="normal">'.$title[0].':</span> '.$title[1]; }
                                elseif( count($title) > 2) { $title = '<span class="normal">'.$title[0].':</span> '.$title[1]; }
                    
                                $data .= '
                                <div class="filter-pitem col-md-12">
                                    <div class="row">
                                        <div class="col-md-3 col-sm-4 col-xs-12 thumb-box">
                                            '.$thumb.'
                                        </div>
                                        <div class="col-md-7 col-sm-6 col-xs-12 title-box">
                                            <h3 class="item-title"><a href="'.$plink.'">'.$title.'</a></h3>
                                            <div class="specs-excerpt">
                                                <div class="row">'.$xcerptSpcs.'</div>
                                            </div>
                                        </div>
                                        <div class="col-md-2 col-sm-2 col-xs-12 actions-box">
                                            <ul class="lsnone p0 '.(wp_is_mobile() ? 'row' : '').' ">
                                                <li class="learnmore-line col-sm-12 '.(wp_is_mobile() ? 'col-xs-6' : '').'"><a href="'.$plink.'" class="btn btn-default w100"><span class="txtlbl">'.__('More Info', THEME_NAME).'</span> <img src="'.IMG_URL.'/black-arrow-right.png" alt="Arrow"></a></li>
                                                <li class="compare-line col-sm-12 '.(wp_is_mobile() ? 'col-xs-6' : '').'"><input type="checkbox" id="compare-'.$pid.'" class="addToCompare" value="'.$pid.'"> <label for="compare-'.$pid.'"> <span></span> '.__('Compare', THEME_NAME).'</label></li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                ';
                    
                            }
                            wp_reset_postdata();
                        } else {
                            // no posts found
                        }

                        echo '
                        <div class="filter-products-infobar">
                            <div class="row">

                                '.(!wp_is_mobile() ? '<div class="col-md-6 col-sm-5 page-location hidden-xs">'.$label_results.' '.$resCount.' '.$label_of.' '.$total.' '.$label_products.'</div>' : '').'

                                <div class="col-md-6 col-sm-7 toolbar">
                                    <ul class="list-inline p0">
                                        <li>
                                            <ul class="list-inline p0 m0">
                                                <li><button class="btn btn-link dnone" id="clearCompare">'.__('Clear', THEME_NAME).'</button></li>
                                                <li><a href="'.get_permalink( get_field('optage_defaultpages_compare', 'option') ).'" class="btn btn-warning" id="productsCompare">'.__('Compare', THEME_NAME).' <span class="cnum">0</span> '.__('Products').'</a></li>
                                            </ul>
                                        </li>
                                        <li class="hidden-xs"><button class="btn btn-link product-layout active" id="rows"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="22" viewBox="0 0 24 22"> <g fill="#0D0D0D" fill-rule="evenodd"> <path d="M6 0h18v4H6zM6 12h18v4H6zM6 18h18v4H6zM6 6h18v4H6zM0 0h4v4H0zM0 12h4v4H0zM0 18h4v4H0zM0 6h4v4H0z"/> </g> </svg> </button></li>
                                        <li class="hidden-xs"><button class="btn btn-link product-layout" id="grid"><svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 22 22"> <g fill="#0D0D0D" fill-rule="evenodd"> <path d="M0 0h10v10H0zM12 0h10v10H12zM0 12h10v10H0zM12 12h10v10H12z"/> </g> </svg> </button></li>
                                    </ul>
                                </div>

                                '.(wp_is_mobile() ? '<div class="col-md-6 col-sm-6 page-location visible-xs">'.$label_results.' '.$resCount.' '.$label_of.' '.$total.' '.$label_products.'</div>' : '').'

                            </div>
                        </div>
                        <div class="filter-products-loop">
                            <div class="row">
                                '.$data.'
                            </div>
                        </div>
                        ';
                        ?>
                        <div class="pgnavi-box"><?php wp_pagenavi( array('query' => $query) ); ?></div>
                    </div>


                </div>
            </div>
        </div>
        <!--===END========= PAGE BODY =================-->
    

    </div>


    

        <!--===STR========= CAT LONG TEXT MODAL =================-->
        <div class="modal fade centered-modal" id="catLongTextModal" tabindex="-1" role="dialog" aria-labelledby="catLongTextModalLabel">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    </div>
                    <div class="modal-body">
                        <?php echo apply_filters('the_content', $ctax['longtxt']); ?>
                    </div>
                </div>
            </div>
        </div>
        <!--===END========= CAT LONG TEXT MODAL =================-->





    <script>
    jQuery(function($){


        /*********************************************
        ** PRODUCT GRID / ROW VARIATION
        *********************************************/
        $('.product-layout').click(function() {
            $('.product-layout').each(function() { $(this).removeClass('active'); });

            var action = $(this).attr('id');
            
            $(this).addClass('active');
            $('.filter-products-wrap').attr('data-viewstate', action);
        });


            "use strict";
            function centerModal() {
                $(this).css('display', 'block');
                var $dialog  = $(this).find(".modal-dialog"),
                offset       = ($(window).height() - $dialog.height()) / 2,
                bottomMargin = parseInt($dialog.css('marginBottom'), 10);

                // Make sure you don't hide the top part of the modal w/ a negative margin if it's longer than the screen height, and keep the margin equal to the bottom margin of the modal
                if(offset < bottomMargin) offset = bottomMargin;
                $dialog.css("margin-top", offset);
            }

            $(document).on('show.bs.modal', '.modal', centerModal);
            $(window).on("resize", function () {
                $('.modal:visible').each(centerModal);
            });

    });
    </script>


<?php get_footer(); ?>