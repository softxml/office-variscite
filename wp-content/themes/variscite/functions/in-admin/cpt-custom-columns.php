<?php
/********************************
**	POSTS COLUMNS
********************************/
add_filter('manage_posts_columns', 'posts_columns', 5);
add_action('manage_posts_custom_column', 'posts_custom_columns', 5, 2);

function posts_columns($defaults){
    $defaults['riv_post_views'] = __('Views');
    $defaults['riv_post_idnum'] = __('Post ID');
    return $defaults;
}

function posts_custom_columns($column_name, $id){
	if($column_name === 'riv_post_views') {
		echo get_post_meta(get_the_ID(), THEME_NAME.'_post_views', true);
	}
	
	if($column_name === 'riv_post_idnum') {
		echo __('Id: ', THEME_NAME).get_the_ID();
	}
}




/********************************
**	SPECS COLUMNS
********************************/
add_filter( 'manage_edit-specs_columns', 'sgx_edit_specs_columns' ) ;
function sgx_edit_specs_columns( $columns ) {

	$columns = array(
		'cb' 		=> '<input type="checkbox" />',
		'pthumb'	=> __('Thumb', THEME_NAME),
		'title'		=> __('Product Name'),
		'cat'		=> __('Category'),
		'pgallery'	=> __('Slider Gallery', THEME_NAME),
		'status'	=> __('Status', THEME_NAME),
		'date'		=> __('Date')
	);

	return $columns;
}

add_action( 'manage_specs_posts_custom_column', 'sgx_manage_specs_columns', 10, 2 );
function sgx_manage_specs_columns( $column, $post_id ) {


	global $post;

	// post data
	$cats   		= ''; 
	$pterms 		= wp_get_post_terms( $post_id, 'products');
	$firstCatSlug 	= !empty($pterms[0]->slug) ? $pterms[0]->slug : '';

	switch( $column ) {


		case 'pthumb' :

			$pthumb = wp_get_attachment_url( get_post_thumbnail_id($post_id) );
			if(!$pthumb) {$pthumb = 'http://placehold.it/75x75/&text=No IMG';}
			echo '<img src="'.$pthumb.'" alt="" width="75" style="border: 1px solid #ddd;" />';
			
		break;
		case 'cat' :

			foreach($pterms as $pt) {
				// if child term than get parent
				if( !empty($pt->parent) && $pt->parent != 0 ) {
					$parent 	= get_term_by( 'term_id', $pt->parent, 'products' );
					$parentname = '<br /><sup>parent: '.$parent->name.'</sup>';
				} else {$parentname = '';}

				// ouput result
				$cats .= '<strong>'.$pt->name.'</strong>'.$parentname;
			}

			echo '<ul class="list-inline">'.$cats.'</ul>';

		break;
		case 'pgallery' :

			$imgList 	= '';
			$media 		= get_field('vrs_specs_slider_media', $post_id);

			if( !empty($media) ) {
				foreach($media as $item) {

					if($item['sliderimg']){  
						$imgList .= '<li><img src="'.$item['sliderimg'].'" alt="" style="width: 40px; border: 1px solid #ddd;"> '.( !empty($item['webp_sliderimg']) ? '<span class="webpImg">WEBP</span>' : '').'</li>';  
					}
					elseif($item['sliderimgvideo']){
						$imgList .= '<li><img src="'.IMG_URL.'/media-video.jpg" alt="vIDEO"></li>';
					}
				}
			}
			else {
				$imgList .= '<li class="cred bold"><i class="fa fa-times"></i> '.__('ALERT: No Slider Images!').'</li>';
			}

			
			echo '<ul class="list-inline">'.$imgList.'</ul>';

		break;
		case 'status' :

			$missingItems = '';

			$flds['Short Desc']		= get_field('vrs_specs_short_desc', $post_id);
			$flds['Long Desc'] 		= get_field('vrs_specs_product_middesc', $post_id);
			$flds['No Brief']		= get_field('vrs_specs_product_brief', $post_id);
			$flds['No Datasheet']	= get_field('vrs_specs_product_datasheet', $post_id);
			$flds['No Wiki Page']	= get_field('vrs_specs_wiki_page', $post_id);
			$flds['Evaluation Kit']	= get_field('vrs_specs_relevant_mid_product', $post_id);


			// count accessories
			$xcc = get_field('vrs_specs_accesories_products', $post_id);
            if( is_countable($xcc) && count($xcc) < 5 ) {
                $tkey = 'Only '.count($xcc).' Accessories';
                $flds[$tkey] = '';
            }


			// count related
			$rltd = get_field( 'vrs_specs_related_products', $post_id );
			if( is_countable( $rltd ) && count( $rltd ) < 4 ){
				$tkey = 'Only '.count( $rltd ).' Related';
				$flds[$tkey] = '';
			}


			foreach($flds as $key => $fld) {
				if( empty($fld) ) {
					$missingItems .= '<li class="cred"><i class="fa fa-times"></i> '.$key.'</li>';
				}
			}

			echo '<ul class="list-inline">'.$missingItems.'</ul>';

		break;


		default :
		break;
	}
}


add_filter( 'manage_edit-specs_sortable_columns', 'sortable_specs_column' );
function sortable_specs_column( $columns ) {
    $columns['cat'] = __('Category', THEME_NAME);
 
    //To make a column 'un-sortable' remove it from the array
    //unset($columns['date']);
 
    return $columns;
}







/********************************
**	LEADS COLUMNS
********************************/
add_filter( 'manage_edit-leads_columns', 'sgx_edit_leads_columns' ) ;
function sgx_edit_leads_columns( $columns ) {

	$columns = array(
		'cb' 		=> '<input type="checkbox" />',
		'title'		=> __('Title', THEME_NAME),
		'name'		=> __('Name', THEME_NAME),
		'company'	=> __('Company', THEME_NAME),
		'country'	=> __('Country', THEME_NAME),
		'phone'		=> __('Phone', THEME_NAME),
		'status'	=> __('Status', THEME_NAME),
		'date'		=> __('Date')
	);

	return $columns;
}

add_action( 'manage_leads_posts_custom_column', 'sgx_manage_leads_columns', 10, 2 );
function sgx_manage_leads_columns( $column, $post_id ) {


	global $post;
	$pid = $post_id;


	switch( $column ) {


		case 'name' :
			echo get_field('first_name', $pid).' '.get_field('last_name', $pid);
		break;
		case 'company' :
			echo get_field('company', $pid);
		break;
		case 'country' :
			echo get_field('country', $pid);
		break;
		case 'phone' :
			echo get_field('phone', $pid);
		break;
		case 'status' :
			$records['Created']			= get_field('lead_record_created', $pid);
			$records['Email']			= get_field('lead_record_email', $pid);
			$records['SalesForce']		= get_field('lead_record_sf', $pid);

			foreach($records as $key => $r) {
				if($r == 'on' || (is_array($r) && $r[0] == 'on')) {
					echo '<span style="margin: 0 10px;"><i class="fa fa-check"></i>'.$key.'</span>';
				}
				else {
					echo '<span class="cred" style="margin: 0 10px;"><i class="fa fa-times"></i>'.$key.'</span>';
				}
			}

		break;


		default :
		break;
	}
}



?>