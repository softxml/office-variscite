<?php get_header(); ?>

<div class="cat-box">

	<div class="container relative">

        <!--===STR===== SIDEBAR MODAL ===========-->
        <div class="modal fade" id="mobileSidebarModal" tabindex="-1" role="dialog" aria-labelledby="mobileSidebarModalLabel">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    </div>
                    <div class="modal-body">
                        <div class="mobile-category-sidebar">
                            <?php if ( is_active_sidebar( 'blogsidebarmobile' ) ) { dynamic_sidebar( 'blogsidebarmobile' ); } ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--===END===== SIDEBAR MODAL ===========-->


		<div class="breadcrumbs hideInMobile"><?php echo breadcrumbs(); ?></div>

		<div class="row relative">
            <?php
            $ccat           = get_queried_object();
            $custom_title   = get_field('custom_cat_title', 'category_'.$ccat->term_id);
            ?>

            <div class="col-md-12 cat-title">
                <h1><?php echo ($custom_title ? $custom_title : single_cat_title('', false)); ?></h1>
                <ul class="mcatActionList list-inline hideInDesktop">
                    <li class="rss"><a href="<?php get_field('optage_rss_url', 'option'); ?>"><i class="fa fa-rss"></i></a></li>
                    <li class="ssearch"><button class="btn btn-link" id="showMobileSidebar" data-toggle="modal" data-target="#mobileSidebarModal"><img src="<?php echo IMG_URL; ?>/search.png" alt="Search"></button></li>
                </ul>
            </div>

			<div class="col-sm-7 col-md-8 cat-loop">

				<div id="posts-box">
                    <?php
                    // BASIC CAT INFO
					$ccat 		= get_category( get_query_var( 'cat' ) );
					$ccat_id 	= $ccat->cat_ID;
                    $paged 		= get_query_var('paged') ? get_query_var('paged') : 1;
                    

                    // GET CHILD CATEGORIES
					$childCats  = get_field( 'custom_childcats', $ccat );

					if($childCats) {
						$tax_query[] = array(
							'taxonomy' => 'category',
							'field'    => 'term_id',
							'terms'    => array( implode(',', $childCats ) ),
						);
					}
					else {
						$tax_query[] = array(
							'taxonomy' => 'category',
							'field'    => 'term_id',
							'terms'    => array( $ccat_id ),
						);
					}

					$args = array(
						'post_type'			=> 'post', 
						'posts_per_page'	=> 10,
						'paged'				=> $paged,
						'tax_query' 		=> $tax_query,
					);
					$query = new WP_Query( $args );

					if ( $query->have_posts() ) {
						while ( $query->have_posts() ) {
							$query->the_post();

							// ITEM DATA
							$pid 		= get_the_ID();
							$title 		= get_the_title();
							$link 		= get_permalink();
							$date 		= get_the_date( 'l, d M Y', $pid );
							$cat 		= get_the_category();
							$excerpt	= content_to_excerpt( get_the_content(), 232 );

							// ITEM PARTS
							$item_image 	= '<div class="item-thumb"><a href="'.$link.'"><img src="'.siteitsob_smart_thumbnail_url( $pid, 430, 226, get_field('optage_defaults_blog_image', 'option') ).'" alt="'.$title.'" class="img-responsive"></a></div>';
							$item_meta 		= '<div class="item-meta"><div class="row"> <div class="col-md-6 text-left date">'.$date.'</div> <div class="col-md-6 catname text-right"><span>'.__('Category:', THEME_NAME).'</span> <strong>'.$cat[0]->name.'</strong></div> </div></div>';
							$item_title 	= '<div class="item-title"><h2><a href="'.$link.'">'.$title.'</a></h2></div>';
							$item_excerpt 	= '<div class="item-excerpt">'.$excerpt.'</div>';
							$item_rmore 	= '<div class="item-rmore"><a href="'.$link.'">'.__('Read More', THEME_NAME).'</a></div>';

							echo '
							<div class="post-item">
								<div class="row">
									<div class="col-md-5 col-xs-12">
										'.$item_image.'
										'.$item_meta.'
									</div>
									<div class="col-md-7 col-xs-12">
										'.$item_title.'
										'.$item_excerpt.'
										'.$item_rmore.'
									</div>
								</div>
							</div>
							';

						}
					}
					?>
				</div>

			</div>


            <div class="col-sm-5 col-md-4 hidden-xs category-sidebar">
                <?php if ( is_active_sidebar( 'blogsidebar' ) ) { dynamic_sidebar( 'blogsidebar' ); } ?>
            </div>
		</div>

		<div class="pgnavi-box">
			<?php 
			wp_pagenavi(array( 'query' => $query ) );
			wp_reset_query();
			?>
		</div>
	</div>

</div>


<?php get_footer(); ?>