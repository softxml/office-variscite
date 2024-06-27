<?php
/*
Template Name: Text-Page
*/
get_header(); ?>


<div class="container relative hidden-xs">
    <div class="page-breadcrumbs"><?php echo breadcrumbs(); ?></div>
</div>

<div class="page-box text-page">
    <?php
    if ( have_posts() ) {
        while ( have_posts() ) {
            the_post(); 

			// PAGE DATA
			$pid            = get_the_ID();
			$post_title     = get_the_title();
			$title_thumb 	= get_field('textpage_image_above_title');
			$relArticles	= get_field('textpage_related_articles');
			$related 		= '';

			// build realted posts
			if( !empty($relArticles) ) {
				foreach($relArticles as $article) {
					$relID = $article['related_article'];
					$related .= '<li><a href="'.get_permalink($relID).'">'.get_the_title($relID).'</a></li>';
				}
			}


			if( empty(siteorigin_panels_render()) ) {
				echo '
				<div class="container">
				<div class="thumb"><img src="'.$title_thumb.'" alt="'.$post_title.'" class="img-responsive"></div>
					<h1 class="ppage-title">'.$post_title.'</h1>

					<div class="row">
						<div class="col-md-7 col-sm-8">
							'.apply_filters('the_content', get_the_content()).'
						</div>
						<div class="col-md-2 hidden-sm"></div>
						<div class="col-md-3 col-sm-4">
							<div class="inner-services-sidenav">
								<h3 class="widget-title">'.__('Related Articles', THEME_NAME).'</h3>
								<ul>'.$related.'</ul>
							</div>
						</div>
					</div>
				</div>
				';
			}
			else {
				echo '
				<div class="container">
					<div class="notitle-spacer"></div>
					'.($title_thumb ? '<div class="thumb"><img src="'.$title_thumb.'" alt="'.$post_title.'" class="img-responsive"></div>' : '').'
					

					<div class="row">
						<div class="col-md-12"><h1 class="ppage-title">'.$post_title.'</h1></div>
						<div class="col-md-7 col-sm-8 innerNoSidePads">
							'.apply_filters('the_content', get_the_content()).'
						</div>
						<div class="col-md-2 hidden-sm"></div>
						<div class="col-md-3 col-sm-4">
							<div class="inner-services-sidenav">
								<h3 class="widget-title">'.__('Related Articles', THEME_NAME).'</h3>
								<ul>'.$related.'</ul>
							</div>
						</div>
					</div>
				</div>
				';
			}

        }
    }
    ?>
</div>

<?php
get_sidestrip();
get_footer();
?>
