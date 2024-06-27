<?php get_header(); ?>

<div class="cat-loop container">

	<div class="cat-title">
		<h1><?php the_archive_title();?></h1>
	</div>

	<div id="posts-box">
		<?php
		$queried 	= get_queried_object();
        $tag		= $queried->name;
        
		$args = array(
			'post_type'			=> 'post', 
            'posts_per_page'	=> 6,
            'tag'               => $tag
		);
        $query = new WP_Query( $args );

        if ( $query->have_posts() ) {
            while ( $query->have_posts() ) {
                $query->the_post();

				echo 'TAG LOOP';
			}
		}
		?>
	</div>
</div>


<?php get_footer(); ?>