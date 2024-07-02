<?php
defined('ABSPATH') || exit;

$is_block_theme = wp_is_block_theme();
$header_id = wopb_function()->conditions( 'header' );
$footer_id = wopb_function()->conditions( 'footer' );

if ( $is_block_theme ) {
	wp_site_icon();
	wp_head();
	if ( !$header_id ) {
        block_template_part( 'header' );
		wp_head();
    }
} else {
	get_header();
}

do_action( 'wopb_before_content' );

$width = wopb_function()->get_setting( 'container_width' );
$width = $width ? $width : '1140';
?>
<div class="wopb-template-container" style="margin:0 auto; max-width:<?php echo esc_attr($width); ?>px; padding: 0 15px; width: -webkit-fill-available; width: -moz-available;">
	<?php
		while ( have_posts() ) : the_post();
			the_content();

			if (comments_open() || get_comments_number() ) {
				comments_template();
			}
		endwhile;
	?>
</div>
<?php

do_action( 'wopb_after_content' );

if ( wp_is_block_theme() ) {
    wp_footer();
	if ( !$footer_id ) {
        block_template_part( 'footer' );
    }
} else {
	get_footer();
}