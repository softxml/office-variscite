<?php
// SIMPLE POST COUNT
function posts_amount($postype) {
	$total = wp_count_posts($postype);
	$total = $total->publish;
	
	return $total;
}



// COUNT WORDS IN POST BY ID
function post_wordcount($postid) {
	$content = get_post($postid);
	$completContent = $content->post_content;
	$wordsCount = count(explode(" ", $completContent)); 

	return $wordsCount;
	
	// usage: 
	// $postID = get_the_ID();
	// echo post_wordcount($postID); 
}


// COUNT POSTS BY TYPE & STATUS
function simplePostsCounter($type, $status) {
 
	if($type == '') {$type = 'post';}
	if($status == '') {$status = 'publish';}
	 
	$foundResults = wp_count_posts($type);
	return $foundResults->$status;

	// usage: 
	// echo simplePostsCounter('post', 'publish');
	// echo simplePostsCounter('post', 'draft');
	// echo simplePostsCounter('gallery', 'future');
}



// COUNT TERMS / CATEGORIES
function simpleTermCount($taxonomy, $postype){
	$args = array( 
			'fields' =>'ids',
			'post_type' => $postype,
			'hide_empty' => 0
	 );
	$terms = get_terms($taxonomy, $args);
	$count = count($terms);
	 
	return $count;

	// usage: 
	// echo simpleTermCount('category', 'post');
	// echo simpleTermCount('gallery_cat', 'galleries');
}



// COUNT TERMS / CATEGORIES OF POST
function postTermCount($post_id, $taxonomy){
	$args = array( 
			'fields' =>'ids',
			'hide_empty' => 0
	 );
	$terms = wp_get_post_terms($post_id, $taxonomy, $args);
	$count = count($terms);
	 
	return $count;
	
	// usage: 
	// echo postTermCount('10', 'category');
	// echo postTermCount('10', 'galleries');
}


/*************************************************
** POSTYPE IN TERM COUNT
*************************************************/
function get_term_post_count_by_type($terms, $taxonomy, $type){
    $args = array( 
        'fields'            =>'ids', //we don't really need all post data so just id wil do fine.
        'posts_per_page'    => -1, //-1 to get all post
        'post_type'         => $type, 
        'tax_query'         => array(
            array(
                'taxonomy' => $taxonomy,
                'field' => 'term_id',
                'terms' => $terms
            )
        )
     );
    $ps = get_posts( $args );
    if (count($ps) > 0){return count($ps);}else{return 0;}
}




// COUNT POSTS INSIDE TERMS / CATEGORIES
function posts_in_term($catid, $taxonomy) {

	if(is_array($catid)) {
		$total = 0;
		foreach($catid as $id) {$qTerm = get_term($id, $taxonomy); $total = $total + $qTerm->count;}
		return $total;
	}
	else {
		$qTerm = get_term($catid, $taxonomy);
		return $qTerm->count;
	}
	
	// usage: 
	// echo posts_in_term(1, 'category').' Posts in General Category.';
	// echo posts_in_term(1, 'gallery_cat').' Custom Posts Gallery Taxonomy.';
}


/*** USER COMMENTS ***/
function countUserComments($userEmail) {
	$args = array(
		'author_email' => $userEmail
	);
	$comments = get_comments( $args );
	return count($comments);
}


/*** POST COMMENTS COUNT ***/
function countPostComments($postid) {
	$args = array(
		'status' 	=> 'approve',
		'post_id'	=> $postid,
	);
	$comments = get_comments( $args );
	return count($comments);
}



/*** COUNT USERS BY TYPE ***/
function countUsers($usersType){
	$args = array(
		'role' => $usersType,
	 ); 
	$usersCount = get_users( $args );
	return count($usersCount);
}


?>