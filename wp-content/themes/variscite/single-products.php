<?php
get_header();

$fields = get_field_objects(get_the_ID());
?>

<?php
if ( have_posts() ) {
	while ( have_posts() ) {
		the_post();

		$pid = get_the_ID();
		?>
		
		<div class="specs-page <?php if( !empty(get_field('vrs_specs_store_product')) ) {echo 'cart-product';} else {echo 'quote-product';} ?>">
			<div class="inner">

					<!--===STR=============== GENERAL INFO =====================-->
					<div id="general-info">
						<div class="top-slider" style="background: url(<?php echo specs_top_bgimg( get_field('vrs_specs_headimg') ); ?>) no-repeat center top;">
							<div class="container-wrap">
								<div class="container">
									<div class="row">
										<div class="col-md-4 col-sm-12 col-xs-12">
											<h1 class="page-title"><?php echo specs_page_title(get_the_title()); ?></h1>
											<?php
											if( !empty(get_field('vrs_specs_price')) ) { echo '<div class="price-block">'.get_field('vrs_specs_price').'</div>'; }
											if( !empty(get_field('vrs_specs_short_desc')) ) { echo '<div class="short-desc">'.get_field('vrs_specs_short_desc').'</div>'; }
											?>
											<button class="btn btn-warning btn-lg quote-scroll scroll hidden-xs hidden-sm" data-to="quote-tabs"><span class="text"><?php _e('Get a quote', THEME_NAME); ?></span> <img src="<?php echo IMG_URL; ?>/button-arrow.png" alt="arrow"></button>
										</div>
										<div class="col-md-1"></div>
										<div class="col-md-7 col-xs-12">
											<?php echo specs_product_slider( get_field('vrs_specs_slider_images'), get_the_title() ); ?>

											<button class="btn btn-warning btn-lg quote-scroll scroll visible-xs" data-to="quote-tabs"><span class="text"><?php _e('Get a quote', THEME_NAME); ?></span> <img src="<?php echo IMG_URL; ?>/button-arrow.png" alt="arrow"></button>
											<button class="btn btn-warning btn-lg quote-scroll scroll visible-sm" data-to="quote-tabs"><span class="text"><?php _e('Get a quote', THEME_NAME); ?></span> <img src="<?php echo IMG_URL; ?>/button-arrow.png" alt="arrow"></button>
										</div>
									</div>
								</div>
							</div>
						</div>
			
						<div class="top-desc">
							<div class="container-wrap">
								<div class="container">
									<div class="row">
										<div class="product-datapdf col-md-3 col-sm-3">
											<ul class="lsnone p0">
												<?php
												$pdf['brief'] 		= get_field('vrs_specs_product_brief');
												$pdf['datasheet'] 	= get_field('vrs_specs_product_datasheet');
												$pdf['wiki'] 		= get_field('vrs_specs_wiki_page');
												$pdf['schematics'] 	= get_field('vrs_specs_board_schematics');

												if( !empty($pdf['brief']) ) {echo '<li><a href="'.product_data_pdf( $pdf['brief'] ).'" target="_blank" class="btn btn-default btn-lg datapdf-btn"><span class="text">'.__('Product Brief', THEME_NAME).'</span> <img src="'.IMG_URL.'/mini-red-arrow-001.png" alt="arrow"></a></li>';}
												if( !empty($pdf['datasheet']) ) {echo '<li><a href="'.product_data_pdf( $pdf['datasheet'] ).'" target="_blank" class="btn btn-default btn-lg datapdf-btn"><span class="text">'.__('Product Datasheet', THEME_NAME).'</span> <img src="'.IMG_URL.'/mini-red-arrow-001.png" alt="arrow"></a></li>';}
												if( !empty($pdf['wiki']) ) {echo '<li><a href="'.product_data_pdf( $pdf['wiki'] ).'" target="_blank" class="btn btn-default btn-lg datapdf-btn"><span class="text">'.__('Wiki Page', THEME_NAME).'</span> <img src="'.IMG_URL.'/mini-red-arrow-001.png" alt="arrow"></a></li>';}
												if( !empty($pdf['schematics']) ) {echo '<li><a href="'.product_data_pdf( $pdf['schematics'] ).'" target="_blank" class="btn btn-default btn-lg datapdf-btn"><span class="text">'.__('Board Schematics', THEME_NAME).'</span> <img src="'.IMG_URL.'/mini-red-arrow-001.png" alt="arrow"></a></li>';}
												?>
											</ul>
										</div>

										<?php if(is_mobile()) { ?>
										<div class="product-compliance col-md-3">
											<?php if( !empty(get_field('vrs_specs_compliance_icons')) ) {echo specs_compliance_icons(get_field('vrs_specs_compliance_icons'));} ?>
										</div>
										<?php } ?>

										<div class="product-desc col-md-6 col-sm-6">
											<?php if( !empty(get_field('vrs_specs_product_middesc')) ) {echo apply_filters('the_content', get_field('vrs_specs_product_middesc'));} ?>
										</div>

										<?php if(!is_mobile()) { ?>
										<div class="product-compliance col-md-3 col-sm-3">
											<?php if( !empty(get_field('vrs_specs_compliance_icons')) ) {echo specs_compliance_icons(get_field('vrs_specs_compliance_icons'));} ?>
										</div>
										<?php } ?>

									</div>
								</div>
								<div class="clearfix"></div>
							</div>
						</div>
					</div>
					<!--===END=============== GENERAL INFO =====================-->

			

					<!--===STR=============== SPECIFICATION =====================-->
					<div id="specification" class="skew-before skew-after">
						<div class="container-wrap">
							<div class="container"><h2 class="section-title"> <?php if( !empty(get_field('vrs_specs_spec_title')) ) {the_field('vrs_specs_spec_title'); } else { _e('Specification', THEME_NAME);} ?> </h2></div>
							<?php echo specs_specification_tabs( get_field('vrs_specs_spec_tabs'), $pid ); ?>
							
							
							<div class="clearfix"></div>
						</div>
					</div>
					<!--===END=============== SPECIFICATION =====================-->


					<!--===STR=============== EVALUATION KIT =====================-->
					<div id="evaluation-kit">
						<div class="container-wrap">
							<div class="container">
								<?php echo spec_evaluation_kit( get_field('vrs_specs_relevant_mid_product'), get_field('vrs_specs_evaluation_kit_cimg') ); ?>
							</div>

							<div class="clearfix"></div>
						</div>
					</div>
					<!--===END=============== EVALUATION KIT =====================-->


					<!--===STR=============== DOCUMENTATION =====================-->
					<?php if( !empty(get_field('vrs_specs_doc_block')) ) { ?>
					<div id="documentation">
						<div class="container-wrap">
							<div class="container">
								<?php echo spec_docs_boxes( get_field('vrs_specs_doc_block'), $pid ); ?>
							</div>
							<div class="clearfix"></div>
						</div>
					</div>
					<?php } ?>
					<!--===END=============== DOCUMENTATION =====================-->

					<div class="spacer s22211"></div>

					<!--===STR=============== ACCESSORIES =====================-->
					<div id="accessories">
					<?php echo specs_accessories_slider( get_field('vrs_specs_accesories_products'), $pid ); ?>
					</div>
					<!--===END=============== ACCESSORIES =====================-->


					<!--===STR=============== QUOTE FORM =====================-->
					<div id="quote-formbox">
						<div class="quote-background"></div>
						<div class="form-box">
							<div class="container-wrap">
								<div class="container">
									<div class="row">
										<div class="quote-title-box col-md-2 p0 col-xs-12">
											<h2 class="section-title"><?php the_field('vrs_specs_quote_title'); ?></h2>
											<p class="section-desc"><?php the_field('vrs_specs_quote_desc'); ?></p>
										</div>
										<div class="vspacer col-md-1 hidden-xs">
											<!--===SPACER===-->
										</div>
										<div class="quote-form-row col-md-9 col-xs-12">
										<?php echo specs_quoteform_box( get_field('vrs_specs_quote_product_addons'), $pid ); ?>
										</div>
									</div>
								</div>

								<div class="clearfix"></div>
							</div>
						</div>
						
					</div>
					<!--===END=============== QUOTE FORM =====================-->

					<div class="spacer s22211 relSpacer"></div>

					<!--===STR=============== RELATED =====================-->
					<div id="related">
						<div class="container-wrap">
							<div class="container">
								<?php echo specs_related_products( get_field('vrs_specs_related_products'), $pid ); ?>
							</div>

							<div class="clearfix"></div>
						</div>
					</div>
					<!--===END=============== RELATED =====================-->

			</div>



			<!--===STR=============== SIDE SCROLL NAV =====================-->
			<?php if( !is_mobile() ) { ?>
			<div class="sideScrollNav">
				<div class="inner">
					<ul class="lsnone p0 m0">
						<li class="scroll" data-to="general-info"><?php _e('General info', THEME_NAME); ?></li>
						<li class="scroll" data-to="specification"><?php _e('Specifications', THEME_NAME); ?></li>
						<?php
						if( !empty(get_field('vrs_specs_relevant_mid_product')) ) {echo '<li class="scroll" data-to="evaluation-kit">'.__('Evaluation Kit', THEME_NAME).'</li>';}
						if( !empty(get_field('vrs_specs_doc_block')) ) {echo '<li class="scroll" data-to="documentation">'.get_field('vrs_specs_doc_section_title').'</li>';}
						if( !empty(get_field('vrs_specs_accesories_products')) ) {echo '<li class="scroll" data-to="accessories">'.get_field('vrs_specs_accesories_title').'</li>';}
						?>
						<li class="scroll" data-to="quote-formbox"><?php the_field('vrs_specs_quote_title'); ?></li>
					</ul>
				</div>
			</div>
			<?php } ?>
			<!--===END=============== SIDE SCROLL NAV ============CHEN TEST=========-->


		</div>
	<?php
	}
}
?>




<!--<script type="text/javascript">-->
<!--	var onloadCallback = function() {-->
<!--		grecaptcha.render('captcha', {-->
<!--			'sitekey' : '6Lc9usIUAAAAAPM1xJlaO-rMZKfjJVzL0tTX42Wx',-->
<!--			'callback' : grabCaptchaRes-->
<!--		});-->
<!--	};-->
<!---->
<!--	function grabCaptchaRes(res) {-->
<!--		$('#captachResponse').val(res);-->
<!--	}-->
<!---->
<!---->
<!---->
<!--</script>-->


<script>
jQuery(document).ready(function($) {

	if( $('#accesoriesSlider').length > 0 ) {
		$('#accesoriesSlider').bxSlider({
			<?php
			if(is_mobile()) {
				// echo "
				// minSlides: 1,
				// maxSlides: 3,
				// ";
				echo "
				centeredSlides: true,
				slidesPerView:'auto',
				slideWidth: 225,
				";
			}
			else {
				echo "
				minSlides: 4,
				maxSlides: 4,
				slideWidth: 900,
				";
			}
			?>
			moveSlides: 1,
			slideMargin: 0,
			pager: false,
            touchEnabled: <?php echo (is_mobile() ? 'true' : 'false'); ?>,
			controls: <?php echo (is_mobile() ? 'false' : 'true'); ?>,
			nextText: '<i class="fa fa-angle-right"></i>',
			prevText: '<i class="fa fa-angle-left"></i>',
		});
	}


	if( $('#relatedMobileSlider').length > 0 ) {
		$('#relatedMobileSlider').bxSlider({
			<?php
			if(is_mobile()) {
				echo "
				centeredSlides: true,
				slidesPerView:'auto',
				slideWidth: 225,
				";
			}
			else {
				echo "
				minSlides: 4,
				maxSlides: 4,
				slideWidth: 900,
				";
			}
			?>
			moveSlides: 1,
			slideMargin: 0,
			pager: false,
			controls: false,
		});
	}



	<?php if(is_mobile()) { ?>
	$('.product-desc').readmore({
		speed: 75,
		collapsedHeight: 155,
		moreLink: '<a href="#" class="rmExpend">Read More</a>',
		lessLink: '<a href="#" class="rmExpend">Read less</a>'
	});



	// slider toggle sub-tables
	$('thead.sub-table').each(function() {
		$(this).nextUntil('thead.sub-table').slideToggle(50);
	});
	$('.sub-table').click(function(){
		$(this).nextUntil('thead.sub-table').slideToggle(50);
		$(this).toggleClass('open');
	});
	<?php } ?>


});
</script>


<!--<script src="https://www.google.com/recaptcha/api.js?onload=onloadCallback&render=explicit" async defer> </script>-->

<?php get_footer(); ?>