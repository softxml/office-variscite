<?php
/*
Template Name: Landing Page
*/
get_header();
?>

<div class="landing-page-content">
    <?php
    // Display ACF fields
    if (function_exists('the_field')) {
        the_field('landing_header_image');
        the_field('landing_header_text');
        the_field('landing_selected_products');
        the_field('landing_featured_products');
        the_field('landing_footer_text');
    }
    ?>
</div>

<?php get_footer(); ?>
