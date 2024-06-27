<?php
/*************************************************
** CUSTOM SPEC'S PAGE - QUOTE FORM PRODUCT ADDONS
************************************************
function custom_select_optage_specs_quote_som_addons( $field ) {

    // optage_specs_quote_som_addons
    // optage_specs_quote_kits_addons 


    
    // reset choices
    $field['vrs_specs_quote_product_addons'] = array();


    $bundles = get_field('optage_specs_quote_som_addons');

    echo '<pre dir="ltr">';
    print_r($bundles);
    echo '</pre>';


    // return the field
   // return $field;
    
}

add_filter('acf/load_field/name=quote_product_addons_group', 'custom_select_optage_specs_quote_som_addons');
*/


/*********************************************
** BUILD HTML TABLE FROM TABLE-PLUGIN-ACF
*********************************************/
function siteit_build_acftable($tableArr, $tableId, $tableClass) {

    $tbl    = $tableArr;
    $thead  = '';
    $tbody  = '';

    // build thead
    foreach($tbl['header'] as $header) {
        $thead .= '<th>'.$header['c'].'</th>';
    }
    $thead = '<thead> <tr>'.$thead.'</tr> </thead>';


    // build tbody
    foreach($tbl['body'] as $tblBody) {
        $trs = '';

        foreach($tblBody as $td) {
            $trs .= '<td><span>'.$td['c'].'</span></td>';
        }
        $tbody .= '<tr>'.$trs.'</tr>';

    }
    $tobdy = '<tbody>'.$tbody.'</tbody>';



    return '<table id="'.$tableId.'" class="'.$tableClass.'">'.$thead.$tbody.'</table>';
}
?>