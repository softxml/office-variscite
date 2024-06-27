<?php
/*
Template Name: Thanks page
*/
get_header();


	if ( have_posts() ) {
		while ( have_posts() ) { 
			the_post();

			the_content();
		}
	}


	if(get_field('hide_side_strip', get_the_ID()) != 'on') {
		include(THEME_PATH.'/parts/side-strip.php');
	}


get_footer(); ?>