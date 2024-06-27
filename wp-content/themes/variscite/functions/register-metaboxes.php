<?php
// KICKSTART METABXOES
if ( file_exists(THEME_PATH.'/metaboxes/init.php' ) ) {
	require_once THEME_PATH.'/metaboxes/init.php';
}



// CUSTOM FIELDS
include(THEME_PATH.'/metaboxes/cmb-field-select2/cmb-field-select2.php');



// HELPER FUNC
// cant include since using func in frontEnd
function create_numstring_array($startNum, $endNum, $jumps, $sideString = NULL) {

    if(is_int($startNum) && $endNum) {

        $data       = array();
        $counter    = $startNum;
        $cumulative = '';

        while($endNum > $counter ) {
            $data["$counter"] = $counter.' '.$sideString;
            $counter        = $counter + $jumps;

        }

        return $data;
    }
}




/********************************************
**	POSTS & PAGES METABOXES
********************************************/
add_action( 'cmb2_admin_init', 'sght_postpagemb_metaboxes' );
function sght_postpagemb_metaboxes() {

    $ppmb = new_cmb2_box( array(
        'id'            => 'postpage_metabox',
        'title'         => __('Advanced options', THEME_NAME),
        'object_types'  => array('page', 'post'), // Post type
        'context'       => 'side',
        'priority'      => 'high',
        'show_names'    => true, // Show field names on the left
        'classes_cb'    => 'wrap_cmb2_with_bootstraprow',
        // 'cmb_styles' => false, // false to disable the CMB stylesheet
        // 'closed'     => true, // Keep the metabox closed by default
    ) );

}



/*************************************************
** NEW SPEC FILTER SETTINGS
*************************************************/
function buildUniqueFldId($tabName, $subTitle, $optionName){

    return strtolower( str2id( str_replace('/', '_', $tabName) ).($subTitle ? '_'.str2id($subTitle) : '').'_'.str2id($optionName) );
}

add_action( 'cmb2_admin_init', 'spec_filter_mbsettings' );
function spec_filter_mbsettings() {


    $somFltrStng = new_cmb2_box( array(
        'id'            => 'spec_filter_mb',
        'title'         => __('New Product Filter Settings', THEME_NAME),
        'object_types'  => array('specs'), // Post type
        'context'       => 'side',
        'priority'      => 'high',
        'show_names'    => true, // Show field names on the left
        'classes_cb'    => 'wrap_cmb2_with_bootstraprow'
    ));

    
    // GET FILTER PAGE SETTINGS (FIELDS)
    $counter            = 0;
    if(get_field('optage_defpages_somfilter', 'option')){
         $somFilterId        = get_field('optage_defpages_somfilter', 'option');
    }
    if(get_field('optage_defpages_somfilter', 'option')){
         $somFilterFields    = get_field('som_filter_tab_group', $somFilterId);
    }
   
    
    
    // BUILD FIELDS ACCRODING TO SETTINGS
    foreach($somFilterFields as $tab) {

        $fieldsArr  = $tab['som_filter_field_box'];
        $tabName    = $tab['som_filter_field_title'];

        $somFltrStng->add_field( array(
            'name'       => $tabName,
            'id'         => str2id($tabName),
            'type'       => 'title',
            'show_on_cb' => 'cmb2_hide_if_no_cats',
        ));

        if( !empty($fieldsArr) ) {


            foreach($fieldsArr as $field) {

                if( !empty($field['som_filter_single_field_title']) ) {

                    $somFltrStng->add_field( array(
                        'name'       => $field['som_filter_single_field_title'],
                        'id'         => str2id($field['som_filter_single_field_title']),
                        'type'       => 'title',
                        'show_on_cb' => 'cmb2_hide_if_no_cats',
                        'classes'    => 'subtitle'
                    ));
                }

                // BASIC DATA
                $dataSource     = $field['som_filter_data_source'];
                $fieldType      = $field['som_filter_field_type'];
                $rangeSettings  = $field['range_settings_group'];


                if( $fieldType == 'checkbox' && $dataSource != 'category' || $fieldType == 'btngroup' && $dataSource != 'category' ) {

                    $chckboxOptions = array();

                    foreach($field['som_filter_source_checkbox'] as $field_checkbox) {
                        $chckboxOptions[] = $field_checkbox['som_filter_source_checkbox_val'];
                    }

                    foreach($chckboxOptions as $cbOption) {

                        // echo str2id($field['som_filter_single_field_title']).str2id($cbOption)."<br>";

                        $somFltrStng->add_field( array(
                            'desc'      => $cbOption,
                            'id'        => buildUniqueFldId($tabName, $field['som_filter_single_field_title'], $cbOption),
                            'type'      => 'checkbox',
                            'classes'   => str2id($tabName).'_group '.( !empty($field['som_filter_single_field_title']) ? 'sub-checkbox-list' : '').' '.buildUniqueFldId($tabName, $field['som_filter_single_field_title'], $cbOption)
                        ));

                        // $somFltrStng->add_field( array(
                        //     'desc'      => $cbOption,
                        //     'id'        => str2id($cbOption),
                        //     'type'      => 'checkbox',
                        //     'classes'   => str2id($tabName).'_group '.( !empty($field['som_filter_single_field_title']) ? 'sub-checkbox-list' : '').' '.buildUniqueFldId($tabName, $field['som_filter_single_field_title'], $cbOption)
                        // ));
                    }
                }

                elseif( $fieldType == 'range' ) {

                    $optId = '';

                    foreach($field['som_filter_source_checkbox'] as $field_checkbox) {
                        $optId .= $field_checkbox['som_filter_source_checkbox_val'];
                    }

                    $somFltrStng->add_field( array(
                        'name' => 'Range Settings (Numbers Only)',
                        'id'   => str2id($optId).'_title',
                        'type' => 'intitle',
                        'classes' => 'range-title',
                    ));
                    $somFltrStng->add_field( array(
                        'desc' => 'Starting From',
                        'id'   => str2id($optId).'_from',
                        'type' => 'text',
                        'classes' => 'range',
                    ));
                    $somFltrStng->add_field( array(
                        'desc' => 'Maxing At',
                        'id'   => str2id($optId).'_to',
                        'type' => 'text',
                        'classes' => 'range',
                    ));
                }
 
            }
        }

        $counter++;
    }



}



function cmb_only_show_for_som( $cmb ) {
	$pterms = wp_get_post_terms($cmb->object_id(), 'product_cat');
	// Only show if status is 'external'
	return 'external' === $status;
}







/*************************************************
** LEADS CMB2 FIELDS
************************************************
add_action( 'cmb2_admin_init', 'leads_mbsettings' );
function leads_mbsettings() {


    $leadmb = new_cmb2_box( array(
        'id'            => 'leads_mb',
        'title'         => __('Lead Data', THEME_NAME),
        'object_types'  => array('leads'), // Post type
        'context'       => 'normal',
        'priority'      => 'high',
        'show_names'    => true, // Show field names on the left
        'classes_cb'    => 'wrap_cmb2_with_bootstraprow'
    ));

    $leadmb->add_field( array(
        'name'       =>  __('First Name', THEME_NAME),
        'id'         => THEME_PREF.'lead_first_name',
        'type'       => 'text',
        'show_on_cb' => 'cmb2_hide_if_no_cats',
        'classes'    => 'col-md-4'
    ));

    $leadmb->add_field( array(
        'name'       =>  __('last Name', THEME_NAME),
        'id'         => THEME_PREF.'lead_last_name',
        'type'       => 'text',
        'show_on_cb' => 'cmb2_hide_if_no_cats',
        'classes'    => 'col-md-4'
    ));

    $leadmb->add_field( array(
        'name'       =>  __('Company', THEME_NAME),
        'id'         => THEME_PREF.'lead_company',
        'type'       => 'text',
        'show_on_cb' => 'cmb2_hide_if_no_cats',
        'classes'    => 'col-md-4'
    ));

    $leadmb->add_field( array(
        'name'       =>  __('Country', THEME_NAME),
        'id'         => THEME_PREF.'lead_country',
        'type'       => 'text',
        'show_on_cb' => 'cmb2_hide_if_no_cats',
        'classes'    => 'col-md-4'
    ));

    $leadmb->add_field( array(
        'name'       =>  __('Phone', THEME_NAME),
        'id'         => THEME_PREF.'lead_phone',
        'type'       => 'text',
        'show_on_cb' => 'cmb2_hide_if_no_cats',
        'classes'    => 'col-md-4'
    ));

    $leadmb->add_field( array(
        'name'       =>  __('Note', THEME_NAME),
        'id'         => THEME_PREF.'lead_note',
        'type'       => 'textarea',
        'show_on_cb' => 'cmb2_hide_if_no_cats',
        'classes'    => 'col-md-12'
    ));

    $leadmb->add_field( array(
        'name'       =>  __('Product Specification', THEME_NAME),
        'id'         => THEME_PREF.'lead_product_specs_title',
        'type'       => 'title',
        'show_on_cb' => 'cmb2_hide_if_no_cats',
        'classes'    => 'col-md-12'
    ));
    $leadmb->add_field( array(
        'name'       =>  __('System on Module', THEME_NAME),
        'id'         => THEME_PREF.'lead_sysom',
        'type'       => 'textarea',
        'show_on_cb' => 'cmb2_hide_if_no_cats',
        'classes'    => 'col-md-12'
    ));
    $leadmb->add_field( array(
        'name'       =>  __('Operating Systems', THEME_NAME),
        'id'         => THEME_PREF.'lead_opsys',
        'type'       => 'textarea',
        'show_on_cb' => 'cmb2_hide_if_no_cats',
        'classes'    => 'col-md-12'
    ));
    $leadmb->add_field( array(
        'name'       =>  __('Estimated Quantities', THEME_NAME),
        'id'         => THEME_PREF.'lead_quan',
        'type'       => 'text',
        'show_on_cb' => 'cmb2_hide_if_no_cats',
        'classes'    => 'col-md-12'
    ));

}
*/





/********************************************************
** CALLBACK TO WRAP ROW WITH BOOTSTRAP ROW STYLING
********************************************************/
function wrap_cmb2_with_bootstraprow() {
    $classes = array('admin-row');
    return $classes;
}
?>