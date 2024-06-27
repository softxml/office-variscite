<?php
/*** REGISTER MENU ***/
if ( function_exists('register_sidebar') )
register_sidebar(array('name'=> __('Posts Sidebar', THEME_NAME), 'id' => 'postsidebar', 'before_widget'=>'', 'after_widget'  => '', 'before_title'  => '<h3 class="widgettitle">', 'after_title'   => '</h3>'));
register_sidebar(array('name'=> __('Pages Sidebar', THEME_NAME), 'id' => 'pagessidebar', 'before_widget'=>'', 'after_widget'  => '', 'before_title'  => '<h3 class="widgettitle">', 'after_title'   => '</h3>'));
register_sidebar(array('name'=> __('Blog Sidebar', THEME_NAME), 'id' => 'blogsidebar', 'before_widget'=>'<div class="singleWidget">', 'after_widget'  => '</div>', 'before_title'  => '<h3 class="widgettitle">', 'after_title'   => '</h3>'));
register_sidebar(array('name'=> __('Blog Sidebar Mobile', THEME_NAME), 'id' => 'blogsidebarmobile', 'before_widget'=>'<div class="singleWidget">', 'after_widget'  => '</div>', 'before_title'  => '<h3 class="widgettitle">', 'after_title'   => '</h3>'));

register_sidebar(array('name'=> __('Footer 1', THEME_NAME), 'id' => 'footersb1', 'before_widget'=>'<div class="singleWidget">', 'after_widget'  => '</div>', 'before_title'  => '<h3 class="widgettitle">', 'after_title'   => '</h3>'));
register_sidebar(array('name'=> __('Footer 2', THEME_NAME), 'id' => 'footersb2', 'before_widget'=>'<div class="singleWidget">', 'after_widget'  => '</div>', 'before_title'  => '<h3 class="widgettitle">', 'after_title'   => '</h3>'));
register_sidebar(array('name'=> __('Footer 3', THEME_NAME), 'id' => 'footersb3', 'before_widget'=>'<div class="singleWidget">', 'after_widget'  => '</div>', 'before_title'  => '<h3 class="widgettitle">', 'after_title'   => '</h3>'));
register_sidebar(array('name'=> __('Footer 4', THEME_NAME), 'id' => 'footersb4', 'before_widget'=>'<div class="singleWidget">', 'after_widget'  => '</div>', 'before_title'  => '<h3 class="widgettitle">', 'after_title'   => '</h3>'));
register_sidebar(array('name'=> __('Footer 5', THEME_NAME), 'id' => 'footersb5', 'before_widget'=>'<div class="singleWidget">', 'after_widget'  => '</div>', 'before_title'  => '<h3 class="widgettitle">', 'after_title'   => '</h3>'));
register_sidebar(array('name'=> __('Footer 6', THEME_NAME), 'id' => 'footersb6', 'before_widget'=>'<div class="singleWidget">', 'after_widget'  => '</div>', 'before_title'  => '<h3 class="widgettitle">', 'after_title'   => '</h3>'));
register_sidebar(array('name'=> __('Footer 7', THEME_NAME), 'id' => 'footersb7', 'before_widget'=>'<div class="singleWidget">', 'after_widget'  => '</div>', 'before_title'  => '<h3 class="widgettitle">', 'after_title'   => '</h3>'));
register_sidebar(array('name'=> __('Footer 8 (Mobile)', THEME_NAME), 'id' => 'footersb8', 'before_widget'=>'<div class="singleWidget">', 'after_widget'  => '</div>', 'before_title'  => '<h3 class="widgettitle">', 'after_title'   => '</h3>'));


/*** REGISTER MENU ***/
register_nav_menus(array(
    'topmenu' 		    => __( 'Top Menu', THEME_NAME ),
    'topmenumobile'     => __( 'Top Mobile Menu', THEME_NAME ),
    'footermenu'	    => __( 'Footer Menu', THEME_NAME ),
    'footermenumobile'	=> __( 'Footer Mobile Menu', THEME_NAME ),
));
?>