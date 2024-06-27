<?php
/*************************************************
** ADD USER ID TO ADMIN BODY TAG
*************************************************/
add_filter('admin_body_class', 'custom_adminbody_class');
function custom_adminbody_class($classes){

    global $post;

    $classes .= 'user-'.get_current_user_id().' ';
    
    if( !empty($post->post_type) && $post->post_type == 'page') {
        $classes .= $post->post_name;
    }

    return $classes;
}




/*********************************************
** EASY CHECK IF FILE EXISTS BY NAME
*********************************************/
function does_file_exists($filename) {
    global $wpdb;
    return intval( $wpdb->get_var( "SELECT post_id FROM {$wpdb->postmeta} WHERE meta_value LIKE '%/$filename'" ) );
}



/*********************************************
** DIRECT LINK TO FILTER ACF FIELDS
 
    $file :
    [name] => baby-costume-popcorn.jpg
    [type] => image/jpeg
    [tmp_name] => /tmp/php7RCFyf
    [error] => 0
    [size] => 1915051

    $upload_dir :
    [path] => /home/variscit/domains/variscite.com/public_html/wp-content/uploads/2017/12
    [url] => http://a17133-tmp.s100.upress.link/wp-content/uploads/2017/12
    [subdir] => /2017/12
    [basedir] => /home/variscit/domains/variscite.com/public_html/wp-content/uploads
    [baseurl] => http://a17133-tmp.s100.upress.link/wp-content/uploads
    [error] =>

*********************************************/
//add_filter('wp_handle_upload_prefilter', 'custom_upload_filter' );
function custom_upload_filter( $file ){

    if(does_file_exists($file['name'])) {
        $file['error'] = __('A File with this exact name already exists.', THEME_NAME);
    }

    return $file;

}







/*************************************************
** AJAX REPLY TO SELECT2 GET POSTS INSIDE
** siteit-admin-scripts.min.js
*************************************************/
add_action( 'wp_ajax_sgetposts', 'sgetposts_select2_ajax' ); // wp_ajax_{action}
function sgetposts_select2_ajax(){
 
	// we will pass post IDs and titles to this array
	$return = array();
 
	// you can use WP_Query, query_posts() or get_posts() here - it doesn't matter
	$search_results = new WP_Query( array(
		's'                     => $_GET['q'], // the search query
		'post_status'           => 'publish', // if you don't want drafts to be returned
		'ignore_sticky_posts'   => 1,
		'posts_per_page'        => 50 // how much to show at once
	));
	if( $search_results->have_posts() ) :
		while( $search_results->have_posts() ) : $search_results->the_post();
			// shorten the title a little
			$title = ( mb_strlen( $search_results->post->post_title ) > 50 ) ? mb_substr( $search_results->post->post_title, 0, 49 ) . '...' : $search_results->post->post_title;
			$return[] = array( $search_results->post->ID, $title ); // array( Post ID, Post Title )
		endwhile;
	endif;
	echo json_encode( $return );
	die;
}







add_action( 'restrict_manage_posts', 'my_restrict_manage_posts' );
function my_restrict_manage_posts() {

    // only display these taxonomy filters on desired custom post_type listings
    global $typenow;
    if ($typenow == 'specs') {

        // create an array of taxonomy slugs you want to filter by - if you want to retrieve all taxonomies, could use get_taxonomies() to build the list
        $filters = array('products');

        foreach ($filters as $tax_slug) {
            // retrieve the taxonomy object
            $tax_obj = get_taxonomy($tax_slug);
            $tax_name = $tax_obj->labels->name;

            // output html for taxonomy dropdown filter
            echo "<select name='$tax_slug' id='$tax_slug' class='postform'>";
            echo "<option value=''>Show All $tax_name</option>";
            generate_taxonomy_options($tax_slug,0,0);
            echo "</select>";
        }
    }
}

function generate_taxonomy_options($tax_slug, $parent = '', $level = 0) {
    $args = array('show_empty' => 1);
    if(!is_null($parent)) {
        $args = array('parent' => $parent);
    }
    $terms = get_terms($tax_slug,$args);
    $tab='';
    for($i=0;$i<$level;$i++){
        $tab.='--';
    }
    foreach ($terms as $term) {
        // output each select option line, check against the last $_GET to show the current option selected
        echo '<option value='. $term->slug, $_GET[$tax_slug] == $term->slug ? ' selected="selected"' : '','>' .$tab. $term->name .' (' . $term->count .')</option>';
        generate_taxonomy_options($tax_slug, $term->term_id, $level+1);
    }

}
?>