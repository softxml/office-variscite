<?php
class sgFeaturedSliderWidget extends WP_Widget {
	
	// DEFINE THE WIDGET
	function __construct() {
		$widget_ops = array(
			'classname' 	=> 'sg_featuredslider_widg',
			'description'	=> __('A Custom widget created for featured slider By SITEIT', 'siteitsob')
		);
		
		parent::__construct('sgFeaturedSliderWidget', __('Featured Slider Widget (SiteIT)', 'siteitsob'), $widget_ops);
	}
	
	
	// auto build select array
	function sghttibgw_create_numstring_array($startNum, $endNum, $jumps, $sideString = NULL) {
		
		if($startNum && $endNum) {
			
			$data       = array();
			$counter    = $startNum;
			
			
			while($endNum > $counter ) {
				$data[$counter] = $counter.' '.$sideString;
				$counter        = $counter + $jumps;
			}
			
			return $data;
		}
	}
	
	
	// BUILD MARGIN or PADDING ARRAY
	function build_margpad_array($top, $right, $bottom, $left, $type) {
		$result  				= '';
		$arr[$type.'-top'] 		= $top;
		$arr[$type.'-right'] 	= $right;
		$arr[$type.'-bottom'] 	= $bottom;
		$arr[$type.'-left'] 	= $left;
		$arr 					= array_filter($arr);
		
		if(!empty($arr)) {
			foreach($arr as $key => $value) {
				$result .= $key.':'.$value.'px;';
			}
		}
		
		return $result;
	}
	
	
	
	function siteit_widget_fields() {
		return array(
			'use_mobile',
		);
	}
	

	function form_fileds_looper($instance) {
		
		// rtl fixes
		if(is_rtl()) {$floatDir = 'left';} else {$floatDir = 'right';}
		
		$formFields = '
		<div class="admin-row">
			This widget has no options. All featured slider options are controlled via the <a href="http://a17133-tmp.s100.upress.link/wp-admin/admin.php?page=acf-options" target="_blank">Options Page</a>
		</div>
		';
		
		return $formFields;
	}
	
	
	
	// BUILDING THE WIDGET FORM
	function form($instance) {
		
		$defaults = array();
		
		foreach($this->siteit_widget_fields() as $f) {
			$defaults[$f] = '';
		}
		
		
		$instanceArray 		= $defaults; // set default values
		$instance 			= wp_parse_args( (array) $instance, $instanceArray );
		
		// widget title
		echo '
		<div>'.$this->form_fileds_looper($instance).'</div>
		';
		
	}
	
	
	// SAVE FORM VALUES
	function update($new_instance, $old_instance) {

		$instance	= $old_instance;
		$fieldNames = $this->siteit_widget_fields();
		
		foreach($fieldNames as $field) {
			$instance[$field] =	$new_instance[$field];
		}
		
		return $instance;
	}
	
	
	
	
	// DISPLAY THE WIDGET
	function widget($args, $instance) {
		extract($args, EXTR_SKIP);
		
		// BASIC SETTINGS
		$result 		= '';
		$before_widget 	= '<div class="singleWidget sitBuilderWidget featuredSliderWidget">';
		$after_widget  	= '</div>';

		// get data from options page
		$data                   = get_field('optage_featured_slider', 'option');
		$section['title']       = $data['optage_featured_slider_title'];
		$section['bgimg']       = $data['optage_featured_slider_bgimg'];
		$catalog['label']       = $data['optage_featured_slider_cataloglbl'];
		$catalog['url']         = get_field( 'pdf_file' , $data['optage_featured_slider_catalogfile']->ID);
		$download['link']        = $data['optage_featured_slider_download_link'];
		$products['label']      = $data['optage_featured_slider_linklbl'];
		$products['url']        = $data['optage_featured_slider_linkurl'];
		
		
		// build slider
		$status = 'active';
		$counter		= 0;
		$indicators		= '';
		$items  		= '';
		$slides 		= $data['optage_featured_slider'];
		
		foreach($slides as $slide) {
			
			$slideData = $slide['optage_featured_slider_product'];

            $img 		= $slide['optage_featured_slider_productimg'];
            $imgWebp 	= $slide['optage_featured_slider_productimg_webp'];

			
			if($data['optage_featured_slider_download_link']){
				$button_link = '<li><a href="'.$download['link']  .'" class="btn btn-warning btn-lg" id="HomepagePDF"><span>'.$catalog['label'] .'</span></a></li>';
			} else {
				$button_link = '<li><a href="'.$catalog['url'] .'" class="btn btn-warning btn-lg" id="HomepagePDF" target="_blank"><span>'.$catalog['label'] .'</span></a></li>';
			}
			
			$items .= '
            <div class="item slider-item '.$status.'">
                <div class="row">
                    <div class="col-md-4 col-sm-5 col-xs-12">
                        <h4 class="item-title"><a href="'.get_permalink($slideData->ID).'">'.$slideData->post_title.'</a></h4>
                    </div>
                    <div class="col-md-8 col-sm-7 col-xs-12">
						<a href="'.get_permalink($slideData->ID).'">
							<picture class="img-responsive">
								'.( !empty($imgWebp) ? '<source srcset="'.$imgWebp.'" type="image/webp" alt="'.attachment_alt_fromurl($img).'">' : '' ).'
								<img src="'.$img.'" alt="'.attachment_alt_fromurl($img).'">
							</picture>
						</a>
                    </div>
                </div>
            </div>
			';
			
			$indicators .= '<li data-target="#featuredSlider" data-slide-to="'.$counter.'" class="'.$status.'"></li>';
			
			$status = '';
			$counter++;
		}
		
		

		$controllers = '
        <a class="left carousel-control hideInMobile" href="#featuredSlider" role="button" data-slide="prev"> <img src="'.IMG_URL.'/featured-slider-arrow-left.png" alt="Prev Slide"> <span class="sr-only">Previous</span> </a>
        <a class="right carousel-control hideInMobile" href="#featuredSlider" role="button" data-slide="next"> <img src="'.IMG_URL.'/featured-slider-arrow-right.png" alt="Next Slide"> <span class="sr-only">Next</span> </a>
        <ol class="carousel-indicators hideInDesktop">
            '.$indicators.'
        </ol>
        ';
		
		
		$result = '
        <div class="featured-slider-box" style="background: url('.$section['bgimg'].') 0 0 no-repeat;">
            <div class="container relative">
                <div id="featuredSlider" class="carousel slide carousel-fade" data-ride="carousel">
                    <!-- Wrapper for slides -->
                    <div class="carousel-inner" role="listbox">
                        '.$items.'
                    </div>
					'.$controllers.'
                </div>
                <div class="fixed-data">
                    <h2 class="section-title">'.$section['title'].'</h2>
                    <ul class="lsnone p0 m0">
                        ' . $button_link . '
                        <li><a href="'.$products['url'] .'" class="btn btn-default btn-lg" id="viewProducts"><span>'.$products['label'] .'</span></a></li>
                    </ul>
                </div>

            </div>
        </div>
        ';
		
		
		echo $before_widget.$result.$after_widget;
		
	}
	
}
//add_action( 'widgets_init', create_function('', 'return register_widget("sgFeaturedSliderWidget");') );
add_action( 'widgets_init', function () {
    return register_widget('sgFeaturedSliderWidget');
}, 1 );
?>