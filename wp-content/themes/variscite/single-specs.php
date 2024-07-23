<?php
get_header();

$fields = get_field_objects(get_the_ID());
?>

<?php
$is_spec_have_read_more = false;
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

        $settings = get_field('quote_settings', 'option');
        $use_global_title = get_field('vrs_specs_global_title' , $pid);
        $use_global_paragraph = get_field('vrs_specs_global_paragraph' , $pid);
        $quote_global_title = $settings['global_quote_title'];
        $quote_global_paragraph = $settings['global_quote_paragraph'];

        $sideBarFormTitle = get_field('change_get_a_quote_title', $pid);
		?>
		
		<div class="specs-page <?php if( !empty(get_field('vrs_specs_store_product')) ) {echo 'cart-product';} else {echo 'quote-product';} ?>">
			<div class="inner">

                <!--===STR=============== GENERAL INFO =====================-->
                <div id="general-info" class="section-box">
                    <?php specs_topbgimg_style(get_field('vrs_specs_headimg')); ?>

                    <div class="top-slider">
                        <div class="container">
                            <div class="breadcrumbs hideInMobile"><?php echo breadcrumbs(); ?></div>
                            <div class="row">
                                <div class="col-sm-5 col-xs-12">
                                    <h1 class="page-title"><?php echo specs_page_title(get_the_title()); ?></h1>
                                    <?php
                                    if( !empty(get_field('vrs_specs_price')) ) { echo '<div class="price-block">'.get_field('vrs_specs_price').'</div>'; } ?>

                                    <div class="top-btns-wrap">
                                    <?php custom_getquote_btn($kit_check, $store_inlink, $store_exlink, $button_text, '');
                                    if( empty($hide_compare[0]) || $hide_compare[0] != 'on') { ?>
                                       <button class="btn btn-link specsComapre btn-lg" value="<?php echo $pid; ?>"><?php _e('Compare to other products', THEME_NAME); ?></button>
                                       <a href="<?php echo get_permalink( get_field('optage_defaultpages_compare', 'option') ); ?>" id="productsCompare" class="dnone"></a>
                                    <?php } ?>
                                    </div>

                                </div>
                                <div class="col-md-6 col-md-offset-1 col-sm-7 col-xs-12">
                                    <?php echo specs_product_slider( $pid ); ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <?php if(get_field('vrs_specs_product_middesc')) { ?>
                    <div class="top-desc">
                        <div class="container">
                            <div class="row">
                                <div class="product-datapdf col-sm-4 col-md-3">
                                    <ul class="lsnone p0">
                                        <?php
                                        // VALUES RETUNED ARE POST IDS OF RELEVANT PDF'S
                                        $pdf['brief'] 		= get_field('vrs_specs_product_brief');
                                        $pdf['datasheet'] 	= get_field('vrs_specs_product_datasheet');
                                        $pdf['quickstart'] 	= get_field('vrs_specs_quickstart_guide');
                                        $pdf['wiki'] 		= get_field('vrs_specs_wiki_page');
                                        $pdf['schematics'] 	= get_field('vrs_specs_board_schematics');
										//if( isset( $_GET['sddg'] ) ){
											if( is_array( $pdf['brief'] ) && isset( $pdf['brief'][0] ) && empty( $pdf['brief'][0] ) ){
												$pdf['brief'] = false;
											}
											if( is_array( $pdf['datasheet'] ) && isset( $pdf['datasheet'][0] ) && empty( $pdf['datasheet'][0] ) ){
												$pdf['datasheet'] = false;
											}
											if( is_array( $pdf['quickstart'] ) && isset( $pdf['quickstart'][0] ) && empty( $pdf['quickstart'][0] ) ){
												$pdf['quickstart'] = false;
											}
											if( is_array( $pdf['schematics'] ) && isset( $pdf['schematics'][0] ) && empty( $pdf['schematics'][0] ) ){
												$pdf['schematics'] = false;
											}
										//}
                                        if( !empty($pdf['brief']) ) {echo '<li><a href="'.product_data_pdf( $pdf['brief'] ).'" target="_blank" class="btn btn-default btn-lg datapdf-btn"><span class="text">'.__('Product Brief', THEME_NAME).'</span> </a></li>';}
                                        if( !empty($pdf['quickstart']) ) {echo '<li><a href="'.product_data_pdf( $pdf['quickstart'] ).'" target="_blank" class="btn btn-default btn-lg datapdf-btn"><span class="text">'.__('Quick Start Guide', THEME_NAME).'</span> </a></li>';}
                                        if( !empty($pdf['datasheet']) ) {echo '<li><a href="'.product_data_pdf( $pdf['datasheet'] ).'" target="_blank" class="btn btn-default btn-lg datapdf-btn"><span class="text">'.__('Product Datasheet', THEME_NAME).'</span></a></li>';}
                                        if( !empty($pdf['wiki']) ) {echo '<li><a href="'.$pdf['wiki'].'" target="_blank" class="btn btn-default btn-lg datapdf-btn"><span class="text">'.__('Wiki Page', THEME_NAME).'</span></a></li>';}
                                        if( !empty($pdf['schematics']) ) {echo '<li><a href="'.product_data_pdf( $pdf['schematics'] ).'" target="_blank" class="btn btn-default btn-lg datapdf-btn"><span class="text">'.__('Board Schematics', THEME_NAME).'</span></a></li>';}
                                        ?>
                                    </ul>
                                </div>
                                <div class="product-desc">
                                <?php 
                                if( !empty(get_field('vrs_specs_product_middesc')) ) {
                                    $the_content = apply_filters('the_content', get_field('vrs_specs_product_middesc'));
                                    if (substr_count($the_content, '<!--more-->') == 1) {
                                        $is_spec_have_read_more = true;
                                        $the_content = str_replace('<p><!--more--></p>', '<div class="read-more-wrap"><div class="read-more-button read-more">Read More</div><div class="read-more-text">', $the_content);
                                        echo $the_content . '</div><div class="read-more-button read-less">Read Less</div></div> <!-- end read-more-wrap -->';
                                    }else{

                                        echo $the_content;
                                    }
                                } 
                                ?>
                                </div>
                                <div class="product-compliance col-sm-2 col-md-3">
                                    <?php if( !empty(get_field('vrs_specs_compliance_icons')) ) {echo specs_compliance_icons(get_field('vrs_specs_compliance_icons'), $local_compliance);} ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php } ?>
                </div>
                <!--===END=============== GENERAL INFO =====================-->

                <!--===STR=============== SPECIFICATION =====================-->
                <div id="specification" class="skewed-section section-box <?php if(!get_field('vrs_specs_product_middesc')) { echo 'noDescProd';} ?>  <?php if(empty($accessoriesArr[0]['accessory']) && empty(get_field('vrs_specs_doc_block')) && empty(get_field('vrs_specs_relevant_mid_product')) ) {echo 'plus2-sections-spacing';} ?>">
                    <div class="innerWrap">
                        <div class="container">
                            <h2 class="section-title"> <?php if( !empty(get_field('vrs_specs_spec_title')) ) {the_field('vrs_specs_spec_title'); } else { _e('Specification', THEME_NAME);} ?> </h2>
                        </div>
                        <?php echo specs_specification_tabs( get_field('vrs_specs_spec_tabs'), $pid ); ?>

                        <?php
                        if($isit_kit) {
                            if( is_array( $store_inlink ) && isset( $store_inlink[0] ) && empty( $store_inlink[0] ) ){
                                $store_inlink = false;
                            }
                            if( is_array( $store_exlink ) && isset( $store_inlink[0] ) && empty( $store_inlink[0] ) ){
                                $store_inlink = false;
                            }

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
                <!--===END=============== SPECIFICATION =====================-->

                <!--===STR=============== EVALUATION KIT =====================-->
                <?php
                $kit_postid = get_field('vrs_specs_relevant_mid_product');

                if( is_array( $kit_postid ) && isset( $kit_postid[0] ) && empty( $kit_postid[0] ) ){
                    $kit_postid = false;
                }
                if($kit_postid) {
                    echo spec_evaluation_kit($kit_postid , get_field('vrs_specs_evaluation_kit_cimg'), get_field('vrs_specs_evaluation_kit_cimg_webp'), get_field('vrs_specs_relevant_mid_prodalturl') );
                }
                ?>
                <!--===END=============== EVALUATION KIT =====================-->

                <!--===STR=============== DOCUMENTATION =====================-->
                <?php if( !empty(get_field('vrs_specs_doc_block')) ) { ?>
                <div id="documentation" class="section-box doc-box-section">
                    <div class="container">
                        <?php echo spec_docs_boxes( get_field('vrs_specs_doc_block'), $pid ); ?>
                    </div>
                </div>
                <?php } ?>
                <!--===END=============== DOCUMENTATION =====================-->

                <!--===STR=============== ACCESSORIES =====================-->
                <?php
                $accessoriesArr = get_field('vrs_specs_accesories_products');

                if( !empty($accessoriesArr[0]['accessory']) ) {
                    echo '
                    <div id="accessories" class="section-box">'.specs_accessories_slider( get_field('vrs_specs_accesories_products'), $pid ).'</div>
                    ';
                }
                ?>
                <!--===END=============== ACCESSORIES =====================-->
                <?php if(!empty(get_field('vrs_specs_quote_product_addons'))): 
                    $vrs_specs_form_type = get_field('vrs_specs_form_type');
                    $vrs_specs_quote_product_addons = get_field('vrs_specs_quote_product_addons'); 
                    
                    $addon = 'som';

                    if($vrs_specs_quote_product_addons == 'optage_specs_quote_som_addons ') {
                        $addon = 'kits';
                    } ?>
                    <!--===STR=============== QUOTE FORM =====================-->
                    <div id="quote-formbox" class="section-box relative<?php echo ($vrs_specs_form_type == 'multi-step-form')?' quote-multi-step':'';?><?php if(empty($accessoriesArr[0]['accessory']) && empty(get_field('vrs_specs_doc_block')) && empty(get_field('vrs_specs_relevant_mid_product')) ) {echo 'min2-sections-spacing';} ?>   <?php if( empty($accessoriesArr[0]['accessory']) ) {echo 'no-accessories-section';} ?>">
                        <div class="quote-background cell-1"></div>
                        <div class="quote-background cell-2"></div>

                        <div class="innerWrap">
                            <div id="get-a-quote" class="form-box">
                                <div class="container">
                                <div class="quote-scroll-section"></div>
                                    <div class="row">
                                        <?php if($vrs_specs_form_type == 'multi-step-form'): ?>
                                            <div class="quote-form-row step-form-on col-md-12 col-xs-12">
                                                <?php echo do_shortcode('[step_quote_form addon="'.$addon.'"]'); ?>
                                            </div>
                                        <?php else: ?>
                                            <div class="quote-title-box col-md-3 col-xs-12">
                                                <h2 class="section-title">
                                                    <?php
                                                    if($use_global_title == 1){
                                                        echo $quote_global_title;
                                                    } else {
                                                        the_field('vrs_specs_quote_title');
                                                    }
                                                    ?>
                                                </h2>
                                                <p class="section-desc">
                                                    <?php
                                                    if($use_global_paragraph == 1){
                                                        echo $quote_global_paragraph;
                                                    } else {
                                                        the_field('vrs_specs_quote_desc');
                                                    }
                                                    ?>
                                                </p>
                                            </div>
                                            <div class="quote-form-row col-md-9 col-xs-12">
                                            <?php echo specs_quoteform_box( get_field('vrs_specs_quote_product_addons'), get_the_ID(), $vrs_specs_form_type ); ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <!--===END=============== QUOTE FORM =====================-->
                <?php endif; ?>

                <!--===STR=============== RELATED =====================-->
                <div id="related" class="section-box">
                    <?php echo specs_related_products( get_field('vrs_specs_related_products'), $pid ); ?>
                </div>
                <!--===END=============== RELATED =====================-->

			</div>


			<!--===STR=============== SIDE SCROLL NAV =====================-->
			<div class="sideScrollNav">
				<div class="inner">
					<ul class="lsnone p0 m0">
						<li id="nav-general-info" class="side-scroll" data-to="general-info"><?php _e('General info', THEME_NAME); ?></li>
						<li id="nav-specification" class="side-scroll" data-to="specification"><?php _e('Specifications', THEME_NAME); ?></li>
						<?php
						if( !empty(get_field('vrs_specs_relevant_mid_product')) ) {echo '<li id="nav-evaluation-kit" class="side-scroll" data-to="evaluation-kit">'.__('Evaluation Kit', THEME_NAME).'</li>';}
						if( !empty(get_field('vrs_specs_doc_block')) ) {echo '<li id="nav-documentation" class="side-scroll" data-to="documentation">'.get_field('vrs_specs_doc_section_title').'</li>';}
						if( !empty(get_field('vrs_specs_accesories_products')[0]['accessory']) ) {echo '<li id="nav-accessories" class="side-scroll" data-to="accessories">'.get_field('vrs_specs_accesories_title').'</li>';}
						?>
                        <li id="nav-quote-formbox" class="side-scroll" data-to="get-a-quote"><?php if(!empty($sideBarFormTitle)){echo $sideBarFormTitle;}else{the_field('vrs_specs_quote_title');}  ?></li>
					</ul>
				</div>
			</div>
			<!--===END=============== SIDE SCROLL NAV =====================-->

	    </div>
	<?php
	}
}
?>
<style>
        .read-more, .read-less, .read-less:hover, .read-less:active,  .read-less:focus, .read-more:hover, .read-more:active, .read-more:focus {
            cursor: pointer;
            font-size: 16px;
            color: #0d0d0d;
            text-decoration: underline;
            margin-top: 10px;
            
        }
        

        

        .product-desc {
            position: relative;
        }
        
        
    </style>
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
        <?php if (!$is_spec_have_read_more): ?>
        /*************************************************
         ** Add read more to text
         *************************************************/
        var $contentContainer = $('.product-desc');
        var originalContent = $contentContainer.html(); // Store original HTML content
        var sentenceLimit = 5; // Sentence limit after which "Read More" is added
        var minCharLimit = 700; // Minimum character length for visible content

       
        function truncateContent(content, limit, minChars) {
            var tempDiv = $('<div>').html(content); 
            var visibleContent = '';
            var hiddenContent = '';
            var sentenceCount = 0;
            var charCount = 0;

            
            var sentenceRegex = /(?<!\b(?:[A-Z]\.|i\.|etc\.|e\.g\.|i\.e\.|vs\.|Mr\.|Mrs\.|Dr\.|Ms\.|Jr\.|Sr\.|Prof\.|St\.))(?<!\.\.\.)(?<!\w\.\w)(?<=\.|\?|!)\s/;

            tempDiv.contents().each(function() {
                if (sentenceCount >= limit && charCount >= minChars) {
                    hiddenContent += $('<div>').append($(this).clone()).html();
                    return;
                }

                var nodeText = $(this).text();
                var nodeHtml = $(this).prop('outerHTML');
                var sentences = nodeText.split(sentenceRegex) || []; 

                for (var i = 0; i < sentences.length; i++) {
                    if (sentenceCount < limit || charCount < minChars) {
                        visibleContent += sentences[i] + (i < sentences.length - 1 ? ' ' : '');
                        charCount += sentences[i].length + 1; 
                        sentenceCount++;
                    } else {
                        hiddenContent += sentences[i] + (i < sentences.length - 1 ? ' ' : '');
                    }
                }
            });

            return { visible: visibleContent, hidden: hiddenContent };
        }

        
        var { visible, hidden } = truncateContent(originalContent, sentenceLimit, minCharLimit);

       
        visible = visible.replace(/undefined/g, '');
        hidden = hidden.replace(/undefined/g, '');

        
        if (hidden.trim().length > 0) {
            
            $contentContainer.html('<div class="visible-content">' + visible + '</div><div class="read-more-content" style="display:none;">' + hidden + '</div><a href="#" class="read-more">Read More</a>');

            
            $contentContainer.on('click', 'a.read-more', function(event) {
                event.preventDefault();
                var $readMoreLink = $(this);
                var $readMoreContent = $readMoreLink.siblings('.read-more-content');

                
                $readMoreContent.toggle();

                
                if ($readMoreContent.is(':visible')) {
                    $readMoreLink.text('Read Less');
                } else {
                    $readMoreLink.text('Read More');
                }
            });
        } else {
            
            $contentContainer.html('<div class="visible-content">' + visible + '</div>');
        }

        <?php endif; ?>



         



        /*************************************************
         ** ACCESSORIES SLIDER
         *************************************************/
        if( $('#accesoriesSlider').length > 0 ) {
            var slidesNum = $('#accesoriesSlider .item').length;

            const swiperAcc = new Swiper('#accesoriesSlider', {
                loop: true,
                breakpoints: {
                    320: {
                        slidesPerView: 'auto',
                        loopedSlides: slidesNum,
                        centeredSlides: true,
                        spaceBetween: 30
                    },
                    768: {
                        slidesPerView: 4,
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
                        slidesPerView: 'auto',
                        loopedSlides: slidesNum,
                        centeredSlides: true,
                        spaceBetween: 20
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

            $('#specproductcarousel').removeClass('carousel-fade');

            setDocDefaults();


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

        } else {
            // FIX TAB ITEMS AND SPACING
            if( $('.specs-wrap').length > 0 ) {
                var tabsCount = $('.specs-wrap .nav-tabs li');
                if(tabsCount < 6) {
                    var newWidth = (tabsCount / 100) + '%';
                }
            }

            if ($('.read-more-wrap').length) {
                $(document).on('click', '.read-more-button', function() {
                    if ($('.read-more-wrap').hasClass('active')) {
                        $('.read-more-wrap').removeClass('active');
                    }else{
                        $('.read-more-wrap').addClass('active');
                    }
                });
            }
        }

        /*************************************************
         ** SIDENAV SCROLL WITH HASH
         *************************************************/
        
        $('.side-scroll').click(function(e) {
            e.preventDefault();
            var parentElementId = '#' + $(this).data('to');
            var scrollto, scrolltoID;

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
            if (!$(parentElementId).length) {
                scrollto='#get-a-quote';
                
            }

            var positon = getPosition(scrollto) - getDesktopMenuOffset() - 70;
            if(scrolltoID === '#documentation'){
                positon = positon + 225
            }
            
            $(':not(:animated),body:not(:animated)').animate({ scrollTop: positon }, 1);
            
            
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

    $(".js-custom-scroll").click(function(event) {
        event.preventDefault();
        var div = $(this).data('to');
        var targetElem=div;
            if( typeof div != 'undefined') {
            /* if documentation toggle the div */
            if(div == 'documentation') {
                $('.document-inner').slideDown();
            }
            
            if (!$('#' + div).length) {
                targetElem='get-a-quote';
                
            }
            $('html, body').animate({
                    scrollTop: $('#'+targetElem).offset().top
                }, 2000);


            // $('html, body').animate({
            //     scrollTop: $('#'+div).offset().top
            // }, 2000);

            setTimeout(function () {
                window.location.hash = '#'+div;
            }, 2500);
        }
    });

});
</script>
<!--<script src="https://www.google.com/recaptcha/api.js?onload=onloadCallback&render=explicit" async defer> </script>-->

<?php get_footer(); ?>