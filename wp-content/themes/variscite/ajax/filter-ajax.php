<?php
/***************************************************************************************
**	HELPER FUNCTIONS
***************************************************************************************/


/*************************************************
** EXTRACT FILTERS INTO ARRAY BY TYPE
*************************************************/
function filterdata_to_filterparams_structure($filterdata) {

	if( !empty($filterdata) ) {

		foreach($filterdata as $subArr) {
			if( !empty($subArr[3]) ) {

				$groupName = str_replace('/', '_', $subArr[1]);

				$filter_params[$groupName][] = $subArr[3];
			}
		}

	}

	return $filter_params;
}



/***************************************************************************************
**	FILTER AJAX ACTIONS
***************************************************************************************/
add_action('wp_enqueue_scripts', 'register_filterajax_scripts');
function register_filterajax_scripts() {
    wp_enqueue_script('filter-ajaxfunc', get_stylesheet_directory_uri().'/ajax/filter-ajax.js', array('jquery'), 1.1);
    wp_localize_script('filter-ajaxfunc', 'ajax_object', array('ajaxurl' => admin_url('admin-ajax.php')));
}

add_action('wp_ajax_filter_ajaxfunc', 'filter_ajaxfunc');
add_action('wp_ajax_nopriv_filter_ajaxfunc', 'filter_ajaxfunc');
function filter_ajaxfunc() {

	$startTime = microtime(true);

	$actionType = sanitize_text_field($_POST['action_type']);
	do_action( 'wpml_switch_language',  wpml_get_current_language() ); // switch the content language

	// POSTS LOAD MORE (in blog)
	if($actionType == 'filter_products') {

		
		/*********************************************
		** INITIAL FILTER (GET IDS) QUERY'S
		*********************************************/
		$filter_pageid 	= intval($_POST['filter_pageid']);
		$filter_cats 	= sanitize_text_field($_POST['filter_cats']);
		$filter_params  = filterdata_to_filterparams_structure($_POST['filter_data']);


		// IF PICKED ASSIGN BASE QUERY PRODUCT CATEGORIES
		if( !empty($filter_params['cpu_architecture']) ) { $cat_filters = geterms_by_names($filter_params['cpu_architecture']); }
		else {$cat_filters = explode(',', $filter_cats);}
		if( !empty($filter_params['cpu_architecture']) ) {unset($filter_params['cpu_architecture']);}

		// DYNAMIC META QUERY
		$postids_arr = array();


		if( !empty($filter_params) ) {

			$filter_params = array_filter(array_map('array_filter', $filter_params));

			foreach ($filter_params as $group => $values) {

		
				$sub_meta_query = array();
				$fieldType      = get_fieldtype_by_group($filter_pageid, $group);
				$sub_meta_query = array('relation' => 'OR');


				// fix range values
				if($fieldType == 'range') {$values = explode('~', $values[0]);}


				if(is_array($values)) {

					if($fieldType == 'checkbox' || $fieldType == 'btngroup') {
						foreach($values as $value) {
							$sub_meta_query[] = array(
								'key'       => str2id($group.'_'.$value),
								'compare'   => 'EXISTS',
							);
						}
					}

					if($fieldType == 'range') {

						if($group == 'temperature_grades') {
							$sub_meta_query[] = array(
								'relation' => 'AND',
								array(
									'key'       => str_replace('-', '_', $group).'_from',
									'value'     => $values[0],
									'type'      => 'NUMERIC',
									'compare'   => '<=',
								),
								array(
									'key'       => str_replace('-', '_', $group).'_to',
									'value'     => $values[1],
									'type'      => 'NUMERIC',
									'compare'   => '>=',
								)
							);
						}
						else {


							$sub_meta_query[] = array(
								'relation' => 'OR',
								array(
									'key'     => str_replace('-', '_', $group).'_from',
									'value'   => array( $values[0], $values[1] ),
									'type'    => 'numeric',
									'compare' => 'BETWEEN',
								),
								array(
									'key'     => str_replace('-', '_', $group).'_to',
									'value'   => array( $values[0], $values[1] ),
									'type'    => 'numeric',
									'compare' => 'BETWEEN',
								),
							);
						}

					}
				}
				// RUN FIRST LOOP
				$tmpArgs = array(
					'posts_per_page'=> -1,
					'post_status' 	=> 'publish',
					'post_type'     => 'specs',
					'order'         => 'ASC',
					'orderby'       => 'menu_order',
					'tax_query'     => array(
						array(
							'taxonomy' => 'products',
							'field' => 'term_id',
							'terms' => $cat_filters
						)
					),
					'meta_query'    => $sub_meta_query,
				);
				$tmpQuery = new WP_Query( $tmpArgs );


				$postids_arr[ str2id($group) ] = wp_list_pluck( $tmpQuery->posts, 'ID' );
				if($tmpQuery->have_posts()) { while ( $tmpQuery->have_posts() ) { $tmpQuery->the_post(); } }
				wp_reset_query();
				wp_reset_postdata();
			}



			// EXTRACT DUPLICATE POST IDS (the ones that answer all params.)
			if(count($postids_arr) == 1) {$postids_arr = call_user_func_array('array_merge', $postids_arr);}
			elseif(count($postids_arr) > 1) {
				$allValues      = call_user_func_array('array_merge', $postids_arr);
				$postids_arr    = array_unique( array_diff_assoc( $allValues, array_unique( $allValues ) ) );
			}



			/*********************************************
			** QUERY BASED ON FOUND POSTID (FILTERED RESULTS)
			*********************************************/
			$filter_params_nocats = $filter_params;
			unset($filter_params_nocats['cpu_name']);

			if( !empty($postids_arr) ) {

				$data = '';
				$finalArgs = array(
					'posts_per_page'=> -1,
					'post_type'     => 'specs',
					'post__in'      => $postids_arr,
					'order'         => 'ASC',
					'orderby'       => 'menu_order',
					'tax_query'     => array(
						array(
							'taxonomy' => 'products',
							'field'    => 'term_id',
							'terms'    => $cat_filters,
						),
					)
				);
				$query = new WP_Query( $finalArgs );
				$found  = $query->found_posts;

				
				// The Loop
				while ( $query->have_posts() ) {
					$query->the_post();

					$pid    = get_the_ID();
					$data   .= filter_build_product($pid);

				}
				wp_reset_query();

				wp_send_json_success( array( 'success' => true, 'posts' => $data, 'count' => $found, 'time' => 'Query Took: '. (microtime(true) - $startTime) .' seconds' ) );
				wp_die();
			}
			else {
				wp_send_json_error( array( 'success' => false, 'posts' => '', 'count' => 0,  'time' => 'Query Took: '. (microtime(true) - $startTime) .' seconds') );
				wp_die();
			}

		}
		else {
			$data = '';
			$args = array(
				'posts_per_page'=> -1,
				'post_type'     => 'specs',
				'order'         => 'ASC',
				'orderby'       => 'menu_order',
				'tax_query'     => array(
					array(
						'taxonomy' => 'products',
						'field'    => 'term_id',
						'terms'    => $cat_filters,
					),
				)
			);
			$query = new WP_Query( $args );
			$found  = $query->found_posts;
			
			// The Loop
			while ( $query->have_posts() ) {
				$query->the_post();

				$pid    = get_the_ID();
				$data   .= filter_build_product($pid);

			}
			wp_reset_query();
			
			wp_send_json_success( array( 'success' => true, 'posts' => $data, 'count' => $found, 'time' => 'Elapsed time is: '. (microtime(true) - $startTime) .' seconds' ) );
			// wp_die();
		}


	}


	wp_die();

}

?>