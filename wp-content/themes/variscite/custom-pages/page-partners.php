<?php
/*
Template Name: Partners Page
*/
get_header(); ?>

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
                $hero_bg        = get_field('hero_background', $pid);?>

                <div class="partner-hero skew-after" style="background-image: url('<?php echo $hero_bg['url']; ?>')">
                    <div class="container partner-row">
                        <div class="partner-col">
                            <?php
                            if( !empty($show_title[0]) && $show_title[0] == 'on') {
                                echo '<h1 class="ppage-title">'.$post_title.'</h1>';
                            } ?>
                        </div>
                    </div>
                </div>

                <div class="container partner-content">
                    <div class="partner-row">
                        <div class="partner-col">
                            <?php the_content(); ?>
                        </div>
                        <div class="partner-col">
                            <?php if( have_rows('partners_grid', $pid) ): ?>
                                <div class="partner-logos">
                                    <?php while( have_rows('partners_grid', $pid) ): the_row();
                                        $image = get_sub_field('partner_logo');
                                        $link = get_sub_field('partner_link');
                                        ?>
                                        <a href="<?php echo $link; ?>" class="partner-logo">
                                            <img src="<?php echo $image['url']?>" alt="">
                                        </a>
                                    <?php endwhile; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>

                <?php
            }
        }
        ?>
    </div>

<?php
get_sidestrip();
get_footer();
?>