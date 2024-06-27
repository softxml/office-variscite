<?php

/*************************************************
 ** EXTRACT FILTERS INTO ARRAY BY TYPE
 *************************************************/
function filterajax_filters_to_arrays($filter_params) {

    // $filter_params  = array_map(function($f) { return explode('=', $f); }, array_filter(explode('&', $filter_params)) );
    $temp_arr       = array();
    $filters_arr    = array();
    parse_str($filter_params, $temp_arr);

    foreach($temp_arr as $k => $p) {
        $filters_arr[$k] = explode(',', $p);
    }

    $filters_arr = array_filter(array_map('array_filter', $filters_arr));

    return $filters_arr;
}








/*********************************************
 ** BUILD PRODUCT LOOP
 *********************************************/
function filter_build_product($pid, $length = 35, $hideCompare = false){

    $clntitle       = get_the_title($pid);
//	$title          = content_to_excerpt( get_the_title($pid), $length );
    $title          = explode(':', $clntitle);
    $plink          = get_permalink($pid);
    $thumb          = str_replace('http://', 'https://', smart_thumbnail($pid, 225, 125, '', $clntitle, get_field('optage_defaults_blog_image', 'option')) );



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
                    <div class="col-md-4 col-xs-5 max25"><strong>'.$item['fld_name'].'</strong></div>
                    <div class="col-md-8 col-xs-7 max25">'.$item['fld_value'].'</div>
                </div>
            </div>
            ';

            $i++;

            if($exCls == 'even') {$exCls = 'odd';} else {$exCls = 'even';}
        }

        if( is_user_logged_in() ) {
            $xcerptSpcs .= '
            <div class="col-md-12 col-xs-12 excerpt-product-spec '.$exCls.' '.($i > 2 ? 'grid-hide' : '').' bgf9">
                <div class="row spec-row">
                    <div class="col-md-4 col-xs-5 max25"><strong>cpu_clock_from</strong></div>
                    <div class="col-md-8 col-xs-7 max25">'.get_post_meta($pid, 'cpu_clock_from', true).'</div>
                </div>
            </div>
            <div class="col-md-12 col-xs-12 excerpt-product-spec '.$exCls.' '.($i > 2 ? 'grid-hide' : '').'  bgf9">
                <div class="row spec-row">
                    <div class="col-md-4 col-xs-5 max25"><strong>cpu_clock_to</strong></div>
                    <div class="col-md-8 col-xs-7 max25">'.get_post_meta($pid, 'cpu_clock_to', true).'</div>
                </div>
            </div>
            <div class="col-md-12 col-xs-12 excerpt-product-spec '.$exCls.' '.($i > 2 ? 'grid-hide' : '').'  bgf9">
                <div class="row spec-row">
                    <div class="col-md-4 col-xs-5 max25"><strong>MENU ORDER</strong></div>
                    <div class="col-md-8 col-xs-7 max25">'.get_post_field('menu_order', $pid).'</div>
                </div>
            </div>
            <div class="col-md-12 col-xs-12 excerpt-product-spec '.$exCls.' '.($i > 2 ? 'grid-hide' : '').'  bgf9">
                <div class="row spec-row">
                    <div class="col-md-4 col-xs-5 max25"><strong>PAGE DATE</strong></div>
                    <div class="col-md-8 col-xs-7 max25">'.get_the_date('d.m.Y', $pid).'</div>
                </div>
            </div>
            ';
        }

    }

    // FIX TITLE
    if(count($title) == 1) { $title = $title[0]; }
    elseif( count($title) >= 2) { $title = '<span class="normal">'.$title[0].':</span> '.$title[1]; }

    return '
	<div class="filter-pitem col-md-12" data-prodid="'.$pid.'">
		<div class="row">
			<div class="col-md-3 col-xs-12 thumb-box">
				<a href="'.$plink.'">'.$thumb.'</a>
			</div>
			<div class="col-xs-12 title-box">
				<h3 class="item-title"><a href="'.$plink.'">'.$title.'</a></h3>

				<div class="specs-excerpt">
					<div class="row">'.$xcerptSpcs.'</div>
				</div>
			</div>
			<div class="col-xs-12 actions-box">
				<ul class="lsnone p0">
					<li><a href="'.$plink.'" class="btn btn-default visit-product"><span class="txtlbl">'.__('More Info', THEME_NAME).'</span></a></li>
					'.($hideCompare == true ? '' : '<li><input type="checkbox" id="compare-'.$pid.'" class="addToCompare" value="'.$pid.'"> <label for="compare-'.$pid.'"> <span></span> '.__('Compare', THEME_NAME).'</label></li>').'
				</ul>
			</div>
		</div>
	</div>
	';
}






/*************************************************
 ** FILTER FIELD BUILDER FUNCTION
 *************************************************/
function filter_tab_builder($fieldsTabs) {

    $tabs       = '';
    $counter    = 0;

    foreach($fieldsTabs as $tab) {

        $collapseId = groupname_str2id( $tab['som_filter_field_title'] );
        $fieldsArr  = $tab['som_filter_field_box'];
        $state      = $tab['som_filter_field_state'];

        $tabs .= '
        <div class="collapse-wrap '.$tab['som_filter_typesettings'].' box-'.$collapseId.'">
            <div class="collapse-head">
                <a class="btn btn-link fs16 bold" role="button" data-toggle="collapse" href="#'.$collapseId.'" aria-expanded="'.($state == 'open' ? 'true' : 'false').'" aria-controls="'.$collapseId.'">'.$tab['som_filter_field_title'].'</a>
            </div>
            <div class="collapse '.($state == 'open' ? 'in' : '').' parent-group" id="'.$collapseId.'" data-type="'.$fieldsArr[0]['som_filter_field_type'].'">
                '.filter_field_builder( $fieldsArr, $collapseId ).'
            </div>
        </div>
        ';
        if ($tab['som_filter_field_title'] == 'CPU Name'){
            $tabs .='<span class="moreless-button" >'.__( 'View more', 'variscite' ).'</span>';
        }
        $counter++;
    }



    return $tabs;
}


/*************************************************
 ** FILTER FIELD BUILDER FUNCTION - FOR ONE FILTER ONLY
 *************************************************/
function filter_tab_builder_one_field ($fieldsTabs, $fieldName, $specName = null) {
    $tabs       = '';
    $counter    = 0;
    foreach($fieldsTabs as $tab) {
        if ($tab['som_filter_field_title'] == $fieldName) {

            $collapseId = groupname_str2id($tab['som_filter_field_title']);
            $fieldsArr = $tab['som_filter_field_box'];
            $state = $tab['som_filter_field_state'];

            $tabs .= '
        <div class="collapse-wrap ' . $tab['som_filter_typesettings'] . ' box-' . $collapseId . '">
            <div class="collapse-head">
                <a class="btn btn-link fs16 bold" role="button" data-toggle="collapse" href="#' . $collapseId . '" aria-expanded="' . ($state == 'open' ? 'true' : 'false') . '" aria-controls="' . $collapseId . '">' . $tab['som_filter_field_title'] . '</a>
            </div>
            <div class="collapse ' . ($state == 'open' ? 'in' : '') . ' parent-group" id="' . $collapseId . '" data-type="' . $fieldsArr[0]['som_filter_field_type'] . '">
                ' . filter_field_builder($fieldsArr, $collapseId, $specName) . '
            </div>
        </div>
        <span class="moreless-button" >'.__( 'View more', 'variscite' ).'</span>';

            $counter++;
        }
    }
    return $tabs;
}





/*************************************************
 ** FILTER FIELD BUILDER FUNCTION
 *************************************************/
function filter_field_builder($fieldsArr, $tabName) {

    $counter            = 0;
    $result             = '';
    $sub_fields         = array();
    $subfields_result   = '';

    if( !empty($fieldsArr) ) {
        foreach($fieldsArr as $field) {


            $dataSource     = !empty($field['som_filter_data_source']) ? $field['som_filter_data_source'] : '';
            $fieldType      = !empty($field['som_filter_field_type']) ? $field['som_filter_field_type'] : '';
            $fieldId        = $tabName.'-'.$fieldType.'-'.$counter;
            $rangeSettings  = !empty($field['range_settings_group']) ? $field['range_settings_group'] : '';
            $imageSelect    = !empty($field['som_filter_image_select']) ? $field['som_filter_image_select'] : '';
            $subFieldTitle  = !empty($field['som_filter_single_field_title']) ? $field['som_filter_single_field_title'] : '';

            // if were using 2nd field group inside same tab
            if( !empty($subFieldTitle ) ) {
                $specTabName                    = $field['som_filter_source_spec'];
                $optArr                         = array();

                foreach($field['som_filter_source_checkbox'] as $field_checkbox) {
                    $optArr[] = $field_checkbox['som_filter_source_checkbox_val'];
                }

                $sub_fields[$subFieldTitle][]   = siteit_build_filter_checkbox( $optArr, $fieldId, $dataSource, $specTabName, $imageSelect, $subFieldTitle, $tabName );
            }

            // SOURCE CATEGORY
            elseif($dataSource == 'category') {

                $optArr = $field['som_filter_source_category'];

                if( $fieldType == 'checkbox' ) { $result = siteit_build_filter_checkbox( $optArr, $fieldId, $dataSource, '', $imageSelect, '', $tabName ); }      // CHECKBOX
                if( $fieldType == 'btngroup' ) { $result = siteit_build_filter_btngroup( $optArr, $fieldId, $dataSource, '', $imageSelect ); }      // BTN GROUP
                if( $fieldType == 'dropdown' ) { $result = siteit_build_filter_select( $optArr, $fieldId, $dataSource, '' ); }                      // SELECT FIELD
            }
            elseif($dataSource == 'specification') {

                $specTabName = $field['som_filter_source_spec'];
                $optArr = array();

                foreach($field['som_filter_source_checkbox'] as $field_checkbox) {
                    $optArr[] = $field_checkbox['som_filter_source_checkbox_val'];
                }

                if( $fieldType == 'checkbox' ) { $result = siteit_build_filter_checkbox( $optArr, $fieldId, $dataSource, $specTabName, $imageSelect, '', $tabName ); }    // CHECKBOX
                if( $fieldType == 'btngroup' ) { $result = siteit_build_filter_btngroup( $optArr, $fieldId, $dataSource, $specTabName, $imageSelect ); }    // BTN GROUP
                if( $fieldType == 'dropdown' ) { $result = siteit_build_filter_select( $optArr, $fieldId, $dataSource, $specTabName ); }                    // SELECT FIELD

                // RANGE FIELD
                if( $fieldType == 'range') {
                    $result = siteit_build_filter_range( $optArr, $rangeSettings, $dataSource, $specTabName );
                }
            }



            $counter++;
        }
    }


    // FIX SUB GROUPS ON TOP OF FILTER RESULTS
    foreach($sub_fields as $key => $subfld) {
        $subfields_result .= '
        <div class="subFldBox">
            <a class="btn btn-link" role="button" data-toggle="collapse" href="#'.str2id($key).'" aria-expanded="false" aria-controls="collapse'.str2id($key).'"><span>'.$key.'</span></a>
            <div class="collapse child-group" id="'.str2id($key).'">'.$subfld[0].'</div>
        </div>
        ';
    }

    return $subfields_result.$result;
}



/*********************************************
 ** BUILD FILTER SELECT
 *********************************************/
function siteit_build_filter_select( $optArr, $selectId, $dataSource, $specTabName ) {

    $result     = '';
    $options    = '';

    foreach($optArr as $option) {
        if( !empty($dataSource) && $dataSource == 'category' ) {
            $ccatData   = get_term( $option, 'products' );
            $options    .=  '<option value="'.$ccatData->term_id.'">'.$ccatData->name.'</option>';
        }
        else {
            $options    .=  '<option value="'.$option.'">'.$option.'</option>';
        }
    }


    return '<select name="'.$selectId.'" id="'.$selectId.'" class="form-control s2" data-type="'.$dataSource.'" data-spec="'.$specTabName.'"></select>';

}




/*********************************************
 ** BUILD FILTER CHECKBOX
 *********************************************/
function siteit_build_filter_checkbox( $optArr, $selectId, $dataSource, $specTabName, $imageSelect = null, $subFieldTitle = null, $tabName = null ) {

    $result     = '';
    $options    = '';

    foreach($optArr as $option) {
        if( !empty($dataSource) && $dataSource == 'category' ) {
            $ccatData   = get_term( $option, 'products' );
            $options     .=  '<div class="checkbox-wrap">';
            if($tabName == 'cpu_architecture' || $tabName == 'cpu_name'){
                $options .= '<a href="'.strtok($_SERVER["REQUEST_URI"], '?'). '?' . $tabName . '=' . $ccatData->name.'">';
            }
            $options .= '<input type="checkbox" name="'.$selectId.'" id="cat'.$ccatData->slug.'" value="'.$ccatData->name.'" > <label for="cat'.$ccatData->slug.'"><span></span> '.$ccatData->name.'</label>';
            if($tabName == 'cpu_architecture' || $tabName == 'cpu_name'){
                $options .= '</a>';
            }
            $options .= '</div>';
        }
        else {
            $catacc = get_queried_object();
            $options     .=  '<div class="checkbox-wrap">';
            if($tabName == 'cpu_architecture' || $tabName == 'cpu_name'){
                $ccatData   = get_term( $option, 'products' );
                if ( $catacc->slug != 'accessories' ) {
                    $options .= '<a href="'.strtok($_SERVER["REQUEST_URI"], '?'). '?' . $tabName . '=' .$option.'">';
                }
            }
            if( $subFieldTitle ) {
                $options .= '<input type="checkbox" name="'.$selectId.'" id="'.str2id($subFieldTitle.'_'.$option).'" value="'.$subFieldTitle.' '.$option.'"> <label for="'.str2id($subFieldTitle.'_'.$option).'"><span></span> '.$option.'</label>'; }
            else {
                $id = str_replace(array(' ' , '/'), '-', strtolower($option) );
                $options .= '<input type="checkbox" name="'.$selectId.'" id="'. $id.'" value="'.$option.'"> <label for="'. $id .'"><span></span> '.$option.'</label>';
            }
            if($tabName == 'cpu_architecture' || $tabName == 'cpu_name'){
                if ( $catacc->slug != 'accessories' ) {
                    $options .= '</a>';
                }
            }
            $options .= '</div>';
        }
    }


    if($dataSource == 'category') {$dataSource = 'cat';}
    if($dataSource == 'specification') {$dataSource = 'spec';}

    return '<div class="checkboxes-box '.$imageSelect.'" data-source="'.$dataSource.'" data-spec="'.$specTabName.'" id="">'.$options.'</div>';

}



/*********************************************
 ** BUILD FILTER BUTTON GROUP
 *********************************************/
function siteit_build_filter_btngroup( $optArr, $selectId, $dataSource, $specTabName ) {

    $result     = '';
    $options    = '';

    foreach($optArr as $option) {

        $clnOption = str_replace(' ', '-', strtolower($option) );

        $options .=  '
        <button type="button" class="btn btn-default filterBtnIconHover" name="'.$selectId.'" id="'.$clnOption.'" value="'.$option.'" data-name="'.$option.'">
            <img src="'.IMG_URL.'/select-icons/'.$clnOption.'.png" alt="'.$clnOption.'">
        </button>
        ';
    }


    if($dataSource == 'category') {$dataSource = 'cat';}
    if($dataSource == 'specification') {$dataSource = 'spec';}

    return '<div class="btn-group" data-source="'.$dataSource.'" data-spec="'.$specTabName.'" role="group" aria-label="">'.$options.'</div>';
}



/*************************************************
 ** BUILD RANGE FIELD
 *************************************************/
function siteit_build_filter_range( $optArr, $rangeSettings, $dataSource, $specTabName ) {

    foreach($optArr as $opt) {

        $rangeSettings  = array_filter($rangeSettings);

        $range          = '';
        $rangeId        = 'range-'.str2id($opt);
        $min            = $rangeSettings['min'];
        $max            = $rangeSettings['max'];
        $start          = ($min * 1.5);
        $end            = ($max / 2) + $start;
        $textaddon      = !empty($rangeSettings['textaddon']) ? $rangeSettings['textaddon'] : '';


        // ON ORDERED VALUES
        $unordered  = !empty($rangeSettings['unordered']) ? $rangeSettings['unordered'] : '';

        if( !empty($unordered) ) {
            $c          = 0;
            $mixRngArr  = array();
            $unor       = explode(',', $unordered);
            $spaces     = 100 / count($unor);
            $unor       = array_slice($unor, 1, -1, true);
            $prmStr     = '';

            foreach($unor as $k => $u) {
                $newStep = $k * $spaces;
                $prmStr  .= '"'.$newStep.'%":'.$u.',';
            }
        }




        $range .= '
        <div class="range-wrap" data-source="'.$dataSource.'" data-spec="'.$specTabName.'">
            <div id="'.$rangeId.'" data-start="'.$start.'"></div>
            <div class="text-right"><button id="'.$rangeId.'" class="btn btn-transparent apply-range" data-range="#'.$rangeId.'" data-from="" data-to="">Apply</button></div>
        </div>
        
        <script>
        var rangeSlider     = document.getElementById("'.$rangeId.'");
 

        noUiSlider.create(rangeSlider, {
            connect: true,
            '.(!empty($unordered) ? 'snap: true,' : '').'
            range: {
                "min": '.$min.',
                '.(!empty($unordered) ? $prmStr : '').'
                "max": '.$max.'
            },
            start: ['.$start.', '.$end.'],
            tooltips: [
                wNumb({ decimals: 0, postfix: " <span class=\"fta\">'.$textaddon.'</span>", }),
                wNumb({ decimals: 0, postfix: " <span class=\"fta\">'.$textaddon.'</span>", })
            ]
        });
        </script>
        ';

        if(!empty($rangeSettings['textaddon_big']) && !empty($rangeSettings['big_numlimit'])) {
            $range .= '
            <script>
            jQuery(function($){
                
                var numBigLimit     = parseInt("'.$rangeSettings['big_numlimit'].'");
                var textaddonBig    = "'.$rangeSettings['textaddon_big'].'";
                var rangeSlider = document.getElementById("'.$rangeId.'");

                rangeSlider.noUiSlider.on("update", function(values, handle, unencoded ) {
    
                    // values: Current slider values;   - Example:  4000.00,32000.00
                    // handle: Handle that caused the event;  0 or 1
                    // unencoded: Slider values without formatting;  Example: 4000,32000

                    if(unencoded[handle] >= numBigLimit) {
                        var newVal = ( Math.round( (unencoded[handle] / numBigLimit) * 10 ) / 10 ) + textaddonBig;
                        $("#'.$rangeId.'").find(".noUi-handle[data-handle=\""+handle+"\"] .noUi-tooltip").text(newVal);
                    }
    
                });
            });
            </script>
            ';
        }

    }

    return $range;

}






/*********************************************
 ** CHECK IF NEEDLE IN ARRAY & SUB ARRAYS
 *********************************************/
function in_array_r($needle, $haystack, $strict = false) {
    foreach ($haystack as $item) {
        if (($strict ? $item === $needle : $item == $needle) || (is_array($item) && in_array_r($needle, $item, $strict))) {
            return true;
        }
    }
    return false;
}




/*********************************************
 ** CHECK TR LABEL EXISTS IN TAB
 *********************************************/
function check_trlabel_inspec_tab($pid, $tabName, $trLabel) {

    $tabs = get_field('vrs_specs_spec_tabs', $pid);

    foreach($tabs as $tab) {
        if($tab['vrs_specs_tbltab_name'] == $tabName) {
            $tables = $tab['vrs_specs_info_table'];
            if( in_array_r($trLabel, $tables) ) { return true; }
        }
    }
}




/*********************************************
 ** CONVERT STRING TO USEABLLE ID
 ** (REMOVE SPCES AND LOWERCASE)
 **
 ** USED IN:
 ** custom-pages/page-som-filter.php
 ** functions/register-metaboxes.php
 *********************************************/
function str2id($str) {
    $str = str_replace( array(':', '/'), '', strtolower($str) );
    return str_replace(array(' ', '.'), '_', strtolower($str));
}




function groupname_str2id($str){
    return str_replace(array(' ', '.', '/'), '_', strtolower($str));
}

/*********************************************
 ** GET ALL SPECS INTO ARRAY (ALL TABS)
 *********************************************/
function post_tabs_specs_array($pid) {

    $tabs       = get_field('vrs_specs_spec_tabs', $pid);
    $body       = array();
    $valsArr    = array();


    if( !empty($tabs) ) {

        // EXTRACT TABLE BODIES FROM ARRAY OF TABS
        foreach($tabs as $tab) {
            $tablesArr  = $tab['vrs_specs_info_table'];

            foreach($tablesArr as $tbl) {
                $body[] = $tbl['table']['body'];
            }
        }
        $body = array_filter($body);


        if(is_array($body)) {
            $tableBodyArr = call_user_func_array('array_merge', $body);

            if( !empty($tableBodyArr) ) {
                foreach($tableBodyArr as $tableBody) {
                    $tempVals = array();
                    foreach($tableBody as $key => $td) {
                        if($key == 1) {$tempVals[] = $td['c'];}
                    }

                    $valsArr[ $tableBody[0]['c'] ] = $tempVals;
                }
            }
        }

    }


    return $valsArr;
}





/*********************************************
 ** GET VALUE BY TABLE>TR LABEL (BY TAB)
 *********************************************/
function post_table_tab_specs_array($pid, $tabName, $trLabel) {

    $tabs       = get_field('vrs_specs_spec_tabs', $pid);
    $body       = array();
    $valsArr    = array();

    // EXTRACT TABLE BODIES FROM ARRAY OF TABS
    foreach($tabs as $tab) {
        if( $tab['vrs_specs_tbltab_name'] == $tabName ) {

            $tablesArr  = $tab['vrs_specs_info_table'];

            foreach($tablesArr as $tbl) {

                $body[] = $tbl['table']['body'];

            }
        }
    }
    $tableBodyArr = call_user_func_array('array_merge', $body);

    foreach($tableBodyArr as $tableBody) {
        $tempVals = array();
        foreach($tableBody as $td) {
            $tempVals[] = $td['c'];
        }

        $valsArr[ $tableBody[0]['c'] ] = $tempVals;
    }

    return $valsArr;
}






/*********************************************
 ** BUILD TABLES FROM ARRAY INTO ARRAY
 *********************************************/
function check_partial_spec_inarray( $needle, $specsArr ) {

    $counter    = 0;
    $matchArr   = array();
    $subNdlArr  = explode(' ',  preg_replace('/[^a-zA-Z0-9_ %\[\]\.\(\)%&-]/s', ' ', strtolower($needle) ) );


    foreach($specsArr as $spec) {

        $subSpcArr  = explode(' ',  preg_replace('/[^a-zA-Z0-9_ %\[\]\.\(\)%&-]/s', ' ', strtolower($spec)) );
        $subSpcArr  = array_filter($subSpcArr);
        $matches    = array_intersect($subNdlArr, $subSpcArr );


        if( count($subNdlArr) == count($matches) ) {
            $matchArr[$counter] = 'found';
            break;
        }

        $counter++;
    }

    if(!empty($matchArr)) { return true; }
    else { return false; }
}




/*************************************************
 ** TERMS BY NAMES: BUILD ARRAY OF TERMS IDS BY NAMES
 *************************************************/
function geterms_by_names($names){
    $termids = array();

    foreach($names as $name) {
        $cterm = get_term_by( 'name', $name, 'products' );
        $termids[] = $cterm->term_id;
    }

    return $termids;
}




/*********************************************
 ** GET FIELD TYPE BY FIELD GROUP
 ** grab page id and then use to get array and
 ** interpelate the right field type
 *********************************************/
function get_fieldtype_by_group($filterPageId, $groupName){

    $fieldType      = '';
    $groupName      = str_replace('-','_', $groupName);
    $allSetFields   = get_field('som_filter_tab_group', $filterPageId);

    // get array index by group name
    foreach($allSetFields as $key => $field) {

        $fieldTitle     = groupname_str2id( $field['som_filter_field_title'] );
        $fieldSubTitles = array_map('strtolower', array_filter(wp_list_pluck( $field['som_filter_field_box'], 'som_filter_single_field_title')) );

        if( $fieldTitle == $groupName || is_array($fieldSubTitles) && in_array($groupName, $fieldSubTitles) ) {
            $fieldType = $field['som_filter_field_box'][0]['som_filter_field_type'];
            break;
        }
    }
    return $fieldType;
}


/*********************************************
 ** SET NO-INDEX, FOLLOW META TAG IF MORE
 ** THAN 1 PARAMETER
 *********************************************/
add_filter('wpseo_robots', 'consitional_param_noindex');
function consitional_param_noindex($robots) {
    $cat = get_queried_object();
    if ($cat->term_id == 43 || $cat->term_id == 65 || $cat->term_id == 99) {
        if(ICL_LANGUAGE_CODE == 'de'){
            $postID = 13406;
        }
        else if(ICL_LANGUAGE_CODE == 'it') {
            $postID = 1277115726;
        }
        else {
            $postID = 1418;
        }
        $check      = false;
        $params     = sanitize_text_field( urldecode($_SERVER['QUERY_STRING']));
        $params     = explode('&', $params);
        $fieldsArr  = get_field('som_filter_tab_group', $postID);


        // BUILD NO INDEX ARRAY BY ADMIN SELECT
        $noIndexArr = array();
        foreach($fieldsArr as $field) {
//			$filter_title = get_fieldtype_by_group($postID, $field);
//			if($filter_title != '') {
            if ( ! empty( $field['noindex'][0] ) && $field['noindex'][0] == 'on' ) {
                $fld_key = str2id(str_replace('/', '_', $field['som_filter_field_title']));
                $noIndexArr[] = $fld_key;
            }
            //}
        }


        // CHECK IF MORE THAN 2 PARAMS > ADD NO-INDEX
        foreach($params as $sub_params) {
            $subParamKey  = explode('=', $sub_params);
            $subParamsArr = explode(',', $sub_params);

            if( count($subParamsArr) > 1 || in_array($subParamKey[0], $noIndexArr) ) {
                $check = true;
            }
        }

        if($check) {
            return 'noindex, follow';

        }

    }
    return $robots;
}

function filter_cpu_meta($meta) {
    $page_id = get_id_by_slug('products/system-on-module-som');
    $fieldgroup = get_field('som_filter_tab_group', $page_id);
    $params = sanitize_text_field( urldecode($_SERVER['QUERY_STRING']) );
    $meta_value = '';
    $title = '';

    if($params) {
        $params = explode('&', $params);

        foreach($params as $param) {

            $key        = explode('=', $param);
            $key        = $key[0];
            $param      = str_replace('=', '', strstr($param, '=') );
            $sub_params = explode(',', $param);

            if( $key == 'cpu_name' || $key == 'cpu_architecture' ) {
                $title = $sub_params[0];
            }
        }

        foreach ($fieldgroup as $field) {
            $ftitle = $field["som_filter_field_title"];

            // Getting CPU Name Meta Title
            if($ftitle == 'CPU Name') {
                $check = $field["som_filter_field_box"];
                foreach ($check as $ch) {
                    $checkwrap = $ch["som_filter_source_checkbox"];
                    foreach ($checkwrap as $item) {
                        if($item["som_filter_source_checkbox_val"] == $title) {
                            if($meta == 'title') {
                                $meta_value = $item["custom_filter_meta_title"];
                            } elseif ($meta == 'description') {
                                $meta_value = $item["custom_filter_meta_description"];
                            }
                        }
                    }
                }
            }
            // Getting CPU Architecture Meta Title
            elseif ($ftitle == 'CPU Architecture') {
                global $terms;
                $parentterm = get_term_by('name', 'System On Module', 'products');
                $terms = get_term_children( $parentterm->term_id, 'products' );

                foreach ($terms as $term) {
                    $option = get_term_by('id', $term, 'products');
                    if($option->name == $title) {
                        if($meta == 'title') {
                            $meta_value = get_field('title_tag', $option);
                        } elseif ($meta == 'description') {
                            $meta_value = get_field('description_tag', $option);
                        }
                    }
                }
            }
        }
    }

    return $meta_value;
}

/*********************************************
 ** SET META TITLE IN FILTER PAGE BASED
 ** ON PRE-LOADED PARAMETERS
 *********************************************/
add_filter('wpseo_title', 'filter_pagetitle');
function filter_pagetitle($title) {
    if ( !is_tax( 'products' ) ) {
        return $title;
    }
    $new_title = filter_cpu_meta('title');

    return ($new_title == '') ? $title : $new_title;
}


/*********************************************
 ** SET META DESCRIPTION IN FILTER PAGE BASED
 ** ON PRE-LOADED PARAMETERS
 *********************************************/
add_filter('wpseo_metadesc', 'filter_pagedescription');
function filter_pagedescription($desc) {
    if ( !is_tax( 'products' ) ) {
        return $desc;
    }
    $new_desc = filter_cpu_meta('description');

    return ($new_desc == '') ? $desc : $new_desc;
}


/*********************************************
 ** SET IN-OAGE PHYSCAL H1 TITLE BASED
 ** ON PRE-LOADED PARAMETERS
 *********************************************/
function preload_h1_title($params, $originalTitle) {
    $new_title = array();
    $fieldgroup = get_field( 'som_filter_tab_group' );

    if($params) {
        $params = explode('&', $params);

        foreach($params as $param) {

            $key        = explode('=', $param);
            $key        = $key[0];
            $param      = str_replace('=', '', strstr($param, '=') );
            $sub_params = explode(',', $param);

            if( $key == 'cpu_name' || $key == 'cpu_architecture' ) {
                $i = 0;
                foreach( $sub_params as $subp ) {
                    $new_title[] = $subp;
                    if (++$i > 1)
                        break;
                }
//				$new_title[] = $sub_params[0];
            }

            $checktitle = array();

            foreach ($fieldgroup as $field) {
                $ftitle = $field["som_filter_field_title"];

                // Getting All CPU Name Items and adding to array
                if($ftitle == 'CPU Name') {
                    $check = $field["som_filter_field_box"];
                    foreach ($check as $ch) {
                        $checkwrap = $ch["som_filter_source_checkbox"];
                        foreach ($checkwrap as $item) {
                            $checktitle[] = $item["som_filter_source_checkbox_val"];
                        }
                    }
                }
                // Getting All CPU Architecture Items and adding to array
                elseif ($ftitle == 'CPU Architecture') {
                    global $terms;
                    $parentterm = get_term_by('name', 'System On Module', 'products');
                    $terms = get_term_children( $parentterm->term_id, 'products' );

                    foreach ($terms as $term) {
                        $option = get_term_by('id', $term, 'products');
                        $checktitle[] = $option->name;
                    }
                }
            }

            // Comparing the title from URL with a list of CPU Name and Architecture filter items
            $new_title = array_intersect($new_title, $checktitle);
        }

        if( !empty($new_title) ) {
            $title = implode(', ', $new_title);
            $title = str_replace(',,',',',$title);
            $title = str_replace(', ,',',',$title);
//            return $title . ' ' .  __( 'System on Module', THEME_NAME );
            if (strpos($title,"Legacy Modules")!== false){
                return $title;
            }
            return $title . ' ' .  __( 'System on Module', THEME_NAME );
        }
        else {
            return $originalTitle;
        }
    }

}


/*************************************************
 ** GET PAGE ID BY SLUG
 *************************************************/
function get_id_by_slug($page_slug) {
    $page = get_page_by_path($page_slug);
    if ($page) {
        return $page->ID;
    } else {
        return null;
    }
}


/*************************************************
 ** ADD CPU SITEMAP
 *************************************************/
/**
 *  Create a new custom yoast seo sitemap
 */

add_filter( 'wpseo_sitemap_index', 'ex_add_sitemap_custom_items' );
add_action( 'init', 'init_wpseo_do_sitemap_actions' );

// Add custom index
function ex_add_sitemap_custom_items(){
    global $wpseo_sitemaps;
    $date = $wpseo_sitemaps->get_last_modified('specs');

    $smp ='';

    $smp .= '<sitemap>' . "\n";
    $smp .= '<loc>' . site_url() .'/cpu-sitemap.xml</loc>' . "\n";
    $smp .= '<lastmod>' . htmlspecialchars( $date ) . '</lastmod>' . "\n";
    $smp .= '</sitemap>' . "\n";


    return $smp;
}

function init_wpseo_do_sitemap_actions(){
    add_action( "wpseo_do_sitemap_cpu", 'ex_generate_origin_combo_sitemap');
}

function ex_generate_origin_combo_sitemap(){

    global $wpseo_sitemaps;

    $date = $wpseo_sitemaps->get_last_modified('specs');

    $page = get_id_by_slug('products/system-on-module-som');
    $fieldgroup = get_field( 'som_filter_tab_group', $page );

    $cpus = array();

    foreach ($fieldgroup as $field) {
        $ftitle = $field["som_filter_field_title"];

        // Getting All CPU Names and adding to array
        if($ftitle == 'CPU Name') {
            $check = $field["som_filter_field_box"];
            foreach ($check as $ch) {
                $checkwrap = $ch["som_filter_source_checkbox"];
                foreach ($checkwrap as $item) {
                    $cpu_name = str_replace(" ", "%20", $item["som_filter_source_checkbox_val"]);
                    $cpus[] = $cpu_name;
                }
            }
        }
    }

    $site_url = site_url();
    $output = '';

    foreach ($cpus as $cpu) {
        $url = array();
        $url['loc'] =  $site_url . '/products/system-on-module-som/?cpu_name=' . $cpu;
        $url['mod'] = $date;
        $url['chf'] = 'weekly';
        $url['pri'] = 1.0;
        $output .= $wpseo_sitemaps->renderer->sitemap_url( $url );
    }


    //Build the full sitemap
    $sitemap = '<urlset xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" ';
    $sitemap .= 'xsi:schemaLocation="http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd" ';
    $sitemap .= 'xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";
    $sitemap .= $output . '</urlset>';

    //echo $sitemap;
    $wpseo_sitemaps->set_sitemap($sitemap);

}


add_filter( 'wpseo_canonical', 'filter_wpseo_canonical' );
function filter_wpseo_canonical( $canonical ) {
    if ( !is_tax( 'products' ) ) {
        return $canonical;
    }

    $url = $_SERVER['REQUEST_URI'];
    $array_query = parse_url($url);

    if(!isset($array_query['query'])) {
        return $canonical;
    }

    // Change canonical for CPU only
    $query_params = explode(',', $array_query['query']);
    $find_cpu = strpos($query_params[0], 'cpu_name');

    if($find_cpu !== false) {
        $canonical = get_site_url() . $array_query['path'] . '?' . $query_params[0];
    }

    return $canonical;
}


add_filter('acf/fields/taxonomy/wp_list_categories', 'my_taxonomy_wp_list_categories', 10, 2);
function my_taxonomy_wp_list_categories( $args, $field ) {
    // modify args
    $args['orderby'] = 'count';
    $args['order'] = 'ASC';

    // return
    return $args;
}


/*********************************************
 ** HELPER FOR CUSTOMIZED QUERIES
 ** had to use this becouse wp returns results
 ** of post__in is empty.
 **
 ** THE LOOP
 ** CASE 1: NO metaQuery + categories                    = RESULTS
 ** CASE 2: metaQuery exists results + categories        = RESULTS
 ** CASE 3: metaQuery exists no results + categories     = NO RESULTS
 *********************************************/

function meta_cat_query_helper($filter_params, $postids_arr, $cat_filters, $pppage, $paged) {

    $data = '';

    $args['posts_per_page'] = $pppage;
    $args['post_type']      = 'specs';
    $args['post_status']    = 'publish';
    $args['order']          = 'ASC';
    $args['orderby']        = 'menu_order';
    $args['paged']          = $paged;
    $args['meta_query'] = array(
        'relation' => 'OR',
        array(
            'key' => 'vrs_specs_exclude_from_search',
            'value' => 1,
            'compare' => '!=',
        ),
        array(
            'key' => 'vrs_specs_exclude_from_search',
            'compare' => 'NOT EXISTS',
        )
    );

    // case 1
    if( empty($filter_params) ) {
        $args['tax_query'] = array(
            array( 'taxonomy' => 'products', 'field' => 'term_id', 'terms' => $cat_filters, )
        );
        $runQuery = true;
    }

    // case 2
    elseif( !empty($filter_params) && !empty($postids_arr) ) {

        $args['order']      = 'ASC';
        $args['orderby']    = 'menu_order';
        $args['post__in']   = $postids_arr;
        $args['tax_query']  = array(
            array(
                'taxonomy' => 'products',
                'field'    => 'term_id',
                'terms'    => $cat_filters,
            )
        );

        $runQuery = true;

    }

    // case 3
    elseif( !empty($filter_params) && empty($postids_arr) ) {
        $runQuery = false;
    }



    // RUN QUERY
    if($runQuery) {
        $query = new WP_Query( $args );
        $found = $query->found_posts;

        if ( $query->have_posts() ) {
            while ( $query->have_posts() ) {
                $query->the_post();

                $pid    = get_the_ID();
                $data   .= filter_build_product($pid, get_field('title_length_filter', 'option'));

            }
        }
        wp_reset_query();
    }


    $results['query'] = !empty($query) ? $query : '';
    $results['found'] = !empty($found) ? $found : '0';
    $results['data'] = $data;

    return $results;
}
?>