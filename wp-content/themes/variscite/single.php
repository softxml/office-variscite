<?php
get_header();

// GET POST CATEGORY
$pid = get_the_id();
$cat = get_the_category($pid);

?>

	<div class="single-box">

		<div class="container">

            <div class="hideInMobile"><?php echo breadcrumbs(); ?></div>

			<div class="row relative">

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

                <div class="col-md-12 cat-title">
                    <h4><?php echo $cat[0]->name; ?></h4>
                    <ul class="mcatActionList list-inline hideInDesktop">
                        <li class="rss"><a href="<?php get_field('optage_rss_url', 'option'); ?>"><i class="fa fa-rss"></i></a></li>
                        <li class="ssearch"><button class="btn btn-link" id="showMobileSidebar" data-toggle="modal" data-target="#mobileSidebarModal"><img src="<?php echo IMG_URL; ?>/search.png" alt="Search"></button></li>
                    </ul>
                </div>

				<div class="col-sm-7 col-md-8 post-loop">
					<?php
					if ( have_posts() ) {
						while ( have_posts() ) {
							the_post();
							
							// ITEM DATA
							$pid 		= get_the_ID();
							$title 		= get_the_title();
							$link 		= get_permalink();
							$date 		= get_the_date( 'l, d M Y', $pid );
							$cat 		= get_the_category();
							$content    = get_the_content();
                            $include_post_author = get_field('include_post_author', get_the_ID());
                            $author_url = $include_post_author ? get_author_posts_url(get_the_author_meta('ID')) : '';
                            $author     = $include_post_author ? ($author_url ? '<a href="' . $author_url . '">'  . get_the_author() . '</a>' : get_the_author()) : '';

                            
							// THUMB SIZES
							$thumb_mobile = '';
							$thumb_desktop = '';
							$thumb = smart_thumbnail( $pid, 450, 235, '', $title, get_field('optage_defaults_blog_image', 'option') );
							
							// NEXT & PREV LINKS
							$prev_post = get_adjacent_post(true,'',true, 'category');
							$next_post = get_adjacent_post(true,'',false, 'category');
							
							
							$title_block = '
                        <div class="post-title">
                            <div class="row">
                                <div class="col-md-8 col-xs-12">
                                    <h1>'.$title.'</h1>
                                </div>
                                <div class="col-md-4 col-xs-12">
                                    <div class="posthumb">'.$thumb.'</div>
                                </div>
                            </div>
                        </div>
                        ';
							
							
							$meta_block = '
                        <div class="title-meta">
                            <div class="row">
                                <div class="row post-meta-data">
                                    <div class="col-xs-7 col-sm-6 col-md-12">
                                        <div class="col-md-4 col-md-meta postdate">'.$date.'</div>
                                        <div class="col-md-4 col-md-meta catname"><span>'.__('Category:', THEME_NAME).'</span> '.$cat[0]->name.'</div>
                                        ' . ($author ? '<div class="col-md-4 col-md-meta author"><span>'.__('Author', THEME_NAME).': </span>' . $author . '</div>' : '') . ' 
                                        <div class="col-md-4 col-md-meta social-meta social-meta-1"><a href="https://www.facebook.com/sharer/sharer.php?u='.urlencode($link).'" target="_blank" class="share-btn"> <i class="fa-brands fa-facebook-f"></i> <span class="hidden-xs hidden-sm hidden-md">'.__('Share', THEME_NAME).'</span> </a></div>
                                        <div class="col-md-4 col-md-meta social-meta social-meta-2"><a href="https://twitter.com/intent/tweet?text='.urlencode($title).'&url='.urlencode($link).'" target="_blank" class="share-btn"> <i class="fa-brands fa-x-twitter"></i> <span class="hidden-xs hidden-sm hidden-md">'.__('Tweet', THEME_NAME).'</span> </a></div>
                                        <div class="col-md-4 col-md-meta social-meta social-meta-3"><a href="https://www.linkedin.com/shareArticle?url='.urlencode($link).'&title='.urlencode($title).'" target="_blank" class="share-btn"> <i class="fa-brands fa-linkedin-in"></i> <span class="hidden-xs hidden-sm hidden-md">'.__('Post', THEME_NAME).'</span> </a></div>   
                                    </div>
                                </div>
                            </div>
                        </div>
                        ';
							
							
							$content_block = '
                        <div class="post-content">
                            '.apply_filters('the_content', $content).'
                        </div>
                        ';
							
							
							$postnav_links = '
                        <div class="postnav-links">
                            <div class="row">
                                <div class="col-md-5 col-xs-12 center-block text-center">
                                    <ul class="list-inline p0 m0">
                                        '.( !empty($prev_post) ? '<li><a href="'.get_permalink( $prev_post->ID ).'"><i class="fa fa-angle-left"></i> '.__('Previous Post', THEME_NAME).'</a></li>' : '' ).'
                                        '.( !empty($next_post) ? '<li><a href="'.get_permalink( $next_post->ID ).'">'.__('Next post', THEME_NAME).' <i class="fa fa-angle-right"></i></a></li>' : '').'
                                    </ul>
                                </div>
                            </div>
                        </div>
                        ';
							
							echo $title_block.$meta_block.$content_block.$postnav_links;
							
						}
					}
					?>
				</div>

                <div class="col-sm-5 col-md-4 hidden-xs category-sidebar">
                    <?php if ( is_active_sidebar( 'blogsidebar' ) ) { dynamic_sidebar( 'blogsidebar' ); } ?>
                </div>

			</div>

		</div>

	</div>


<?php get_footer(); ?>