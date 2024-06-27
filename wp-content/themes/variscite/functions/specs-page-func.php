<?php
/*********************************************
 ** THIS PAGE CONTAINS FUNCTIONS CREATED
 ** FOR PRODUCT SPECS PAGE / INFO PAGE
 ** PATH: THEME_PATH.'/single-specs.php'
 *********************************************/


/*********************************************
 ** TOP BACKGROUND IMAGE
 *********************************************/
function specs_topbgimg_style($bgimg) {

    if( empty($bgimg) ) {
        $bgjpg 	= get_field('optage_defaults_specs_page', 'option');

        echo '
		<style>
		.top-slider {background: url('.$bgjpg.') no-repeat center top;}
		</style>
		';
    }
    else {
        echo '
		<style>
		.top-slider {background: url('.$bgimg.') no-repeat center top;}
		</style>
		';
    }
}

/*********************************************
 ** TOP BACKGROUND IMAGE
 *********************************************/
function specs_top_bgimg($bgimg) {
    if( empty($bgimg) ) {
        $bgjpg 	= get_field('optage_defaults_specs_page', 'option');
        $bgwebp = get_field('optage_defaults_specs_page_webp', 'option');
    }

    return 'background: url('.$bgwebp.') no-repeat center top; background: url('.$bgjpg.') no-repeat center top;';
}


/*********************************************
 ** PAGE TITLE (considering mobile)
 *********************************************/
function specs_page_title($title) {

    $tArr	= explode(':', $title); 	if(count($tArr) > 1) {$dots = ':';} else {$dots = '';}

    $title 	= '<strong>'.$tArr[0].'</strong>'.$dots.'<br>';
    $title 	.= ( !empty($tArr[1]) ? '<span>'.$tArr[1].'</span>' : '' );

    return $title;
}


/*********************************************
 ** TOP SLIDER
 *********************************************/
function specs_product_slider($pid){

    $media 			= get_field('vrs_specs_slider_media', $pid);
    $images 		= get_field('vrs_specs_slider_images', $pid);
    $slideSpeed		= get_field('optage_defaults_specs_sliderspeed', 'option');   if(!$slideSpeed) {$slideSpeed = 6000;}
    $productname 	= get_the_title($pid);

    if( !empty($media) ) {

        $counter		= 0;
        $slides			= '';
        $indicators		= '';
        $state			= 'active';

        foreach ($media as $item) {


            if( !empty($item['sliderimgvideo']) ) {
                preg_match('/src="([^"]+)"/', $item['sliderimgvideo'], $match);
                $url = $match[1];

                $mediaItem = '<div class="item-vid item-overlay"><a href="'.$url.'&rel=0" class="videoLightbox"><img src="'.$item['sliderimg'].'" alt="'.$item['sliderimgalt'].'" class="img-responsive"></a></div>';
            }
            else {
                $mediaItem = '
				<div class="item-img">
					<picture  class="img-responsive">
						'.( !empty($item['webp_sliderimg']) ? '<source srcset="'.$item['webp_sliderimg'].'" type="image/webp"  alt="'.$item['sliderimgalt'].'" class="img-responsive">' : '' ).'
						<img src="'.$item['sliderimg'].'" alt="'.$item['sliderimgalt'].'" class="img-responsive">
					</picture>
				</div>
				<div class="item-shadow	"><img src="'.IMG_URL.'/product-shadow.png" alt="shadow"></div>
				'.( !empty($item['sliderimgname']) ? '<span class="prodImgName">'.$item['sliderimgname'].'</span>' : '' ).'
				';
            }

            $slides .= '
			<div class="item '.$state.' relative" item-type="'.($item['sliderimgvideo'] ? 'video' : 'image').'">
				'.$mediaItem.'
			</div>
			';

            $indicators .= '<li data-target="#specproductcarousel" data-slide-to="'.$counter.'" class="'.$state.'"></li>';

            $state = '';
            $counter++;
        }

        // CONTROLLERS (not in mobile)
        $controllers = '';
        if(count($media) > 1) {
            $controllers = '
		    <ol class="carousel-indicators">
                '.$indicators.'
            </ol>
            <!-- Controls -->
			<a class="left carousel-control" href="#specproductcarousel" role="button" data-slide="prev"> <img src="'.IMG_URL.'/specs/topslider-slider-arrow-left.png" alt="'.__('Previous', THEME_NAME).'"> </a>
			<a class="right carousel-control" href="#specproductcarousel" role="button" data-slide="next"> <img src="'.IMG_URL.'/specs/topslider-slider-arrow-right.png" alt="'.__('Next', THEME_NAME).'"> </a>
		';
        }



        return '
		<div class="specs-product-wrapper">
			<div id="specproductcarousel" class="carousel specproductcarousel slide carousel-fade" data-ride="carousel" data-interval="'.$slideSpeed.'">
				<div class="carousel-inner" role="listbox">'.$slides.'</div>
				'.$controllers.'
			</div>

		</div>
		
		
		';
    }
}






/*************************************************
 ** GET A QUOTE BUTTON GENERATOR
 *************************************************/
function custom_getquote_btn($isit_kit, $store_inlink, $store_exlink, $button_text, $xclasses) {
  

    if(empty($button_text)) {
        
        if ( $isit_kit && !empty($store_exlink) || $isit_kit && !empty($store_inlink)) {
            $button_text = 'Order a kit';
        } else {
            
            $button_text = 'Documentation';
        }
    } else {
        if($button_text=="Documentation" && !empty(get_field('vrs_specs_doc_block')) ) {
            $button_text = 'Documentation';
        }
        else{
            if ( $button_text=="Documentation"){
                $button_text = 'Get a Quote';
            }
            else{
                $button_text = $button_text;
            }
            
        }
       
    }
    

    if( is_array( $store_inlink ) && isset( $store_inlink[0] ) && empty( $store_inlink[0] ) ){
        $store_inlink = false;
    }

    if( !empty($store_exlink) || $store_exlink != null ) {
        echo '<a href="'.$store_exlink.'" class="btn btn-warning btn-lg quote-scroll '.$xclasses.'" data-to="get-a-quote" target="_blank"><span class="text">'.__($button_text, THEME_NAME).'</span> <img src="'.IMG_URL.'/button-arrow.png" alt="arrow"></a>';
    }
    elseif( !empty($store_inlink) || $store_inlink != null ) {
        echo '<a href="'.get_permalink($store_inlink).'" class="btn btn-warning btn-lg quote-scroll '.$xclasses.'" data-to="get-a-quote" target="_parent"><span class="text">'.__($button_text, THEME_NAME).'</span> <img src="'.IMG_URL.'/button-arrow.png" alt="arrow"></a>';
    }
    else {

        if($isit_kit){
            
            $scroll_to = "get-a-quote";
        }
        else{
            
            if(!empty(get_field('vrs_specs_doc_block')) ) {
                $scroll_to = "documentation";
            }
            else{
                $scroll_to = "get-a-quote";
            }
            
            
        }

        echo '<button class="btn btn-warning btn-lg quote-scroll side-scroll'.$xclasses.'" data-to="'.$scroll_to.'"><span class="text">'.__($button_text, THEME_NAME).'</span> <img src="'.IMG_URL.'/button-arrow.png" alt="arrow"></button>';
    }

}




/*********************************************
 ** GET PRODUCT ATA PDF URL
 *********************************************/
function product_data_pdf($postid) {
    $file = get_field('pdf_file', $postid);
    $link = get_field('pdf_direct_link', $postid);

    if($file) { return $file; }
    else  { return $link; }
}




/*********************************************
 ** COMPLIANCE ICONS
 *********************************************/
function specs_compliance_icons($iconsArr, $local_compliance) {

    $items = null;


    if( ($local_compliance && in_array('on', $local_compliance)) && !empty($iconsArr) ) {
        $items = '';
        foreach( $iconsArr as $icon) {
                $items .= '<li><img src="'.$icon['compliance_icon']['url'].'" alt="'.$icon['compliance_icon']['alt'].'"></li>';
        }
        return '<ul class="compliance-list test lsnone p0">'.$items.'</ul>';
    }
    else {
        $cicons = get_field('product_compliance_icons', 'option');

        foreach( $cicons as $icons) {
            $items .= '
			<li>
				<picture  class="img-responsive test2">
					'.( !empty($icons['compliance_icon_webp']) ? '<source srcset="'.$icons['compliance_icon_webp'].'" type="image/webp"  alt="'.$icons['compliance_icon_alt'].'" class="img-responsive">' : '' ).'
					<img src="'.$icons['compliance_icon'].'" alt="'.$icons['compliance_icon_alt'].'" class="img-responsive">
				</picture>
			</li>
			';
        }

        return '<ul class="compliance-list lsnone p0">'.$items.'</ul>';
    }
}



/*********************************************
 ** SPECS TABS LOOP
 *********************************************/
function specs_specification_tabs($tabsArr, $postid) {



    $status 		= 'active';
    $tabsName 		= '';
    $tabsContent 	= '';

    $kit_check		= get_field('vrs_specs_evaluation_kit', $postid);
    $isit_kit		= ( !empty($kit_check[0]) && $kit_check[0] == 'evkit' ? true : false );


    foreach($tabsArr as $tab) {

        $tabId				= str2id($tab['vrs_specs_tbltab_name']);
        $tabsName 			.= '<li role="presentation" class="'.$status.'"><a href="#'.str2id($tabId).'" aria-controls="'.str2id($tabId).'" role="tab" data-toggle="tab"><span>'.$tab['vrs_specs_tbltab_name'].'</span></a></li>';
        $tableArr 			= $tab['vrs_specs_info_table'];

        $tableCount			= 0;
        $table 				= '';

        // BUILD TAB TABLE
        if( !empty($tableArr) ) {
            foreach($tableArr as $singleTable) {

                $tableData	= $singleTable['table'];
                $tableHead	= '';
                $tableBody	= '';



                // TABLE HEAD
                if ( !empty($tableData['header']) ) {

                    foreach ( $tableData['header'] as $th ) {
                        $tableHead .= '<th>'.$th['c'].'</th>';
                    }

                    // $tableHead = '<thead class="'.( $tableCount > 0 ? 'sub-table' : '' ).'"><tr>'.$tableHead.'</tr></thead>';
                    $tableHead = '<thead class="sub-table '.( $tableCount == 0 ? 'open ' : '' ).'"><tr>'.$tableHead.'</tr></thead>';
                }


                // TABLE BODY
                $body_trs 	= '';
                $tr_counter = 0;

                foreach ( $tableData['body'] as $tr ) {

                    $body_tds 	= '';
                    $tdcounter	= 0;

                    foreach ( $tr as $td ) {
                        $body_tds .= '<td class="'.($tdcounter == 0 ? 'tr-label '.strip_tags(str2id($td['c'])) : '').'">'.apply_filters('the_content', do_shortcode($td['c'])).'</td>';

                        $tdcounter++;
                    }

                    $body_trs .= '<tr class="'.strip_tags(str2id($tr[0]['c'])).'">'.$body_tds.'</tr>';

                    $tr_counter++;
                }
                $tbody = '<tobdy>'.$body_trs.'</tobdy>';



                // RETURN TABLE
                $table .= '<table class="table table-responsive table-striped">'.$tableHead.$tbody.'</table>';


                if($isit_kit && trim(strtolower($tab['vrs_specs_tbltab_name'])) == 'included som') {

                    $relSom = get_field('vrs_specs_relsom_link', $postid);
                    if($relSom) {
                        $table .= '
						<div class="relSom">
							<a href="'.get_permalink($relSom).'">'.__('More Info', THEME_NAME).'</a>
						</div>
						';
                    }
                }

                $tableCount++;
            }
        } else {$table = '';}

        $tabsContent .= '<div role="tabpanel" class="tab-pane '.$status.' '.( !empty($tableArr) && count($tableArr) > 1 ? 'multiple-tables' : '' ).' " id="'.strip_tags($tabId).'">'.$table.'</div>';
        $status = '';




    }



    // PARENT POST CATEGORY (for classes)
    $terms = wp_get_post_terms( $postid, 'products' );
    if( !empty($terms[0]->parent) ) {$pterm = $terms[0]->parent;} else {$pterm = $terms[0]->term_id;}



    return '
	<div class="specs-wrap term-'.$pterm.'">
		<!-- Nav tabs -->
		<div class="container tab-navs-wrap">
			<div class="inner inner-tabs-'.count($tabsArr).'">
				<ul class="nav nav-tabs tabs-'.count($tabsArr).'" role="tablist">'.$tabsName.'</ul>
			</div>
		</div>

		<!-- Tab panes -->
		<div class="container data-tables-box"><div class="tab-content">'.$tabsContent.'</div></div>
	</div>
	';
}





/*********************************************
 ** EVALUATION KIT
 *********************************************/
function spec_evaluation_kit( $kit_postid, $custom_image, $webp_image, $alter_link ) {


    if($kit_postid) {
        $price		= get_field('vrs_specs_price', $kit_postid);
        $desc		= strip_tags( get_field('vrs_specs_product_middesc', $kit_postid) );

        if(!$custom_image) {$thumb = smart_thumbnail($kit_postid, NULL, NULL, NULL, get_the_title($kit_postid));}
        else {

            $thumb = '
			<picture class="img-responsive">
				'.( !empty($webp_image) ? '<source srcset="'.$webp_image['url'].'" type="image/webp"  alt="'.$custom_image['alt'].'">' : '' ).'
				<img src="'.$custom_image['url'].'" alt="'.$custom_image['alt'].'">
			</picture>
			';

        }


        // CONDITIONAL ORDER A KIT BTN
        $btns 				= array();
        $btns['order'] 		= !empty($alter_link) ? '<li><a href="'.$alter_link.'" target="_blank" class="btn btn-warning btn-lg orderkit-btn"><span class="text">'.__('order a kit', THEME_NAME).'</span></a></li>' : '';
        $btns['kitspecs'] 	= '<li><a href="'.get_permalink($kit_postid).'" class="btn btn-default btn-lg kitspecs-btn"><span class="text">'.__('Kit Specs', THEME_NAME).'</span> </a></li>';


        // RETURN RESULT
        return '
		<div id="evaluation-kit" class="section-box">
            <div class="container">
                <div class="row">
                    <div class="col-xs-12 col-sm-4 col-md-3">
                        <h3 class="section-title">'.get_the_title($kit_postid).'</h3>
                        '.($price ? '<div class="price">'.$price.'</div>' : '').'
                        '.($desc ? '<div class="desc"> <p>'.$desc.'</p> </div>' : '').'
                        <ul class="lsnone btns-list-box">
                            '.implode(' ', $btns).'
                        </ul>
                    </div>
                    <div class="col-xs-12 col-sm-7 col-sm-offset-1 col-md-8 p0 relative thumb-box">
                        '.$thumb.'
                    </div>
                </div>
            </div>
		</div>
		';
    }

}




/*********************************************
 ** DOCUMENTATION BOXES
 *********************************************/
function spec_docs_boxes($boxes, $pid) {

    $docboxes 		= '';
    $boxCounter		= 0;

    foreach($boxes as $box) {

        $boxTitle 	= $box['vrs_specs_doc_block_title'];  			if(!$boxTitle) {$boxTitle = __('Docs', THEME_NAME);}
        $boxBg 		= $box['vrs_specs_doc_block_bgclr'];  			if(!$boxBg) {$boxBg = '#04386f';}
        $files 		= '';
        $docsArr 	= $box['vrs_specs_doc_block_documents'];

        // BUILD DOCS FOR THIS BOX

        if(is_array($docsArr)) {
            foreach($docsArr as $doc) {

                $doc = $doc['doc_document_upload'];

                // doc data
                $title		= get_the_title($doc);
                $sname		= get_field('pdf_short_name', $doc);	if(!$sname) {$sname = $title;}
                $file		= get_field('pdf_file', $doc);			if(!$file) {$file = get_field('pdf_direct_link', $doc);}
                $fileType	= get_field('pdf_file_type', $doc);
                $version	= get_field('pdf_file_version', $doc);
                $pdf_class = '';

                if(!empty($fileType) && $fileType == 'pdf') {
                    $pdf_class = 'popup-pdf';
                }
                if(!$fileType) {$fileType = 'pdf';}
                $version	= get_field('pdf_file_version', $doc);

                // $files 		.= '<li class="'.$fileType.'"> <img src="'.IMG_URL.'/files/'.$fileType.'.png" alt="'.$fileType.' '.__('Files', THEME_NAME).'"> <a href="'.$file.'" target="_blank">'.$sname.'</a> '.($version ? '<span class="verLbl">('.$version.')</span>' : '').'</li>';
                $files 		.= '<li class="'.$fileType.' '.$pdf_class.'"> <span class="filetype '.$fileType.'"></span> <a href="'.$file.'" target="_blank">'.$sname.'</a> '.($version ? '<span class="verLbl">('.$version.')</span>' : '').'</li>';
            }
        }

        $docboxes .= '
        <div class="doc-box-wrap col-md-4 col-sm-6 col-xs-12">
            <div class="doc-box-collapse" style="background: '.$boxBg.';">
                <h4 class="box-title" role="button" data-toggle="collapse" href="#docbox-0'.$boxCounter.'" aria-expanded="true" aria-controls="docbox-0'.$boxCounter.'">'.$boxTitle.'</h4>
                <div id="docbox-0'.$boxCounter.'" class="collapse in">
                    <div class="files-box">
                        <ul class="files-list">'.$files.'</ul>
                    </div>
                </div>
            </div>
        </div>
        ';

        $boxCounter++;
    }

    if(!is_page_template( 'custom-pages/page-specs-new.php' ) ) {
        $html_output = '<h2 class="section-title">'.get_field('vrs_specs_doc_section_title', $pid).'</h2>';
    }

    $html_output .= '<div class="row">'.$docboxes.'</div>';

    return $html_output;

}



/*********************************************
 ** ACCESSORIES SLIDER
 *********************************************/
function specs_accessories_slider($postids, $cpid) {


    if( !empty($postids) ) {


        $slides 	= '';
        $postids 	= wp_list_pluck( $postids, 'accessory' );
        $query 		= new WP_Query( array(
                'post_type' => array('products', 'specs'),
                'posts_per_page' => -1,
                'post__in' => $postids,
                'orderby'  => 'post__in'
        ) );

        // The Loop
        if ( $query->have_posts() ) {
            while ( $query->have_posts() ) {
                $query->the_post();


                // item id
                $pid 		= get_the_ID();
                $title 		= get_the_title();
                $link 		= get_permalink();
                $sname 		= get_field('vrs_specs_short_pname');
                $thumb 		= smart_thumbnail($pid, 159, 120, '', $title, get_field('optage_defaults_specs_accessory_img', 'option'), true );

                $slides .= '
				<div class="item swiper-slide" title="'.$title.'">
					<a href="'.$link.'">'.$thumb.'</a>
					<div class="item-title text-center fs16"><a href="'.$link.'">'.$sname.'</a></div>
				</div>
				';
            }
            wp_reset_postdata();
            wp_reset_query();

            $accesories = '
			<div class="container-wrap">
				<div class="container new-spec-page">
					<h2 class="section-title">'.get_field('vrs_specs_accesories_title', $cpid).'</h2>
					<div class="acc-slider-wrapper">
                        <div id="accesoriesSlider" class="swiper-container">
                            <div class="swiper-wrapper"> '.$slides.' </div>
                        </div>
                        <div class="swiper-button-prev"></div>
                        <div class="swiper-button-next"></div>
                    </div>
				</div>
			</div>
			';



            return $accesories;
        }
    }

}




/*********************************************
 ** QUOTE FORM
 *********************************************/
function specs_quoteform_box($fieldkey, $cpid, $vrs_specs_form_type = 'single-step-form'){
    // IS CONTACT STEP FORM
    $isContactStepForm = $vrs_specs_form_type == 'contact-step-form' ? true : false;

    // PARAMS
    $tag          = isset($_GET['noajax']) ? 'form' : 'div';
    $actual_link  = "https://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
    $form_atts    = isset($_GET['noajax']) ? "method=\"post\" action=\"$actual_link\"" : '';
    $name_prefix  = isset($_GET['noajax']) ? 'form_data[' : '';
    $name_suffix  = isset($_GET['noajax']) ? ']' : '';

    $is_valid = true;
    $message  = '';

    if(isset($_GET['noajax'])) {
        ?>

        <script type="text/javascript">
            console.log('noajax page');
        </script>

        <?php
    }

    if(isset($_POST['submit']) && esc_html($_POST['submit']) === 'Submit') {
        $email = $_POST['form_data'];

        $reqs  = array(
            'first_name'   => 'First Name',
            'last_name'    => 'Last Name',
            'company'      => 'Company Name',
            'email'        => 'A Valid Email',
            'phone'        => 'Phone',
            'country'      => 'Country',
            'country_code' => 'Country'
        );

        foreach(explode(',', esc_html($email['required'])) as $req) {

            if(! $is_valid) {
                continue;
            }

            if(! isset($email[$req]) || empty($email[$req])) {
                $is_valid = false;
                $message  = "{$reqs[$req]} is Required";
            }
        }

        if($is_valid && strlen($email['first_name']) > 50) {
            $is_valid = false;
            $message  = "A Valid {$reqs['first_name']} is Required";
        }

        if($is_valid && strlen($email['last_name']) > 50) {
            $is_valid = false;
            $message  = "A Valid {$reqs['last_name']} is Required";
        }

        if($is_valid && ! is_valid_email($email['email'])) {
            $is_valid = false;
            $message  = "{$reqs['email']} is Required";
        }

        // EXCLUD FORM INPUTS VALIDATION FOR CONTACT STEP FORM
        if (!$isContactStepForm) {
            if($is_valid && (! isset($email['quote-product']) || empty($email['quote-product']))) {
                $is_valid = false;
                $message  = 'Please select SoM Platform';
            }

        }

        if($is_valid && (! isset($email['quote-quantity']) || empty($email['quote-quantity']))) {
            $is_valid = false;
            $message  = 'Please select Estimated Quantities';
        }

        if($is_valid) {
            require_once(WP_CONTENT_DIR . '/plugins/variscite-salesforce/inc/global-ajax.php');
            $resp = global_ajaxfunc();

            if(isset($resp['thanks']) && ! empty($resp['thanks'])) {
                wp_redirect($resp['thanks']);
                exit;
            }
        } else {
            ?>

            <script>
                document.addEventListener('DOMContentLoaded', function(e) {
                    document.getElementById('quote-formbox').scrollIntoView({
                        behavior: 'instant'
                    });
                });
            </script>

            <?php
        }
    }


    // QUOTE PRODUCT ADDONS (SELECTED BY USER)
    $shortName              = get_field('vrs_specs_short_pname', $cpid);
    $quoteSettings 		    = get_field('quote_settings', 'option');

    // CUSTOM (PAGE SPECIFIC) DATA
    $cThanksPage 		    = get_field('vrs_specs_cthanks_page', $cpid);
    $cpuName                = get_field('vrs_specs_processor_pname', $cpid);
    // EXCLUD FORM INPUTS FOR CONTACT STEP FORM
    if (!$isContactStepForm) {
        $quoteProducts		= get_field('quote_product_addons_group', 'option');
        $selectedProducts 	= $quoteProducts[$fieldkey];
        $productCheckboxes  = '';
        $cSomProducts 		= get_field('vrs_specs_custom_som_products', $cpid);
        if(!empty($cSomProducts)) {
            $selectedProducts = $cSomProducts;
        }
        // BUILD SOME CHECKBOXES
        foreach($selectedProducts as $product) {

            $product 	= ( !empty($product['caddon_product']) ? $product['caddon_product'] : $product['product_addons'] );
            $moduleName = get_field('vrs_specs_processor_pname', $product->ID);
            $checked    = (isset($_GET['noajax']) && $_POST['form_data'] && isset($_POST['form_data']['quote-product']) && in_array($moduleName, $_POST['form_data']['quote-product']) ? 'checked' : (($cpuName == $moduleName) ? 'checked' : ''));

            $productCheckboxes .= '
            <div class="col-md-4 col-sm-6 col-xs-12 paddons-checkbox required">
                <input type="checkbox" name="' . sprintf('%s%s%s%s', $name_prefix, 'quote-product', $name_suffix, (isset($_GET['noajax']) ? '[]' : '')) . '" id="product-'.$product->ID.'" value="'.$moduleName.'"'.$checked.'><label for="product-'.$product->ID.'"> <span></span> '.$moduleName.'</label>
            </div>
            ';
        }
    }

    // EXCLUD FORM INPUTS FOR CONTACT STEP FORM
    // QUATITIES FIELDSc
    $quanRadios 	= '<option value="" ' . (! isset($_GET['noajax']) || ! $_POST['form_data']['quote-quantity'] ? 'selected' : '') . '>'.__('Choose Quantity', THEME_NAME).'</option>';
    $quatitiesArr	= $quoteSettings['quantities_values'];
    $quatitiesArr	= preg_split('/\r\n|\r|\n/', $quatitiesArr);;

    foreach($quatitiesArr as $quan) {
        $quanVal = str_replace('>', 'more-than-', $quan);
        $quanRadios .= '<option ' . (isset($_GET['noajax']) && $_POST['form_data']['quote-quantity'] == $quan ? 'selected' : '') .' value="'.$quan.'">'.$quan.'</option>';
    }

    // COUNTRY LIST HANDLER FOR SELECT FIELD
    $cnSelect  = '<option value="" ' . (! isset($_GET['noajax']) || ! $_POST['form_data']['country_code'] ? 'selected' : '') . '>'.__('Country', THEME_NAME).'</option>';
    $countries = get_field('quote_product_country_select', 'option');
    $countries = preg_split("/\\r\\n|\\r|\\n/", $countries);

    foreach($countries as $country) {
        $cnSelect .= '<option ' . (isset($_GET['noajax']) && $_POST['form_data']['country_code'] == str_replace(' ', '-', strtolower($country)) ? 'selected' : '') .' value="'.str_replace(' ', '-', strtolower($country)).'">'.$country.'</option>';
    }


    // USER DEVICE

    $leadsource = "Web";
    if(ICL_LANGUAGE_CODE == 'de') {
        $leadsource = 'de Web';
    } else if(ICL_LANGUAGE_CODE == 'it') {
        $leadsource = 'it Web';
    }

    $requiredFields = "first_name,last_name,email,company,country_code,phone";
    return '
	<' . $tag . ' ' . $form_atts . 'class="quote-form" id="prodQuoteForm">
        <input type="hidden" id="action_type" name="action_type" value="send_quote" />
        ' . ($isContactStepForm ?
            '<input style="display: none;" type="checkbox" id="product-' . $cpid . '" name="quote-product" value="' . $cpuName . '" checked>' : '' ) . '
        <div class="inner">
            <div class="row">
                <div class="col-md-4 form-group"><input type="text" name="' . sprintf('%s%s%s', $name_prefix, 'first_name', $name_suffix) . '" id="first_name" class="form-control" placeholder="'.__('First Name', THEME_NAME).'" value="' . (isset($_GET['noajax']) ? esc_html($_POST['form_data']['first_name']) : '') . '"></div>
                <div class="col-md-4 form-group"><input type="text" name="' . sprintf('%s%s%s', $name_prefix, 'last_name', $name_suffix) . '" id="last_name" class="form-control" placeholder="'.__('Last Name', THEME_NAME).'" value="' . (isset($_GET['noajax']) ? esc_html($_POST['form_data']['last_name']) : '') . '"></div>
                <div class="col-md-4 form-group"><input type="text" name="' . sprintf('%s%s%s', $name_prefix, 'email', $name_suffix) . '" id="email" class="form-control" placeholder="'.__('Email', THEME_NAME).'" value="' . (isset($_GET['noajax']) ? esc_html($_POST['form_data']['email']) : '') . '"></div>
                <div class="col-md-4 form-group"><input type="text" name="' . sprintf('%s%s%s', $name_prefix, 'company', $name_suffix) . '" id="company" class="form-control" placeholder="'.__('Company', THEME_NAME).'" value="' . (isset($_GET['noajax']) ? esc_html($_POST['form_data']['company']) : '') . '"></div>
                <div class="col-md-4 form-group"><select name="' . sprintf('%s%s%s', $name_prefix, 'country_code', $name_suffix) . '" id="country_code" class="form-control">'.$cnSelect.'</select></div>
                <div class="col-md-4 form-group"><input type="text" id="phone" name="' . sprintf('%s%s%s', $name_prefix, 'phone', $name_suffix) . '" class="form-control" placeholder="'.__('Phone', THEME_NAME).'" value="' . (isset($_GET['noajax']) ? esc_html($_POST['form_data']['phone']) : '') . '"></div>
            </div>
            ' . (!$isContactStepForm ?
            '<div class="row product-addons-box options-box">
                <div class="col-xs-12 formBlockMiniTitle">'.__('System on Module Platform', THEME_NAME).'</div>
                <div class="col-xs-12 p0">'.$productCheckboxes.'</div>
            </div>' : '' ) .


        '<div class="row product-quantities-box options-box">
                <div class="col-xs-12 col-sm-4 col-lg-3 formBlockMiniTitle">'.__('Estimated Quantities', THEME_NAME).'</div>
                <div class="col-xs-12 col-sm-6 col-md-4">
                    <select name="' . sprintf('%s%s%s', $name_prefix, 'quote-quantity', $name_suffix) . '" id="quote-quantity" class="form-control">'.$quanRadios.'</select>
                </div>
            </div>
            
            <div class="row">
                <div class="col-xs-12 form-group"><textarea maxlength="2000" id="note" name="' . sprintf('%s%s%s', $name_prefix, 'note', $name_suffix) . '" cols="30" rows="10" class="form-control" placeholder="'. ($isContactStepForm ? __('Tell us about your project', THEME_NAME) : __('Note', THEME_NAME)) .'"></textarea></div>
                <div class="col-xs-12 form-group">
                    <div class="paddons-checkbox">
                        <input type="checkbox" name="' . sprintf('%s%s%s', $name_prefix, 'agreement', $name_suffix) . '" id="agreement" value="' . date('Y-m-d\TH:i:s\Z') . '"' . (isset($_GET['noajax']) && isset($_POST['form_data']['agreement']) && ! empty($_POST['form_data']['agreement']) ? 'checked' : '') . '>
                        <label for="agreement"><span></span>' . esc_attr__('I agree to the Variscite', THEME_NAME) .' <a target="_blank" href="'. get_site_url() .'/privacy-policy/">' . __(' Privacy Policy</a>', THEME_NAME) .'</label>
                    </div>
                </div>
            </div>
            
            <div class="row btn-box">
                <div class="col-sm-6 col-md-8">
                    <div class="notice">' . (! $is_valid && isset($_GET['noajax']) ? '<i class="fa fa-exclamation-triangle c6"></i> ' . $message : '') .
        '</div>
                </div>
                <div class="col-sm-6 col-md-4">
                    <div class="submit-wrap"><input type="submit" name="submit" class="btn btn-warning btn-lg btn-arrow-01 submitQuoteRequest" value="'.__('Submit', THEME_NAME).'"></div>
                </div>
            </div>

        </div>

        <input type="hidden" id="curl" name="' . sprintf('%s%s%s', $name_prefix, 'curl', $name_suffix) . '" value="'.get_permalink(get_the_ID()).'">
        <input type="hidden" id="Product_page__c" name="' . sprintf('%s%s%s', $name_prefix, 'Product_page__c', $name_suffix) . '" value="'.$shortName.'">
        <input type="hidden" id="leadsource" name="' . sprintf('%s%s%s', $name_prefix, 'leadsource', $name_suffix) . '" value="'. $leadsource . '">
        <input type="hidden" id="required" name="' . sprintf('%s%s%s', $name_prefix, 'required', $name_suffix) . '" value="'. $requiredFields. '">
        <input type="hidden" id="thanks" name="' . sprintf('%s%s%s', $name_prefix, 'thanks', $name_suffix) . '" value="'.($cThanksPage ? get_permalink($cThanksPage) : get_permalink( $quoteSettings['thanks_page'] )).'">
        
        <input type="hidden" id="event_name" value="form-mainSite-getAQuote-success">

        <!--=== ADWORDS FIELDS ===-->
        <input type="hidden" id="Campaign_medium__c" name="' . sprintf('%s%s%s', $name_prefix, 'Campaign_medium__c', $name_suffix) . '" value="' . (isset($_GET['noajax']) ? esc_html($_POST['form_data']['Campaign_medium__c']) : '') . '">
        <input type="hidden" id="Campaign_source__c" name="' . sprintf('%s%s%s', $name_prefix, 'Campaign_source__c', $name_suffix) . '" value="' . (isset($_GET['noajax']) ? esc_html($_POST['form_data']['Campaign_source__c']) : '') . '">
        <input type="hidden" id="Campaign_content__c" name="' . sprintf('%s%s%s', $name_prefix, 'Campaign_content__c', $name_suffix) . '" value="' . (isset($_GET['noajax']) ? esc_html($_POST['form_data']['Campaign_content__c']) : '') . '">
        <input type="hidden" id="Campaign_term__c" name="' . sprintf('%s%s%s', $name_prefix, 'Campaign_term__c', $name_suffix) . '" value="' . (isset($_GET['noajax']) ? esc_html($_POST['form_data']['Campaign_term__c']) : '') . '">
        <input type="hidden" id="Page_url__c" name="' . sprintf('%s%s%s', $name_prefix, 'Page_url__c', $name_suffix) . '" value="' . (isset($_GET['noajax']) ? esc_html($_POST['form_data']['Page_url__c']) : '') . '">
        <input type="hidden" id="Paid_Campaign_Name__c" name="' . sprintf('%s%s%s', $name_prefix, 'Paid_Campaign_Name__c', $name_suffix) . '" value="' . (isset($_GET['noajax']) ? esc_html($_POST['form_data']['Paid_Campaign_Name__c']) : '') . '">
        <input type="hidden" id="GA_id__c" name="' . sprintf('%s%s%s', $name_prefix, 'GA_id__c', $name_suffix) . '" value="' . (isset($_GET['noajax']) ? esc_html($_POST['form_data']['GA_id__c']) : '') . '">
        <!--=== ADWORDS FIELDS ===-->

        <input type="hidden" id="GA_id" name="' . sprintf('%s%s%s', $name_prefix, 'GA_id', $name_suffix) . '"  value="' . (isset($_GET['noajax']) ? esc_html($_POST['form_data']['GA_id']) : '') . '">
	</' . $tag . '>
	';
}




/*********************************************
 ** RELATED PRODUCTS
 *********************************************/
function specs_related_products($items, $pid) {

    $slides 	= '';

    // BUILD RELATED PRODUCTS
    foreach($items as $item) {
        $item 	= $item['product'];
        $plink 	= get_permalink($item->ID);
        $sname  = get_field('vrs_specs_short_pname', $item->ID);
        $slides .= '
        <div class="item related-product swiper-slide" title="'.$sname.'">
            <a href="'.$plink.'">
                <span class="rprod-inner-wrap">
                    '.smart_thumbnail($item->ID, 135, 135, '', $item->post_title, get_field('optage_defaults_specs_related_img', 'option')).'
                </span>
                <span class="product-name item-title text-center fs16">'.$item->post_title.'</span>
            </a>
        </div>
        ';
    }

    return '
    <div class="container">
        <div class="related-row">
            <h3 class="section-title">'.get_field('vrs_specs_related_products_title', $pid).'</h3>
            <div id="relatedMobileSlider" class="swiper-container"><div class="swiper-wrapper"> '.$slides.' </div></div>
        </div>
    </div>
    ';
}

/*********************************************
 ** PAGE TITLE (considering mobile) new product layout
 *********************************************/
function specs_new_page_title($title) {

    $tArr	= explode(':', $title); 	if(count($tArr) > 1) {$dots = ':';} else {$dots = '';}

    $title 	= '<strong>'.$tArr[0].'</strong>'.$dots;
    $title 	.= ( !empty($tArr[1]) ? '<span>'.$tArr[1].'</span>' : '' );

    return $title;
}

?>