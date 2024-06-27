<?php
add_action('init', 'specs_pages_labels');
function specs_pages_labels() {
	$labels = array(
		'name' 					=> __('Product Specs', THEME_NAME),
		'singular_name' 		=> __('Product Specs', THEME_NAME),
		'add_new' 				=> __('Add Specs Page', THEME_NAME),
		'add_new_item' 			=> __('Add Specs Page', THEME_NAME),
		'edit_item' 			=> __('Edit Page', THEME_NAME),
		'new_item'				=> __('New Specs Page', THEME_NAME),
		'all_items' 			=> __('Spec\'s Pages', THEME_NAME),
		'view_item' 			=> __('View Spec Page', THEME_NAME),
		'search_items' 			=> __('Find a Spec Page', THEME_NAME),
		'not_found' 			=>  __('No Specs Page Found', THEME_NAME),
		'not_found_in_trash' 	=> __('No Specs Page Found In The Trash', THEME_NAME),
		'parent_item_colon' 	=> '',
		'menu_name' 			=> __('Spec\'s Pages', THEME_NAME),
	
	);
	$args = array(
		'labels' 				=> $labels,
		'public' 				=> true,
		'publicly_queryable'	=> true,
		'show_ui' 				=> true,
		'show_in_rest'			=> true,
		'query_var' 			=> true,
		// 'rewrite' 				=> true,
		'rewrite'               => array( 'slug' => 'product/%product_cat%', 'with_front' => false ),
//		'rewrite'               => array( 'slug' => 'product', 'with_front' => false ),
		'capability_type'		=> 'post',
		'has_archive' 			=> false,
		'hierarchical'			=> true,
		'menu_position' 		=> 5,
		'menu_icon' 			=> get_bloginfo('template_url').'/images/cpt/chip-icon.png',
		'supports' 				=> array('title', 'thumbnail', 'custom-fields', 'page-attributes', 'revisions'),
	);
	register_post_type('specs', $args);
}


// FAQ CATEGORIES
add_action('init', 'create_specs_cat', 0);
function create_specs_cat() {
	$labels = array(
		'name' 					=> __('Product Category', THEME_NAME),
		'singular_name' 		=> __('Category', THEME_NAME),
		'search_items' 			=> __('Search Categories', THEME_NAME),
		'all_items' 			=> __('All Categories', THEME_NAME),
		'parent_item' 			=> __('Parent Category', THEME_NAME),
		'parent_item_colon' 	=> __('Parent Category:', THEME_NAME),
		'edit_item' 			=> __('Edit Category', THEME_NAME),
		'update_item' 			=> __('Update Category', THEME_NAME),
		'add_new_item' 			=> __('Add Category', THEME_NAME),
		'new_item_name' 		=> __('New Category', THEME_NAME),
		'menu_name' 			=> __('Category', THEME_NAME),
	);
	
	register_taxonomy('products',array('specs'), array(
		'hierarchical' 			=> true,
		'labels' 				=> $labels,
		'show_ui' 				=> true,
		'query_var' 			=> true,
		'rewrite'       		=> array( 'slug' => 'products','with_front' => false ),
	));
}



/********************************************************
 **  HANDLE PERMALINKS ISSUSE
 *******************************************************
add_filter('post_type_link', 'filter_post_type_link', 10, 2);
function filter_post_type_link( $post_link, $id = 0, $leavename = FALSE ) {

if ( strpos('%product_cat%', $post_link) === 'FALSE' ) {
return $post_link;
}

$post = get_post($id);
if ( !is_object($post) || $post->post_type != 'specs' ) {
return $post_link;
}

$terms = wp_get_object_terms($post->ID, 'products');
if ( !$terms ) {
return str_replace('products/%product_cat%', '', $post_link);
}
return str_replace('%product_cat%', $terms[0]->slug, $post_link);
}


// Adding a new rule
add_filter( 'rewrite_rules_array','my_insert_rewrite_rules' );
function my_insert_rewrite_rules( $rules ) {
$newRules['products/(.+)/(.+)/?$'] 				= 'index.php?specs=$matches[2]'; // my custom structure will always have the post name as the 5th uri segment
$newRules['products/(.+)/(.+)/(.+)/?$'] 		= 'index.php?specs=$matches[3]'; // my custom structure will always have the post name as the 5th uri segment
$newRules['products/(.+)/(.+)/(.+)/(.+)/?$'] 	= 'index.php?specs=$matches[4]'; // my custom structure will always have the post name as the 5th uri segment
$newRules['products/(.+)/?$']                	= 'index.php?products=$matches[1]';

return array_merge($newRules, $rules);
}
 */


add_filter('rewrite_rules_array', 'mmp_rewrite_rules');
function mmp_rewrite_rules($rules) {
	$newRules  = array();
	// $newRules['products/(.+)/?$'] 				= 'index.php?specs=$matches[1]'; // my custom structure will always have the post name as the 5th uri segment
	$newRules['product/(.+)/(.+)/?$'] 				= 'index.php?specs=$matches[2]'; // my custom structure will always have the post name as the 5th uri segment
	$newRules['product/(.+)/(.+)/(.+)/?$'] 		= 'index.php?specs=$matches[3]'; // my custom structure will always have the post name as the 5th uri segment
	$newRules['product/(.+)/(.+)/(.+)/(.+)/?$'] 	= 'index.php?specs=$matches[4]'; // my custom structure will always have the post name as the 5th uri segment
	$newRules['product/(.+)/?$']                	= 'index.php?product=$matches[1]';

	return array_merge($newRules, $rules);
}

if ( ! function_exists( 'get_primary_taxonomy_id' ) ) {
	function get_primary_taxonomy_id( $post_id, $taxonomy ) {
		$prm_term = '';
		if (class_exists('WPSEO_Primary_Term')) {
			$wpseo_primary_term = new WPSEO_Primary_Term( $taxonomy, $post_id );
			$prm_term = $wpseo_primary_term->get_primary_term();
		}
		if ( !is_object($wpseo_primary_term) || empty( $prm_term ) ) {
			$term = wp_get_post_terms( $post_id, $taxonomy );
			if (isset( $term ) && !empty( $term ) ) {
				return $term[0]->term_id;
			} else {
				return '';
			}
		}
		return $wpseo_primary_term->get_primary_term();
	}
}

function filter_post_type_link($link, $post) {
	if ($post->post_type != 'specs')
		return $link;
	
	if ($cats = get_the_terms($post->ID, 'products')) {
		if(get_primary_taxonomy_id($post->ID, 'products') && get_primary_taxonomy_id($post->ID, 'products') != ''){
			$link = str_replace( '%product_cat%', get_taxonomy_parents( get_primary_taxonomy_id($post->ID, 'products'), 'products', false, '/', true ), $link );
		} else {
			$link = str_replace( '%product_cat%', get_taxonomy_parents( array_pop( $cats )->term_id, 'products', false, '/', true ), $link ); // see custom function defined below
		}
		$link	= parse_url($link);
		$path 	= preg_replace('#/{2}#','/',$link['path']);
		$link 	= $link['scheme'].'://'.$link['host'].$path. (isset($link['query']) ? '?' . $link['query'] : '' );
		// $link 	= $link['scheme'].'://www.'.$link['host'].$path;
	}
	return $link;
}
add_filter('post_type_link', 'filter_post_type_link', 10, 2);


// my own function to do what get_category_parents does for other taxonomies
function get_taxonomy_parents($id, $taxonomy, $link = false, $separator = '/', $nicename = false, $visited = array()) {
	$chain = '';
	$parent = get_term($id, $taxonomy);
	// $parent = &get_term($id, $taxonomy);

	if (is_wp_error($parent)) {
		return $parent;
	}

	if ($nicename) { $name = $parent -> slug; }
	else { $name = $parent -> name; }

	if ($parent -> parent && ($parent -> parent != $parent -> term_id) && !in_array($parent -> parent, $visited)) {
		$visited[] = $parent -> parent;
		$chain .= get_taxonomy_parents($parent -> parent, $taxonomy, $link, $separator, $nicename, $visited);
	}

	if ($link) {
		// nothing, can't get this working :(
	} else {
		$chain .= $name . $separator;
	}

	return $chain;
}

add_action('get_header', 'testing');
function testing() {
	if (get_post_type() == 'specs' && strpos($_SERVER['QUERY_STRING'], 'specs=') === 0) {
		wp_redirect(get_permalink());
	}
}




/******************************************************
 **	LEAD COLLECTOR
 ** 	this is a cpt created to document new leads
 ******************************************************/
add_action('init', 'leads_labels');
function leads_labels() {
	$labels = array(
		'name' 					=> __('Leads', THEME_NAME),
		'singular_name' 		=> __('Lead', THEME_NAME),
		'add_new' 				=> __('Add Lead', THEME_NAME),
		'add_new_item' 			=> __('Add Lead', THEME_NAME),
		'edit_item' 			=> __('Edit Lead', THEME_NAME),
		'new_item'				=> __('New Lead', THEME_NAME),
		'all_items' 			=> __('Leads', THEME_NAME),
		'view_item' 			=> __('View Lead', THEME_NAME),
		'search_items' 			=> __('Find a Lead', THEME_NAME),
		'not_found' 			=> __('No Leads Found', THEME_NAME),
		'not_found_in_trash' 	=> __('No Leads Found In The Trash', THEME_NAME),
		'parent_item_colon' 	=> '',
		'menu_name' 			=> __('Leads', THEME_NAME),
	
	);
	$args = array(
		'labels' 				=> $labels,
		'public' 				=> false,
		'publicly_queryable'	=> false,
		'show_ui' 				=> true,
		'show_in_rest'			=> true,
		'query_var' 			=> true,
		'rewrite' 				=> true,
		'capability_type'		=> 'post',
		'has_archive' 			=> false,
		'hierarchical'			=> true,
		'menu_position' 		=> 65,
		'menu_icon' 			=> get_bloginfo('template_url').'/images/cpt/leads.png',
		'supports' 				=> array('title', 'custom-fields'),
	);
	register_post_type('leads', $args);
}




/******************************************************
 **	PDF LIBRARY
 ** 	to be used inside specification page (custom postype)
 ******************************************************/
add_action('init', 'pdf_library_labels');
function pdf_library_labels() {
	$labels = array(
		'name' 					=> __('PDF Library', THEME_NAME),
		'singular_name' 		=> __('PDF File', THEME_NAME),
		'add_new' 				=> __('Add PDF File', THEME_NAME),
		'add_new_item' 			=> __('Add PDF File', THEME_NAME),
		'edit_item' 			=> __('Edit PDF File', THEME_NAME),
		'new_item'				=> __('New PDF File', THEME_NAME),
		'all_items' 			=> __('PDF Files', THEME_NAME),
		'view_item' 			=> __('View PDF File', THEME_NAME),
		'search_items' 			=> __('Find a PDF File', THEME_NAME),
		'not_found' 			=>  __('No PDF Files Found', THEME_NAME),
		'not_found_in_trash' 	=> __('No PDF Files Found In The Trash', THEME_NAME),
		'parent_item_colon' 	=> '',
		'menu_name' 			=> __('PDF Files', THEME_NAME),
	
	);
	$args = array(
		'labels' 				=> $labels,
		'public' 				=> false,
		'publicly_queryable'	=> false,
		'show_ui' 				=> true,
		'show_in_rest'			=> true,
		'show_in_menu' 			=> true,
		'query_var' 			=> true,
		'rewrite' 				=> true,
		'capability_type' 		=> 'post',
		'has_archive' 			=> true,
		'hierarchical' 			=> false,
		'menu_position' 		=> 65,
		'menu_icon' 			=> get_bloginfo('template_url').'/images/cpt/pdf.png',
		'supports' 				=> array('title', 'custom-fields', 'revisions')
	);
	register_post_type('pdfiles', $args);
}

?>