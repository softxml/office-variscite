<?php
/*********************************************
** THIS FILE WAS CREATED TO INCLUDE 
** ADDITIONAL EXTENSTION TO BE USED 
** WITH THE ACF (OR ACF PRO) PLUGIN
*********************************************/


/*********************************************
** IMAGE SELECT
********************************************
add_action('acf/register_fields', 'siteit_acfimage_select_field');
function siteit_acfimage_select_field() {
	include_once(THEME_PATH.'/functions/acf-ext/acf-image-select/acf-image-select.php');
}
*/

$folderUrl = THEME_PATH.'/functions/acf-ext';
include($folderUrl.'/acf-title-field/acf-title-field.php');
?>