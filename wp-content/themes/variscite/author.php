<?php get_header(); 
$queried 				= get_queried_object();
$authorid 				= $queried->ID;
$author_description 	= get_the_author_meta('user_description');
$author_linkedin 		= get_the_author_meta('linkedin');
$author_photo_id 		= get_field('author_photo', 'user_' . $authorid);
$display_name 			= get_the_author_meta('display_name');
?>

<div class="cat-loop container author-loop">

	<div class="cat-title author-top">

		<?php if ($author_photo_id) { ?>
			<div class="author-thumb">
				<?php echo wp_get_attachment_image($author_photo_id, 'thumbnail'); ?>
			</div>
		<?php } ?>
		
		<div class="author-meta">
			<h1><?php echo $display_name; ?></h1>
			<?php if ($author_description) { ?>
				<p>
					<?php echo $author_description; ?>
				</p>
			<?php } ?>
			<?php if ($author_linkedin){ ?>
				<ul class="author-meta-social">
					<?php if ($author_linkedin) { ?>
						<li>
							<a href="<?php echo $author_linkedin; ?>" target="_blank">
								<span>Linkdin</span>
								<i class="fa-brands fa-linkedin"></i>
							</a>
						</li>
					<?php } ?>
				</ul>
			<?php } ?>
		</div>
	</div>

	<div class="posts-cat-title">
		<h2><?php echo 'Blog posts by ' . $display_name; ?></h2>
	</div>

	<div id="posts-box">
		<?php
	
        if ( have_posts() ) {
            while ( have_posts() ) {
                the_post();
				
				// item data
				$pid 			= get_the_ID();
				$plink 			= get_permalink();
				$title 			= get_the_title();
				$date 			= get_the_date( 'l, F j, Y', $pid );
				$excerpt 		= content_to_excerpt( get_the_content(), 120 );

				// THUMB SETTINGS
				$thumb = [];
				$thumb['id']	= get_post_thumbnail_id($pid);
				$thumb['url']	= get_the_post_thumbnail_url($pid, 'full');
				$thumb['alt']	= get_post_meta($thumb['id'], '_wp_attachment_image_alt', true);


				// IF NO THUMB WAS SET
				if($thumb['url'] == '') {
					$thumb['url'] = get_option(THEME_PREF.'default_post_thumb');
					$thumb['alt'] = __('Post Thumb', THEME_NAME);
				}


				/* echo '
				<div class="cat-post-item">
					<div class="row">
						<div class="col-md-6 col-sm-6 col-xs-12">
							<a href="'.$plink.'"> <img src="'.$thumb['url'].'" alt="'.$thumb['alt'].'" class="img-responsive"> </a>
						</div>
						<div class="col-md-6 col-sm-6 col-xs-12">
							<h3 class="item-title"><a href="'.$plink.'">'.$title.'</a></h3>
							<div class="date">'.$date.'</div>
							<div class="excerpt">'.$excerpt.'</div>
							<div class="rmore"><a href="'.$plink.'" class="btn btn-default">'.__('Read More', THEME_NAME).'</a></div>
						</div>
					</div>
				</div>
				'; */
				echo '
				<div class="cat-post-item">
					<div class="row">
						<div class="col-md-12 col-sm-12 col-xs-12">
							<h3 class="item-title"><a href="'.$plink.'">'.$title.'</a></h3>
							<div class="date">'.$date.'</div>
							<div class="excerpt">'.$excerpt.'</div>
						</div>
					</div>
				</div>
				';


			}
		}
		?>
	</div>
	<div class="pgnavi-box">
		<?php wp_pagenavi(); ?>
	</div>
	<?php /* <div class="cat-loadmore-box">
		<button id="postsLoadMore" data-count="6" data-ccat="" data-tag="" data-author="<?php echo $authorid; ?>" class="btn btn-blue"> <img src="<?php echo IMG_URL; ?>/loading-btn.svg" alt="loading" class="loading"> <?php _e('Load More', THEME_NAME); ?></button>
		<div class="nomoreposts"><?php echo get_option(THEME_PREF.'nomore_posts_blog'); ?></div>
	</div> */ ?>
</div>


<?php get_footer(); ?>