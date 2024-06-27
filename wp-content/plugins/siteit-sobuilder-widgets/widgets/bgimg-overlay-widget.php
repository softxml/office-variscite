<?php
class sgBgImageOverlayWidget extends WP_Widget {


	
	// DEFINE THE WIDGET
	function __construct() {
		$widget_ops = array(
			'classname' 	=> 'sg_bgimage_overlay_widg', 
			'description'	=> __('Background image with overlay and text by SITEIT', 'siteitsob')
		);

		parent::__construct('sgBgImageOverlayWidget', __('Background Image Overlay Widget', 'siteitsob'), $widget_ops);
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

 
 
	// MULTI SELECT
	function sgDataArr($type) {
		
		if($type == 'yesno') {
			$type = array('yes'=>__('Yes'), 'no'=>__('No'));
		}
		elseif($type == 'borderColors') {
			$type = array('fullbDark'=>__('Black', 'siteitsob'), 'fullbCC'=>__('Grey', 'siteitsob'), 'fullbED'=>__('Light Grey', 'siteitsob'), 'fullbRed'=>__('Red', 'siteitsob'), 'fullbBlue'=>__('Blue', 'siteitsob'), 'fullbGreen'=>__('Green', 'siteitsob'));
		}
		elseif($type == 'linkTargets') {
			$type = array('_blank'=>__('New Window'), '_self'=>__('Same Window'));
		}
		elseif($type == 'talign') {
			$type = array('text-left' => __('Left', 'siteitsob'), 'text-center' => __('Center', 'siteitsob'), 'text-right' => __('Right', 'siteitsob'));
		}
		elseif($type == 'valigns') {
			$type = array('top' => __('Top', 'siteitsob'), 'middle' => __('Middle', 'siteitsob'), 'bottom' => __('Bottom', 'siteitsob'));
		}
		elseif($type == 'borderadius') {
			$type = array('brad3'=>__('3px', 'siteitsob'), 'brad5'=>__('5px', 'siteitsob'), 'brad7' => __('7px', 'siteitsob'), 'brad10' => __('10px', 'siteitsob'), 'brad25' => __('25px', 'siteitsob'), 'brad50' => __('50px', 'siteitsob'), 'brad50p' => __('50%', 'siteitsob'));
		}
		elseif($type == 'fontweight') {
			$type = array( 'light' => __('Light', 'siteitsob'), 'normal' => __('Normal', 'siteitsob'), 'bold' => __('Bold', 'siteitsob'));
		}
		return $type;
	}



	
	function multi_select($value, $label, $type) {
		$data = '';
		
		$typeArr = $this->sgDataArr($type);
		
		if($label) {
			$data .= '<option value="">'.$label.'</option>';
		}
		
		foreach($typeArr as $key => $opt) {
			if($key == $value) {$selected = 'selected';} else {$selected = '';}
			$data .= '<option value="'.$key.'" '.$selected.'>'.$opt.'</option>';
		}
		
		return $data;
	}



	// SITEIT GET IMAGE ID
	function siteitsob_get_image_id($image_url) {
		global $wpdb;
		$attachment = $wpdb->get_col($wpdb->prepare("SELECT ID FROM $wpdb->posts WHERE guid='%s';", $image_url )); 
		return $attachment[0]; 
	}
	


	// GET ATTACHMENT HEIGHT
	function siteitsob_img_height_fromurl($image_url) {
		$imgData = wp_get_attachment_metadata($this->siteitsob_get_image_id($image_url));
		return $imgData['height'];
	}



	function siteit_widget_fields() {
		return array(
			'image_url',
			'image_link',
			'link_target',
			'image_nocls',
			'image_pt',
			'image_pr',
			'image_pb',
			'image_pl',
			'image_mt',
			'image_mr', 
			'image_mb',
			'image_ml',
			'image_overlay',
			'overlay_color',
			'overlay_opacity',
			'image_bradius',
			'widget_text',
			'text_fweight',
			'text_size',
			'text_lheight',
			'text_width',
			'text_color',
			'text_classes',
			'text_mt',
			'text_mr',
			'text_mb',
			'text_ml',
			'text_pt',
			'text_pr',
			'text_pb',
			'text_pl',
			'text_location',
			'widget_classes',
			'use_mobile',
		);
	}


	function form_fileds_looper($instance,  $data) {
        $prefix = '';

        // if image saved 
        if($instance[$prefix.'image_url']) {$previewImg = $instance[$prefix.'image_url'];} else {$previewImg = 'http://placehold.it/450x300/eeeeee/212121/&text=Placeholder';}

		// rtl fixes
		if(is_rtl()) {$floatDir = 'left';} else {$floatDir = 'right';}


		// IF INNER HEIGHT IS NEEDED
		if($image_valign = 'middle') {$dnoneVA = 'display: none;';} else {$dnoneVA = '';}



		$formFields = '
        <div class="admin-row">
			<div class="col-md-4">

				<h4 class="row-title">'.__('Image Info', 'siteitsob').'  <span class="sitb-icon icon-cog icon-big"></span></h4>
                <div class="row-wrap">
					<div class="admin-row">
						<div class="col-md-8">
							<label for="'.$this->get_field_name($prefix.'image_url').'"><span class="label-wrap">'. __('Upload Your Image:', 'siteitsob').'</span></label>
							<input name="'.$this->get_field_name($prefix.'image_url').'" id="'.$this->get_field_id($prefix.'image_url').'" class="widefat imageUploader" type="text" size="36"  value="'.esc_url($instance[$prefix.'image_url']).'" style="width: 62%;" /> 
							<input data-input="#'.$this->get_field_id($prefix.'image_url').'" class="upload_image_button button button-primary" type="button" value="'.__('Upload Image', 'siteitsob').'" />
						</div>
                        <div class="col-md-4">
							<label for="'.$this->get_field_id($prefix.'image_nocls').'"><span class="label-wrap">'.__('Responsive Image?', 'siteitsob').':</span>
								<select class="widefat" id="'.$this->get_field_id($prefix.'image_nocls').'" name="'.$this->get_field_name($prefix.'image_nocls').'">
									'.$this->multi_select($instance[$prefix.'image_nocls'], '', 'yesno').'
								</select>
							</label>
						</div>
						<div class="col-md-4 mb0">
							<label for="'.$this->get_field_id($prefix.'image_link').'"><span class="label-wrap">'.__('Image Link <small class="diblock">(Optional)</small>', 'siteitsob').':</span>
							<input class="widefat" id="'.$this->get_field_id($prefix.'image_link').'" name="'.$this->get_field_name($prefix.'image_link').'" type="text" value="'.esc_attr($instance[$prefix.'image_link']).'" />
							</label>
						</div>
						<div class="col-md-4">
							<label for="'.$this->get_field_id($prefix.'link_target').'"><span class="label-wrap">'.__('Link Target', 'siteitsob').':</span>
								<select class="widefat" id="'.$this->get_field_id($prefix.'link_target').'" name="'.$this->get_field_name($prefix.'link_target').'">
									'.$this->multi_select($instance[$prefix.'link_target'], __('Default'), 'linkTargets').'
								</select>
							</label>
						</div>
						<div class="col-md-4">
							<label for="'.$this->get_field_id($prefix.'image_bradius').'"><span class="label-wrap">'.__('Rounded Corners', 'siteitsob').':</span>
								<select class="widefat" id="'.$this->get_field_id($prefix.'image_bradius').'" name="'.$this->get_field_name($prefix.'image_bradius').'">
									'.$this->multi_select($instance[$prefix.'image_bradius'], __('Default'), 'borderadius').'
								</select>
							</label>
						</div>						
						<div class="col-md-6 mb0">
							<label for="'.$this->get_field_id($prefix.'image_pt').'"><span class="label-wrap">'.__('Padding', 'siteitsob').':</span></label>
							<div class="admin-row small-padding mb0">
								<div class="col-md-3"><input class="widefat" id="'.$this->get_field_id($prefix.'image_pt').'" name="'.$this->get_field_name($prefix.'btn_pt').'" type="number" value="'.esc_attr($instance[$prefix.'btn_pt']).'" placeholder="0" /></div>
								<div class="col-md-3"><input class="widefat" id="'.$this->get_field_id($prefix.'image_pr').'" name="'.$this->get_field_name($prefix.'image_pr').'" type="number" value="'.esc_attr($instance[$prefix.'image_pr']).'" placeholder="0" /></div>
								<div class="col-md-3"><input class="widefat" id="'.$this->get_field_id($prefix.'image_pb').'" name="'.$this->get_field_name($prefix.'image_pb').'" type="number" value="'.esc_attr($instance[$prefix.'image_pb']).'" placeholder="0" /></div>
								<div class="col-md-3"><input class="widefat" id="'.$this->get_field_id($prefix.'image_pl').'" name="'.$this->get_field_name($prefix.'image_pl').'" type="number" value="'.esc_attr($instance[$prefix.'image_pl']).'" placeholder="0" /></div>
							</div>	
						</div>
						<div class="col-md-6 mb0">
							<label for="'.$this->get_field_id($prefix.'image_mt').'"><span class="label-wrap">'.__('Margin', 'siteitsob').':</span></label>
							<div class="admin-row small-padding mb0">
								<div class="col-md-3"><input class="widefat" id="'.$this->get_field_id($prefix.'image_mt').'" name="'.$this->get_field_name($prefix.'image_mt').'" type="number" value="'.esc_attr($instance[$prefix.'image_mt']).'" placeholder="0" /></div>
								<div class="col-md-3"><input class="widefat" id="'.$this->get_field_id($prefix.'image_mr').'" name="'.$this->get_field_name($prefix.'image_mr').'" type="number" value="'.esc_attr($instance[$prefix.'image_mr']).'" placeholder="0" /></div>
								<div class="col-md-3"><input class="widefat" id="'.$this->get_field_id($prefix.'image_mb').'" name="'.$this->get_field_name($prefix.'image_mb').'" type="number" value="'.esc_attr($instance[$prefix.'image_mb']).'" placeholder="0" /></div>
								<div class="col-md-3"><input class="widefat" id="'.$this->get_field_id($prefix.'image_ml').'" name="'.$this->get_field_name($prefix.'image_ml').'" type="number" value="'.esc_attr($instance[$prefix.'image_ml']).'" placeholder="0" /></div>
							</div>	
						</div>						

					</div>
				</div>



				<h4 class="row-title">'.__('Image Overlay (optional)', 'siteitsob').'  <span class="sitb-icon icon-pin icon-big"></span></h4>
                <div class="row-wrap">
					<div class="admin-row">
						<div class="col-md-4">
							<label for="'.$this->get_field_id($prefix.'image_overlay').'"><span class="label-wrap">'.__('use Overlay?', 'siteitsob').':</span>
								<select class="widefat" id="'.$this->get_field_id($prefix.'image_overlay').'" name="'.$this->get_field_name($prefix.'image_overlay').'">
									'.$this->multi_select($instance[$prefix.'image_overlay'], __('Default', 'siteitsob'), 'yesno').'
								</select>
							</label>
						</div>
						<div class="col-md-4">
							<label for="'.$this->get_field_id($prefix.'overlay_color').'"><span class="label-wrap">'.__('Overlay Color', 'siteitsob').':</span>
								<input class="widefat" id="'.$this->get_field_id($prefix.'overlay_color').'" name="'.$this->get_field_name($prefix.'overlay_color').'" type="color" value="'.esc_attr($instance[$prefix.'overlay_color']).'" />
							</label>
						</div>
						<div class="col-md-4">
							<label for="'.$this->get_field_id($prefix.'overlay_opacity').'"><span class="label-wrap">'.__('Overlay Opacity', 'siteitsob').':</span>
								<div class="range-counter counter-'.$this->get_field_id($prefix.'overlay_opacity').'">0.5</div>
								<input class="widefat range-input" data-counter="counter-'.$this->get_field_id($prefix.'overlay_opacity').'" id="'.$this->get_field_id($prefix.'overlay_opacity').'" name="'.$this->get_field_name($prefix.'overlay_opacity').'" type="range" value="'.($instance[$prefix.'overlay_opacity'] ? esc_attr($instance[$prefix.'overlay_opacity']) : 0.5).'" min="0.1" max="1" step="0.1" />
							</label>
						</div>
					</div>
				</div>


				<h4 class="row-title">'.__('Advance Settings', 'siteitsob').'  <span class="sitb-icon icon-settings2 icon-big"></span></h4>
                <div class="row-wrap">
					<div class="admin-row">
						<div class="col-md-6 mb0">
							<label for="'.$this->get_field_id($prefix.'widget_classes').'"><span class="label-wrap">'.__('Widget Classes', 'siteitsob').':</span>
							<input class="widefat" id="'.$this->get_field_id($prefix.'widget_classes').'" name="'.$this->get_field_name($prefix.'widget_classes').'" type="text" value="'.esc_attr($instance[$prefix.'widget_classes']).'" placeholder="'.__('For example: mt0 mb0', 'siteitsob').'" />
							</label>
						</div>
					</div>
				</div>

            </div>

			<div class="col-md-4">
				<h4 class="row-title">'.__('Widget Text (optional)', 'siteitsob').'  <span class="sitb-icon icon-text icon-big"></span></h4>
				<div class="row-wrap">
					<div class="admin-row">
						<div class="col-md-12">
							<label for="'.$this->get_field_id($prefix.'widget_text').'"><span class="label-wrap">'.$labelfix.__('Widget Text', 'siteitsob').':</span></label>
							<textarea class="widefat main_text" id="'.$this->get_field_id($prefix.'widget_text').'" name="'.$this->get_field_name($prefix.'widget_text').'" rows="5">'.esc_attr($instance[$prefix.'widget_text']).'</textarea>
						</div>
						<div class="col-md-4">
							<label for="'.$this->get_field_id($prefix.'text_fweight').'"><span class="label-wrap">'.$labelfix.__('Font Weight', 'siteitsob').':</span>
								<select class="widefat" id="'.$this->get_field_id($prefix.'text_fweight').'" name="'.$this->get_field_name($prefix.'text_fweight').'">
									'.$this->multi_select($instance[$prefix.'text_fweight'], __('Default', 'siteitsob'), 'fontweight').'
								</select>
							</label>
						</div>
						<div class="col-md-4">
							<label for="'.$this->get_field_id($prefix.'text_size').'"><span class="label-wrap">'.__('Font Size (px)', 'siteitsob').':</span>
							<input class="widefat" id="'.$this->get_field_id($prefix.'text_size').'" name="'.$this->get_field_name($prefix.'text_size').'" type="number" value="'.esc_attr($instance[$prefix.'text_size']).'" placeholder="'.__('For example: 60', 'siteitsob').'" />
							</label>
						</div>
						<div class="col-md-4">
							<label for="'.$this->get_field_id($prefix.'text_lheight').'"><span class="label-wrap">'.__('Line Height (px)', 'siteitsob').':</span>
							<input class="widefat" id="'.$this->get_field_id($prefix.'text_lheight').'" name="'.$this->get_field_name($prefix.'text_lheight').'" type="number" value="'.esc_attr($instance[$prefix.'text_lheight']).'" placeholder="'.__('For example: 60', 'siteitsob').'" />
							</label>
						</div>
						<div class="col-md-4">
							<label for="'.$this->get_field_id($prefix.'text_width').'"><span class="label-wrap">'.__('Width (px or %)', 'siteitsob').':</span>
								<input class="widefat" id="'.$this->get_field_id($prefix.'text_width').'" name="'.$this->get_field_name($prefix.'text_width').'" type="text" value="'.esc_attr($instance[$prefix.'text_width']).'" placeholder="'.__('For example: 250px or 80%', 'siteitsob').'" />
							</label>								
						</div>
						<div class="col-md-4">
							<label for="'.$this->get_field_id($prefix.'text_color').'"><span class="label-wrap">'.__('Color', 'siteitsob').':</span>
							<input class="widefat" id="'.$this->get_field_id($prefix.'text_color').'" name="'.$this->get_field_name($prefix.'text_color').'" type="color" value="'.esc_attr($instance[$prefix.'text_color']).'" />
							</label>
						</div>
						<div class="col-md-4">
							<label for="'.$this->get_field_id($prefix.'text_classes').'"><span class="label-wrap">'.__('Text Classes', 'siteitsob').':</span>
							<input class="widefat" id="'.$this->get_field_id($prefix.'text_classes').'" name="'.$this->get_field_name($prefix.'text_classes').'" type="text" value="'.esc_attr($instance[$prefix.'text_classes']).'" placeholder="'.__('For example: mt0 mb0', 'siteitsob').'" />
							</label>
						</div>
						<div class="col-md-6 mb0">
							<label for="'.$this->get_field_id($prefix.'text_mt').'"><span class="label-wrap">'.$labelfix.__('Margin', 'siteitsob').':</span></label>
							<div class="admin-row mini-padding mb0">
								<div class="col-md-3"><input class="widefat" id="'.$this->get_field_id($prefix.'text_mt').'" name="'.$this->get_field_name($prefix.'text_mt').'" type="number" value="'.esc_attr($instance[$prefix.'text_mt']).'" placeholder="0" /></div>
								<div class="col-md-3"><input class="widefat" id="'.$this->get_field_id($prefix.'text_mr').'" name="'.$this->get_field_name($prefix.'text_mr').'" type="number" value="'.esc_attr($instance[$prefix.'text_mr']).'" placeholder="0" /></div>
								<div class="col-md-3"><input class="widefat" id="'.$this->get_field_id($prefix.'text_mb').'" name="'.$this->get_field_name($prefix.'text_mb').'" type="number" value="'.esc_attr($instance[$prefix.'text_mb']).'" placeholder="0" /></div>
								<div class="col-md-3"><input class="widefat" id="'.$this->get_field_id($prefix.'text_ml').'" name="'.$this->get_field_name($prefix.'text_ml').'" type="number" value="'.esc_attr($instance[$prefix.'text_ml']).'" placeholder="0" /></div>
							</div>
						</div>
						<div class="col-md-6 mb0">
							<label for="'.$this->get_field_id($prefix.'text_pt').'"><span class="label-wrap">'.$labelfix.__('Padding', 'siteitsob').':</span></label>
							<div class="admin-row mini-padding mb0">
								<div class="col-md-3"><input class="widefat" id="'.$this->get_field_id($prefix.'text_pt').'" name="'.$this->get_field_name($prefix.'text_pt').'" type="number" value="'.esc_attr($instance[$prefix.'text_pt']).'" placeholder="0" /></div>
								<div class="col-md-3"><input class="widefat" id="'.$this->get_field_id($prefix.'text_pr').'" name="'.$this->get_field_name($prefix.'text_pr').'" type="number" value="'.esc_attr($instance[$prefix.'text_pr']).'" placeholder="0" /></div>
								<div class="col-md-3"><input class="widefat" id="'.$this->get_field_id($prefix.'text_pb').'" name="'.$this->get_field_name($prefix.'text_pb').'" type="number" value="'.esc_attr($instance[$prefix.'text_pb']).'" placeholder="0" /></div>
								<div class="col-md-3"><input class="widefat" id="'.$this->get_field_id($prefix.'text_pl').'" name="'.$this->get_field_name($prefix.'text_pl').'" type="number" value="'.esc_attr($instance[$prefix.'text_pl']).'" placeholder="0" /></div>
							</div>
						</div>
					</div>
				</div>
			</div>

            <div class="col-md-4">
				<h4 class="row-title">'.__('Text Position', 'siteitsob').'  <span class="sitb-icon icon-image icon-big"></span></h4>
				<div class="row-wrap">
				<label for=""><span class="label-wrap" style="margin-bottom: 5px;">'.__('Position Above Image', 'siteitsob').':</span></label>
                    <div class="admin-row radio-20">
						<div class="col-md-4 text-center"><label for="'.$this->get_field_name($prefix.'text_location_tl').'"> <img src="'.plugin_dir_url( __FILE__ ) . '../lib/images/pos/tl.jpg" alt=""> <input type="radio" class="radio1" name="'.$this->get_field_name($prefix.'text_location').'" id="'.$this->get_field_name($prefix.'text_location_tl').'" value="top-left" '.($instance[$prefix.'text_location'] == 'top-left' ? 'checked' : '').' > </label></div>
						<div class="col-md-4 text-center""><label for="'.$this->get_field_name($prefix.'text_location_tc').'"> <img src="'.plugin_dir_url( __FILE__ ) . '../lib/images/pos/tc.jpg" alt=""> <input type="radio" class="radio1" name="'.$this->get_field_name($prefix.'text_location').'" id="'.$this->get_field_name($prefix.'text_location_tc').'" value="top-center" '.($instance[$prefix.'text_location'] == 'top-center' ? 'checked' : '').' ></label></div>
						<div class="col-md-4 text-center""><label for="'.$this->get_field_name($prefix.'text_location_tr').'"> <img src="'.plugin_dir_url( __FILE__ ) . '../lib/images/pos/tr.jpg" alt=""> <input type="radio" class="radio1" name="'.$this->get_field_name($prefix.'text_location').'" id="'.$this->get_field_name($prefix.'text_location_tr').'" value="top-right" '.($instance[$prefix.'text_location'] == 'top-right' ? 'checked' : '').' ></label></div>
						<div class="col-md-4 text-center""><label for="'.$this->get_field_name($prefix.'text_location_ml').'"> <img src="'.plugin_dir_url( __FILE__ ) . '../lib/images/pos/ml.jpg" alt=""> <input type="radio" class="radio1" name="'.$this->get_field_name($prefix.'text_location').'" id="'.$this->get_field_name($prefix.'text_location_ml').'" value="middle-left" '.($instance[$prefix.'text_location'] == 'middle-left' ? 'checked' : '').' ></label></div>
						<div class="col-md-4 text-center""><label for="'.$this->get_field_name($prefix.'text_location_mc').'"> <img src="'.plugin_dir_url( __FILE__ ) . '../lib/images/pos/mc.jpg" alt=""> <input type="radio" class="radio1" name="'.$this->get_field_name($prefix.'text_location').'" id="'.$this->get_field_name($prefix.'text_location_mc').'" value="middle-center" '.($instance[$prefix.'text_location'] == 'middle-center' ? 'checked' : '').' ></label></div>
						<div class="col-md-4 text-center""><label for="'.$this->get_field_name($prefix.'text_location_mr').'"> <img src="'.plugin_dir_url( __FILE__ ) . '../lib/images/pos/mr.jpg" alt=""> <input type="radio" class="radio1" name="'.$this->get_field_name($prefix.'text_location').'" id="'.$this->get_field_name($prefix.'text_location_mr').'" value="middle-right" '.($instance[$prefix.'text_location'] == 'middle-right' ? 'checked' : '').' ></label></div>
						<div class="col-md-4 text-center""><label for="'.$this->get_field_name($prefix.'text_location_bl').'"> <img src="'.plugin_dir_url( __FILE__ ) . '../lib/images/pos/bl.jpg" alt=""> <input type="radio" class="radio1" name="'.$this->get_field_name($prefix.'text_location').'" id="'.$this->get_field_name($prefix.'text_location_bl').'" value="bottom-left" '.($instance[$prefix.'text_location'] == 'bottom-left' ? 'checked' : '').' ></label></div>
						<div class="col-md-4 text-center""><label for="'.$this->get_field_name($prefix.'text_location_bc').'"> <img src="'.plugin_dir_url( __FILE__ ) . '../lib/images/pos/bc.jpg" alt=""> <input type="radio" class="radio1" name="'.$this->get_field_name($prefix.'text_location').'" id="'.$this->get_field_name($prefix.'text_location_bc').'" value="bottom-center" '.($instance[$prefix.'text_location'] == 'bottom-center' ? 'checked' : '').' ></label></div>
						<div class="col-md-4 text-center""><label for="'.$this->get_field_name($prefix.'text_location_br').'"> <img src="'.plugin_dir_url( __FILE__ ) . '../lib/images/pos/br.jpg" alt=""> <input type="radio" class="radio1" name="'.$this->get_field_name($prefix.'text_location').'" id="'.$this->get_field_name($prefix.'text_location_br').'" value="bottom-right" '.($instance[$prefix.'text_location'] == 'bottom-right' ? 'checked' : '').' ></label></div>
                    </div>
                </div>
            </div>
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
		<div>'.$this->form_fileds_looper($instance, $this).'</div>

		<script>
		jQuery(function($){
			$(".main_text").trumbowyg({
				resetCss: true,
				btns: [
					["viewHTML"],
					["formatting"],
					"btnGrp-semantic",
					["link"],
					["insertImage"],
					"btnGrp-justify",
					"btnGrp-lists",
					["fontsize"],
					["foreColor", "backColor"],
					["horizontalRule"],
					["removeformat"],
					["fullscreen"]
				]
			});
		});
		</script>
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
		$result	= '';
        $prefix = '';


		$before_widget 		= '<div class="singleWidget sitBuilderWidget sitImageBgOverlayWidget sobImageWidget '.$instance[$prefix.'widget_classes'].'">';
		$after_widget 		= '</div>';

		if( isset($instance[$prefix.'image_url']) ) {


			// FIX TEXT
			if( isset($instance[$prefix.'widget_text']) ) {
				$textCss = '';
				$textCls = '';

				// some defaults
				if(!$instance[$prefix.'text_location']) {$instance[$prefix.'text_location'] = 'bottom-center';}


				// build text styles
				if($instance[$prefix.'text_size']) {$textCss .= 'font-size: '.$instance[$prefix.'text_size'].'px; ';}
				if($instance[$prefix.'text_lheight']) {$textCss .= 'line-height: '.$instance[$prefix.'text_lheight'].'; ';}
				if($instance[$prefix.'text_color']) {$textCss .= 'color: '.$instance[$prefix.'text_color'].'; ';}
				if($instance[$prefix.'text_width']) {$textCss .= 'width: '.$instance[$prefix.'text_width'].'; display: inline-block;';}
				
				if($instance[$prefix.'text_fweight']) {$textCls .= $instance[$prefix.'text_fweight'].' ';}
				if($instance[$prefix.'text_classes']) {$textCls .= $instance[$prefix.'text_classes'].' ';}

				$textCss 	.= $this->build_margpad_array($instance[$prefix.'text_pt'], $instance[$prefix.'text_pr'], $instance[$prefix.'text_pb'], $instance[$prefix.'text_pl'], 'padding');
				$textCss 	.= $this->build_margpad_array($instance[$prefix.'text_mt'], $instance[$prefix.'text_mr'], $instance[$prefix.'text_mb'], $instance[$prefix.'text_ml'], 'margin');
				
				// build text position
				$position 	= explode('-', $instance[$prefix.'text_location']);

				if($position[1] != 'center') {$textCss 	.= 'position: absolute; '.$position[0].': 0;'.$position[1].': 0; z-index: 3;';}
				else {$textCss 	.= 'position: absolute; '.$position[0].': 0; right: 0; left: 0; z-index: 3; margin: 0 auto;';}

				$text 		= ' <div class="widgetText '.$textCls.'" style="'.$textCss.'">'.$instance[$prefix.'widget_text'].'</div> ';
			} else {$text = '';}
			

			// FIX OVERLAY
			if( $instance[$prefix.'image_overlay'] ) {
				$overlay = '<div class="overlay" style="position: absolute; z-index: 2; width: 100%; height: 100%; top: 0; left: 0; background: '.$instance[$prefix.'overlay_color'].'; opacity: '.$instance[$prefix.'overlay_color'].';"></div>';
			} else {$overlay = '';}


			// FIX LINK
			if( isset($instance[$prefix.'image_link']) ) {
				$link = '<a href="'.$instance[$prefix.'image_link'].'" class="fullink" style="position: absolute; z-index: 5; width: 100%; height: 100%; top: 0; left: 0;" target="'.$instance[$prefix.'link_target'].'"></a>';
			} else {$link = '';}


			$result = '
			<div style="background: url('.$instance[$prefix.'image_url'].') 0 0 no-repeat; height: '.$this->siteitsob_img_height_fromurl($instance[$prefix.'image_url']).'px; position: relative;">
				'.$link.'
				'.$overlay.'
				'.$text.'
			</div>
			';		

			echo $before_widget.$result.$after_widget;
		}

	}
 
}
// add_action( 'widgets_init', create_function('', 'return register_widget("sgBgImageOverlayWidget");') );
add_action( 'widgets_init', function () {
    return register_widget('sgBgImageOverlayWidget');
}, 1 );
?>