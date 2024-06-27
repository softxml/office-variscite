<?php
/*
Template Name: Specs New
Template Post Type: specs
*/
get_header();

$fields = get_field_objects(get_the_ID());
?>

<?php
if ( have_posts() ) {
	while ( have_posts() ) {
		the_post();

		$pid 				= get_the_ID();
		$ptitle				= get_the_title();
		$thumburl			= get_the_post_thumbnail_url( $pid, 'full' );
		$pterms				= wp_get_post_terms($pid, 'products');
		$ptermId			= $pterms[0]->term_id;
		$post_meta 			= get_post_meta($pid);

		$kit_check			= get_field('vrs_specs_evaluation_kit', $pid);
		$isit_kit			= ( !empty($kit_check[0]) && $kit_check[0] == 'evkit' ? true : false );

		$store_inlink		= get_field('vrs_specs_instore_product', $pid);
		$store_exlink		= get_field('vrs_specs_exstore_product', $pid);
		$button_text		= get_field('vrs_specs_button_text', $pid);

		$hide_compare 		= get_field('hide_compare_link', $pterms[0]);
		$local_compliance 	= get_field('global_compliance_icons', $pid);
        $slider_option      = get_field('slider_option');
        $single_image_with_chip = get_field('single_image_with_chip');
        $imgalttext         = get_field('imgalttext');
        $imageversion         = get_field('hero-image-version');
        
		?>
		<div class="specs-page <?php if( !empty(get_field('vrs_specs_store_product')) ) {echo 'cart-product';} else {echo 'quote-product';} ?>">
			<div class="inner">

                <!--===STR=============== GENERAL INFO =====================-->
                <div id="general-info" class="section-box sidebar-push">
                    <?php //specs_topbgimg_style(get_field('vrs_specs_headimg')); ?>

                    <div class="top-slider<?php echo ($slider_option == 'carousel')?' top-slider-white':'';?>">
                        <div class="container new-spec-page">
                            <?php 
                            $popup_form_button_label = get_field('popup_form_button_label');
                            
                            
                            if(empty($popup_form_button_label)) {
                                $popup_form_button_label = get_field('popup_form_button_label','option');
                            } ?>
                            <a class="btn btn-link btn-lg js-link-popup-form" href="#contact-form-popup">
                                <div class="popup-btn"><?php echo $popup_form_button_label; ?></div>
                                <div class="popup-btn-icon"></div>
                            </a>
                            <div class="row hero-content-wrapper">
                                <div class="hero-content hero-left-col">
                                    <div class="breadcrumbs hideInMobile"><?php echo breadcrumbs(); ?></div>
                                    <h1 class="page-title"><?php echo specs_new_page_title(get_the_title()); ?></h1>
                                    <?php if(($full_support_default_text = get_field('full_support_default_text','option')) && !empty($full_support_default_text)): ?>
                                        <div class="support"><?php echo $full_support_default_text; ?></div>
                                    <?php endif; 
                                    
                                    if($slider_option != 'with_chip' ) : ?>
                                        <div class="mobile-img">
                                            <?php echo specs_product_slider_mobile( $pid ); ?>
                                        </div>
                                    <?php endif; 

                                    $global_header_text = get_field('global_header_text');
                                    
                                    if(have_rows('header_image_text') && (is_array($global_header_text) && in_array('on', $global_header_text))): ?>
                                        <ul class="list-item">
                                            <?php while(have_rows('header_image_text')): 
                                                the_row();
                                                $list_text = get_sub_field('list_text');
                                                $text_color = get_sub_field('text_color'); 
                                                
                                                if(!empty($list_text)): ?>
                                                    <li><?php echo $list_text; ?></li>
                                                <?php endif; 
                                            endwhile; 
                                            
                                            if( !empty(get_field('vrs_specs_price')) ) : ?>
                                                <li><?php  echo '<div class="price-block">'.get_field('vrs_specs_price').'</div>'; ?></li>            
                                            <?php endif; ?>
                                        </ul>
                                    <?php else: ?> 
                                        <?php if(have_rows('default_header_image_text','option')): ?>
                                            <ul class="list-item">
                                                <?php while(have_rows('default_header_image_text','option')): 
                                                    the_row();
                                                    $list_text = get_sub_field('list_text');
                                                    $text_color = get_sub_field('text_color'); 
                                                    
                                                    if(!empty($list_text)): ?>
                                                <li><?php echo $list_text; ?></li>
                                                        <?php endif; 
                                                    endwhile; 
                                                    
                                                if( !empty(get_field('vrs_specs_price')) ) :  ?>
                                                    <li><?php  echo '<div class="price-block">'.get_field('vrs_specs_price').'</div>'; ?></li>            
                                                <?php endif; ?>
                                            </ul>
                                        <?php endif; ?>
                                    <?php endif; 
                                    
                                    if(($mobile_single_image_with_chip = get_field('mobile_single_image_with_chip')) && !empty($mobile_single_image_with_chip)): ?>
                                        <div class="hero-image-mobile">
                                            <img src="<?php echo $mobile_single_image_with_chip; ?>" alt="<?php echo $imgalttext ?>"/> 
                                        </div>
                                    <?php endif; ?>

                                    
                                    <?php
                                    if(!empty($local_compliance)) :

                                        if(have_rows('vrs_specs_compliance_icons')): ?>        
                                            <div class="compliance-icons">
                                                <?php while(have_rows('vrs_specs_compliance_icons')): the_row(); 
                                                    $compliance_icon = get_sub_field('compliance_icon'); ?>        
                                                    <div class="icon-item"><?php echo wp_get_attachment_image($compliance_icon['ID'], 'full', false); ?></div>
                                                <?php endwhile; ?>
                                            </div>
                                        <?php endif; 
                                    else:
                                        if(have_rows('product_compliance_icons','option')): ?>        
                                            <div class="compliance-icons">
                                                <?php while(have_rows('product_compliance_icons','option')): the_row(); 
                                                    $compliance_icon = get_sub_field('compliance_icon');
                                                    $compliance_icon_alt = get_sub_field('compliance_icon_alt'); ?>        
                                                    
                                                    <?php if(!empty($compliance_icon)): ?>
                                                        <div class="icon-item">
                                                            <img src="<?php echo $compliance_icon; ?>" alt="<?php echo $compliance_icon_alt; ?>">
                                                        </div>
                                                    <?php endif; ?>
                                                <?php endwhile; ?>
                                            </div>
                                        <?php endif;
                                    endif; ?>
                                </div>
                                
                            </div>
                            <div class="hero-right-col">
                                    <div class="hero-product-image <?php echo $imageversion; ?>">
                                        <img src="<?php echo $single_image_with_chip; ?>" alt="<?php echo $imgalttext; ?>">
                                    </div>  
                            </div>
                        </div>
                    </div>
                    <div class="contact-form-popup" id="contact-form-popup">
                        <div class="contact-form-content">
                            <?php echo do_shortcode('[contact_form link_label="contact form"]');?>
                        </div>
                    </div>
 
                    <?php if(get_field('vrs_specs_product_middesc')) { ?>
                    <?php } ?>
                </div>
                
                <!--===END=============== GENERAL INFO =====================-->
                <?php 
                $count_testimonial = wp_count_posts( $post_type = 'testimonial' ); 
                $partners_image_desktop = get_field('partners_image','option');
                $partners_image_mobile = get_field('partners_image_mobile','option');

                if($count_testimonial > 0 && (!empty($partners_image_desktop) || !empty($partners_image_mobile))): ?>
                    <!--===STR=============== Testimonial Slider =====================-->                        
                    <div class="customer-say section-box sidebar-push" id="customer-say">
                        <div class="container new-spec-page ">
                            <div class="row">
                                <?php if($count_testimonial > 0): ?>
                                    <div class="col-md-6">
                                        <?php echo do_shortcode('[testimonial_slider]'); ?>
                                    </div>
                                <?php endif; 
                                
                                if(!empty($partners_image_desktop) || !empty($partners_image_mobile)): ?>
                                    <div class="col-md-6 partners-col">
                                        <?php if(($partners_title = get_field('partners_title','option')) && !empty($partners_title)): ?>
                                            <h2><?php echo $partners_title; ?></h2>
                                        <?php endif; 
                                         
                                        if(!empty($partners_image_desktop)): ?>
                                            <?php echo wp_get_attachment_image($partners_image_desktop,'full', false, ['class' => 'desktop-img']); ?>
                                        <?php endif; 
                                        
                                        if(!empty($partners_image_mobile)): ?>
                                            <?php echo wp_get_attachment_image($partners_image_mobile,'full', false, ['class' => 'mobile-img']); ?>
                                        <?php endif; ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>                        
                    <!--===END=============== Testimonial Slider =====================-->
                <?php endif; ?>
                <!--===STR=============== Highlight =====================-->                        
                <?php echo get_highlight_tab_content(get_field('vrs_specs_spec_tabs'), get_the_ID()); ?>
                <!--===END=============== Highlight =====================-->
                
                <!--===STR=============== NOTICES =====================-->                        
                <?php if(have_rows('notices','option')): ?>
                    <div class="notices diagonal-cut section-box sidebar-push" id="notices">
                        <div class="container new-spec-page">
                            <div class="row">
                                <?php while(have_rows('notices','option')): the_row(); 
                                $icon_image = get_sub_field('icon_image');
                                $title = get_sub_field('title');
                                $content = get_sub_field('content'); ?>
                                <div class="col-md-3">
                                    <div class="notice-item">
                                        <?php echo wp_get_attachment_image($icon_image, 'full', false); ?>
                                        
                                        <?php if(!empty($title)): ?>
                                            <div class="item-title"><?php echo $title; ?></div>
                                        <?php endif; 
                                        
                                        echo $content; ?>
                                    </div>
                                </div>
                                <?php endwhile; ?>

                            </div>
                            <!-- <div class="text-center section-btn">
                                    <button class="btn btn-warning btn-lg js-custom-scroll " data-to="get-a-quote"><span class="text"><?php _e('Get a Quote'); ?></span> <img src="<?php echo get_template_directory_uri(); ?>/images/button-arrow.png" alt="arrow"></button>
                                </div> -->
                        </div>
                    </div>                                    
                <?php endif; ?>
                <!--===END=============== NOTICES =====================-->

                <?php if(have_rows('vrs_specs_spec_tabs')): ?>
                <!--===STR=============== SPECIFICATION =====================-->
                <div id="specification" class="section-box sidebar-push diagonal-cut <?php if(!get_field('vrs_specs_product_middesc')) { echo 'noDescProd';} ?>  <?php if(empty($accessoriesArr[0]['accessory']) && empty(get_field('vrs_specs_doc_block')) && empty(get_field('vrs_specs_relevant_mid_product')) ) {echo 'plus2-sections-spacing';} ?>">
                    <div class="innerWrap">
                        <div class="container new-spec-page">
                            <div class="row flex">
                                <div class="col-md-9">
                                    <h2 class="section-title"> <?php if( !empty(get_field('vrs_specs_spec_title')) ) {the_field('vrs_specs_spec_title'); } else { _e('Specification & Documentation', THEME_NAME);} ?> </h2>
                                </div>
                                <div class="col-md-3 specs-compare-btn desktop-compare-btn">
                                    <?php if( empty($hide_compare[0]) || $hide_compare[0] != 'on') { ?>
                                       <button class="btn btn-link specsComapre btn-lg" value="<?php echo $pid; ?>"><?php _e('Compare to other products', THEME_NAME); ?></button>
                                       <a href="<?php echo get_permalink( get_field('optage_defaultpages_compare', 'option') ); ?>" id="productsCompare" class="dnone"></a>
                                    <?php } ?>
                                </div>
                            </div>
                        </div>
                        <?php echo specs_specification_tabs_new_layout( get_field('vrs_specs_spec_tabs'), $pid ); ?>

                        <?php
                        if($isit_kit) {
                            if( !empty($store_inlink) || !empty($store_exlink)) {

                                echo '
                                <div class="buykit-bar">
                                    <div class="inner">
                                        <div class="row">
                                            <div class="col-sm-6 col-xs-12 cta-text">
                                                <span>' . __('Order your Evaluation Kit', THEME_NAME) . '</span>
                                            </div>
                                            <div class="col-sm-6 col-xs-12">
                                                <div class="btn-wrap">
                                                <a href="'.( !empty($store_exlink) ? $store_exlink : get_permalink(get_field('vrs_specs_instore_product')) ).'" '.( !empty($store_exlink) ? 'target="_blank"' : '').' class="btn btn-default"><span>' . __('Add to cart', THEME_NAME) . '</span></a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                ';
                            }
                        }
                        ?>
                        <div class="clearfix"></div>
                    </div>
				</div>
                <?php endif;
                
                if( !empty(get_field('vrs_specs_doc_block')) ) : ?>

                <div class="specs-compare-btn mobile-compare-btn">
                    <?php if( empty($hide_compare[0]) || $hide_compare[0] != 'on') { ?>
                        <button class="btn btn-link specsComapre btn-lg" value="<?php echo $pid; ?>"><?php _e('Compare to other products', THEME_NAME); ?></button>
                        <a href="<?php echo get_permalink( get_field('optage_defaultpages_compare', 'option') ); ?>" id="productsCompare" class="dnone"></a>
                    <?php } ?>
                </div>
                    <!--===STR=============== DOCUMENTATION =====================-->
                    <div id="documentation" class="section-box sidebar-push">
                        <div class="document-wrap">
                            <h2 class="section-title"><?php echo get_field('vrs_specs_doc_section_title', $pid); ?></h2>
                            <div class="document-inner">
                                <?php if( !empty(get_field('vrs_specs_doc_block')) ) { ?>
                                    <div id="documentation-content" class="section-box doc-box-section">
                                        <div class="container">
                                            <?php echo spec_docs_boxes( get_field('vrs_specs_doc_block'), $pid ); ?>
                                        </div>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                    <!--===END=============== DOCUMENTATION =====================-->
                <?php endif; ?>
                <!--===END=============== SPECIFICATION =====================-->

                <?php if(!empty(get_field('vrs_specs_quote_product_addons'))): 
                     $vrs_specs_form_type = get_field('vrs_specs_form_type');
                     $vrs_specs_quote_product_addons = get_field('vrs_specs_quote_product_addons'); 
                     
                     $addon = 'som';

                     if($vrs_specs_quote_product_addons == 'optage_specs_quote_som_addons ') {
                        $addon = 'kits';
                     } ?>
                    <!--===STR=============== QUOTE FORM =====================-->
                    <div id="quote-formbox" class="diagonal-cut <?php if(empty($accessoriesArr[0]['accessory']) && empty(get_field('vrs_specs_doc_block')) && empty(get_field('vrs_specs_relevant_mid_product')) ) {echo 'min2-sections-spacing';} ?>   <?php if( empty($accessoriesArr[0]['accessory']) ) {echo 'no-accessories-section';} ?>">
                        <div class="innerWrap">
                            <div id="get-a-quote" class="form-box section-box sidebar-push">
                                <div class="container new-spec-page">
                                    <div class="quote-scroll-section"></div>
                                    <div class="row">
                                        <?php if($vrs_specs_form_type == 'multi-step-form'): ?>
                                            <div class="quote-form-row col-md-12 col-xs-12">
                                                <?php echo do_shortcode('[step_quote_form addon="'.$addon.'"]'); ?>
                                            </div>
                                        <?php else: ?>
                                            <div class="quote-form-row col-md-12 col-xs-12">
                                                <h2 class="section-title"><?php the_field('vrs_specs_quote_title'); ?></h2>
                                                <p class="section-desc"><?php the_field('vrs_specs_quote_desc'); ?></p>
                                                <div class="quote-form">
                                                    <?php echo specs_quoteform_box( get_field('vrs_specs_quote_product_addons'), get_the_ID() ); ?>
                                                </div>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--===END=============== QUOTE FORM =====================-->
                <?php endif; ?>    
					
                <!--===STR=============== ACCESSORIES =====================-->
                <?php
                $accessoriesArr = get_field('vrs_specs_accesories_products');

                if( !empty($accessoriesArr[0]['accessory']) ) {
                    echo '
                    <div id="accessories" class="diagonal-cut section-box">'.specs_accessories_slider( get_field('vrs_specs_accesories_products'), $pid ).'</div>
                    ';
                }
                ?>
                <!--===END=============== ACCESSORIES =====================-->
                             
                <?php $related_products = get_field('vrs_specs_related_products'); 
                
                if(!empty($related_products)) : ?> 
                    <!--===STR=============== RELATED =====================-->
                    <!-- <div id="related" class="section-box">
                        <div class="container-wrap new-spec-page">
                            <?php echo specs_related_products( get_field('vrs_specs_related_products'), $pid ); ?>
                        </div>
                    </div>  -->
                    <!--===END=============== RELATED =====================-->
                <?php endif; ?>
			</div>


			<!--===STR=============== SIDE SCROLL NAV =====================-->
			<div class="sideScrollNav">
				<div class="inner">
					<ul class="lsnone p0 m0">
						<li id="nav-general-info" class="side-scroll" data-to="general-info"><?php _e('General info', THEME_NAME); ?></li>
						<li id="nav-highlights-sec" class="side-scroll" data-to="highlights-sec"><?php _e('Highlights', THEME_NAME); ?></li>

						<li id="nav-specification" class="side-scroll" data-to="specification"><?php _e('Specifications', THEME_NAME); ?></li>
						<?php
						if( !empty(get_field('vrs_specs_doc_block')) ) {echo '<li id="nav-documentation" class="side-scroll" data-to="documentation">'.get_field('vrs_specs_doc_section_title').'</li>';}
						// if( !empty(get_field('vrs_specs_accesories_products')[0]['accessory']) ) {echo '<li id="nav-accessories" class="side-scroll" data-to="accessories">'.get_field('vrs_specs_accesories_title').'</li>';}
						?>
						<li id="nav-get-a-quote" class="side-scroll quote-link" data-to="get-a-quote"><?php the_field('vrs_specs_quote_title'); ?></li>
					</ul>
				</div>
			</div>
			<!--===END=============== SIDE SCROLL NAV =====================-->

	    </div>
	<?php
	}
}
?>


<script>
jQuery(function($) {

	/*************************************************
	** TOGGLE BUTTON STATUS (IN QUOTE FORM)
	*************************************************/
	$('.opsysBtns button').click(function() {
		$(this).toggleClass('active');
		$('.opsysBtns button').not(this).removeClass('active');
	});

    function setDocDefaults() {
        if( $('.doc-box-wrap').length > 0 ) {
            $('.doc-box-collapse').removeClass('open').addClass('closed');
            $('.box-title').addClass('collapsed').attr('aria-expanded', false);
            $('.doc-box-collapse > div').removeClass('in').attr('aria-expanded', false);

            $('.doc-box-wrap').first().find('.doc-box-collapse').removeClass('closed').addClass('open');
            $('.doc-box-wrap').first().find('.box-title').removeClass('collapsed').attr('aria-expanded', true);
            $('.doc-box-wrap').first().find('.doc-box-collapse > div').addClass('in').attr('aria-expanded', true);
        }
    }

    function docsEqualHeight() {
        if( $('.doc-box-wrap').length > 0 ) {
            var $colClass = $('.doc-box-wrap');

            if (window.matchMedia("(max-width: 767px)").matches) {
                $colClass.height('');
                return;
            }
            var heights = $colClass.map(function () {
                return $(this).height();
            }).get();
            $colClass.height(Math.max.apply(null, heights));
        }
    }

    docsEqualHeight();

    // scroll functions
    function is_visible(el) {
        el = "#" + el[0].id;
        
        var top_of_element = $(el).offset().top;
        var bottom_of_element = $(el).offset().top + $(el).outerHeight();
        var bottom_of_screen = $(window).scrollTop() + $(window).innerHeight();
        var top_of_screen = $(window).scrollTop();

        if ((bottom_of_screen > top_of_element) && (top_of_screen < bottom_of_element)) {
            return 'true';
        } else {
            return 'false';
        }
    }

    function getViewportAnchor() {
        var scroll = $(window).scrollTop();
        var windowHeight = $(window).height() * .7;
        var windowHeightDefault = windowHeight;
        var elements = $(".section-box");
        
        var el;
        for (var i=0; i<elements.length; i++) {
            el = $(elements[i]);
            var if_is_visible = is_visible(el);
            var elminusheight = el.offset().top - windowHeight;
            //console.log("EL = " + el[0].id + " && windowheight = " + $(window).height() + " && winheight = " + windowHeight + " && Scroll = " + scroll + " && ElOffset = " + el.offset().top + " && el.offset - windowHeight = " + elminusheight + " && if_is_visible = " + if_is_visible);
            if(el[0].id === "documentation"){windowHeight = $(window).scrollTop() * .3 } else {windowHeight = windowHeightDefault}
            if(el[0].id === "get-a-quote"){windowHeight = $(window).scrollTop() * .3 } else {windowHeight = windowHeightDefault}
            //console.log(el[0].id + ' el is visible: ' + if_is_visible);
            if (el.offset().top + windowHeight >= scroll && if_is_visible === 'true'){
                var el_next = $(elements[i+1]);
                if(el_next[0] && el_next[0].id === "documentation"){windowHeight = $(window).scrollTop() * .3 } else {windowHeight = windowHeightDefault}
                if (el_next[0] && el_next.offset().top + windowHeight <= scroll && is_visible(el_next) === 'true'){
                    //console.log(el_next[0].id + ' el_next is visible: ' + if_is_visible);
                    return el_next[0].id;
                    break;
                }
                if_is_visible = 'false';
                return el[0].id;
                break;
            }
        }
    }

    function navMapSections() {
        var cSection = getViewportAnchor();
        var cSectionId = '#nav-' + cSection;

        if(!$(cSectionId).hasClass('active')) {
            $('.sideScrollNav li').each(function() {
                $(this).removeClass('active');
            });
            $(cSectionId).addClass('active');
        }
        if(cSection === undefined){
            // console.log(cSection);
            $('.sideScrollNav li').each(function() {
                $(this).removeClass('active');
            });
            $('.section-box').each(function(){
                if(is_visible($(this)) === true){
                    console.log($(this));
                    $('#nav-' + $(this)).addClass('active');
                }
            });
        }
    }

    /*********************************************
     ** ACTIVATE TABS BY LINK
     *********************************************/
    function ezShowTab(tabid) {
        
        $('#specification .nav-tabs li').removeClass('active');									// 1st: remove active from current tab
        $('#specification .nav-tabs li a[href="'+tabid+'"]').parent().addClass('active');		// 2nd: add active class to current LI nav
        $('#specification .tab-content div.tab-pane').removeClass('active');					// 3rd: remove active class to current TAB
        $('#specification .tab-content div'+tabid+'.tab-pane').addClass('active');				// 4th: add active class to current TAB
        $( tabid ).tab('show');																	// 5th: show tab
    }

    // ONLOAD OPEN - IF HASH == TO TAB - OPEN TAB
    function checkHashIsTab() {
        var chash = window.location.hash;
        
        if(chash) {

            if(chash == '#documentation') {
                $('.document-inner').slideDown();
            }
            
            var tabslist = [];

            $('#specification .nav-tabs li a').each(function() {
                tabslist.push( $(this).attr('href') );
            });
            var exists = tabslist.includes(chash);

            if(exists) {
                ezShowTab(chash);
                setTimeout(function() {
                    parentPos = $('#specification').offset().top;
                    $(window).scrollTop(parentPos);
                },5);
            }
        }

    }
 
    function getPosition(element) {
        return $(element).offset().top;
    }

    function getDesktopMenuOffset() {
        return $('#desktopMenuWrap').outerHeight();
    }

    $(document).ready(function() {

        /*************************************************
         ** COMBINE TH CELLS IN "ordering_info" TAB
         ** AND ADD COLSPAN ACCRODINGLY
         *************************************************/
        var thCount = 1;
        $('.tab-pane#ordering_info thead th').each( function(index) {
            var ctxt = $(this).text();
            if(ctxt == '') { thCount = thCount + 1; $(this).remove(); }
            $('.tab-pane#ordering_info thead th:first-child').attr('colspan', thCount);
        });


        /*************************************************
         ** ACCESSORIES SLIDER
         *************************************************/
        if( $('#accesoriesSlider').length > 0 ) {
            var slidesNum = $('#accesoriesSlider .item').length;

            const swiperAcc = new Swiper('#accesoriesSlider', {
                loop: true,
                breakpoints: {
                    768: {
                        slidesPerView: 1,
                        loopedSlides: slidesNum,
                        centeredSlides: true,
                        spaceBetween: 30
                    },
                    800: {
                        slidesPerView: 2,
                        centeredSlides: false,
                        spaceBetween: 30,
                        navigation: {
                            nextEl: '.swiper-button-next',
                            prevEl: '.swiper-button-prev'
                        }
                    },
                    1200: {
                        slidesPerView: 3,
                        centeredSlides: false,
                        spaceBetween: 30,
                        navigation: {
                            nextEl: '.swiper-button-next',
                            prevEl: '.swiper-button-prev'
                        }
                    }
                }
            });
        }

        if( $('#relatedMobileSlider').length > 0 ) {
            var slidesNum = $('#relatedMobileSlider .item').length;

            const swiperAcc = new Swiper('#relatedMobileSlider', {
                loop: true,
                breakpoints: {
                    320: {
                        slidesPerView: 2,
                        loopedSlides: slidesNum,
                        centeredSlides: true,
                        spaceBetween: 30
                    },
                    768: {
                        slidesPerView: 4,
                        centeredSlides: false,
                        spaceBetween: 30
                    },
                    1200: {
                        slidesPerView: 4,
                        centeredSlides: false,
                        spaceBetween: 60
                    }
                }
            });
        }

        if (window.matchMedia("(max-width: 767px)").matches) {

             setDocDefaults();

            $('.product-desc').readmore({
                speed: 75,
                collapsedHeight: 155,
                moreLink: '<a href="#" class="rmExpend">Read More</a>',
                lessLink: '<a href="#" class="rmExpend">Read less</a>'
            });

            // slider toggle sub-tables
            $('thead.sub-table').each(function() {
                if( !$(this).hasClass('open') ) { $(this).nextUntil('thead.sub-table').slideToggle(50); }
            });
            $('.sub-table').click(function(){
                $(this).nextUntil('thead.sub-table').slideToggle(50);
                $(this).toggleClass('open');
            });

            // SWIPE SUPPORT IN MOBILE
            $('.carousel').swipe({
                swipe: function(event, direction, distance, duration, fingerCount, fingerData) {
                    if (direction == 'left') $(this).carousel('next');
                    if (direction == 'right') $(this).carousel('prev');
                },
                tap: function(event) {
                    var item = event.target;
                    if(item.classList.contains('item-vid')) {
                        item.childNodes[0].click();
                    }
                },
                allowPageScroll:"vertical"
            });

            $('.carousel-mobile').swipe({
                loop: true,
	            noSwiping: true,
                swipe: function(event, direction, distance, duration, fingerCount, fingerData) {
                    if (direction == 'left') $(this).carousel('next');
                    if (direction == 'right') $(this).carousel('prev');
                },
                tap: function(event) {
                    var item = event.target;
                    if(item.classList.contains('item-vid')) {
                        item.childNodes[0].click();
                    }
                },
                allowPageScroll:"vertical"
            });

        } else {
            // FIX TAB ITEMS AND SPACING
            if( $('.specs-wrap').length > 0 ) {
                var tabsCount = $('.specs-wrap .nav-tabs li');
                if(tabsCount < 6) {
                    var newWidth = (tabsCount / 100) + '%';
                }
            }
        }

       /*************************************************
         ** SIDENAV SCROLL WITH HASH
         *************************************************/

        $('.side-scroll').click(function(e) {
            e.preventDefault();
            var parentElementId = '#' + $(this).data('to');
            var scrollto, scrolltoID;

            console.log(parentElementId);

            if (parentElementId == '#general-info'){
                scrollto 	= parentElementId;
                scrolltoID 	= parentElementId;
            } else if (parentElementId == '#documentation'){
                scrollto 	= parentElementId;
                scrolltoID 	= parentElementId;
            } else if (parentElementId == '#get-a-quote'){
                scrollto 	= parentElementId+ ' .quote-scroll-section';
                scrolltoID 	= parentElementId;
            }else {
                scrollto = parentElementId + ' .section-title';
                scrolltoID 	= parentElementId;
            }

            var positon = getPosition(scrollto) - getDesktopMenuOffset() - 70;
            if(scrolltoID === '#documentation'){
                positon = positon + 225
            }
            $(':not(:animated),body:not(:animated)').animate({ scrollTop: positon }, 1000);
            history.pushState(null, null, scrolltoID);
            return false;
        });

        navMapSections();

        // REPLACE HASH WHEN TAB IS ShOWN
        $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
            
            var newhash = $(e.target).attr('href');
            
            if(history.pushState) { history.pushState(null, null, newhash); }
            else { location.hash = newhash; }

        })

        // FIX CUSTOM LINK THAT GOES TO TAB CLICK
        $('#specification .tab-link').click(function(e) {
            e.preventDefault();
          
            ezShowTab($(this).attr('href'));
        });

        checkHashIsTab();

        localStorage.removeItem('lasturl');
        var prodUrl = window.location.href;
        $('.specsComapre').on('click', function(){
            localStorage.setItem('lasturl', prodUrl);
        });
    });

    $(window).scroll(function(){
        navMapSections();
    });

    $(window).resize(function(){
        navMapSections();
    });

    $('.doc-box-section .files-list li.popup-pdf a').magnificPopup({
        type: 'iframe',
        disableOn: function() {
            if( $(window).width() < 767 ) {
                return false;
            }
            return true;
        }
    });

    $(".js-custom-scroll").click(function(event) {
        event.preventDefault();
        var div = $(this).data('to');

        if( typeof div != 'undefined') {
            /* if documentation toggle the div */
            if(div == 'documentation') {
                $('.document-inner').slideDown();
            }

            $('html, body').animate({
                scrollTop: $('#'+div).offset().top
            }, 2000);

            setTimeout(function () {
                window.location.hash = '#'+div;
            }, 2500);
        
        }
    });

    $(window).on('load resize', function () {
        var hash = window.location.hash;

        if($('.tab-content #highlights').length > 0 && hash == '') {
            console.log('tab');
            $('#specification .nav-tabs > li').removeClass('active');
            $('.tab-content .tab-pane ').removeClass('active');

            if($(window).width() < 768) {
                $('#specification .nav-tabs').find('li:first').addClass('active');
                $('.tab-content').find('.tab-pane:first-child').addClass('active');
            } else {
                $('#specification .nav-tabs').find('li:nth-child(2)').addClass('active'); 
                $('.tab-content').find('.tab-pane:nth-child(2)').addClass('active');
            }
        }
    });

});
</script>
<!--<script src="https://www.google.com/recaptcha/api.js?onload=onloadCallback&render=explicit" async defer> </script>-->

<?php get_footer(); ?>