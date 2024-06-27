<?php get_header(); ?>

<div class="container relative">
    <div class="page-breadcrumbs"><?php echo breadcrumbs(); ?></div>
</div>

<div class="page-box">
	<?php
	$args 	= array( 'page_id' => 944 );
	$query 	= new WP_Query( $args );

	// The Loop
	if ( $query->have_posts() ) {
		while ( $query->have_posts() ) {
			$query->the_post();


			the_content();
		}
		wp_reset_postdata();
	}
    ?>
</div>
<?php get_footer(); ?>