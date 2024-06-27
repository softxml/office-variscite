<?php

add_action( 'wp_print_styles', 'deregister_styles', 100 );
function deregister_styles() {
    if( !is_user_logged_in() )
        wp_deregister_style('dashicons');
}

function sg_theme_js() {
    $ver = '1.2.3';
	wp_enqueue_style('bootstrapcss', 'https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css', array(), '3.3.6', 'all');
//	wp_enqueue_style('fontawesome', 'https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css', array());
    wp_enqueue_style('fontawesome', '//use.fontawesome.com/releases/v6.4.2/css/all.css', array(), 'v6.4.2' );


//	if( is_singular('specs') ) {
		wp_enqueue_style('swiperCss', 'https://unpkg.com/swiper/swiper-bundle.min.css', array());
//	}

    wp_enqueue_style('RobotoFont', 'https://fonts.googleapis.com/css?family=Roboto:300,400,500,700', array());
	wp_enqueue_style('maincss', BASE_URL . '/css/main-new.css', array(), $ver);

    // SEARCH PAGE CSS
    if( is_search() ) {
        wp_enqueue_style('search', BASE_URL . '/css/pages/search.css', array());
    }

   
    // PRODUCT PAGE CSS
    if( is_singular('specs') && is_page_template( 'custom-pages/page-specs-new.php' ) ) {
        wp_enqueue_style('specs-new', BASE_URL . '/css/pages/specs-new.css', array());
		wp_enqueue_style('magnific-popup', BASE_URL . '/css/magnific-popup.css', array());
		wp_enqueue_script('magnific-popup-script', BASE_URL.'/js/jquery.magnific-popup.min.js', array('jquery'), false, true);
        // wp_enqueue_style( 'intlTelInput-css', get_template_directory_uri() . '/css/intlTelInput.min.css' );
		// wp_enqueue_script( 'intlTelInput-js', get_template_directory_uri() . '/js/intlTelInput-jquery.min.js', array(), '1.0.0', true );
    }
	if( is_singular('specs') && !is_page_template( 'custom-pages/page-specs-new.php' ) ) {
        wp_enqueue_style('specs', BASE_URL . '/css/pages/specs.css', array());
	}
	wp_enqueue_style( 'sumoselect-css', get_template_directory_uri() . '/css/sumoselect.css' );
	wp_enqueue_script( 'sumoselect-js', get_template_directory_uri() . '/js/jquery.sumoselect.js', array(), '1.0.0', true );

    // BLOG AND BLOG POST CSS
    if( is_singular('post') || is_category() ) {
        wp_enqueue_style('blog', BASE_URL . '/css/pages/blog.css', array(), $ver);
    }

    // PRODUCT CATEGORY CSS
    if( is_tax( 'products' ) ) {
        wp_enqueue_style('filter', BASE_URL . '/css/pages/filter-page.css', array());
    }

	// COMPARE PAGE CSS
	if( is_page_template('custom-pages/page-compare.php') ) {
		wp_enqueue_style('datatablesCss', 'https://cdn.datatables.net/v/dt/dt-1.10.16/fc-3.2.4/fh-3.1.3/datatables.min.css', array());
	}

	// FILTER PAGE CSS
	$cat = get_queried_object();
	if ($cat->term_id == 43 || $cat->term_id == 65 || $cat->term_id == 99) {
		if(is_user_logged_in()) {
			wp_enqueue_style('iziToastCss', BASE_URL . '/js/iziToast/iziToast.min.css', array());
		}
	}


    wp_enqueue_script('js-cookie', BASE_URL.'/js/js.cookie.min.js', array(), false, false);

	// FILTER PAGE
	if ($cat->term_id == 43 || $cat->term_id == 65 || $cat->term_id == 99) {
		wp_enqueue_script('nouislider', BASE_URL.'/js/nouislider.min.js', array(), false, false);

		if(is_user_logged_in()) {
			wp_enqueue_script('iziToastJs', BASE_URL.'/js/iziToast/iziToast.min.js', array(), false, false);
		}
	}


	wp_enqueue_script('bootstrap', 'https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js', array('jquery'), '3.3.6', true);
	// wp_enqueue_script('miscscripts', BASE_URL.'/js/main.min.js?v='.time(), array('jquery'), false, true);

    if(!isset($_GET['elementor-preview'])){
        wp_enqueue_script('miscscripts', BASE_URL.'/js/main.js', array('jquery'), false, true);
    }

    wp_enqueue_script('custom-scripts', BASE_URL.'/js/custom-scripts.js', array('jquery'), false, true);

	if( is_singular('products') || is_singular('specs') ) {
//		wp_enqueue_script('swiperJs', 'https://unpkg.com/swiper/swiper-bundle.min.js', array('jquery'), false, true);
        wp_enqueue_script('diagram-tabs', BASE_URL.'/js/diagram-tabs.js', array('jquery'), false, true);
	}

    // SINGLE SPECS PAGE // HOME
    if( is_singular('specs') || is_home() || is_front_page() ) {
        wp_enqueue_script('touchSwipe', 'https://cdnjs.cloudflare.com/ajax/libs/jquery.touchswipe/1.6.4/jquery.touchSwipe.min.js', array('jquery'), false, true);
    }

	// COMPARE PAGE JS
	if( is_page_template('custom-pages/page-compare.php') ) {
		wp_enqueue_script('xlsx-core', BASE_URL.'/js/xlsx.core.min.js', array('jquery'), false, true);
		wp_enqueue_script('fileSaver', BASE_URL.'/js/FileSaver.min.js', array('jquery'), false, true);
		wp_enqueue_script('tableexport', BASE_URL.'/js/tableexport.min.js', array('jquery', 'fileSaver'), false, true);
        wp_enqueue_script('touchSwipe', BASE_URL.'/js/jquery.touchSwipe.min.js', array('jquery'), false, true);
	}

    // FILTER PAGE JS
    global $wp;
    if (strpos(home_url( $wp->request ), 'products/system-on-module-som') !== false ) {
        wp_enqueue_script( 'filterFuncJs', BASE_URL . '/js/filter-functions.js', array( 'jquery' ), false, true );
    }
	
	// TAXONOMY PRODUCTS FILTERS
	$term = get_queried_object();
	if(get_field('prod_specs_enable_filters', $term)) {
		wp_enqueue_script('specs_filters_ajax', BASE_URL.'/ajax/specs-filters-ajax.js', array('jquery'), false, true);
	}
	
    wp_enqueue_script('langOpen', BASE_URL.'/js/lang-open.js', array('jquery'), false, true);


    // PAGE SPECIFIC CSS AND JS (controllable via ACF)
    $css = get_field('vrs_page_scripts_css');
    $js  = get_field('vrs_page_scripts_js');

    if(! empty($css)) {
        $css_files = explode(' ', $css);

        foreach($css_files as $file) {
            wp_enqueue_style($file, trailingslashit(BASE_URL) . 'css/pages/' . $file . '.css');
        }
    }

    if(! empty($js)) {
        $js_files = explode(' ', $js);

        foreach($js_files as $file) {
            wp_enqueue_script($file, trailingslashit(BASE_URL) . 'js/pages/' . $file . '.js', array(), '', true);
        }
    }

    wp_enqueue_script('swiperJs', 'https://unpkg.com/swiper/swiper-bundle.min.js', array('jquery'), false, true);
    wp_enqueue_script('diagram-tabs', BASE_URL.'/js/diagram-tabs.js', array('jquery'), false, true);

    $dynamic_css = get_dynamic_css();

    if(!empty($dynamic_css)) {
        wp_add_inline_style( 'specs-new', $dynamic_css );
    }
}
add_action('wp_enqueue_scripts', 'sg_theme_js');

function wpdocs_selectively_enqueue_admin_script( $hook ) {

    if ( 'post.php' != $hook ) {
        return;
    }
    wp_deregister_script( 'jquery-migrate' );
    wp_register_script( 'jquery-migrate', "https://code.jquery.com/jquery-migrate-1.4.1.js", array(), '1.4.1' );

}
add_action( 'admin_enqueue_scripts', 'wpdocs_selectively_enqueue_admin_script' );

function get_dynamic_css() {
    $dynamic_css = '';
    $slider_option  = get_field('slider_option');
    $single_image_with_chip = get_field('single_image_with_chip');
    $mobile_single_image_with_chip = get_field('mobile_single_image_with_chip');

    if(!empty($single_image_with_chip) && $slider_option != 'carousel') {
        $dynamic_css .= "
		.top-slider .bg-image {
			background-image: url({$single_image_with_chip});
		}";
    }

    if(!empty($mobile_single_image_with_chip) && $slider_option != 'carousel') {
        $dynamic_css .= "@media only screen and (max-width: 1140px) {
			.top-slider .bg-image {
				background-image: url({$mobile_single_image_with_chip});
			};
		}";
    }

    if(!is_page_template( 'custom-pages/page-specs-new.php' )) {
    
        $optage_defaults_specs_page = get_field('optage_defaults_specs_page','option');

        if(!empty($optage_defaults_specs_page) && $slider_option == 'carousel' ) {
            $dynamic_css .= "
            .top-slider {
                background-image: url({$optage_defaults_specs_page});
            }";
        }
    }
    return $dynamic_css;
}
?>