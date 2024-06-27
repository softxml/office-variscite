<!DOCTYPE HTML>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="shortcut icon" href="<?php the_field('optage_fav_icon', 'option'); ?>" />
<!-- 	    <script src="https://cdn.lr-ingest.com/LogRocket.min.js" crossorigin="anonymous"></script>
    <script>window.LogRocket && window.LogRocket.init('ayccbd/wordpress-site');</script> -->
	
	<!--=== WP HEAD ===-->
	<?php wp_head(); ?>
	<!--=== WP HEAD ===-->

    <!--=== CUSTOM LOAD TIME CALCULATION ===-->
    <script type="text/javascript">
        var timerStart = Date.now();
    </script>

	<?php
	$pageHeaderScripts = get_field('common_header_scripts');
	if($pageHeaderScripts) { echo $pageHeaderScripts; }
	?>
	<!--=== CUSTOM HEADER SCRIPTS ===-->
	<?php
	$headerScripts = get_field('optage_header_scripts', 'option');
    if(! is_user_logged_in()){
        if($headerScripts) { echo $headerScripts; }
    }

	?>
	<!--=== CUSTOM HEADER SCRIPTS ===-->

	<script>
        var getLangCode = '<?php echo apply_filters( 'wpml_current_language', NULL );  ?>';
	</script>
</head>
<body <?php body_class(); ?>>

    <!--=== CUSTOM LOAD TIME CALCULATION ===-->
    <script type="text/javascript">
        $(document).ready(function() {
            console.log("Time until DOMready (GTM fires): ms", Date.now()-timerStart);
        });
        $(window).load(function() {
            console.log("Time until everything on page loaded: ms", Date.now()-timerStart);
        });
    </script>

	<!--=== CUSTOM AFTER BODY SCRIPTS ===-->
	<?php
	$headerScripts = get_field('optage_aftbody_scripts', 'option');
    if(! is_user_logged_in()){
        if($headerScripts) { echo $headerScripts; }
    }

	?>
	<!--=== CUSTOM HEAAFTER BODYDER SCRIPTS ===-->


	<!--=====STR========== TOP MENU =======================-->
	<?php include(THEME_PATH.'/parts/top-bar.php'); ?>
	<!--=====END========== TOP MENU =======================-->

	<div id="top" class="body-container body-container--new">


	