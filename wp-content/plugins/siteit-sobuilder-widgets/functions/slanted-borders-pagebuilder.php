<?php
add_filter( 'siteorigin_panels_row_style_groups', 'slantedborder_row_style_group', 10, 3 );
function slantedborder_row_style_group( $groups, $post_id, $args ) {

	$groups['slantedborder'] = array(
		'name' => __('Slanted Border Options', 'siteorigin-panels'),
		'priority' => 25
	);

	return $groups; 
}




add_filter( 'siteorigin_panels_row_style_fields', 'slantedborder_row_style_fields' );
function slantedborder_row_style_fields( $fields ) {
	
	$group = 'slantedborder';
	$order = 10;

	// Intro
	$fields['slnborder_before'] = array(
		'name'        => __('Slanted Border Before Section', 'siteorigin-panels'),
		'type'        => 'checkbox',
		'group'       => $group,
		'priority'    => $order ++,
		'default'     => ''
	);
	$fields['slnborder_before_angle'] = array(
		'type'        => 'text',
		'group'       => $group,
		'description' => __('Border Slope Angle ', 'siteorigin-panels'),
		'priority'    => $order ++,
		'default'     => ''
	);
	$fields['slnborder_before_direction'] = array(
		'type'        => 'select',
		'group'       => $group,
		'description' => __('Border Slope Direction', 'siteorigin-panels'),
		'priority'    => $order ++,
		'options'     => array(
							'left' => 'Left to Right',
							'right' => 'Right To Left'
						 ),
		'default'     => 'right'
	);	

	$fields['slnborder_after'] = array(
		'name'        => __('Border After Section', 'siteorigin-panels'),
		'type'        => 'checkbox',
		'group'       => $group,
		'priority'    => $order ++,
		'default'     => ''
	);
	$fields['slnborder_after_angle'] = array(
		'type'        => 'text',
		'group'       => $group,
		'description' => __('Border Slope Direction (percentage)e', 'siteorigin-panels'),
		'priority'    => $order ++,
		'default'     => ''
	);
	$fields['slnborder_after_direction'] = array(
		'type'        => 'select',
		'group'       => $group,
		'description' => __('Border Slope Direction', 'siteorigin-panels'),
		'priority'    => $order ++,
		'options'     => array(
							'left' => 'Left to Right',
							'right' => 'Right To Left'
						 ),
		'default'     => 'right'
	);	

	return $fields;
}



function slantedborder_panels_css_object($css, $panels_data, $post_id){

    foreach ( $panels_data as $panel ) {

		if( !empty($panel['grid']) ) {
			$current_grid = $panel['grid'];

			if( $panels_data['grids'][$current_grid]['slnborder_before'] == '1' || $panels_data['grids'][$current_grid]['slnborder_after'] == '1' ) {
				// RETURN HERE #ROW_ID:before {some css here}
			}

			// $css->add_row_css($post_id, $gi, '', array(
			//     'margin-left' => '-10px',
			//     'margin-right' => '-10px'
			// ));
		}
	};
	
    return $css;
};
add_filter('siteorigin_panels_css_object', 'slantedborder_panels_css_object', 10, 3);
?>