<?php
if( get_field('exit_popup_on', 'option') ) {
?>
<!--===STR========= EXIT CONTACT FORM MODAL =================-->
<div class="modal fade conFormExitPopup-modal" id="conFormExitPopup" tabindex="-1" role="dialog" aria-labelledby="conFormExitPopupLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body">
                <div class="modal-con-left">
                    <div class="con-form-before">
                        <h3>Still Looking for the Right SoM?</h3>
                        <p>Contact our experts to find the best SoM for your project.</p>
                        <span class="ContactNow">
							  Contact Now
							</span>
                    </div>
                    <div class="con-form-after">
                        <?php echo do_shortcode( '[exit_contact_form_popup f-name="First Name" l-name="Last Name" email="Email" phone="Phone" company="Company" country="Country" note="Note..." privacy="I agree to the Variscite" privacy-link="Privacy Policy" submit-btn="Submit"]', false ); ?>
                    </div>
                </div>
                <figure class="modal-con-right"><img src="<?php echo get_stylesheet_directory_uri(); ?>/images/exit-popup-1.png">
                    <i id="modal-close-icon" class="fa fa-times"></i>
                </figure>
            </div>
        </div>
    </div>
</div>
<!--===END========= EXIT CONTACT FORM MODAL =================-->
	<?php
}
	$footerMargin = get_field('pp_page_nofooter_margin', get_the_id());
	?>

	<footer class="<?php
    if(!is_null($footerMargin) && is_array($footerMargin) && isset($footerMargin[0])){
        if($footerMargin[0] == 'on') {echo 'footerNoMargin';}
    }
    ?>">
		<div class="container">

			<div class="menus">
				<div class="row">
                    <div class="col-md-8 col-sm-7 footer-menus hideInMobile">
                        <div class="row">
                            <div class="footer-nav"> <?php if ( is_active_sidebar('footersb1') ) { dynamic_sidebar( 'footersb1' ); } ?> </div>
                            <div class="footer-nav"> <?php if ( is_active_sidebar('footersb2') ) { dynamic_sidebar( 'footersb2' ); } ?> </div>
                            <div class="footer-nav"> <?php if ( is_active_sidebar('footersb3') ) { dynamic_sidebar( 'footersb3' ); } ?> </div>
                            <div class="footer-nav"> <?php if ( is_active_sidebar('footersb4') ) { dynamic_sidebar( 'footersb4' ); } ?> </div>
                            <div class="footer-nav"> <?php if ( is_active_sidebar('footersb5') ) { dynamic_sidebar( 'footersb5' ); } ?> </div>
                        </div>
                    </div>
					<div class="col-md-4 col-sm-5 col-xs-12">
						<?php if ( is_active_sidebar('footersb7') ) { dynamic_sidebar( 'footersb7' ); } ?>
					</div>
				</div>
			</div>

			<div class="copyrights">
                <div class="row">
                    <div class="col-xs-12 socialize">
                        <ul class="list-inline">

                            <?php $socialGroup = get_field('optage_social_accounts', 'option'); ?>

                            <li><a href="<?php echo $socialGroup['optage_social_yt']; ?>" target="_blank" rel="nofollow"><i class="fa-brands fa-youtube"></i></a></li>
                            <li><a href="<?php echo $socialGroup['optage_social_lk']; ?>" target="_blank" rel="nofollow"><i class="fa-brands fa-linkedin-in"></i></a></li>
                            <li><a href="<?php echo $socialGroup['optage_social_tw']; ?>" target="_blank" rel="nofollow"><i class="fa-brands fa-x-twitter"></i></a></li>
                            <li><a href="<?php echo $socialGroup['optage_social_fb']; ?>" target="_blank" rel="nofollow"><i class="fa-brands fa-facebook-f"></i></a></li>
                            <li><a href="https://github.com/varigit" target="_blank" rel="nofollow"><i class="fa-brands fa-github"></i></a></li>
                            <li><a href="https://www.xing.com/pages/variscite" target="_blank" rel="nofollow"><i class="fa-brands fa-xing"></i></a></li>
                        </ul>
                    </div>
                    <div class="col-xs-12 hideInDesktop">
                        <?php if ( is_active_sidebar('footersb8') ) { dynamic_sidebar( 'footersb8' ); } ?>
                    </div>
                    <div class="col-xs-12 text-copyrights"><?php echo do_shortcode( get_field('optage_footer_copyrights', 'option') ); ?></div>
                </div>
			</div>

		</div>
	</footer>

	</div> <!-- // .body-container -->

	<!--=== WP FOOTER ===-->
	<?php wp_footer(); ?>
	<!--=== WP FOOTER ===-->


	<!--=== CUSTOM FOOTER SCRIPTS ===-->
	<?php
	wp_reset_query();
	$footerScripts = get_field('optage_footer_scripts', 'option');
	if($footerScripts) { echo $footerScripts; }
	?>
	<!--=== CUSTOM FOOTER SCRIPTS ===-->


	<!--=== ON PAGE FOOTER SCRIPTS ===-->
	<?php
	$pageFooterScripts = get_field('common_footer_scripts', get_the_ID());
	if($pageFooterScripts) { echo '<script>'.$pageFooterScripts.'</script>'; }


	// ALL SPECS CUSTOM SCRIPTS: MANAGED IN THEME OPTIONS
	if(is_singular('specs')) {
		$globalSpecsFooterScripts = get_field('globalspecs_footer_scripts', 'option');
		if($globalSpecsFooterScripts) { echo $globalSpecsFooterScripts; }
	}
	?>
	<!--=== ON PAGE FOOTER SCRIPTS ===-->

    <!-- Exit popup modal script-->
    <?php if(ICL_LANGUAGE_CODE=='en'){

        $popup = false;
        if (is_page(['home' , 6671 , 8655 , 8787 , 1277111331 ])){
            $popup = true;
        } elseif (is_singular('specs')){
            $popup = true;
        } elseif (is_tax('products','43')){
            $popup = true;
        }

        if( get_field('exit_popup_on', 'option') ) {
            if ($popup) {
                $script_path = get_stylesheet_directory_uri() . '/js/form-popup.js';
                ?>
                <script src="<?= $script_path ?>"></script>

            <?php }
        }
    }
    ?>
    <!-- Exit popup modal script END-->
</body>
</html>