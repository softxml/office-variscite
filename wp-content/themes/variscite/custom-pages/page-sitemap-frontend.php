<?php
/*
Template Name: Visual Sitemap
*/
get_header();
?>

	<div class="container relative hidden-xs">
		<div class="page-breadcrumbs"><?php echo breadcrumbs(); ?></div>
	</div>

		<?php
		if ( have_posts() ) {
			while ( have_posts() ) { 
				the_post();

				// COLLECT PAGE DATA
				$pid 			= get_the_ID();
				$content		= get_the_content();
				$post_title     = get_the_title();
				$show_title     = get_field('pp_page_showtitle', $pid);
				$no_topmarg     = get_field('pp_page_notopmarg', $pid);
				$items 			= get_field('page_settings', $pid);
				$col2items		= get_field('page_settings_col2', $pid);
				$settings		= get_field('basic_settings', $pid);

				if($show_title[0] == 'on') { $pageTitle = '<h1 class="ppage-title">'.$post_title.'</h1>'; } else { if($no_topmarg[0] != 'yes') { $pageTitle = '<div class="notitle-spacer"></div>'; } }


				$list = '';
				foreach($items as $item){

					$type 	= $item['ptype'];
					$ctitle = $item['citem_title'];
					$clink 	= $item['custom_link'];
					$child	= $item['item_children'];
					$clevel	= ( $item['list_level'] ? $item['list_level'] : '1');
					$class	= $item['custom_class'];

					// POST or PAGE
					if($type == 'post') {

						// item data
						$pp['id'] 		= $item['picked_post']->ID;
						$pp['title'] 	= ( $ctitle ? $ctitle : $item['picked_post']->post_title );
						$pp['link'] 	= ( $clink ? $clink : get_permalink($pp['id']) );
						$pp['child'] 	= !empty($item->item_children) ? $item->item_children : '';
						$list 			.= '<li data-level="'.$clevel.'" class="'.$class.'" class="'.$class.'"><a href="'.$pp['link'].'">'.$pp['title'].'</a></li>';

						if( !empty($child) && $child[0] == 'on' ) { $list .= sitemap_page_children( $pp['id'],  $settings['children_amount'], ($clevel + 1) ); }

					}

					// PRODUCTS CATEGORY
					elseif($type == 'products_cat' || $type == 'category') {

						// SET TAXONOMY 
						if($type == 'products_cat') { $term_id = $item['pick_products_cat']; $tax = 'products';} 
						else { $term_id = $item['pick_category']; $tax = 'category';}


						// item data
						$term	 		= get_term( $term_id );
						$pp['id']		= $term->term_id;
						$pp['title'] 	= ( $ctitle ? $ctitle : $term->name );
						$pp['link'] 	= ( $clink ? $clink : get_term_link($pp['id']) );

						$list .= '<li data-level="'.$clevel.'" class="'.$class.'"><a href="'.$pp['link'].'">'.$pp['title'].'</a></li>';

						if( !empty($child) && $child[0] == 'on' ) { $list .= sitemap_term_recent_postlist($pp['id'], $tax, $settings['children_amount'], ($clevel + 1) ); }
					}

					elseif( empty($type) && !empty($ctitle) && !empty($clink) ) {
						$list .= '<li data-level="'.$clevel.'" class="'.$class.'"><a href="'.$clink.'">'.$ctitle.'</a></li>'; 
					}
				}

				/*************************************************
				** BUILD SECOND COL DATA
				*************************************************/
				$col2List = '';

				foreach($col2items as $item){

					$type 	= $item['ptype'];
					$ctitle = $item['citem_title'];
					$clink 	= $item['custom_link'];
					$child	= $item['item_children'];
					$clevel	= ( $item['list_level'] ? $item['list_level'] : '1');
					$class	= $item['custom_class'];

					// POST or PAGE
					if($type == 'post') {

						// item data
						$pp['id'] 		= $item['picked_post']->ID;
						$pp['title'] 	= ( $ctitle ? $ctitle : $item['picked_post']->post_title );
						$pp['link'] 	= ( $clink ? $clink : get_permalink($pp['id']) );
						$pp['child'] 	= !empty($item->item_children) ? $item->item_children : '';
						$col2List 			.= '<li data-level="'.$clevel.'" class="'.$class.'"><a href="'.$pp['link'].'">'.$pp['title'].'</a></li>';

						if( !empty($child) && $child[0] == 'on' ) { $list .= sitemap_page_children( $pp['id'],  $settings['children_amount'], ($clevel + 1) ); }

					}

					// PRODUCTS CATEGORY
					elseif($type == 'products_cat' || $type == 'category') {

						// SET TAXONOMY 
						if($type == 'products_cat') { $term_id = $item['pick_products_cat']; $tax = 'products';} 
						else { $term_id = $item['pick_category']; $tax = 'category';}


						// item data
						$term	 		= get_term( $term_id );
						$pp['id']		= $term->term_id;
						$pp['title'] 	= ( $ctitle ? $ctitle : $term->name );
						$pp['link'] 	= ( $clink ? $clink : get_term_link($pp['id']) );

						$col2List 			.= '<li data-level="'.$clevel.'" class="'.$class.'"><a href="'.$pp['link'].'">'.$pp['title'].'</a></li>';

						if( !empty($child) && $child[0] == 'on' ) { $col2List .= sitemap_term_recent_postlist($pp['id'], $tax, $settings['children_amount'], ($clevel + 1) ); }
					}
					elseif( $type == 'Pick Item Type' && !empty($ctitle) && !empty($clink) ) {
						$col2List 			.= '<li data-level="'.$clevel.'" class="'.$class.'"><a href="'.$clink.'">'.$ctitle.'</a></li>'; 
					}
				}

			}
		}
		?>
	<div class="sitemap-content skewed-section">
        <div class="innerWrap">
            <div class="container">
                <?php echo $pageTitle; ?>
            </div>
        </div>
	</div>
	<div class="container sitemap-pagelist">
		<div class="row">
			<div class="col-md-6">
				<?php echo '<ul class="sitemap-list">'.$list.'</ul>'; ?>
			</div>
			<div class="col-md-6">
				<?php echo '<ul class="sitemap-list">'.$col2List.'</ul>'; ?>
			</div>
		</div>

	</div>

	<?php
	if(get_field('hide_side_strip', get_the_ID()) != 'on') {
		include(THEME_PATH.'/parts/side-strip.php');
	}
	?>

<?php get_footer(); ?>