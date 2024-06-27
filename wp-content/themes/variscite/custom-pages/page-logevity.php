<?php
/*
Template Name: Longevity
*/
get_header(); ?>

<div class="container relative">
    <div class="page-breadcrumbs"><?php echo breadcrumbs(); ?></div>
</div>

<div class="page-box longevity-page">
    <?php 
    if ( have_posts() ) {
        while ( have_posts() ) {
            the_post(); 

			// COLLECT POST DATA
			$pid 	= get_the_ID();
			$title 	= get_field('longevity_page_title');
			$text 	= get_field('longevity_page_text');
			$table 	= get_field('longevity_table');
			$closer	= get_field('longevity_closing_text');

			echo '
			<div class="longevity-head">
				<div class="container">
					<div class="title-box"><h1>'.$title.'</h1></div>
				</div>
			</div>
			<div class="logevity-table-box skew-before">
				<div class="container">
					<div class="row">
						<div class="col-md-5 col-xs-12 content-box">
							'.apply_filters('the_content', $text).'
						</div>
						<div class="col-md-6 col-md-offset-1 col-xs-12">
							'.siteit_build_acftable($table, 'longevityTable', 'table table-responsive table-striped').'

							<div class="closing-text">
								'.apply_filters('the_content', $closer).'
							</div>
						</div>
					</div>
				</div>
			</div>
			';
        }
    }
    ?>
</div>


<?php
get_sidestrip();
get_footer();
?>