<?php

add_action('wp_ajax_specs_filter_ajaxfunc', 'specs_filter_ajaxfunc');
add_action('wp_ajax_nopriv_specs_filter_ajaxfunc', 'specs_filter_ajaxfunc');
function specs_filter_ajaxfunc() {

    $postTerm = $_POST['filter_cat'];
    $postCats = $_POST['filter_cats'];
    if($postCats == '' || $postCats == null){
        $postCats = $postTerm;
    }
    $postCats = explode( ',',$postCats);

    $term                   = get_term($postTerm, 'products');

    $ctax                   = array();
    $ctax['id']             = $term->term_id;
    $ctax['title']          = get_field('produtcscat_title', $term);
    $ctax['titleclr']       = get_field('produtcscat_titleclr', $term);
    $ctax['bcrumbsclr']     = get_field('produtcscat_bcrumbs_color', $term);
    $ctax['desc']           = get_field('produtcscat_desc', $term);
    $ctax['bgimg']          = get_field('produtcscat_bgimg', $term);
    $ctax['bgmobimg']       = get_field('produtcscat_mobile_bgimg', $term);
    $ctax['bgmobimg']       = $ctax['bgmobimg'] ? $ctax['bgmobimg'] : $ctax['bgmbgimgobimg'];
    $ctax['mobtitleclr']    = get_field('produtcscat_mobile_titleclr', $term);          $ctax['mobtitleclr']    = $ctax['mobtitleclr'] ? $ctax['mobtitleclr'] : $ctax['titleclr'];
    $ctax['mobbcrumbsclr']  = get_field('produtcscat_mobile_bcrumbs_color', $term);     $ctax['mobbcrumbsclr']  = $ctax['mobbcrumbsclr'] ? $ctax['mobbcrumbsclr'] : $ctax['bcrumbsclr'];
    $ctax['desc']           = get_field('produtcscat_desc', $term);
    $ctax['rmlink']         = get_field('produtcscat_rmlink', $term);
    $ctax['longtxt']        = get_field('produtcscat_long_text', $term);
    $ctax['hcompare']       = get_field('hide_compare_link', $term);

    if($ctax['hcompare'][0] == 'on') {$hideCompare = true;} else {$hideCompare = false;}


    if( empty($ctax['title']) ) {$ctax['title'] = $term->name;}
    if( empty($ctax['desc']) ) {$ctax['desc'] = $term->description;}

    if(!empty($ctax['titleclr'])) {
        $colorSettings  = 'style="color:'. $ctax['titleclr'].';" ';
        $btnColor       = 'style="color:'.$ctax['titleclr'].'; border: 1px solid '.$ctax['titleclr'].';" ';
    }
    else {$colorSettings = ''; $btnColor = '';}

    $pppage         = -1;
    $paged 		    = (get_query_var('paged')) ? get_query_var('paged') : 1;
    $data           = '';
    $pcount         = '';

    // PAGE DATA
    $pageData       = products_cat_pagenum_data_accessories($ctax['id'], $pppage, true);
	if( isset( $pageData['offset'] ) ){
		$offset = $pageData['offset'];
	}else{
		$offset = 0;
	}
	
    // The Query
    $args = array(
        'post_status'     => 'publish',
        'posts_per_page'=> $pppage,
        'post_type'     => array('specs'),
        'order'         => 'ASC',
        'orderby'       => 'menu_order',
        'offset'        => $offset,
        'tax_query'     => array(
            'relation' => 'OR',
            array(
                'taxonomy' => 'products',
                'field'    => 'term_id',
                'terms'    => $postCats,
            ),
        ),
    );
    $query      = new WP_Query( $args );
    $total      = $query->found_posts;      // total post count
    $pcount     = $query->post_count;       // post count current page


    // RESULTS STRING
    // $resOffset   = ($pageData['offset']  == '0' ? ( $total - (1 * $pppage) ) : $pageData['offset']);
    // $resCount    = $total - $resOffset;


    // THE LOOP
    if ( $query->have_posts() ) {
        while ( $query->have_posts() ) {
            $query->the_post();


            // item id
            $pid    = get_the_ID();
            $data   .= filter_build_product($pid, get_field('title_length_cat', 'option'), $hideCompare);

        }
        wp_reset_postdata();
        wp_reset_query();
    } else {
        // no posts found
    }
    wp_send_json_success( array( 'success' => true, 'posts' => $data, 'count' => $pcount) );

}