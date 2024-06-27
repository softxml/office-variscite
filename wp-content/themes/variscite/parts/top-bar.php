<?php
$logo     = get_field('optage_company_logo', 'option');
$logoAlt  = get_field('optage_company_sname', 'option');
$logoTitle  = get_field('optage_logo_title', 'option');
$url = get_option('siteurl');

// LOGO TITLE
if($logoTitle) {$logoTitle = 'title="'.$logoTitle.'"';} else {$logoTitle = '';}

echo '
        <script type="application/ld+json">
        { 
            "@context" : "https://schema.org", 
            "@type" : "LocalBusiness",
            "image": "' . $logo . '",
            "name" : "Variscite",
            "url" : "' . $url . '",
            "telephone" : "T: +972 (9) 9562910", 
            "email" : "sales@variscite.com", 
            "address" : 
                { 
                    "@type" : "PostalAddress", 
                    "streetAddress" : "7222 Hidden Valley Cove S, Cottage Grove, MN 55016, United States" 
                }
        }
        </script>
        
        ';
?>

<div id="desktopMenuWrap" class="top-menu-box">
	<nav class="navbar navbar-top" role="navigation">
		<div class="container-fluid navbar-top-container">
            <div class="navbar-header">
                <a href="/" class="logo" content="/"><img src="<?php echo $logo; ?>" alt="<?php echo $logoAlt; ?>" <?php echo $logoTitle; ?> ></a>
            </div>
            <?php wp_nav_menu( array( 'theme_location' => 'topmenu' ) ); ?>
		</div>
	</nav>
</div>

<div id="mobileMenuWrap">
	<nav class="navbar navbar-default navbar-fixed-bottom" role="navigation">
		<div class="container-fluid">
			
			
			<?php
			wp_nav_menu( array(
					'menu'              => 'topmenumobile',
					'theme_location'    => 'topmenumobile',
					'depth'             => 2,
					'container'         => 'div',
					'container_class'   => 'collapse navbar-collapse',
					'container_id'      => 'bs-topmenumobile',
					'menu_class'        => 'nav navbar-nav',
					'fallback_cb'       => 'wp_bootstrap_navwalker::fallback',
					'walker'            => new wp_bootstrap_navwalker())
			);
			?>

			<div class="navbar-header">

				<button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-topmenumobile">
					<span class="sr-only">Toggle navigation</span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>

				<a href="https://shop.variscite.com/" class="storelink pull-right" style="cursor: pointer;"><?php _e('Shop', THEME_NAME); ?></a>

                <div class="language-switcher bottom-language-switcher">
                    <?php echo custom_wpml_lang_switcher(); ?>
                </div>

				<a href="<?php echo bloginfo('wpurl'); ?>" class="c1"><img src="<?php echo $logo; ?>" alt="<?php echo $logoAlt; ?>" <?php echo $logoTitle; ?> ></a>
			</div>

		</div>
	</nav>
	<div class="clearfix"></div>
</div>
