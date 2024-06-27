<?php
/*
Template Name: Home Page
*/
get_header();


get_template_part('/parts/hero-home');

if ( have_posts() ) {
    while ( have_posts() ) {
        the_post();

        the_content();

    }
}

if(get_field('hide_side_strip', get_the_ID()) != 'on') {
    include(THEME_PATH.'/parts/side-strip.php');
};

// SOCIAL DATA FOR SCHEMA
$social = get_field('optage_social_accounts', 'option');

get_footer(); ?>
