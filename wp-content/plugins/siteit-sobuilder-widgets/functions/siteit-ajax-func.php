<?php
/*************************************************
** AJAX REPLY TO SELECT2 GET POSTS INSIDE 
** siteit-admin-scripts.min.js
*************************************************/
add_action( 'wp_ajax_sgxgetpostdata', 'sgsiteetposts_select2_ajax' ); // wp_ajax_{action}
function sgsiteetposts_select2_ajax(){
 
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
?>