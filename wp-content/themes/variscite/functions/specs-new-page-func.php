<?php
/*********************************************
** THIS PAGE CONTAINS FUNCTIONS CREATED
** FOR PRODUCT NEW SPECS PAGE / INFO PAGE
** PATH: THEME_PATH.'/custom-pages/page-specs-new.php'
*********************************************/


/*********************************************
** TOP SLIDER MOBILE
*********************************************/
function specs_product_slider_mobile($pid){

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
				'.( !empty($item['sliderimgname']) ? '<span class="prodImgName">'.$item['sliderimgname'].'</span>' : '' );
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
			<div id="specproductcarousel-mobile" class="carousel-mobile specproductcarousel slide carousel-fade" data-ride="carousel" data-interval="'.$slideSpeed.'">
				<div class="carousel-inner" role="listbox">'.$slides.'</div>
				'.$controllers.'
			</div>
		</div>
		';
	}
}


/*********************************************
** SPECS TABS LOOP New Layout
*********************************************/
function specs_specification_tabs_new_layout($tabsArr, $postid) {



	$tabsName 		= '';
	$tabsContent 	= '';

	$kit_check		= get_field('vrs_specs_evaluation_kit', $postid);
	$isit_kit		= ( !empty($kit_check[0]) && $kit_check[0] == 'evkit' ? true : false );
	 
	foreach($tabsArr as $tab) {

		if(!empty($tab['vrs_specs_tbltab_name'])) {
			$class = strtolower($tab['vrs_specs_tbltab_name']).'-icon';
		}

		$tabId				= str2id($tab['vrs_specs_tbltab_name']);

		if($tabId == 'cpu') {
			$status = 'active';
		}
 
		$tabsName 			.= '<li role="presentation" class="'.$class.' '.$status.'"><a href="#'.str2id($tabId).'" aria-controls="'.str2id($tabId).'" role="tab" data-toggle="tab"><span>'.$tab['vrs_specs_tbltab_name'].'</span></a></li>';
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


function get_highlight_tab_content($tabsArr, $postid) {

    $status 		= 'active';
    $tabsName 		= '';
    $tabsContent 	= '';

    $kit_check		= get_field('vrs_specs_evaluation_kit', $postid);
    $isit_kit		= ( !empty($kit_check[0]) && $kit_check[0] == 'evkit' ? true : false );

    foreach($tabsArr as $tab) {

        if( isset($tab['vrs_specs_tbltab_name']) && $tab['vrs_specs_tbltab_name'] != 'Highlights' ) {
            continue;
        }

        $tabId				= str2id($tab['vrs_specs_tbltab_name']);
        $tableArr 			= $tab['vrs_specs_info_table'];

        $tableCount			= 0;
        $table 				= '';
        $output = '';
        // BUILD TAB TABLE
        if( !empty($tableArr) ) {
            foreach($tableArr as $singleTable) {

                $tableData	= $singleTable['table'];
                $tableBody	= '';

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

				$anchors = '<a href="#" data-to="documentation" class="js-custom-scroll">Product Documentation</a>';

				$vrs_specs_wiki_page = get_field('vrs_specs_wiki_page', $postid);

				if(!empty($vrs_specs_wiki_page)) {
					$anchors .= ', <a href="'.$vrs_specs_wiki_page.'" target="_blank">Wiki Page</a>';
				}

                $tbody = '<tobdy>'.$body_trs.'</tobdy>';

                // RETURN TABLE
                $table .= '<table class="table table-responsive table-striped">'.$tbody.'</table>';
                $tableCount++;
                $tabsContent .= $table;
                $status = '';
            }
        } else {$table = '';}


    }

    $vrs_specs_product_middesc = get_field('vrs_specs_product_middesc');
    $col = 12;

    if((isset($tableArr) && !empty($tableArr)) && !empty($vrs_specs_product_middesc)) {
        $col = 6;
    }

	if((isset($tableArr) && !empty($tableArr)) || !empty($vrs_specs_product_middesc)) {
        $output .= '
        <div class="highlight diagonal-cut section-box sidebar-push" id="highlights-sec">
            <div class="container new-spec-page">
                <h2 class="section-title">Highlights</h2>
                <div class="highlight-wrap">
                    <div class="row">';
                        if(isset($tableArr) && !empty($tableArr)) {
                            $output .= 
                            '<div class="col-md-'.$col.'">
                                <div class="data-tables-box">'.$tabsContent.'</div>
                            </div>';
                        }

                        if(!empty($vrs_specs_product_middesc)) {
                            $output .='<div class="col-md-'.$col.'">'.$vrs_specs_product_middesc.'</div>';
                        }
        $output .='</div>';
        $output .= '<div class="text-center">';
		if(($first_installation_text = get_field('first_installation_text', 'option')) && !empty($first_installation_text)) {
			$output .= '<h3>'.$first_installation_text.'</h3>';
		}
        $output .= '<button class="btn btn-warning btn-lg js-custom-scroll" data-to="get-a-quote"><span class="text">Get a Quote</span> <img src="'.get_template_directory_uri().'/images/button-arrow.png" alt="arrow"></button>
                    </div>
                </div>
            </div>
        </div>';
    }

    return $output;
}
?>