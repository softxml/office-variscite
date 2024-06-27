<?php
/*
Template name: SOM Filter Page
*/

//get_header();
?>


<?php
// BUILD PAGE INFORMATION
$queryParams            = sanitize_text_field( urldecode($_SERVER['QUERY_STRING']) );
$pageinfo['title']      = get_field( 'filter_page_title' );
$pageinfo['rmlink']   = get_field( 'filter_page_rm_link' );
$pageinfo['subtitle'] = get_field( 'filter_page_sub_title' );
$pageinfo['pcats']    = get_field( 'som_filter_product_categories' );
$pageinfo['longtext'] = get_field( 'filter_page_longtext' );
$params_title         = preload_h1_title( $queryParams, $pageinfo['title'] );

if ( $params_title ) {
    $pageinfo['title'] = $params_title;
}

// GET PARAM: If cpu_name is set, check if it has a unique description
if ( isset( $_GET['cpu_name'] ) ) {
    $filtered_cpu_name = explode( ',', $_GET['cpu_name'] )[0];      // The selected (checked) cpu names
    $cpu_filter_group  = array();

    // Iterating fields
    $filter_groups = get_field( 'som_filter_tab_group' );
    foreach ( $filter_groups as $filter_group ) {
        // If 'cpu name' is a part of the current field title
        if ( strpos( strtolower( $filter_group['som_filter_field_title'] ), 'cpu name' ) !== false ) {
            // Include this filter's options ("values")
            $cpu_filter_group = $filter_group['som_filter_field_box'][0]['som_filter_source_checkbox'];

            foreach ( $cpu_filter_group as $cpus_group ) {
                // Checks if the currently-checked cpus are in this field's values
                if ( strtolower( $cpus_group['som_filter_source_checkbox_val'] ) == strtolower( $filtered_cpu_name ) ) {
                    // Add the CPU name decription and text to page's subtitle etc.
                    if ( ! empty( $cpus_group['filter_page_description'] ) ) {
                        $pageinfo['subtitle'] = $cpus_group['filter_page_description'];
                    }

                    if ( ! empty( $cpus_group['filter_page_desc_long_text'] ) ) {
                        $pageinfo['longtext'] = $cpus_group['filter_page_desc_long_text'];
                    }
                }
            }

            break;
        }
    }
}
?>

    <input type="hidden" id="page_id" value="<?php the_ID(); ?>">
    <input type="hidden" id="page_plink" value="<?php the_permalink(); ?>">

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
                    <h1 class="page-title"><?php echo $pageinfo['title']; ?></h1>
                    <div class="row">
                        <div class="col-md-7 col-xs-12">
                            <div class="row">
                                <div class="col-md-9 sub-title">
                                    <p><?php echo $pageinfo['subtitle']; ?></p>
                                </div>
                                <div class="col-md-3 rmlink">
                                    <button data-toggle="modal" data-target="#catLongTextModal">
                                        <span class="text"><?php _e( 'Read More' ); ?></span>
                                        <div class="svg-wrap"><?php echo svg_arrow( 8, 12, '#000' ); ?></div>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--===END========= PAGE HEADER =================-->


        <!--===STR========= PAGE BODY =================-->

        <!--        <div class="filter_mobile_wrap closed hideInDesktop">-->
        <!--            <button class="btn toggleFilterbar hideInDesktop"><i class="fa fa-filter"></i></button>-->
        <!--            <button class="btn btn-link clearFilters">--><?php //_e( 'Clear Filters' ); ?>
        <!--        </div>-->
        <!--        <div class="filter_mobile_wrap_overlay"></div>-->

        <div class="filter-page-body page-body">
            <div class="filter-box">
                <div class="inner relative">
                    <button class="btn innertoggleFilterbar hideInDesktop"><i class="fa fa-times"></i></button>
                    <div class="filter-contollers-box">
                        <div class="search-box">
                            <form role="search" method="get" id="searchform" class="searchform" action="/">
                                <div>
                                    <label class="screen-reader-text" for="s">Search for:</label>
                                    <input type="text" name="s" id="s" class="form-control" placeholder="<?php _e( 'Search', THEME_NAME ); ?>" value=""/>
                                    <input type="hidden" value="products" name="post_type"/>
                                    <input type="submit" id="searchsubmit" value=""/>
                                </div>
                            </form>
                        </div>
                        <div class="filter-sidebar-scrolled">
                            <div class="filters-controll-panel dnone">
                                <div class="block-head">
                                    <div class="row">
                                        <div class="col-md-4"><?php _e( 'Filter By' ); ?></div>
                                        <div class="col-md-4"></div>
                                        <div class="col-md-4">
                                            <button class="btn btn-link clearFilters"><?php _e( 'Clear Filters' ); ?></button>
                                        </div>
                                    </div>
                                </div>
                                <div class="block-body">
                                    <input type="hidden" id="appliedFilters">
                                    <ul class="filters-list row"></ul>
                                </div>
                            </div>
                            <div class="filter-tabs-wrap">
                                <?php echo filter_tab_builder( get_field( 'som_filter_tab_group' ) ); ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="clearfix"></div>
            </div>

            <div class="container-wrap">
                <div class="container">

                    <div class="filter-products-wrap" data-viewstate="row">

                        <?php
                        $data    = '';
                        $page_id = get_the_ID();
                        $pppage  = get_field( 'filter_page_postperpage', $page_id );
                        if ( ! $pppage ) {
                            $pppage = 10;
                        }

                        $query         = str_replace( array( ',,' ), ' ', urldecode($_SERVER['QUERY_STRING']) );
                        $query         = str_replace( array( '= ' ), '=', $query );
                        $filterArgs     = sanitize_text_field( $query );
                        $filter_params = ( ! empty( $filterArgs ) ? filterajax_filters_to_arrays( $filterArgs ) : '' );

                        // IF PICKED ASSIGN BASE QUERY PRODUCT CATEGORIES
                        if ( ! empty( $filter_params['cpu_architecture'] ) ) {
                            $cat_filters   = geterms_by_names( $filter_params['cpu_architecture'] );
                            $catids_string = implode( ',', geterms_by_names( $filter_params['cpu_architecture'] ) );
                        }
                        else {
                            $cat_filters   = $pageinfo['pcats'];
                            $catids_string = implode( ',', $pageinfo['pcats'] );
                        }
                        if ( ! empty( $filter_params['cpu_architecture'] ) ) {
                            unset( $filter_params['cpu_architecture'] );
                        }

                        // DYNAMIC META QUERY
                        $postids_arr = array();
                        $cpu_name_postids = array();

                        if ( ! empty( $filter_params ) ) {

                            $filter_params = array_filter( array_map( 'array_filter', $filter_params ) );
                            //var_dump($filter_params);

                            $i = 0;

                            if (array_key_exists("cpu_name", $filter_params)){
                                foreach ( $filter_params as $group => $values ) {
                                    if( $group == 'cpu_name'){
                                        $sub_meta_query = array();
                                        $fieldType      = get_fieldtype_by_group( $page_id, $group );
                                        $sub_meta_query = array( 'relation' => 'OR' );
                                        if ( is_array( $values ) ) {
                                            foreach ( $values as $value ) {
                                                if ( $fieldType == 'checkbox' ) {
                                                    $sub_meta_query[] = array(
                                                        'key'     => str2id( $group . '_' . $value ),
                                                        'compare' => ( ! empty( $comapre ) ? $comapre : '' ),
                                                    );

                                                }
                                            }
                                        }
                                        // RUN FIRST LOOP
                                        $cpuArgs = array(
                                            'posts_per_page'         => - 1,
                                            'post_status'            => 'publish',
                                            'post_type'              => 'specs',
                                            'order'                  => 'ASC',
                                            'orderby'                => 'menu_order',
                                            'meta_query'             => $sub_meta_query,
                                            'update_post_term_cache' => false,
                                            'update_post_term_cache' => false,
                                            'no_found_rows'          => true,
                                        );
                                        $cpuQuery = new WP_Query( $cpuArgs );
                                        $cpu_name_postids = wp_list_pluck( $cpuQuery->posts, 'ID' );

                                    }
                                }
                            }

                            foreach ( $filter_params as $group => $values ) {

                                $sub_meta_query = array();
                                $fieldType      = get_fieldtype_by_group( $page_id, $group );
                                $sub_meta_query = array( 'relation' => 'OR' );

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

                                // RUN FIRST LOOP
                                $tmpArgs = array(
                                    'posts_per_page'         => - 1,
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
                                if(!empty($cpu_name_postids)){
                                    $tmpArgs['post__in'] = $cpu_name_postids;
                                }
                                $tmpQuery = new WP_Query( $tmpArgs );

                                //echo $wpdb->last_query;

                                $postids_arr[ str2id( $group ) ] = wp_list_pluck( $tmpQuery->posts, 'ID' );
                                if ( $tmpQuery->have_posts() ) {
                                    while ( $tmpQuery->have_posts() ) {
                                        $tmpQuery->the_post();
                                    }
                                }
//                                echo "sub_meta_query = "; var_dump($sub_meta_query); echo "<br>";
//                                echo "postids_arr = "; var_dump($postids_arr);echo "<br><br>";

                                wp_reset_postdata();
                                wp_reset_query();
                            } // End of foreach filter_params


                            // EXTRACT DUPLICATE POST IDS (the ones that answer all params.)
                            if ( count( $postids_arr ) == 1 ) {
                                $postids_arr = call_user_func_array('array_merge', array_values($postids_arr));
                            } elseif ( count( $postids_arr ) > 1 ) {
                                $allValues   = call_user_func_array( 'array_merge', array_values($postids_arr));
                                $postids_arr = array_unique( array_diff_assoc( $allValues, array_unique( $allValues ) ) );
                            }
                            // echo "Test res <br/><br/>";
                            // print_r($postids_arr);

                        } else {
                            $meta_query = '';
                        }


                        // FIELDS ACCESSIBLE FROM JQUERY
                        echo '
                        <input type="hidden" id="page" value="1">
                        <input type="hidden" id="page_id" value="' . $page_id . '">
                        <input type="hidden" id="taxonomy" value="products">
                        <input type="hidden" id="cats" value="' . $catids_string . '">
                        ';


                        // The Query
                        $paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1;


                        // run query
                        $query_results = meta_cat_query_helper( $filter_params, $postids_arr, $cat_filters, $pppage, $paged );

                        $label_results  = __( 'Showing', THEME_NAME );
                        $label_of       = __( 'of', THEME_NAME );
                        $label_products = __( 'results', THEME_NAME );
                        $label_product  = __( 'result', THEME_NAME );
                        $label_all      = __( 'all', THEME_NAME );

                        // count posts
                        //						$productsCount = get_term_post_count_by_type( $cat_filters, 'products', 'specs' );
                        $productsCount = $query_results['found'];

                        if ( ICL_LANGUAGE_CODE == 'it' ) {
                            if ( $query_results['found'] > 15 ) {
                                $start_num = 15 * $paged - 14;
                                $end_num   = 15 * $paged;
                                if ( $end_num > $query_results['found'] ) {
                                    $end_num = $query_results['found'];
                                }
                                $label_final = $label_results . ' <span id="foundProducts">' . $start_num . '-' . $end_num . '</span> ' . $label_of . ' ' . $productsCount;
                            } elseif ( $query_results['found'] > 1 && $query_results['found'] <= 15 ) {
                                $label_final = $label_results . ' <span id="foundProducts"> 1' . '-' . $query_results['found'] . '</span> ' . $label_of . ' ' . $query_results['found'];
                            } else {
                                $label_final = $label_results . ' <span id="foundProducts">' . $query_results['found'] . '</span>';
                            }
                        }
                        elseif ( ICL_LANGUAGE_CODE == 'de' ) {
                            if ( $query_results['found'] > 15 ) {
                                $start_num = 15 * $paged - 14;
                                $end_num   = 15 * $paged;
                                if ( $end_num > $query_results['found'] ) {
                                    $end_num = $query_results['found'];
                                }
                                $label_final = $label_results . ' <span id="foundProducts">' . $start_num . '-' . $end_num . '</span> ' . $label_of . ' ' . $productsCount . ' ' . $label_products;
                            } elseif ( $query_results['found'] == 1 ) {
                                $label_final = $label_results . ' <span id="foundProducts">' . $query_results['found'] . '</span> ' . $label_product;
                            } else {
                                $label_final = 'Zeige alle <span id="foundProducts">' . $query_results['found'] . '</span> Ergebnisse an';
                            }
                        }
                        else {
                            if ( $query_results['found'] > 15 ) {
                                $start_num = 15 * $paged - 14;
                                $end_num   = 15 * $paged;
                                if ( $end_num > $query_results['found'] ) {
                                    $end_num = $query_results['found'];
                                }
                                $label_final = $label_results . ' <span id="foundProducts">' . $start_num . '-' . $end_num . '</span> ' . $label_of . ' ' . $productsCount . ' ' . $label_products;
                            }
                            elseif ( $query_results['found'] == 1 ) {
                                $label_final = $label_results . ' <span id="foundProducts">' . $query_results['found'] . '</span> ' . $label_product;
                            }
                            else {
                                $label_final = $label_results . ' ' . $label_all . ' <span id="foundProducts">' . $query_results['found'] . '</span> ' . $label_products;
                            }
                        }
                        wp_reset_query();
                        ?>


                        <div class="filter-products-infobar">
                            <div class="row">
                                <!--                                <div class="filter_mobile_wrap closed hideInDesktop">-->
                                <!--                                    <button class="btn toggleFilterbar hideInDesktop"><i class="fa fa-filter"></i></button>-->
                                <!--                                    <button class="btn btn-link clearFilters hideInDesktop">--><?php //_e( 'Clear Filters' ); ?>
                                <!--                                </div>-->
                                <!--                                <div class="filter_mobile_wrap_overlay"></div>-->

                                <div class="page-location">
                                    <?php echo $label_final; ?>
                                </div>
                                <div class="toolbar">
                                    <ul class="list-inline p0 m0">
                                        <li>
                                            <ul class="list-inline p0 m0">
                                                <li>
                                                    <button class="btn btn-link dnone" id="clearCompare"><?php _e( 'Clear', THEME_NAME ); ?></button>
                                                </li>
                                                <li>
                                                    <a href="<?php echo get_permalink( get_field( 'optage_defaultpages_compare', 'option' ) ); ?>" class="btn btn-warning" id="productsCompare">
                                                        <span class="cnum">0</span> <?php _e( 'Products' ); ?> <?php _e( 'Compare', THEME_NAME ); ?>
                                                    </a>
                                                </li>
                                            </ul>
                                        </li>
                                        <li class="hidden-xs hidden-sm">
                                            <button class="btn btn-link product-layout active" id="rows">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="22" viewBox="0 0 24 22">
                                                    <g fill="#0D0D0D" fill-rule="evenodd">
                                                        <path d="M6 0h18v4H6zM6 12h18v4H6zM6 18h18v4H6zM6 6h18v4H6zM0 0h4v4H0zM0 12h4v4H0zM0 18h4v4H0zM0 6h4v4H0z"/>
                                                    </g>
                                                </svg>
                                            </button>
                                        </li>
                                        <li class="hidden-xs hidden-sm">
                                            <button class="btn btn-link product-layout" id="grid">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 22 22">
                                                    <g fill="#0D0D0D" fill-rule="evenodd">
                                                        <path d="M0 0h10v10H0zM12 0h10v10H12zM0 12h10v10H0zM12 12h10v10H12z"/>
                                                    </g>
                                                </svg>
                                            </button>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="filter-products-loop">
                            <div class="row">
                                <?php
                                // echo $data;
                                echo $query_results['data'];
                                ?>
                            </div>
                        </div>

                        <!--===STR===== NOTHING FOUND BOX ==============-->
                        <div class="nothing-found dnone">
                            <div class="row">
                                <?php
                                $ntImg   = get_field( 'som_filter_noresults_img', $page_id );
                                $ntTitle = get_field( 'som_filter_noresults_title', $page_id );
                                $ntText  = get_field( 'som_filter_noresults_text', $page_id );

                                echo '
                                <div class="col-md-4 col-sm-6 col-xs-12">
                                    ' . ( $ntImg ? '<img src="' . $ntImg . '" alt="' . $ntTitle . '" class="img-responsive">' : '' ) . '
                                </div>
                                <div class="col-md-8 col-sm-6 col-xs-12">
                                    <h3>' . $ntTitle . '</h3>
                                    <div class="ntContent">' . apply_filters( 'the_content', $ntText ) . '</div>
                                </div>
                                ';
                                ?>
                            </div>
                        </div>
                        <!--===END===== NOTHING FOUND BOX ==============-->

                        <!--===STR===== LOW RESULTS BOX ==============-->
                        <?php
                        $lowresults['amount']   = get_field( 'lowresults_amount', $page_id );
                        $lowresults['title']    = get_field( 'lowresults_title', $page_id );
                        $lowresults['products'] = get_field( 'lowresults_products', $page_id );
                        ?>

                        <div class="low-amount dnone">
                            <h3 class="low-amount-title"><?php echo $lowresults['title']; ?></h3>
                            <div class="row">
                                <?php
                                foreach ( $lowresults['products'] as $fProduct ) {
                                    echo filter_build_product( $fProduct['lowresults_prod'], get_field( 'title_length_filter', 'option' ) );
                                }
                                ?>
                            </div>

                            <input type="hidden" id="lowResultsAmount" value="<?php echo $lowresults['amount']; ?>">
                        </div>
                        <!--===END===== LOW RESULTS BOX ==============-->

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
                    <?php echo apply_filters( 'the_content', $pageinfo['longtext'] ); ?>
                </div>
            </div>
        </div>
    </div>
    <!--===END========= CAT LONG TEXT MODAL =================-->


    <script>
        // LOCAL STORAGE EASY FUNC FOR OBJECTS
        Storage.prototype.setObj = function (key, obj) {
            return this.setItem(key, JSON.stringify(obj));
        }
        Storage.prototype.getObj = function (key) {
            return JSON.parse(this.getItem(key));
        }


        jQuery(document).ready(function ($) {
            localStorage.removeItem('lasturl');
            var prodUrl = window.location.href;
            $('#productsCompare').on('click', function(){
                localStorage.setItem('lasturl', prodUrl);
            });

            $('.filter-box').css('min-height', $('.filter-page-body').outerHeight(true));
            $('.filter-box > .inner').css('min-height', $('.filter-box > .inner').outerHeight(true));
            $('.filter-box .filter-contollers-box').css('min-height', $('.filter-box .filter-contollers-box').outerHeight(true));


            // SHOW NOTHING FOUND
            var foundProducts = $('.filter-products-loop .row .filter-pitem').length;
            if (foundProducts < 1) {
                $('.nothing-found').fadeIn('fast');
                // Show related products if no results
                $('.low-amount').removeClass('dnone').fadeIn('fast');
            } else {
                $('.nothing-found').fadeOut('fast');
            }


            // REMOVE DUPLICATES FROM low-amount & main loop
            if ($('#lowResultsAmount').val() >= $('.filter-products-loop  .row .filter-pitem').length && $('.filter-products-loop  .row .filter-pitem').length > 0) {
                $('.low-amount .row .filter-pitem').each(function () {
                    var fprodid = $(this).attr('data-prodid');
                    $(this).removeClass('dnone');

                    if ($('.filter-products-loop  .row .filter-pitem[data-prodid="' + fprodid + '"]').length) {
                        $(this).addClass('dnone');
                    }
                });
                $('.low-amount').fadeIn('fast');
            }
            else {
                // $('.low-amount').fadeOut('fast');
            }

        });


        jQuery(function ($) {

            function heightByViewport(ele) {
                return ($(window).height() - $('.page-header').height()) + 100;
            }

/// Load more toggle ////
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
            } else {
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


            // FIXED TOOLBAR INSIDE
            $('.filter-products-infobar').affix({
                offset: {
                    top: function () {
                        return (this.top = $('.page-header').outerHeight(true));
                    },
                    bottom: function () {
                        return (this.bottom = $('footer').outerHeight(true))
                    }
                }
            })


        });
    </script>

<?php //get_footer(); ?>