<?php

/*********************************************
 * CHECK IF FILTERS ARE APPLIED ON PAGE LOAD
 *********************************************/
if(isset($_GET['prod_spec_cats'])){
    add_action( 'wp_head', function(){echo '<meta name="robots" content="noindex, follow" />';} , 10 );
}
$term = get_queried_object();

/*********************************************
 * CHECK IF FILTERS ARE SELECTED FOR CURRENT TERM
 * $PROD_SPEC_FILTERS CAN BE USED FROM THIS POINT
 * +ADD CLASS TO BODY
 * +SET CLASS FOR CONTAINER (FOR FILTERS SIDEBAR
 *********************************************/
if(get_field('prod_specs_enable_filters', $term)){
    $prod_specs_filters = true;

    if(get_field('prod_specs_filter_categories', $term)) {
        $prod_specs_cats =  get_field('prod_specs_filter_categories', $term);
    }
    $prod_specs_class = ' prod-specs-sidebar';
    add_filter( 'body_class', function( $classes ) {
        return array_merge( $classes, array( 'prod-specs-sidebar-page' ) );
    } );
} else {
    $prod_specs_class = ' prod-specs-no-sidebar';
}

get_header();

// GET SOME PREDEFINED DATA
$term                   = get_queried_object();
$ctax                   = array();
$ctax['id']             = $term->term_id;
$ctax['title']          = get_field('produtcscat_title', $term);
$ctax['desc']           = get_field('produtcscat_desc', $term);
$ctax['rmlink']         = get_field('produtcscat_rmlink', $term);
$ctax['longtxt']        = get_field('produtcscat_long_text', $term);
$ctax['hcompare']       = get_field('hide_compare_link', $term);

if($ctax['hcompare'] == 'on') {$hideCompare = true;} else {$hideCompare = false;}


if( empty($ctax['title']) ) {$ctax['title'] = $term->name;}
if( empty($ctax['desc']) ) {$ctax['desc'] = $term->description;}


$tax = get_queried_object();
$category = get_category(get_query_var('cat'));
// get the page whose slug matches this category's slug
if($tax->slug == 'system-on-module-som'){
    if(ICL_LANGUAGE_CODE == 'de'){
        $postID = 13406;
    }
    else if(ICL_LANGUAGE_CODE == 'it') {
        $postID = 1277115726;
    }
    else {
        $postID = 1418;
    }
    $post   = get_post( $postID );
    $output =  apply_filters( 'the_content', $post->ID );
    include 'custom-pages/page-som-filter.php';
} else {
    ?>


    <input type="hidden" id="page_id" value="<?php the_ID(); ?>">


    <div class="page-wrap filter-page">

        <!--===STR========= PAGE HEADER =================-->
        <div class="page-header">
            <div class="container-wrap">
                <div class="container relative hidden-xs">
                    <div class="breadcrumbs"><?php echo breadcrumbs(); ?></div>
                </div>
            </div>

            <div class="container-wrap">
                <div class="container title-wrap">
                    <h1 class="page-title"><?php echo $ctax['title']; ?></h1>
                    <div class="row">
                        <div class="col-md-8 col-xs-12">
                            <div class="row">
                                <div class="col-md-9 sub-title">
                                    <p><?php echo $ctax['desc']; ?></p>
                                </div>
                                <?php if ( $ctax['desc'] && strlen( $ctax['desc'] ) > 275 ) { ?>
                                    <div class="col-md-3 rmlink">
                                        <button class="btn btn-default" data-toggle="modal" data-target="#catLongTextModal">
                                            <span class="text"><?php _e( 'Read More' ); ?></span>
                                            <div class="svg-wrap"><?php echo svg_arrow( 8, 12, '#000' ); ?></div>
                                        </button>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--===END========= PAGE HEADER =================-->


        <!--===STR========= PAGE BODY =================-->
        <div class="category-wrap page-body">

            <?php
            /*********************************************
             * CREATE FILTERS HTML & JS OBJECT TO SAVE DATA
             *********************************************/
            if(! $prod_specs_filters = true){
                ?>
                <script>
                    window.specsData = {};
                    specsData.term = <?php echo $ctax['id'] ?>

                </script>
                <div class="specs-filters_sidebar filter-box">
                    <div class="search-box">
                        <form role="search" method="get" id="searchform" class="searchform" action="/">
                            <div>
                                <label class="screen-reader-text" for="s">Search for:</label>
                                <input type="text" name="s" id="s" class="form-control" placeholder="Search" value="">
                                <input type="hidden" value="products" name="post_type">
                                <input type="submit" id="searchsubmit" value="">
                            </div>
                        </form>
                    </div>

                    <div class="filter-sidebar">

                        <div class="filters-controll-panel dnone">
                            <div class="block-head">
                                <div class="row">
                                    <div class="col-md-4"><?php echo __('Filter By', THEME_NAME); ?></div>
                                    <div class="col-md-4"></div>
                                    <div class="col-md-4"><button class="btn btn-link clearFilters"><?php echo __('Clear Filters', THEME_NAME); ?></button></div>
                                </div>
                            </div>
                            <div class="block-body">
                                <input type="hidden" id="appliedFilters">
                                <ul class="filters-list row"></ul>
                            </div>
                        </div>

                        <div class="filter-tabs-wrap">
                            <?php
                            /*********************************************
                             * IF SPECIFIC CATEGORIES WERE CHOSEN THEN USE THE OBJECT FROM ACF OTHERWISE QUERY ALL SUB CATS OF CURRENT TERM
                             *********************************************/
                            if(is_array(isset($prod_specs_cats))) {
                                $specTerms = $prod_specs_cats;
                            } else {
                                $taxonomies = array(
                                    'products',
                                );
                                $args = array(
                                    'child_of' => $ctax['id'],
                                );
                                $specTerms = get_terms( $taxonomies, $args );
                            }
                            //var_dump($specTerms);
                            ?>

                            <div class="collapse-wrap refresh-filter box-cpu_cat">
                                <div class="collapse-head">
                                    <a class="btn btn-link fs16 bold" role="button" data-toggle="collapse" href="#products_cat" aria-expanded="true" aria-controls="cpu_cat"><?php echo __('Accessories category', THEME_NAME); ?></a>
                                </div>
                                <div class="collapse in" id="products_cat" data-type="checkbox">
                                    <div class="checkboxes-box" data-source="spec" data-spec="cpu" id="rmjs-1" data-readmore="" aria-expanded="false">
                                        <div class="checkboxes-box-inner">
                                            <?php
                                            $count = 0;
                                            foreach($specTerms as $specTerm){
                                            if($count == 5){
                                            ?>
                                        </div><div class="checkboxes-box-inner checkboxes-box-inner-hidden" style="display: none">
                                            <?php
                                            }
                                            ?>
                                            <div class="checkbox-wrap">
                                                <input type="checkbox" name="cpu_cat-checkbox-<?php echo $count; ?>" data-name="<?php echo $specTerm->name; ?>" id="<?php echo str_replace(' ', '-', $specTerm->name); ?>" value="<?php echo $specTerm->term_id; ?>">
                                                <label for="<?php echo str_replace(' ', '-', $specTerm->name); ?>"><span></span> <?php echo $specTerm->name; ?></label>
                                            </div>
                                            <?php
                                            $count++;
                                            } ?>
                                        </div>
                                    </div>
                                    <?php if($count > 5){ ?>
                                        <a href="" class="filterParamsExpend" data-readmore-toggle="rmjs-1" aria-controls="rmjs-1">Load More</a>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>

                    </div>

                </div>

                <?php
            }
            ?>
            <div class="container-wrap">
                <div class="container">

                    <div class="filter-products-wrap" data-viewstate="row">

                        <input type="hidden" id="page" value="1">
                        <input type="hidden" id="taxonomy" value="product_cat">
                        <input type="hidden" id="cats" value="<?php echo $ctax['id']; ?>">

                        <?php
                        $pppage = 10;
                        $paged  = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;
                        $data   = '';

                        // PAGE DATA
                        $pageData = products_cat_pagenum_data( $ctax['id'], $pppage, false );

                        // The Query
                        $args           = array(
                            'posts_per_page' => $pppage,
                            'post_type'      => array( 'specs' ),
                            'order'          => 'ASC',
                            'orderby'        => 'menu_order',
                            'offset'         => $pageData['offset'],
                            'tax_query'      => array(
                                array(
                                    'taxonomy' => 'products',
                                    'field'    => 'term_id',
                                    'terms'    => array( $ctax['id'] ),
                                ),
                            ),
                        );
                        $query          = new WP_Query( $args );
                        $total          = $query->found_posts;      // total post count
                        $pcount         = $query->post_count;       // post count current page

                        $label_results = __('Showing', THEME_NAME);
                        $label_of = __('of', THEME_NAME);
                        $label_products = __('results', THEME_NAME);
                        $label_product = __('result', THEME_NAME);
                        $label_all = __('all', THEME_NAME);


                        if($total > 10){
                            $start_num = 10*$paged-9;
                            $end_num = 10*$paged;
                            if($end_num > $total){$end_num = $total;}
                            $label_final = $label_results.' <span id="foundProducts">'.$start_num.'-'.$end_num.'</span> '.$label_of.' '.$total.' '.$label_products;
                        } elseif($total == 1){
                            $label_final = $label_results. ' <span id="foundProducts">'.$total.'</span> ' . $label_product;
                        } else {
                            $label_final = $label_results. ' ' . $label_all .' <span id="foundProducts">'.$total.'</span> ' . $label_products;
                        }


                        // RESULTS STRING
                        // $resOffset   = ($pageData['offset']  == '0' ? ( $total - (1 * $pppage) ) : $pageData['offset']);
                        // $resCount    = $total - $resOffset;


                        // THE LOOP
                        if ( $query->have_posts() ) {
                            while ( $query->have_posts() ) {
                                $query->the_post();


                                // item id
                                $pid  = get_the_ID();
                                $data .= filter_build_product( $pid, get_field( 'title_length_cat', 'option' ), $hideCompare );

                            }
                            wp_reset_postdata();
                            wp_reset_query();
                        } else {
                            // no posts found
                        }
                        echo '
                        <div class="filter-products-infobar">
                            <div class="row">

                                <div class="page-location">' . $label_final . ' </div>

                                <div class="col-md-6 col-sm-7 toolbar">
                                    <ul class="list-inline p0 m0">
                                        ' . ( $hideCompare == true ? '' : '<li> <ul class="list-inline p0 m0"> <li><button class="btn btn-link dnone" id="clearCompare">' . __( 'Clear', THEME_NAME ) . '</button></li> <li><a href="' . get_permalink( get_field( 'optage_defaultpages_compare', 'option' ) ) . '" class="btn btn-warning" id="productsCompare">' . __( 'Compare', THEME_NAME ) . ' <span class="cnum">0</span> ' . __( 'Products' ) . '</a></li> </ul> </li>' ) . '
                                        <li class="hidden-xs hidden-sm"><button class="btn btn-link product-layout active" id="rows"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="22" viewBox="0 0 24 22"> <g fill="#0D0D0D" fill-rule="evenodd"> <path d="M6 0h18v4H6zM6 12h18v4H6zM6 18h18v4H6zM6 6h18v4H6zM0 0h4v4H0zM0 12h4v4H0zM0 18h4v4H0zM0 6h4v4H0z"/> </g> </svg> </button></li>
                                        <li class="hidden-xs hidden-sm"><button class="btn btn-link product-layout" id="grid"><svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 22 22"> <g fill="#0D0D0D" fill-rule="evenodd"> <path d="M0 0h10v10H0zM12 0h10v10H12zM0 12h10v10H0zM12 12h10v10H12z"/> </g> </svg> </button></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="filter-products-loop">
                            <div class="row">
                                ' . $data . '
                            </div>
                        </div>
                        ';
                        ?>
                        <?php
                        /*
                        DOESNT WORK WITH CUSTOM PERMALINK DUE TO "PRODUCTS PAGE" ALREADY EXISTS (SAME SLUG)
                        <div class="pgnavi-box"><?php wp_pagenavi( array('query' => $query) ); ?></div>
                        */
                        ?>

                        <br>
                        <div class="pgnavi-box">
                            <?php
                            $pnav = products_cat_pagenum_data( $ctax['id'], $pppage, true );

                            echo $pnav;
                            ?>
                        </div>
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
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span></button>
                </div>
                <div class="modal-body">
                    <?php echo apply_filters( 'the_content', $ctax['longtxt'] ); ?>
                </div>
            </div>
        </div>
    </div>
    <!--===END========= CAT LONG TEXT MODAL =================-->


    <script>
        jQuery(function ($) {


            /*********************************************
             ** PRODUCT GRID / ROW VARIATION
             *********************************************/
            $('.product-layout').click(function () {
                $('.product-layout').each(function () {
                    $(this).removeClass('active');
                });

                var action = $(this).attr('id');

                $(this).addClass('active');
                $('.filter-products-wrap').attr('data-viewstate', action);
            });


            "use strict";

            function centerModal() {
                $(this).css('display', 'block');
                var $dialog = $(this).find(".modal-dialog"),
                    offset = ($(window).height() - $dialog.height()) / 2,
                    bottomMargin = parseInt($dialog.css('marginBottom'), 10);

                // Make sure you don't hide the top part of the modal w/ a negative margin if it's longer than the screen height, and keep the margin equal to the bottom margin of the modal
                if (offset < bottomMargin) offset = bottomMargin;
                $dialog.css("margin-top", offset);
            }

            $(document).on('show.bs.modal', '.modal', centerModal);
            $(window).on("resize", function () {
                $('.modal:visible').each(centerModal);
            });

            $('.refresh-filter input').click(function() {

                // Collect needed data
                var data_label          = $("label[for='"+$(this).attr('id')+"']").text();
                var data_source         = $(this).parent().parent().attr('data-source');
                // var data_spec           = $(this).parent().parent().attr('data-spec');
                var data_spec           = $(this).parents('.collapse').attr('id');
                var field_type          = $(this).attr('type');
                var field_id            = $(this).attr('id');
                var field_val			= $(this).val();


                if( $(this).attr('type') == 'checkbox' ) {
                    if( $('.filters-list li[field-id="'+field_id+'"]').length > 0 ) {
                        $('.filters-list li[field-id="'+field_id+'"]').remove();
                    }
                    else {
                        var filter_listItem = '<li class="col-md-6 btn-filter" data-source="'+data_source+'" data-spec="'+data_spec+'" field-type="'+field_type+'" field-id="'+field_id+'" field-val="'+field_val+'"> <span class="removeFilter"><i class="fa fa-times-circle tip" title="Remove"></i></span> <a href="#" class="filter-link">'+data_label+'</span></a></li>';
                        $('.filters-list').append(filter_listItem);
                    }
                }

            });

            $('document').ready(function(){
                $('.checkboxes-box input:checked').each(function(){
                    var data_label          = $("label[for='"+$(this).attr('id')+"']").text();
                    var data_source         = $(this).parent().parent().attr('data-source');
                    // var data_spec           = $(this).parent().parent().attr('data-spec');
                    var data_spec           = $(this).parents('.collapse').attr('id');
                    var field_type          = $(this).attr('type');
                    var field_id            = $(this).attr('id');
                    var field_val			= $(this).val();


                    if( $(this).attr('type') == 'checkbox' ) {
                        if( $('.filters-list li[field-id="'+field_id+'"]').length > 0 ) {
                            $('.filters-list li[field-id="'+field_id+'"]').remove();
                        }
                        else {
                            var filter_listItem = '<li class="col-md-6 btn-filter" data-source="'+data_source+'" data-spec="'+data_spec+'" field-type="'+field_type+'" field-id="'+field_id+'" field-val="'+field_val+'"> <span class="removeFilter"><i class="fa fa-times-circle tip" title="Remove"></i></span> <a href="#" class="filter-link">'+data_label+'</span></a></li>';
                            $('.filters-list').append(filter_listItem);
                        }
                    }
                });
            });

            $('.removeFilter, .filter-link').live('click', function(e) {

                e.preventDefault();

                var field_type = $(this).parents('li').attr('field-type');
                var field_id = $(this).parents('li').attr('field-id');


                if (field_type == 'checkbox') {
                    $('label[for="' + field_id + '"]').trigger("click");
                    $('input#' + field_id).prop('checked', false);
                }

                $(this).parents('li').remove();
            });
            $('.clearFilters').click(function() {
                $('.filters-list li').remove();
            });

        });

    </script>


    <?php

}

get_footer(); ?>