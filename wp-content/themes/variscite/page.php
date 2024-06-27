<?php get_header(); ?>

<div class="container relative hidden-xs">
    <div class="page-breadcrumbs"><?php echo breadcrumbs(); ?></div>
</div>

<div class="page-box">
    <?php
    if ( have_posts() ) {
        while ( have_posts() ) {
            the_post(); 

            // PAGE DATA
            $pid            = get_the_ID();
            $post_title     = get_the_title();
            $show_title     = get_field('pp_page_showtitle', $pid);
            $no_topmarg     = get_field('pp_page_notopmarg', $pid);

            if( !empty($show_title[0]) && $show_title[0] == 'on') {
                echo ' <div class="container"> <h1 class="ppage-title">'.$post_title.'</h1> </div> ';
            }
            else {
                if( !empty($no_topmarg[0]) && $no_topmarg[0] != 'yes') {echo '<div class="notitle-spacer"></div>';}
            }

            if( empty(siteorigin_panels_render()) ) {
                echo '
                <div class="container">
                    '.apply_filters('the_content', get_the_content()).'
                </div>
                ';
            }
            else {
                the_content(); 
            }

            

        }
    }
    ?>
</div>

<?php
get_sidestrip();
get_footer();
?>