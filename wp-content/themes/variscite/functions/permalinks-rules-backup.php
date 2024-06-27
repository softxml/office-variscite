<?php
/*
add_filter('rewrite_rules_array', 'mmp_rewrite_rules');
function mmp_rewrite_rules($rules) {
    $newRules  = array();
    // $newRules['products/(.+)/?$'] 				= 'index.php?specs=$matches[1]'; // my custom structure will always have the post name as the 5th uri segment
    $newRules['products/(.+)/(.+)/?$'] 				= 'index.php?specs=$matches[2]'; // my custom structure will always have the post name as the 5th uri segment
    $newRules['products/(.+)/(.+)/(.+)/?$'] 		= 'index.php?specs=$matches[3]'; // my custom structure will always have the post name as the 5th uri segment
    $newRules['products/(.+)/(.+)/(.+)/(.+)/?$'] 	= 'index.php?specs=$matches[4]'; // my custom structure will always have the post name as the 5th uri segment
    $newRules['products/(.+)/?$']                	= 'index.php?products=$matches[1]'; 

    return array_merge($newRules, $rules);
}

function filter_post_type_link($link, $post) {
    if ($post->post_type != 'specs')
        return $link;

    if ($cats = get_the_terms($post->ID, 'products')) {
        $link	= str_replace('%product_cat%', get_taxonomy_parents(array_pop($cats)->term_id, 'products', false, '/', true), $link); // see custom function defined below
        $link	= parse_url($link);
        $path 	= preg_replace('#/{2}#','/',$link['path']);
        $link 	= $link['scheme'].'://'.$link['host'].$path;
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

*/
?>