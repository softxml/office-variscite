<?php

// AUTOMATED TITLE
add_action( 'after_setup_theme', 'variscite_title_support' );
function variscite_title_support() {
    add_theme_support( 'title-tag' );
}


include(THEME_PATH.'/functions/pbuilder/siteorigin-panels.php');			// PBUILDER

remove_action('wp_head', 'wp_generator');									// REMOVE WP VERSION
$lang = TEMPLATEPATH . '/lang'; load_theme_textdomain(THEME_NAME, $lang);	// SET TRANSLATION
remove_action( 'wp_head', 'print_emoji_detection_script', 7 );				// REMOVE EMOJI SCRIPT
remove_action( 'wp_print_styles', 'print_emoji_styles' );					// REMOVE EMOJI SCRIPT
// add_theme_support('woocommerce');



// FIX MAX IMG SIZE
ini_set('post_max_size', '50M');
ini_set('upload_max_filesize', '50M');




/*******************************
 ** ADD THEME SUPPORT:
 ** THUMBNAIL
 *******************************/
add_theme_support('post-thumbnails', array( 'post', 'page', 'specs' ));



/*************************************************
 ** ACF SUPPORT FOR THEME OPTIONS PAGE
 *************************************************/
if( function_exists('acf_add_options_page') ) {
    acf_add_options_page();
}




/*************************************************
 ** ADD WP_MAIL SUPPORT FOR HTML
 *************************************************/
function wpse27856_set_content_type(){
    return "text/html";
}
add_filter( 'wp_mail_content_type','wpse27856_set_content_type' );



/*******************************
 ** REMOVE EMPTY P
 *******************************/
add_filter('the_content', 'remove_empty_tags_recursive', 20, 1);
function remove_empty_tags_recursive ($str, $repto = NULL) {
    $str = force_balance_tags($str);
    if (!is_string ($str) || trim ($str) == '') return $str;
    return preg_replace ( '/<([^<\/>]*)>([\s]*?|(?R))<\/\1>/imsU', !is_string ($repto) ? '' : $repto, $str );
}



/*******************************
 ** REMOVE QUERY STRING
 ** FROM STATIC RESOURCES
 *******************************/
function _remove_query_strings_1($src){
    $rqs = explode( '?ver', $src );
    return $rqs[0];
}
function _remove_query_strings_2($src){
    $rqs = explode( '&ver', $src );
    return $rqs[0];
}
add_filter('script_loader_src', '_remove_query_strings_1', 15, 1);
add_filter('style_loader_src', '_remove_query_strings_1', 15, 1);
add_filter('script_loader_src', '_remove_query_strings_2', 15, 1);
add_filter('style_loader_src', '_remove_query_strings_2', 15, 1);


/*******************************
 ** ATTACHMENT ID BY SRC
 *******************************/
function get_attachment_id_from_src($image_src) {
    // Split the $url into two parts with the wp-content directory as the separator
    $parsed_url  = explode( parse_url( WP_CONTENT_URL, PHP_URL_PATH ), $url );

    // Get the host of the current site and the host of the $url, ignoring www
    $this_host = str_ireplace( 'www.', '', parse_url( home_url(), PHP_URL_HOST ) );
    $file_host = str_ireplace( 'www.', '', parse_url( $url, PHP_URL_HOST ) );

    // Return nothing if there aren't any $url parts or if the current host and $url host do not match
    if ( ! isset( $parsed_url[1] ) || empty( $parsed_url[1] ) || ( $this_host != $file_host ) ) {
        return;
    }

    // Now we're going to quickly search the DB for any attachment GUID with a partial path match
    // Example: /uploads/2013/05/test-image.jpg
    global $wpdb;

    $attachment = $wpdb->get_col( $wpdb->prepare( "SELECT ID FROM {$wpdb->prefix}posts WHERE guid RLIKE %s;", $parsed_url[1] ) );

    // Returns null if no attachment is found
    return $attachment[0];
}



/*************************************************
 ** SITEIT GET IMAGE ID
 *************************************************/
function siteite_get_image_id($image_url) {
    global $wpdb;
    $attachment = $wpdb->get_col($wpdb->prepare("SELECT ID FROM $wpdb->posts WHERE guid='%s';", $image_url ));
    return $attachment[0];
}



/*******************************
 ** GET ATTACHMENT HEIGHT
 *******************************/
function attachment_height_fromurl($image_url) {
    $imgData = wp_get_attachment_metadata(siteite_get_image_id($image_url));
    return $imgData['height'];
}



/*******************************
 ** GET ATTACHMENT HEIGHT
 *******************************/
function attachment_alt_fromurl($image_url) {
    return get_post_meta( siteite_get_image_id($image_url), '_wp_attachment_image_alt', true) ;
}



/*******************************
 ** SMART THuMBNAIL
 *******************************/
function smart_thumbnail($postid, int $width = NULL, int $height = NULL, $xclasses = NULL, $thumb_alt = NULL, $defaultThumb = NULL, $forceCut = NULL, $useWebp = false) {

    $thumb_webp			= get_field('webp_featured_image', $postid);
    $thumb_id 			= get_post_thumbnail_id($postid);

    if($thumb_id) {
        $thumb_data		= wp_get_attachment_image_src( $thumb_id, 'full' );
        $thumb_url		= $thumb_data[0];
        $thumb_height	= intval($thumb_data[1]);
        $thumb_width	= intval($thumb_data[2]);
    }
    else {
        $thumb_url		= $defaultThumb;
        $thumb_data		= siteite_get_image_id($thumb_url);
        $thumb_height	= intval($thumb_data[1]);
        $thumb_width	= intval($thumb_data[2]);
    }


    // MAKE SURE IMAGE SIZE IS BIGGET BEFORE RESIZING
    if( !$width || !$height ) {$width = 360; $height = 240;}

    if( $width < $thumb_width && $height < $thumb_height) {
        $thumb_url = str_replace('http://', 'https://', aq_resize($thumb_url, $width, $height) );
    }

    // BUILD ALT
    if($thumb_url && !$thumb_alt) {
        $thumb_alt		=	explode('/', $thumb_url);
        $thumb_alt		=	end($thumb_alt);
        $thumb_alt		=	str_replace(array('-', '_'), '', $thumb_alt);
    }
    if(!$thumb_alt) {$thumb_alt = '';}



    return '
		<picture>
			'.( !empty($thumb_webp['url']) && $useWebp ? '<source srcset="'.$thumb_webp['url'].'" type="image/webp"  alt="'.$thumb_alt.'"  class="img-responsive '.$xclasses.'" style="'.$webp_width.'">' : '' ).'
			<img src="'.$thumb_url.'" alt="'.$thumb_alt.'" class="img-responsive '.$xclasses.'">
		</picture>
		';


}





/*******************************
 ** CUSTOM BODY CLASS
 *******************************/
add_filter('body_class', 'custom_body_class');
function custom_body_class($classes){

    if(!is_rtl()) {$classes[] = 'ltr';}


    if(!is_home() && !is_front_page()) {

        // SINGULAR ITEM
        $customPageClass = get_field('pp_page_class', get_the_ID());
        if($customPageClass) {$classes[] = $customPageClass;}
        $classes[] = 'singular-item';


        // IS SPECS PAGE ADD CATEGORY SLUG TO URL
        if(is_singular('specs')) {
            $pterms 	= wp_get_post_terms(get_the_ID(), 'products');
            $termSlug 	= $pterms[0]->slug;

            $classes[] = 'specs-'.$termSlug;
        }
    }

    // IF SINGLE POST
    if(is_singular('post')) {
        $classes[] = 'singular-post';
    }


    // ALL PAGES IF IS *NOT MOBILE
    if( !wp_is_mobile() ) {
        $classes[] = 'not-mobile';
    }



    return $classes;
}





/*******************************
 ** GET CURRENT URL
 *******************************/
function sagive_current_url() {
    if(is_home() || is_front_page()) {
        $curl	=	get_bloginfo('wpurl');
    }
    elseif(is_category()) {
        $cat_id = 	get_query_var('cat');
        $curl	=	get_category_link( $cat_id );
    }
    else {
        $curl	=	get_permalink();
    }

    return $curl;
}




/*************************************************
 ** SVG & WEBP SUPPORT
 *************************************************/
function cc_mime_types($mimes) {
    $mimes['svg'] = 'image/svg+xml';
    $mimes['webp'] = 'image/webp';
    return $mimes;
}
add_filter('upload_mimes', 'cc_mime_types');

function fix_svg_thumb_display() {
    echo '<style>td.media-icon img[src$=".svg"], img[src$=".svg"].attachment-post-thumbnail { width: 100% !important; height: auto !important; }</style>';
}
add_action('admin_head', 'fix_svg_thumb_display');



/*********************************************
 ** EASY ARROW (SVG) INCLUDE
 *********************************************/
function svg_arrow($width, $height, $color) {
    return '<svg xmlns="http://www.w3.org/2000/svg" width="'.$width.'" height="'.$height.'" viewBox="0 0 '.$width.' '.$height.'"> <path fill="none" fill-rule="evenodd" stroke="'.$color.'" stroke-linejoin="round" stroke-width="2" d="M1 1l6 5-6 5"/> </svg> ';
}




/*************************************************
 ** PAGE ID BY PAGE TEMPLATE
 *************************************************/
function get_pageid_by_pagetemplate($page_template) {
    $pages = get_pages(array(
        'meta_key' 		=> '_wp_page_template',
        'meta_value' 	=> $page_template
    ));
    return $pages[0]->ID;
}




/*************************************************
 ** CONDITIONAL NO INDEX TAG TO PAGE
 *************************************************/
add_action('wp_head', 'conditional_noindex_tag');
function conditional_noindex_tag() {
    $pid 		= get_the_ID();
    $noIndex 	= get_field('no_index', $pid);

    if( !empty($noIndex[0]) && $noIndex[0] == 'on') {
        echo '<meta name="robots" content="noindex"‎>';
    }
}




/*************************************************
 ** SEARCH RESULTS PAGE META CONDITIONAL NO INDEX
 ************************************************

// WAS SET USING YOAST


add_action('wp_head', 'custom_search_noindex');
function custom_search_noindex(){

if(is_search()) {
$whiteList 		= get_field('search_page_whitelist', get_the_ID());
$whiteList 		= preg_split('/\r\n|\r|\n/', $whiteList);
$baseUrl 		= get_bloginfo('wpurl');
$searchQuery    = get_query_var( 's' );
$currenUrl 		= $baseUrl.$searchQuery;

if( in_array($currenUrl, $whiteList) ) { echo '<meta name="robots" content="index,follow">'; }
else { echo '<meta name="robots" content="noindex,follow">'; }
}
}
 */

/*********************************************
 ** BUILD SEARCH RESULT  PRODUCT LOOP
 *********************************************/
function search_build_product($pid, $counter){

    $title          = get_the_title($pid);
    $clntitle		= $title;
    $title          = explode(':', $title);
    $plink          = get_permalink($pid);
    $thumb          = smart_thumbnail($pid, 225, 125, '', $clntitle, get_field('optage_defaults_blog_image', 'option'));



    // EXCERPT SPECS NEW
    $excerptitems   = get_field('specs_category_values', $pid);
    $xcerptSpcs     = '';
    $exCls          = 'even';
    $i              = 0;

    if( !empty($excerptitems) ) {
        foreach($excerptitems as $item) {
            $xcerptSpcs .= '
				<div class="col-md-12 col-xs-12 excerpt-product-spec '.$exCls.' '.($i > 2 ? 'grid-hide' : '').'">
					<div class="row spec-row">
						<div class="col-md-4 col-xs-6"><strong>'.$item['fld_name'].'</strong></div>
						<div class="col-md-8 col-xs-6 max25">'.$item['fld_value'].'</div>
					</div>
				</div>
				';

            $i++;

            if($exCls == 'even') {$exCls = 'odd';} else {$exCls = 'even';}
        }
    }

    // FIX TITLE
    if(count($title) == 1) { $title = $title[0]; }
    elseif( count($title) >= 2) { $title = '<span class="normal">'.$title[0].':</span> '.$title[1]; }

    return '
		<div class="filter-pitem '.($counter > 5 ? 'hidden-result dnone' : 'visible-result').'">
			<div class="row">
				<div class="col-md-3 col-xs-12 thumb-box">
					<a href="'.$plink.'">'.$thumb.'</a>
				</div>
				<div class="col-md-9 col-xs-12 title-box">
					<h3 class="item-title"><a href="'.$plink.'">'.$title.'</a></h3>

					<div class="specs-excerpt">
						<div class="row">'.$xcerptSpcs.'</div>
					</div>
				</div>
			</div>
		</div>
		';
}





/*************************************************
 ** SIDE STRIP EASY FUNC
 *************************************************/
function get_sidestrip() {
    $hide = get_field('hide_side_strip', get_the_ID());

    if(!empty($hide[0]) && $hide[0] != 'on') {
        include(THEME_PATH.'/parts/side-strip.php');
    }
}


/*************************************************
 ** 404 REDIRECT
 *************************************************/
function siteit_grab_404(){
    if( is_404() ){
        wp_redirect( get_bloginfo('wpurl').'/404/' );
    }
}
//add_action( 'template_redirect', 'siteit_grab_404' );




/*********************************************
 ** CATEGORY (PRODUCTS) PAGE NUMBER
 *********************************************/
function products_cat_pagenum_data($catid, $posts_per_page, $returnNav) {

    $p = get_query_var('paged');
    $total 			= get_term_post_count_by_type($catid, 'products', 'specs');
    $totalPages 	= ceil($total / $posts_per_page);
    $page 			= array();

    $label_prev = __( '&lt; Prev ', 'variscite' );
    $label_next = __( 'Next &gt; ', 'variscite' );


    if(empty($p) || $p == '1') {
        $page['current'] 	= 1;
        $page['prev'] 		= '';
        $page['next'] 		= 2;
        $page['offset']		= 0;
        $page['pages'] 		= $totalPages;
        $page['total'] 		= $total;
    }
    else {
        $page['current'] 	= $p;
        $page['prev'] 		= ($p - 1);
        $page['next'] 		= ($page['current'] < $totalPages ? ($p + 1) : '');
        $page['offset'] 	= ( ($p - 1) * 10);
        $page['pages'] 		= $totalPages;
        $page['total'] 		= $total;
    }

    $tax = get_queried_object();
    $tax_url = get_term_link($tax->term_id);

    $catNavi = '
		<div class="wp-pagenavi">
			<span class="pages">of '.$page['pages'].'</span>
			'.($page['prev'] ? '<a class="previouspostslink" rel="prev" href="' . $tax_url . 'page/'.$page['prev'].'/">'. $label_prev .'</a>' : '').'
			<span class="current">'.$page['current'].'</span>
			'.($page['next'] ? '<a class="nextpostslink" rel="next" href="' . $tax_url . 'page/'.$page['next'].'/">'. $label_next .'</a>' : '').'
		</div>
		';

    if( $returnNav ) {return $catNavi;}
    else {return $page;}


}

/*********************************************
 ** CATEGORY (PRODUCTS) PAGE NUMBER
 *********************************************/
function products_cat_pagenum_data_accessories($catid, $posts_per_page, $returnNav) {

    $p 				= !empty($_GET['page']) ? $_GET['page'] : '';
    $total 			= get_term_post_count_by_type($catid, 'products', 'specs');
    $totalPages 	= ceil($total / $posts_per_page);
    $page 			= array();

    $label_prev = __( '&lt; Prev ', 'variscite' );
    $label_next = __( 'Next &gt; ', 'variscite' );

    if(empty($p) || $p == '1') {
        $page['current'] 	= 1;
        $page['prev'] 		= '';
        $page['next'] 		= 2;
        $page['offset']		= 0;
        $page['pages'] 		= $totalPages;
        $page['total'] 		= $total;
    }
    else {
        $page['current'] 	= $p;
        $page['prev'] 		= ($p - 1);
        $page['next'] 		= ($page['current'] < $totalPages ? ($p + 1) : '');
        $page['offset'] 	= ( ($p - 1) * 10);
        $page['pages'] 		= $totalPages;
        $page['total'] 		= $total;
    }

    $catNavi = '
		<div class="wp-pagenavi">
			<span class="pages">of '.$page['pages'].'</span>
			'.($page['prev'] ? '<a class="previouspostslink" rel="prev" href="?page='.$page['prev'].'">'. $label_prev .'</a>' : '').'
			<span class="current">'.$page['current'].'</span>
			'.($page['next'] ? '<a class="nextpostslink" rel="next" href="?page='.$page['next'].'">'. $label_next .'</a>' : '').'
		</div>
		';

    if( $returnNav ) {return $page . $catNavi;}
    else {return $page;}


}


/*********************************************
 ** CATEGORY (posts) PAGE NUMBER
 *********************************************/
function blog_cat_pagenum_data($catids, $posts_per_page, $returnNav) {

    //$p 				= !empty($_GET['page']) ? $_GET['page'] : '';
    $p = get_query_var('paged');
    $total 			= get_term_post_count_by_type($catids, 'category', 'post');
    $totalPages 	= ceil($total / $posts_per_page);
    $page 			= array();

    $label_prev = __( '&lt; Prev ', 'variscite' );
    $label_next = __( 'Next &gt; ', 'variscite' );


    if(empty($p) || $p == '1') {
        $page['current'] 	= 1;
        $page['prev'] 		= '';
        $page['next'] 		= 2;
        $page['offset']		= 0;
        $page['pages'] 		= $totalPages;
        $page['total'] 		= $total;
    }
    else {
        $page['current'] 	= $p;
        $page['prev'] 		= ($p - 1);
        $page['next'] 		= ($page['current'] < $totalPages ? ($p + 1) : '');
        $page['offset'] 	= ( ($p - 1) * 10);
        $page['pages'] 		= $totalPages;
        $page['total'] 		= $total;
    }

    $tax = get_queried_object();
    $tax_url = get_term_link($tax->term_id);


    $catNavi = '
		<div class="wp-pagenavi">
			<span class="pages">of '.$page['pages'].'</span>
			'.($page['prev'] ? '<a class="previouspostslink" rel="prev" href="' . $tax_url . 'page/'.$page['prev'].'/">'. $label_prev .'</a>' : '').'
			<span class="current">'.$page['current'].'</span>
			'.($page['next']  && $totalPages != 1 ? '<a class="nextpostslink" rel="next" href="' . $tax_url . 'page/'.$page['next'].'/">'. $label_next .'</a>' : '').'
		</div>
		';

    if( $returnNav ) {return $catNavi;}
    else {return $page;}


}



/*********************************************
 ** VISUAL SITEMAP: X PRODUCTS LIST OF TERM
 *********************************************/
function sitemap_term_recent_postlist($term_id, $tax, $amount, $listlevel) {

    $postlist = '';

    if(!$amount) {$amount = 10;}
    if(!$listlevel) {$listlevel = 1;}
    if($tax == 'category') {$postype = 'post';} elseif ($tax == 'products') {$postype = 'specs';}


    $smargs = array(
        'post_type'			=> $postype,
        'posts_per_page'	=> $amount,
        'tax_query' 		=> array(
            array(
                'taxonomy' => $tax,
                'field'    => 'term_id',
                'terms'    => array( $term_id ),
            ),
        ),
    );
    $smquery	= new WP_Query( $smargs );
    $found 		= $smquery->found_posts;
    $counter 	= 1;

    if ($smquery->have_posts()) {
        while ($smquery->have_posts()) {
            $smquery->the_post();

            // ITEM DATA
            $pid 		= get_the_ID();
            $title 		= get_the_title();
            $link 		= get_permalink();

            $postlist  .= '<li data-level="'.$listlevel.'" class="'.($counter == $found ? 'last-child' : '').'"><a href="'.$link.'">'.$title.'</a></li>';

            $counter++;
        }
    }
    wp_reset_query();

    return $postlist;

}



/*********************************************
 ** VISUAL SITEMAP: GET CHILD PAGES
 *********************************************/
function sitemap_page_children($page_id, $amount = 10, $listlevel = null) {

    $postlist = '';

    return $postlist;

}




/*************************************************
 ** REDIRECT TO URL WITHOUT "ITEM"
 ** CUSTOM REDIRECT DIDNT WORK ON NGINX / PLUGIN
 *************************************************/
add_action('template_redirect', 'remove_itemstr_fromurl');
function remove_itemstr_fromurl() {

    $str = "/item/";

    if(strpos($_SERVER['REQUEST_URI'], $str) !== false){

        $newURL = str_replace('/item', '', $_SERVER['REQUEST_URI']);

        wp_redirect($newURL);
        exit();
    }

}




/*********************************************
 ** CUSTOM PREV/NEXT REL LINKS IN HEADER
 *********************************************/
//	add_action('wp_head', 'wp_head_pagination_mod');
//	function wp_head_pagination_mod() {
//		$cpagednum = get_query_var( 'paged' ) ? get_query_var( 'paged' ) : get_query_var( 'page' );
//
//		if( ! $cpagednum ) return;
//
//		$url = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
//		$url = substr($url, 0, strpos($url, "page"));
//
//		$link_prev = $url . 'page/' . ($cpagednum - 1);
//		$link_next = $url  . 'page/' . ($cpagednum + 1);
//
//		$output  = ' 	<link rel="prev" href="'. $link_prev .'" />' . PHP_EOL;
//		$output .= ' 	<link rel="next" href="'. $link_next .'" />' . PHP_EOL;
//		echo $output;
//	}



/*********************************************
 ** SEND EMAIL ALERT IF "FORM TO SALESFORCE" FAILS
 *********************************************/
function sfalert_email( $leadid, $initiator = '', $response = '' ) {

    $to 		= get_field('sferror_alert_email', 'option');
    $subject 	= get_field('sferror_alert_subject', 'option');
    $message 	= get_field('sferror_alert_content', 'option');

    // GET LEAD INFO
    // <li>Lead Time: [lead_time]</li>
    // <li>Lead Date: [lead_date]</li>
    // <li>Lead Link: [lead_title]</li>
    // <li>Lead Link: [lead_link]</li>
    $time 		= date('G:i a');
    $date 		= date('d.m.Y');
    $title 		= get_the_title($leadid);
    $editlink	= get_edit_post_link( $leadid );

    $domain = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS']
        === 'on' ? "https" : "http") .
        "://" . $_SERVER['HTTP_HOST'] .
        $_SERVER['REQUEST_URI'];


    $message 	= str_replace('[lead_time]', $time, $message);
    $message 	= str_replace('[lead_date]', $date, $message);
    $message 	= str_replace('[lead_title]', $title, $message);
    $message 	= str_replace('[lead_link]', $link, $message);
//        $message 	= str_replace('[lead_domain]', $domain, $message);
	if( !empty( $response ) ){
		$message .= PHP_EOL;
		$message .= $response;
	}

    wp_mail( $to, $subject, $message, array( 'Content-Type: text/html; charset=UTF-8' ) );

}

function custom_wpml_lang_switcher() {
    if (function_exists('icl_get_languages')) {
        // remove WPML default css
        define( 'ICL_DONT_LOAD_LANGUAGE_SELECTOR_CSS', true );
        $languages = apply_filters( 'wpml_active_languages', null, 'orderby=id&order=desc' );
        if ( ! empty( $languages ) ) {
            $outerlist = '<ul><li>';
            $list = '';
            foreach ( $languages as $language ) {
                //$flag = $language['country_flag_url'];
                $url      = $language['url'];
                $isActive = $language['active'];
                $name = $language['native_name'];
                $code = $language['language_code'];

                if($isActive == 1){
                    $outerlist .=  $code . '<ul class="language-dropup">';
                }

                $list .= '<li><a';
                if ( $isActive == 1 ){
                    $list .= ' class="active"';
                }
                $list .= ' href="' . $url .'">' . $name . '</a></li>';
            }
            $list .= '</ul></li></li></ul>';
            return $outerlist . $list;
        }
    }
}

//add_action( 'wp_trash_post', 'delete_specific_custom_post_type_with_translation_fnc' );
//
//function delete_specific_custom_post_type_with_translation_fnc($post_id) {
//	global $post;
//	$post_type = $post->post_type;
//	if ($post_type == 'attachment' || get_post_type() == 'attachment') {
//		global $sitepress;
//		$post_id = $post_id;
//		$trid = $sitepress->get_element_trid($post_id);
//		$translation = $sitepress->get_element_translations($trid);
//		foreach ($translation as $key => $data) {
//			$post_id = $data->element_id;
//
//			$args = array(
//				'ID' => $post_id,
//				'post_status' => 'trash'
//			);
//
//			wp_update_post( $args );
//
//		}
//
//	}
//}

/*********************************************
 ** COOKIE NOTICE POPUP
 *********************************************/


function cookie_notice_vari() {
    $opt = get_field('cookie_pop-up_settings', 'option');
    ?>
    <div class="cookie-notice" style="background-color: <?php echo the_sub_field('cookies_popup_color') ?> ; color: <?php echo the_sub_field('cookies_popup_text_color' ) ?>">
        <div class="cookie-notice__container">
            <div class="cookie-notice__row">
                <div class="cookie-notice__left-col">
                    <p><?php _e("Dear user, by continuing to use our site, you consent to our cookies policy. Please review","Variscite_Privacy"); ?> <a href="/privacy-policy/" title="Variscite Privacy Policy"  style="color: <?php echo the_sub_field('cookies_popup_text_color' ) ?>"><?php _e("Variscite Privacy Policy","Variscite_Privacy"); ?></a><?php _e(" to learn how they can be disabled but notice that some features of the site will not work.","Variscite_Privacy"); ?></p>
                </div>
                <div class="cookie-notice__right-col">
                    <div class="cookie-notice__action">
                       <span class="close-cookie-notice close-cookie-notice--text" style="background-color: <?php echo the_sub_field('cookies_popup_button_color'); ?> ; color: <?php echo the_sub_field('cookies_popup_text_color'); ?>">
                          <?php _e("Accept cookies","Variscite_Privacy"); ?>
                           <i class="fa fa-times"></i>
                       </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php
}
//add_action( 'wp_footer', 'cookie_notice_vari'  , 99);

/***************************************************************
 ** ADDING SCRIPT IN THE SPECS EDIT MODE TO UNCHECK WPML SETTING
 ***************************************************************/
function add_specs_edit_scripts( $hook ) {
    global $post;

    if ( $hook == 'post-new.php' || $hook == 'post.php' ) {
        if ( 'specs' === $post->post_type ) {
            wp_enqueue_script(  'uncheck-script', get_stylesheet_directory_uri().'/js/uncheck-script.js' );
        }
    }
}
add_action( 'admin_enqueue_scripts', 'add_specs_edit_scripts', 10, 1 );

/***************************************************************
 ** Set hreflang="x-default" with WPML
 ***************************************************************/
//add_filter('wpml_alternate_hreflang', 'wps_head_hreflang_xdefault', 10, 2);
//function wps_head_hreflang_xdefault($url, $lang_code) {
//    if($lang_code == apply_filters('wpml_default_language', NULL )) {
//        echo '<link rel="alternate" href="' . $url . '" hreflang="x-default" />'.PHP_EOL;
//    }
//    return $url;
//}

//add_action('wp_head', 'google_optimizer_anti_clicker', 1);
//function google_optimizer_anti_clicker(){
//    if(is_singular('specs')) {
//        print("<!-- anti-flicker snippet (recommended)  -->
//
//<style>.async-hide { opacity: 0 !important} </style>
//
//<script>(function(a,s,y,n,c,h,i,d,e){s.className+=' '+y;h.start=1*new Date;
//
//h.end=i=function(){s.className=s.className.replace(RegExp(' ?'+y),'')};
//
//(a[n]=a[n]||[]).hide=h;setTimeout(function(){i();h.end=null},c);h.timeout=c;
//
//})(window,document.documentElement,'async-hide','dataLayer',4000,
//
//{'OPT-NP2T46B':true});</script>");
//    }
//}