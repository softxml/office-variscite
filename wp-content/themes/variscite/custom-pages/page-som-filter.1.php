<?php
/*
Template name: SOM Filter Page
*/

get_header();
?>


    <?php
    // BUILD PAGE INFORMATION
    $pageinfo['bgimg']      = get_field('filter_page_header_image');
    $pageinfo['title']      = get_field('filter_page_title');
    $pageinfo['rmlink']     = get_field('filter_page_rm_link');
    $pageinfo['subtitle']   = get_field('filter_page_sub_title');
    $pageinfo['pcats']      = get_field('som_filter_product_categories');
    $pageinfo['longtext']   = get_field('filter_page_longtext');
    ?>


    <input type="hidden" id="page_id" value="<?php the_ID(); ?>">


    <div class="page-wrap filter-page">
    

        <!--===STR========= PAGE HEADER =================-->
        <div class="page-header" style="background: url('<?php echo $pageinfo['bgimg']; ?>') no-repeat 0 0;">
            <div class="container-wrap">

                <div class="container">
                    <h1 class="page-title"><?php echo $pageinfo['title']; ?></h1>
                    <div class="row">
                        <div class="col-md-7 col-xs-12 clearfix">
                            <div class="row">
                                <div class="col-md-9 sub-title">
                                    <p><?php echo $pageinfo['subtitle']; ?></p>
                                </div>
                                <div class="col-md-3 rmlink"> 
                                    <button data-toggle="modal" data-target="#catLongTextModal"><span class="text"><?php _e('Read More'); ?></span> <img src="<?php echo IMG_URL.'/button-arrow-mini.png'; ?>" alt="Arrow"></button>
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
        <div class="filter-page-body page-body">

            <div class="filter-box">
                <div class="inner relative">
                    <?php if(is_mobile()) { echo '<button class="btn toggleFilterbar"><i class="fa fa-filter"></i></button>'; } ?>

                    <div class="filter-contollers-box">
                        <div class="search-box">
                            <form role="search" method="get" id="searchform" class="searchform" action="/">
                                <div>
                                    <label class="screen-reader-text" for="s">Search for:</label>
                                    <input type="text" name="s" id="s" class="form-control" placeholder="<?php _e('Search', THEME_NAME); ?>" value="" />
                                    <input type="hidden" value="products" name="post_type" />
                                    <input type="hidden" value="product_cat" name="magazines,books" />
                                    <input type="submit" id="searchsubmit" value="" />
                                </div>
                            </form>
                        </div>
                        <div class="filters-controll-panel dnone">
                            <div class="block-head">
                                <div class="row">
                                    <div class="col-md-4"><?php _e('Filter By'); ?></div>
                                    <div class="col-md-4"></div>
                                    <div class="col-md-4"><button class="btn btn-link clearFilters"><?php _e('Clear Filters'); ?></button></div>
                                </div>
                            </div>
                            <div class="block-body">
                                <input type="hidden" id="appliedFilters">
                                <ul class="filters-list row"></ul>
                            </div>
                        </div>
                        <div class="filter-tabs-wrap">
                            <?php echo filter_tab_builder( get_field('som_filter_tab_group') ); ?>
                        </div>
                    </div>
                </div>
                <div class="clearfix"></div>
            </div>

            <div class="container-wrap">
                <div class="container">

                    <div class="filter-products-wrap" data-viewstate="row">

                        <?php
                        
                        $data 		    = '';
                        $page_id		= get_the_ID();
                        $pppage         = get_field('filter_page_postperpage', $page_id);  if( !$pppage ) {$pppage = 10;}
                        $filterArgs     = sanitize_text_field( str_replace('%20', ' ', $_SERVER['QUERY_STRING']));
                        $filter_params  = (!empty($filterArgs) ? filterajax_filters_to_arrays($filterArgs) : '');


                        // IF PICKED ASSIGN BASE QUERY PRODUCT CATEGORIES
                        if( !empty($filter_params['cpu-architecture']) ) { $cat_filters = geterms_by_names($filter_params['cpu-architecture']); $catids_string =  implode(',', geterms_by_names($filter_params['cpu-architecture']) ) ; } 
                        else {$cat_filters = $pageinfo['pcats']; $catids_string = implode(',', $pageinfo['pcats']);}
                        if($filter_params['cpu-architecture']) {unset($filter_params['cpu-architecture']);}


                        // DYNAMIC META QUERY
                        if( !empty($filter_params) ) {


                            $meta_query[] = array('relation' => 'OR');
                            foreach ($filter_params as $group => $values) {

                                $sub_meta_query = array();
                                $fieldType      = get_fieldtype_by_group(get_the_ID(), $group);
                                 
                                if($fieldType == 'checkbox') {$comapre = 'EXISTS';}

                                if(is_array($values)) {
                                    foreach($values as $value) {
                                        $sub_meta_query[] = array(
                                            'key'       => $value,
                                            'compare'   => $comapre,
                                        );
                                    }
                                    $meta_query[] = array('relation' => 'AND', $sub_meta_query);
                                }
                                else {
                                    $meta_query[] = array(
                                        'key'       => $values,
                                        'compare'   => $comapre,
                                    );
                                }
                            }
                        }

                        echo '<pre dir="ltr">Print: '."<br>";
                        print_r($meta_query);
                        echo '</pre>';


                        // FIELDS ACCESSIBLE FROM JQUERY
                        echo '
                        <input type="hidden" id="page" value="1">
                        <input type="hidden" id="taxonomy" value="product_cat">
                        <input type="hidden" id="cats" value="'.$catids_string.'">
                        ';

                        // 	THE QUERY
                        // 	functions from functions/filter-function.php:
                        $foundPostsids  = array();
                        $queryPostids   = array();
                        $counter        = '';
                        $post_specs		= '';

                        // The Query
                        $args = array( 
                            'posts_per_page'=> -1,
                            'post_type'     => 'specs',
                            'tax_query'     => array(
                                array(
                                    'taxonomy' => 'product_cat',
                                    'field'    => 'term_id',
                                    'terms'    => $cat_filters,
                                ),
                            ),
                        );
                        $query = new WP_Query( $args );
                        $found = $query->found_posts;

                        // The Loop
                        if ( $query->have_posts() ) {
                            while ( $query->have_posts() ) {
                                $query->the_post();

                                $pid = get_the_ID();

                                if( !empty($filter_params) ) {
                                    
                                    $post_specs	= post_tabs_specs_array($pid);
                                    $post_specs	= array_merge(array_values($post_specs), array_keys($post_specs));
                                    $post_specs	= array_reduce($post_specs, function ($a, $b) { return array_merge($a, (array) $b); }, []);
                                    $post_specs = array_map('trim', $post_specs);

                                    // LOOP TROUGH ALL SPECS PARAMS AND COLLECT IDS FOR EACH CHECK
                                    // GROUP IDS INTO SUB ARRAYS TO LATER CROSS FOR RELEVANT PRODUCTS
                                    foreach($filter_params as $groupName => $paramGroup) {
                                        foreach($paramGroup as $singleParam) {
                                            if( check_partial_spec_inarray($singleParam, $post_specs) ) {
                                                $foundPostsids[$groupName][] = $pid; 
                                            }
                                        }
                                    }

                                }
                                else {
                                    $queryPostids[] = $pid;
                                }
                            }
                            wp_reset_postdata();
                            wp_reset_query();

                        } else {
                            $data = __('Couldnt find anything.');
                        }


                        
                        
                        /*************************************************
                        ** IF MORE THAN ONE SPECS GROUP IS BEING USED
                        ** TO FILTER THAN FIND INTERSECTING POSTIDS
                        ** ELSE RETURN ALL FOR ONE GROUP 
                        *************************************************/
                        if( !empty($foundPostsids) ) {

                            if(count($filter_params) > 1) {
                                $queryPostids = call_user_func_array('array_intersect',$foundPostsids);
                            }
                            else {
                                $queryPostids = call_user_func_array('array_merge', $foundPostsids);
                            }

                        }



                        // SECOND QUERY USING PICKED POST-IDS
                        // The Query
                        $args = array( 
                            'posts_per_page'=> $pppage,
                            'post_type'     => 'specs',
                            'post__in'      => $queryPostids,
                            'tax_query'     => array(
                                array(
                                    'taxonomy' => 'product_cat',
                                    'field'    => 'term_id',
                                    'terms'    => $cat_filters,
                                ),
                            ),
                        );
                        $pquery = new WP_Query( $args );
                        $found  = $pquery->found_posts;
                        
                        // The Loop
                        if ( $pquery->have_posts() ) {
                            while ( $pquery->have_posts() ) {
                                $pquery->the_post();

                                $pid    = get_the_ID();
                                $data   .= filter_build_product($pid);

                            }
                        }

                        // count posts
                        $productsCount = wp_count_posts( 'specs' );
                        $productsCount = $productsCount->publish;
                        ?>



                        <div class="filter-products-infobar">
                            <div class="row">
                                <?php if(!is_mobile()) { ?>
                                <div class="col-md-6 page-location">
                                    <?php _e('Results:', THEME_NAME); ?><?php echo $found.' of '.$productsCount.' products'; ?>
                                </div>
                                <?php } ?>
                                <div class="col-md-6 toolbar">
                                    <ul class="list-inline p0">
                                        <li>
                                            <ul class="list-inline p0 m0">
                                                <li><button class="btn btn-link dnone" id="clearCompare"><?php _e('Clear', THEME_NAME); ?></button></li>
                                                <li><a href="<?php echo get_permalink( get_field('optage_defaultpages_compare', 'option') ); ?>" class="btn btn-warning" id="productsCompare"><?php _e('Compare', THEME_NAME); ?> <span class="cnum">0</span> <?php _e('Products'); ?></a></li>
                                            </ul>
                                        </li>
                                        <?php if(!is_mobile()) { ?>
                                        <li><button class="btn btn-link product-layout active" id="rows"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="22" viewBox="0 0 24 22"> <g fill="#0D0D0D" fill-rule="evenodd"> <path d="M6 0h18v4H6zM6 12h18v4H6zM6 18h18v4H6zM6 6h18v4H6zM0 0h4v4H0zM0 12h4v4H0zM0 18h4v4H0zM0 6h4v4H0z"/> </g> </svg> </button></li>
                                        <li><button class="btn btn-link product-layout" id="grid"><svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 22 22"> <g fill="#0D0D0D" fill-rule="evenodd"> <path d="M0 0h10v10H0zM12 0h10v10H12zM0 12h10v10H0zM12 12h10v10H12z"/> </g> </svg> </button></li>
                                        <?php } ?>
                                    </ul>
                                </div>
                                <?php if(is_mobile()) { ?>
                                <div class="col-md-6 col-xs-12 page-location">
                                    <?php _e('Results:', THEME_NAME); ?><?php echo $found.' of '.$productsCount.' products'; ?>
                                </div>
                                <?php } ?>
                            </div>
                        </div>
                        <div class="filter-products-loop">
                            <div class="row">
                                <?php echo $data; ?>
                            </div>
                        </div>
                        <div class="pgnavi-box"><?php wp_pagenavi( array('query' => $pquery) ); ?></div> 
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
                    <?php echo apply_filters('the_content', $pageinfo['longtext']); ?>
                </div>
            </div>
        </div>
    </div>
    <!--===END========= CAT LONG TEXT MODAL =================-->




<script>
jQuery(document).ready(function($) {
    $('.filter-box').css( 'height', $('.filter-page-body').outerHeight(true) );
    $('.filter-box > .inner').css( 'height', $('.filter-box > .inner').outerHeight(true) );
    $('.filter-box .filter-contollers-box').css( 'height',  $('.filter-box .filter-contollers-box').outerHeight(true) );
});

jQuery(function($){

    <?php if(is_mobile()) { ?>
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
    <?php } ?>

});
</script>

<?php get_footer(); ?>