<?php
/*********************************************
** 	WIDGETS GROUP FOR PAGE BUILDER
*********************************************/
function siteit_pbuilder_add_widget_tabs($tabs) {
    $tabs[] = array(
        'title' => __('SiteIT Widgets', 'siteitsob'),
        'filter' => array(
            'groups' => array('siteitsob_siteit')
        )
    );

    return $tabs;
}
add_filter('siteorigin_panels_widget_dialog_tabs', 'siteit_pbuilder_add_widget_tabs', 20);


function mytheme_add_widget_icons($widgets){

    $widgets['sgBtnWidget']['groups'] 						= array('siteitsob_siteit');
    $widgets['sgTextWidget']['groups'] 						= array('siteitsob_siteit');
    $widgets['sgSpacerWidget']['groups'] 					= array('siteitsob_siteit');
    $widgets['sgPostIsotopeLooperWidget']['groups']         = array('siteitsob_siteit');
    $widgets['sgImageWidget']['groups']                     = array('siteitsob_siteit');
    $widgets['sgImgTextWidget']['groups']                   = array('siteitsob_siteit');
    $widgets['sgBgImageOverlayWidget']['groups']            = array('siteitsob_siteit');
    $widgets['sgExpendingElementWidget']['groups']          = array('siteitsob_siteit');
    $widgets['sgSocialIconList']['groups']                  = array('siteitsob_siteit');
    $widgets['wbCF7formWidget']['groups']                   = array('siteitsob_siteit');
    $widgets['sgCleanJsWidget']['groups']                   = array('siteitsob_siteit');

    return $widgets;
}
add_filter('siteorigin_panels_widgets', 'mytheme_add_widget_icons');
?>