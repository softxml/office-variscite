<?php
get_header();


// COLLECT DATA
$searchQuery    = get_query_var( 's' );
$resultsCount   = 0;
$results        = array();


/*************************************************
** QUERY PRODUCTS
*************************************************/
$counter        = 0;
$args           = array( 'post_type' => 'specs', 'posts_per_page' => -1, 's' => $searchQuery );
$productsQuery  = new WP_Query( $args );
$resultsCount   = $resultsCount + $productsQuery->found_posts;
// The Loop
if ( $productsQuery->have_posts() ) {
	while ( $productsQuery->have_posts() ) {
        $productsQuery->the_post();
        $results['products'][] = search_build_product(get_the_ID(), $counter);

        $counter++;
	}
    wp_reset_postdata();


    if($productsQuery->found_posts > 6) {
        $results['products'][] = '<button class="toggleVisible btn btn-warning" data-type="#section-products .hidden-result">'.__('Load More', THEME_NAME).'</button>';
    }
}
wp_reset_query();



/*************************************************
** QUERY POSTS AND PAGES (NOT UNDER COMPANY CAT)
*************************************************/
$counter = 0;
$args = array(
    'post_type' => array('post'),
    'posts_per_page' => -1,
    'tax_query' => array(
        array(
            'taxonomy' => 'category',
            'field'    => 'slug',
            'terms'    => array( 'blog'),
            'operator' => 'NOT IN',
        )
     ),
    's' => $searchQuery
);
$ppQuery        = new WP_Query( $args );
$resultsCount   = $resultsCount + $ppQuery->found_posts;

// The Loop
if ( $ppQuery->have_posts() ) {
	while ( $ppQuery->have_posts() ) {
        $ppQuery->the_post();

        // POST DATA
        $ptitle     = get_the_title();
        $pxcerpt    = content_to_excerpt(get_the_content(), 315);
        $plink      = get_permalink();

        $results['all'][] = '
        <div class="search-post-box relative '.($counter > 2 ? 'hidden-result dnone' : 'visible-result').'">
            <h3>'.$ptitle.'</h3>
            <p>'.$pxcerpt.'</p>
            <a href="'.$plink.'" class="full-link"></a>
        </div>
        ';

        $counter++;
	}
    wp_reset_postdata();

    $results['all'][] = '<button class="toggleVisible btn btn-warning" data-type="#section-all .hidden-result">'.__('Load More', THEME_NAME).'</button>';
}
wp_reset_query();



/*************************************************
** QUERY ONLY THE BLOG
*************************************************/
$counter = 0;
$args = array(
    'post_type' => array('post'),
    'posts_per_page' => -1,
    'tax_query' => array(
        array(
            'taxonomy' => 'category',
            'field'    => 'slug',
            'terms'    => array( 'blog'),
            'operator' => '=',
        )
     ),
    's' => $searchQuery
);
$blogQuery      = new WP_Query( $args );
$resultsCount   = $resultsCount + $blogQuery->found_posts;

if ( $blogQuery->have_posts() ) {
	while ( $blogQuery->have_posts() ) {
        $blogQuery->the_post();

        // POST DATA
        $ptitle     = get_the_title();
        $pxcerpt    = content_to_excerpt(get_the_content(), 315);
        $plink      = get_permalink();

        $results['blog'][] = '
        <div class="search-post-box relative '.($counter > 2 ? 'hidden-result dnone' : 'visible-result').'">
            <h3>'.$ptitle.'</h3>
            <p>'.$pxcerpt.'</p>
            <a href="'.$plink.'" class="full-link"></a>
        </div>
        ';

        $counter++;
	}
    wp_reset_postdata();

    $results['blog'][] = '<button class="toggleVisible btn btn-warning" data-type="#section-blog .hidden-result">'.__('Load More', THEME_NAME).'</button>';

}
wp_reset_query();

$searchQuery = htmlspecialchars($searchQuery, ENT_QUOTES);
?>

<div class="search-page-wrap">

    <input type="hidden" id="search_query" value="<?php echo $searchQuery; ?>">

    <div class="search-box">
        <div class="container">
            <form role="search" method="get" class="search-form form-horizontal" action="/">
                <div class="row">
                    <div class="col-xs-9 col-md-11"><input type="search" class="search-field form-control input-lg" placeholder="<?php _e( 'Search:', THEME_NAME ) ?>" value="<?php echo $searchQuery; ?>" name="s" /></div>
                    <div class="col-xs-3 col-md-1"><input type="submit" class="search-submit btn btn-warning btn-lg" value="<?php _e( 'GO', THEME_NAME ) ?>" /></div>
                </div>
            </form>

            <h1 class="page-title"><?php echo $resultsCount.' '.__('Results for', THEME_NAME) .' <span class="upcase">“'.$searchQuery.'”</span>'; ?> </h1>
        </div>
    </div>


    <div class="search-results">
        <div class="container">
        <?php
        foreach($results as $key => $posts) {
            echo '
            <h2 class="search-section-title">'.ucfirst($key).'</h2>
            <div id="section-'.$key.'" class="section">
                '.implode('', $posts).'
            </div>
            ';
        }
        ?>
        </div>
    </div>



    <?php
	if(get_field('hide_side_strip', get_the_ID()) != 'on') {
		include(THEME_PATH.'/parts/side-strip.php');
    }
    ?>



</div>

<script>
jQuery(document).ready(function($) {

    // WRAP SEARCH QUERY STRING FOR HIGHLIGHT
    // var wrap    = $('.search-results');
    // var oldhtml = $('.search-results').html();
    // var newhtml = oldhtml.replace(/<?php //echo $searchQuery; ?>/g, '<span class="highlight"><?php //echo $searchQuery; ?></span>');
    // $('.search-results').html(newhtml);


    $('.toggleVisible').click(function(){
        var toggle_this = $(this).attr('data-type');
        $(toggle_this).toggleClass('dnone');

        if( $(this).text() == 'Load More' ) { $(this).text('Show Less'); }
        else { $(this).text('Load More'); }
    })


});
</script>

<?php get_footer(); ?>