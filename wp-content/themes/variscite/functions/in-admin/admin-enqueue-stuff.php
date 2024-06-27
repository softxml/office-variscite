<?php
// CUSTOM CSS IN WP ADMIN
function siteit_custom_admin_css(){
    wp_register_style('siteit_admin_css', BASE_URL.'/functions/in-admin/css/siteit-admin-style.css', false, '1.0.0' );
    wp_enqueue_style('siteit_admin_css');

	wp_register_script('siteit_admin_scripts', BASE_URL.'/functions/in-admin/js/siteit-admin-scripts.min.js', array('jquery'), false, true);
    wp_enqueue_script('siteit_admin_scripts');
    
}
add_action('admin_enqueue_scripts', 'siteit_custom_admin_css');
?>