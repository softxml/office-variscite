<?php

/*********************************************
 * CHECK IF FILTERS ARE APPLIED ON PAGE LOAD
 *********************************************/
if(isset($_GET['cpu_cat'])){
//    add_action( 'wp_head', function(){echo '<meta name="robots" content="noindex, follow" />';} , 10 );
    // 21/09/23- moved to ('wpseo_robots', 'noindex_filter_pages') filter of functions.php //
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

if($ctax['hcompare'][0] == 'on') {$hideCompare = true;} else {$hideCompare = false;}


if( empty($ctax['title']) ) {$ctax['title'] = $term->name;}
if( empty($ctax['desc']) ) {$ctax['desc'] = $term->description;}


$tax = get_queried_object();
$category = get_category(get_query_var('cat'));
?>
    <input type="hidden" id="page_id" value="<?php the_ID(); ?>">
    <input type="hidden" id="page_plink" value="<?php echo get_term_link($term->term_id); ?>">

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
            if($prod_specs_filters){
            ?>
            <script>
                window.specsData = {};
                specsData.term = <?php echo $ctax['id'] ?>
            </script>
            <style>
                .filters-controll-panel.show-filters {
                    display: block;
                }
                .filter-page .filter-box .filter-tabs-wrap .checkbox-wrap input.checkedbox + label span {
                    background: url(<?php echo get_stylesheet_directory_uri(); ?>/images/checkbox/filter-checkbox-checked.png) left top no-repeat
                }
                .filter-page .filter-box .filters-controll-panel .filters-list .btn-filter .removeFilterCross {
                    color: #fff;
                    cursor: pointer;
                    padding: 3px 10px 0 0;
                    margin: 0 13px 0 0
                }
                .filter-page .filter-products-wrap .actions-box .addToCompare + label {
                    display: none;
                }
            </style>
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

                <?php
                // if taxonomy page is for Accessories category, show scrolled sidebar
                $current_term = get_queried_object();
                if ( $current_term->slug == 'accessories' )
                    echo '<div class="filter-sidebar-scrolled">';
                else echo '<div class="filter-sidebar">';
                ?>

                <div class="filters-controll-panel dnone <?php echo (!empty($_GET)? 'show-filters' : ''); ?>" >
                    <div class="block-head">
                        <div class="row">
                            <div class="col-md-4"><?php echo __('Filter By', THEME_NAME); ?></div>
                            <div class="col-md-4"></div>
                            <div class="col-md-4"><button class="btn btn-link clearFilters"><?php echo __('Clear Filters', THEME_NAME); ?></button></div>
                        </div>
                    </div>
                    <div class="block-body">
                        <input type="hidden" id="appliedFilters">
                        <ul class="filters-list row">
                            <?php
                            if( !empty( $_GET ) ){

                                $query         = str_replace( array( ',,' ), ' ', urldecode($_SERVER['QUERY_STRING']) );
                                $query         = str_replace( array( '= ' ), '=', $query );
                                $filterArgs    = sanitize_text_field( $query );
                                $filter_params = ( ! empty( $filterArgs ) ? filterajax_filters_to_arrays( $filterArgs ) : '' );

                                // IF PICKED ASSIGN BASE QUERY PRODUCT CATEGORIES
                                //$cat_filters   = $current_term->term_id;

                                // DYNAMIC META QUERY
                                $postids_arr = array();
                                $som_page_id = 1418;

                                if ( ! empty( $filter_params ) ) {

                                    $filter_params = array_filter( array_map( 'array_filter', $filter_params ) );

                                    //print_r($filter_params);

                                    $i = 0;
                                    foreach ( $filter_params as $group => $values ) {
                                        //print_r($values);
                                        if ( is_array( $values ) ) {
                                            foreach ( $values as $value ) {
                                                $fieldType = '';
                                                if ( $fieldType == 'checkbox' ) {
                                                    $sub_meta_query[] = array(
                                                        'key'     => str2id( $group . '_' . $value ),
                                                        'compare' => ( ! empty( $comapre ) ? $comapre : '' ),
                                                    );
                                                    echo '<li class="col-md-6 btn-filter" data-source="'.$data_source.'" data-spec="'.$data_spec.'" field-type="'.$field_type.'" field-id="'.$field_id.'" field-val="'.$field_val.'"> <span class="removeFilterCross"><i class="fa fa-times-circle tip" title="Remove"></i></span> <a href="#" class="filter-link">'.$data_label.'</span></a></li>';
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                            ?>

                        </ul>
                    </div>
                </div>

                <div class="filter-tabs-wrap">
                    <form role="filter" method="get" id="filtersForm" class="filtersForm" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
                        <?php
                        $cat_filters = '';
                        if( isset( $_GET['cpu_cat'] ) && !empty( $_GET['cpu_cat'] ) )
                        {
                            $cat_filters = $_GET['cpu_cat'];
                        }
                        /*********************************************
                         * IF SPECIFIC CATEGORIES WERE CHOSEN THEN USE THE OBJECT FROM ACF OTHERWISE QUERY ALL SUB CATS OF CURRENT TERM
                         *********************************************/
                        if(is_array($prod_specs_cats)) {
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
                        <?php // Add custom filter cpu name
                        $cpu_filter_name = array(
                            'data-source' => 'cpu_name'
                        );
                        echo filter_tab_builder_one_field(get_field( 'som_filter_tab_group',1418 ),"CPU Name", 'cpu_name');
                        ?>
                        <div class="collapse-wrap refresh-filter box-cpu_cat">
                            <div class="collapse-head">
                                <a class="btn btn-link fs16 bold" role="button" data-toggle="collapse" href="#cpu_cat" aria-expanded="true" aria-controls="cpu_cat"><?php echo __('Accessories category', THEME_NAME); ?></a>
                            </div>
                            <div class="collapse in" id="cpu_cat" data-type="checkbox">
                                <div class="checkboxes-box" data-source="spec" data-spec="acc" id="rmjs-1-1" data-readmore="" aria-expanded="false">
                                    <?php
                                    $count = 0;
                                    foreach($specTerms as $specTerm){
                                        ?>
                                        <div class="checkbox-wrap">
                                            <input type="checkbox" name="cpu_cat[]" data-name="<?php echo $specTerm->name; ?>" id="<?php echo str_replace(' ', '-', $specTerm->name); ?>" value="<?php echo $specTerm->term_id; ?>">
                                            <label class="<?php echo ($cat_filters == $specTerm->term_id)? 'checkedlabel' : '' ?>" for="<?php echo str_replace(' ', '-', $specTerm->name); ?>"><span></span> <?php echo $specTerm->name; ?></label>
                                        </div>
                                        <?php
                                        $count++;
                                    } ?>
                                </div>
                            </div>
                        </div>

                    </form>
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

                    // Check if its Accessories page///
                    $current_term = get_queried_object();
                    if ( $current_term->slug == 'accessories' )
                    {
                        // ROI and CHEN code adaptation:
                        // Preparing sub meta query
                        $query         = str_replace( array( ',,' ), ' ', urldecode($_SERVER['QUERY_STRING']) );
                        $query         = str_replace( array( '= ' ), '=', $query );
                        $filterArgs    = sanitize_text_field( $query );
                        $filter_params = ( ! empty( $filterArgs ) ? filterajax_filters_to_arrays( $filterArgs ) : '' );
                        // echo "<div id='test_chen1'>filter_params=".print_r($filter_params,true)."</div>";

                        // $filterArgs: cpu_name -> array (NXP iMX8, NXP iMX7),
                        //              Architecture -> array (120,121);

                        // IF PICKED ASSIGN BASE QUERY PRODUCT CATEGORIES
                        $cat_filters   = $current_term->term_id;

                        // DYNAMIC META QUERY
                        $postids_arr = array();
                        $som_page_id = 1418;

                        if ( ! empty( $filter_params ) ) {

                            $filter_params = array_filter( array_map( 'array_filter', $filter_params ) );
                            //print_r($filter_params);

                            $i = 0;
                            foreach ( $filter_params as $group => $values ) {

                                $sub_meta_query = array();
                                $fieldType      = get_fieldtype_by_group( $som_page_id, $group );
                                $sub_meta_query = array( 'relation' => 'OR' );

                                // echo "<div id='test_chen2'>filter_params foreach: group=$group, values=".print_r($values,true)."; fieldType=$fieldType</div>";

                                if ( is_array( $values ) ) {
                                    foreach ( $values as $value ) {
                                        if ( $fieldType == 'checkbox' ) {
                                            $sub_meta_query[] = array(
                                                'key'     => str2id( $group . '_' . $value ),
                                                'compare' => ( ! empty( $comapre ) ? $comapre : '' ),
                                            );
                                        }
                                        else if ( $fieldType == 'range' ) {
                                            $ranges = explode( '~', $value );
                                            $sub_meta_query[] = array(
                                                'relation' => 'AND',
                                                array(
                                                    'key'     => str_replace( '-', '_', $group ) . '_from',
                                                    'value'   => array( $ranges[0], $ranges[1] ),
                                                    'type'    => 'numeric',
                                                    'compare' => 'BETWEEN',
                                                ),
                                                array(
                                                    'key'     => str_replace( '-', '_', $group ) . '_to',
                                                    'value'   => array( $ranges[0], $ranges[1] ),
                                                    'type'    => 'numeric',
                                                    'compare' => 'BETWEEN',
                                                ),
                                            );
                                        }
                                        else if ( $fieldType == 'btngroup' ) {
                                            $sub_meta_query[] = array(
                                                'relation' => 'AND',
                                                'key'      => str2id( $group . '_' . $value ),
                                                'compare'  => 'EXISTS',
                                            );
                                        }
                                    }
                                }
                                else {}
                                // echo "<div id='test_chen3'>sub_meta_query=".print_r($sub_meta_query,true)."</div>";
                                if( isset( $_GET['cpu_cat'] ) && !empty( $_GET['cpu_cat'] ) ){

                                    $cat_filters = $filter_params['cpu_cat'];
                                }

                                //print_r($filter_params);
                                //print_r($cat_filters);

                                // RUN FIRST LOOP
                                $tmpArgs = array(
                                    'posts_per_page'         => -1,
                                    'post_status'            => 'publish',
                                    'post_type'              => 'specs',
                                    'order'                  => 'ASC',
                                    'orderby'                => 'menu_order',
                                    'meta_query'             => $sub_meta_query,
                                    'tax_query'              => array(
                                        array(
                                            'taxonomy' => 'products',
                                            'field'    => 'term_id',
                                            'terms'    => $cat_filters
                                        )
                                    ),
                                    'update_post_term_cache' => false,
                                    'update_post_term_cache' => false,
                                    'no_found_rows'          => true,
                                );
                                $tmpQuery = new WP_Query( $tmpArgs );
                                $postids_arr[ str2id( $group ) ] = wp_list_pluck( $tmpQuery->posts, 'ID' );
                                if ( $tmpQuery->have_posts() ) {
                                    while ( $tmpQuery->have_posts() ) {
                                        $tmpQuery->the_post();
                                    }
                                }else{
                                    //echo "no posts";
                                }

                                wp_reset_postdata();
                                wp_reset_query();
                            } // End of foreach filter_params

                            // EXTRACT DUPLICATE POST IDS (the ones that answer all filter params.)

                            if ( count( $postids_arr ) == 1 ) {
                                $postids_arr = call_user_func_array( 'array_merge', array_values($postids_arr) );
                            }
                            elseif ( count( $postids_arr ) > 1 ) {
                                $allValues   = call_user_func_array( 'array_merge', array_values($postids_arr) );
                                $postids_arr = array_unique( array_diff_assoc( $allValues, array_unique( $allValues ) ) );
                            }
                            $pppage = -1;
                        }
                        else {
                            $pppage = 10;
                            $meta_query = '';
                        }
                        // End of copied code


                        // Major query - copied from SOM page:
                        // The Query
                        $paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;

                        //print_r($filter_params);
                        //print_r($postids_arr);

                        // run query
                        //$pppage = -1;
                        $query_results = meta_cat_query_helper( $filter_params, $postids_arr, $cat_filters, $pppage, $paged );


                        $total      = $query_results['found'];
                        //$pcount     = $query_results['query']->post_count;
                        $query      = $query_results['query'];


                        $label_results  = __('Showing', THEME_NAME);
                        $label_of       = __('of', THEME_NAME);
                        $label_products = __('results', THEME_NAME);
                        $label_product  = __('result', THEME_NAME);
                        $label_all      = __('all', THEME_NAME);
                    }else{
                        // If its not accessories page , run original query //
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
                    }

                    if ($total > 10 && $pppage >= 10 ){
                        $start_num = 10*$paged-9;
                        $end_num = 10*$paged;
                        if($end_num > $total){$end_num = $total;}
                        $label_final = $label_results.' <span id="foundProducts">'.$start_num.'-'.$end_num.'</span> '.$label_of.' '.$total.' '.$label_products;
                    }
                    elseif($total == 1){
                        $label_final = $label_results. ' <span id="foundProducts">'.$total.'</span> ' . $label_product;
                    }
                    else {
                        $label_final = $label_results. ' ' . $label_all .' <span id="foundProducts">'.$total.'</span> ' . $label_products;
                    }


                    // RESULTS STRING
                    // $resOffset   = ($pageData['offset']  == '0' ? ( $total - (1 * $pppage) ) : $pageData['offset']);
                    // $resCount    = $total - $resOffset;


                    // THE LOOP
                    /*
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
                    */
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
                                ' . $query_results['data'] . '
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
                    <!-- <div class="pgnavi-box">
                        <-?php
                        $pnav = products_cat_pagenum_data( $ctax['id'], $pppage, true );

                        echo $pnav;
                        ?>
                    </div> -->
                    <div class="pgnavi-box"><?php if ( $query_results['found'] > 0 ) {
                            wp_pagenavi( array( 'query' => $query_results['query'] ) );
                        } ?></div>
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

            function isEmpty(obj) {
                if(isSet(obj)) {
                    if (obj.length && obj.length > 0) {
                        return false;
                    }

                    for (var key in obj) {
                        if (hasOwnProperty.call(obj, key)) {
                            return false;
                        }
                    }
                }
                return true;
            };

            function isSet(val) {
                if ((val != undefined) && (val != null)){
                    return true;
                }
                return false;
            };


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

            Array.prototype.remove = function(v) { this.splice(this.indexOf(v) == -1 ? this.length : this.indexOf(v), 1); }

            // checkbox input click add/update data to url
            function addAndBuildQueryUrl(clickInput, reload = false) {

                // Collect needed data
                var data_label_new         = $("label[for='"+$(clickInput).attr('id')+"']").text();
                var data_source_new         = $(clickInput).parent().parent().attr('data-source');
                var data_spec_new           = $(clickInput).parents('.collapse').attr('id');
                var field_type_new         = $(clickInput).attr('type');
                var field_id_new            = $(clickInput).attr('id');
                var field_val_new			= $(clickInput).val();

                var filter_params = {};
                var gotourl = "";

                $(".filters-list li").each(function (key, value) {
                    var data_source = $(this).attr("data-source").trim();
                    var data_spec = $(this).attr("data-spec");
                    var field_id = $(this).attr("field-id");
                    var field_val = $(this).attr("field-val");

                    if (!filter_params.hasOwnProperty(data_spec)) {
                        filter_params[data_spec] = [];
                    }

                    filter_params[data_spec][key] = field_val;
                });

                //console.log(JSON.stringify(filter_params) + 'uuuuu');
                var finalparams = "";
                var urlparams = $.map(filter_params, function (value, key) {
                    ckey = filter_params[key] + "";
                    params = ckey.split(",");
                    params = params.filter(function (v) {
                        return v !== "";
                    });
                    finalparams = finalparams + key + "=" + params + "&";
                    finalparams = finalparams.replace("=,", "=");
                });

                if (reload) {
                    plink = $("#page_plink").val();
                    gotourl = plink.split("?")[0];
                } else {
                    gotourl = window.location.href.split("?")[0];
                }

                gotourl = gotourl + "?" + finalparams;
                if (gotourl.lastIndexOf("?") == gotourl.length - 1) {
                    gotourl = gotourl.substring(0, gotourl.length - 1);
                }
                if (gotourl.lastIndexOf("&") == gotourl.length - 1) {
                    gotourl = gotourl.substring(0, gotourl.length - 1);
                }
                //console.log(gotourl + "zzzz");
                window.location.href = gotourl;
            }

            /*************************************************
             ** ADD FILTER TO FILTER CONTROL PANEL
             ** grey section under search
             *************************************************/
            $('.refresh-filter input').click(function() {

                // Collect needed data
                var data_label = $("label[for='" + $(this).attr("id") + "']").text();
                var data_source = $(this).parent().parent().attr("data-source");
                // var data_spec           = $(this).parent().parent().attr('data-spec');
                var data_spec = $(this).parents(".collapse").attr("id");
                var field_type = $(this).attr("type");
                var field_id = $(this).attr("id");
                var field_val = $(this).val();

                if ($(this).attr("type") == "checkbox") {
                    if ($('.filters-list li[field-id="' + field_id + '"]').length > 0) {
                        $('.filters-list li[field-id="' + field_id + '"]').remove();
                    } else {
                        var filter_listItem =
                            '<li class="col-md-6 btn-filter" data-source="' +
                            data_source +
                            '" data-spec="' +
                            data_spec +
                            '" field-type="' +
                            field_type +
                            '" field-id="' +
                            field_id +
                            '" field-val="' +
                            field_val +
                            '"> <span class="removeFilterCross"><i class="fa fa-times-circle tip" title="Remove"></i></span> <a href="#" class="filter-link">' +
                            data_label +
                            "</a></li>";
                        $(".filters-list").append(filter_listItem);
                    }
                }
                toggleFiltersList();
                addAndBuildQueryUrl(this, true);
            });


            String.prototype.replaceAll = function (search, replacement) {
                var target = this;
                return target.replace(new RegExp(search, "g"), replacement);
            };

            function getUrlVars() {
                var vars = [],
                    hash;
                var hashes = window.location.href
                    .slice(window.location.href.indexOf("?") + 1)
                    .split("&");

                for (var i = 0; i < hashes.length; i++) {
                    //hash = hashes[i].split(',');
                    hash = hashes[i].split("=");
                    vars.push(hash[0]);
                    vars[hash[0]] = hash[1];
                }
                return vars;
            }
            function toggleFiltersList() {
                var listLength = $(".filters-list").children("li").length;

                if (listLength > 0) {
                    $(".filters-controll-panel").slideDown("fast");
                } else {
                    $(".filters-controll-panel").slideUp("fast");
                }
            }

            onload_urlparams_checkboxs = function () {
                checkQuery = window.location.search;
                if (checkQuery) {
                    var urlPrms = getUrlVars();
                    //console.log(JSON.stringify(urlPrms) + 'uuuuu');
                    if (urlPrms && urlPrms !== null) {
                        $(urlPrms).each(function (key, value) {
                            key = value;
                            //params = urlPrms[value].replaceAll(",,", ",");
                            var params = urlPrms[value];
                            params = params.split(",");
                            base_url = window.location.href.split("?")[0];

                            // CHECK CHECBOXES
                            $(params).each(function (paramKey, paramVal) {
                                //console.log(key + 'kkkkk');

                                if ($("#" + key).length) {
                                    //console.log( 'yyyyy');
                                    var field_type;
                                    if (
                                        typeof $("#" + key).attr("data-type") !== typeof undefined &&
                                        $("#" + key).attr("data-type") !== false
                                    ) {
                                        field_type = $("#" + key).attr("data-type");
                                    } else {
                                        field_type = $("#" + key)
                                            .closest(".parent-group")
                                            .attr("data-type");
                                    }
                                    var fld_val = this.replaceAll("%20", " ");
                                    var fld_str;
                                    var field_id;
                                    var field_val;
                                    var data_source;
                                    var data_group = key;
                                    var crange = "";

                                    if (field_type == "checkbox" || field_type == "btngroup") {
                                        $(":checkbox, .filterBtnIconHover").each(function () {
                                            if ($(this).val() == fld_val) {
                                                fld_str = fld_val;

                                                if (field_type == "checkbox") {
                                                    field_id = $('input[value="' + fld_val + '"]').attr("id");
                                                    field_val = $('input[value="' + fld_val + '"]').val();
                                                    data_source = $('input[value="' + fld_val + '"]')
                                                        .parents(".checkboxes-box")
                                                        .attr("data-source");
                                                    $(':checkbox[value="' + fld_val + '"]').prop(
                                                        "checked",
                                                        "true"
                                                    );
                                                    $(':checkbox[value="' + fld_val + '"]').attr( "class", "checkedbox" );
                                                    //console.log(field_id + "uuu" + field_val);
                                                } else if (field_type == "btngroup") {
                                                    field_id = $('button[value="' + fld_val + '"]').attr(
                                                        "id"
                                                    );
                                                    field_val = $('button[value="' + fld_val + '"]').val();
                                                    data_source = $('button[value="' + fld_val + '"]')
                                                        .parents(".btn-group")
                                                        .attr("data-source");
                                                    $('button[value="' + fld_val + '"]').addClass("active");
                                                }

                                                if( data_group == 'cpu_cat'){
                                                    fld_str = field_id;
                                                }

                                                var tag_url = base_url + "?" + data_group + "=" + field_val;
                                                var filter_listItem =
                                                    '<li class="col-md-6 btn-filter" data-source="' +
                                                    data_source +
                                                    '" data-spec="' +
                                                    data_group +
                                                    '" field-id="' +
                                                    field_id +
                                                    '" field-val="' +
                                                    field_val +
                                                    '" field-type="' +
                                                    field_type +
                                                    '"> <span class="removeFilterCross"><i class="fa fa-times-circle tip" title="Remove"></i></span> <a href="' +
                                                    tag_url +
                                                    '" class="filter-link">' +
                                                    fld_str +
                                                    "</a></li>";
                                                $(".filters-list").append(filter_listItem);
                                            }
                                        });
                                    } else if (field_type == "range") {
                                        field_val = this;
                                        var setrng_val = this.split("~");
                                        field_id = "range-" + key;
                                        crange = document.getElementById(field_id);
                                        data_source = $("#" + key)
                                            .parents(".collapse-wrap")
                                            .find(".range-wrap")
                                            .attr("data-source");
                                        fld_str = $("#" + key)
                                            .parents(".collapse-wrap")
                                            .find(".collapse-head a")
                                            .text();

                                        crange.noUiSlider.updateOptions({
                                            start: [setrng_val[0], setrng_val[1]],
                                        });

                                        var tag_url = base_url + "?" + data_group + "=" + field_val;
                                        var filter_listItem =
                                            '<li class="col-md-6 btn-filter" data-source="' +
                                            data_source +
                                            '" data-spec="' +
                                            data_group +
                                            '" field-id="' +
                                            field_id +
                                            '" field-val="' +
                                            field_val +
                                            '" field-type="' +
                                            field_type +
                                            '"> <span class="removeFilterCross"><i class="fa fa-times-circle tip" title="Remove"></i></span> <a href="##" class="filter-link">' +
                                            fld_str +
                                            "</span></a></li>";
                                        $(".filters-list").append(filter_listItem);
                                    }
                                    toggleFiltersList();
                                }
                            });
                        });
                    }
                }
            };
            onload_urlparams_checkboxs();

            $('.clearFilters').click(function() {
                $('.filters-list li').remove();
                window.location.href = window.location.href.split("?")[0];
            });

            var removeByAttr = function(arr, attr, value){
                var i = arr.length;
                while(i--){
                    if( arr[i]
                        && arr[i].hasOwnProperty(attr)
                        && (arguments.length > 2 && arr[i][attr] === value ) ){

                        arr.splice(i,1);

                    }
                }
                return arr;
            }

            // crosee click remove from url
            $('.removeFilterCross').click(function(e) {

                e.preventDefault();

                var field_type = $(this).parents("li").attr("field-type");
                var field_id = $(this).parents("li").attr("field-id");

                if (field_type == "checkbox") {
                    $('label[for="' + field_id + '"]').trigger("click");
                    $("input#" + field_id).prop("checked", false);
                }

                $(this).parents("li").remove();
                toggleFiltersList();
                addAndBuildQueryUrl(this, true);

            });

        });


        jQuery(function ($) {

            function heightByViewport(ele) {
                return ($(window).height() - $('.page-header').height()) + 100;
            }

            ///// Load more toggle ////
            // $('.checkboxes-box').readmore({
            //     speed: 75,
            //     collapsedHeight: 170,
            //     moreLink: '<a href="#" class="filterParamsExpend">Load More</a>',
            //     lessLink: '<a href="#" class="filterParamsExpend expended">Show less</a>'
            // });

            if(window.matchMedia("(max-width: 767px)").matches) {
                $('.filter-box').affix({
                    offset: {
                        top: function () {
                            return (this.top = $('.page-header').outerHeight(true));
                        },
                        bottom: function () {
                            return (this.bottom = $('.footer').outerHeight(true));
                        }
                    }
                })
            }
            else {
                $('.filter-sidebar-scrolled').affix({
                    offset: {
                        top: function () {
                            return (this.top = $('.page-header').outerHeight(true));
                        },
                        bottom: function () {
                            return $('footer').outerHeight(true);
                            console.log(this.bottom = $('.footer').outerHeight(true));
                        }
                    }
                });
                $('.filter-box .filter-sidebar-scrolled').css('height', 'calc(100vh - 115px)');
            }
        });

    </script>


<?php

get_footer(); ?>