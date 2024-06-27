<?php
class sgExpendingElementWidget extends WP_Widget {


	
	// DEFINE THE WIDGET
	function __construct() {
		$widget_ops = array(
			'classname' 	=> 'sg_expending_element_widg', 
			'description'	=> __('Multiple Design Expending Element Widget by SITEIT', 'siteitsob')
		);

		parent::__construct('sgExpendingElementWidget', __('Expending Widget', 'siteitsob'), $widget_ops);
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
		elseif($type == 'ttype') {
			$type = array('h3'=>__('h3'),'h1'=>__('h1'),'h2'=>__('h2'), 'h4'=>__('h4'), 'h5'=>__('h5'));
		}
		elseif($type == 'talign') {
			$type = array('text-left' => __('Left', 'siteitsob'), 'text-center' => __('Center', 'siteitsob'), 'text-right' => __('Right', 'siteitsob'));
		}
		elseif($type == 'fontweight') {
			$type = array( 'light' => __('Light', 'siteitsob'), 'normal' => __('Normal', 'siteitsob'), 'bold' => __('Bold', 'siteitsob'));
		}		
		elseif($type == 'eledesigns') {
			$type = array( 'd001' => __('Design 001', 'siteitsob') );
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


	function return_fixed_farray() {
		include( plugin_dir_path( __FILE__ ) . '../functions/fontAwesomeArray.php');
		return array_flip($faIconsArr);
	}

	function faicons_select($value, $label = NULL) {
		
		$data = '';
		$faIconsArr = $this->return_fixed_farray();
		
		if($label) {
			$data .= '<option value="">'.$label.'</option>';
		}
		
		foreach($faIconsArr as $key => $icon) {
			if($key == $value) {$selected = 'selected';} else {$selected = '';}
			$data .= '<option value="'.$key.'" '.$selected.'>&#x'.$key.' '.$icon.'</option>';
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
			'eledesign',
			'contracted_icon',
			'expended_icon',
			'contracted_icon_img',
			'expended_icon_img',
			'icon_pt',
			'icon_pr',
			'icon_pb',
			'icon_pl',
			'icon_mt',
			'icon_mr',
			'icon_mb',
			'icon_ml',			
			'image_url',
			'image_alt',
			'image_nocls',
			'image_border',
			'image_bordercolor',
			'image_bradius',
			'image_pt',
			'image_pr',
			'image_pb',
			'image_pl',
			'image_mt',
			'image_mr',
			'image_mb',
			'image_ml',
			'widget_title',
			'title_size',
			'title_fweight',
			'title_color',
			'title_pt',
			'title_pr',
			'title_pb',
			'title_pl',
			'title_mt',
			'title_mr',
			'title_mb',
			'title_ml',
			'widget_subtitle',
			'subtitle_size',
			'subtitle_fweight',
			'subtitle_color',
			'subtitle_pt',
			'subtitle_pr',
			'subtitle_pb',
			'subtitle_pl',
			'subtitle_mt',
			'subtitle_mr',
			'subtitle_mb',
			'subtitle_ml',
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
			'image_classes',
			'title_classes',
			'widget_classes',
			'image_classes',
			'use_mobile',
		);
	}


	function form_fileds_looper($instance) {
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

				<h4 class="row-title">'.__('Design Template', 'siteitsob').'  <span class="sitb-icon icon-styling icon-big"></span></h4>
                <div class="row-wrap">
					<div class="admin-row">
						<div class="col-md-4 mb0">
							<label for="'.$this->get_field_id($prefix.'eledesign').'"><span class="label-wrap">'.__('Element Design', 'siteitsob').':</span>
								<select class="widefat" id="'.$this->get_field_id($prefix.'eledesign').'" name="'.$this->get_field_name($prefix.'eledesign').'">
									'.$this->multi_select($instance[$prefix.'eledesign'], '', 'eledesigns').'
								</select>
							</label>
						</div>
						<div class="col-md-8 mb0">
							<img src="'.plugin_dir_url( __FILE__ ) . '../lib/images/expending-element-design-001.jpg" alt="" class="img-responsive">
						</div>
					</div>
				</div>

				<h4 class="row-title">'.__('Open / Close Icon', 'siteitsob').'  <span class="sitb-icon icon-styling icon-big"></span></h4>
                <div class="row-wrap">
					<div class="admin-row">

						<div class="col-md-6">
							<label for="'.$this->get_field_id($prefix.'contracted_icon').'"><span class="label-wrap">'.__('Contracted Bar Icon', 'siteitsob').'</span>:
								<select class="fa-select widefat" id="'.$this->get_field_id($prefix.'contracted_icon').'" name="'.$this->get_field_name($prefix.'contracted_icon').'">
									'.$this->faicons_select($instance[$prefix.'contracted_icon'], __('No Icon', 'siteitsob')).'
								</select>
							</label>
						</div>
						<div class="col-md-6">
							<label for="'.$this->get_field_id($prefix.'expended_icon').'"><span class="label-wrap">'.__('Expended Bar Icon', 'siteitsob').'</span>:
								<select class="fa-select widefat" id="'.$this->get_field_id($prefix.'expended_icon').'" name="'.$this->get_field_name($prefix.'expended_icon').'">
									'.$this->faicons_select($instance[$prefix.'expended_icon'], __('No Icon', 'siteitsob')).'
								</select>
							</label>	
						</div> 
						<div class="col-md-12">
							<h5 class="mb0">'.__('Image Icon (Optional)', THEME_NAME).'</h5>
						</div>
						<div class="col-md-6">
							<label for="'.$this->get_field_name($prefix.'contracted_icon_img').'"><span class="label-wrap">'. __('Contracted Bar Image:', 'siteitsob').'</span></label>
							<input name="'.$this->get_field_name($prefix.'contracted_icon_img').'" id="'.$this->get_field_id($prefix.'contracted_icon_img').'" class="widefat imageUploader" type="text" size="36"  value="'.esc_url($instance[$prefix.'contracted_icon_img']).'" style="width: 47%;" /> 
							<input data-input="#'.$this->get_field_id($prefix.'contracted_icon_img').'" class="upload_image_button button button-primary" type="button" value="'.__('Upload', 'siteitsob').'" />
						</div>							
						<div class="col-md-6">
							<label for="'.$this->get_field_name($prefix.'expended_icon_img').'"><span class="label-wrap">'. __('Expended Bar Image:', 'siteitsob').'</span></label>
							<input name="'.$this->get_field_name($prefix.'expended_icon_img').'" id="'.$this->get_field_id($prefix.'expended_icon_img').'" class="widefat imageUploader" type="text" size="36"  value="'.esc_url($instance[$prefix.'expended_icon_img']).'" style="width: 47%;" /> 
							<input data-input="#'.$this->get_field_id($prefix.'expended_icon_img').'" class="upload_image_button button button-primary" type="button" value="'.__('Upload', 'siteitsob').'" />
						</div>
						<div class="col-md-6 mb0">
							<label for="'.$this->get_field_id($prefix.'icon_pt').'"><span class="label-wrap">'.__('Padding', 'siteitsob').':</span></label>
							<div class="admin-row small-padding mb0">
								<div class="col-md-3"><input class="widefat" id="'.$this->get_field_id($prefix.'icon_pt').'" name="'.$this->get_field_name($prefix.'icon_pt').'" type="number" value="'.esc_attr($instance[$prefix.'icon_pt']).'" placeholder="0" /></div>
								<div class="col-md-3"><input class="widefat" id="'.$this->get_field_id($prefix.'icon_pr').'" name="'.$this->get_field_name($prefix.'icon_pr').'" type="number" value="'.esc_attr($instance[$prefix.'icon_pr']).'" placeholder="0" /></div>
								<div class="col-md-3"><input class="widefat" id="'.$this->get_field_id($prefix.'icon_pb').'" name="'.$this->get_field_name($prefix.'icon_pb').'" type="number" value="'.esc_attr($instance[$prefix.'icon_pb']).'" placeholder="0" /></div>
								<div class="col-md-3"><input class="widefat" id="'.$this->get_field_id($prefix.'icon_pl').'" name="'.$this->get_field_name($prefix.'icon_pl').'" type="number" value="'.esc_attr($instance[$prefix.'icon_pl']).'" placeholder="0" /></div>
							</div>	
						</div>
						<div class="col-md-6 mb0">
							<label for="'.$this->get_field_id($prefix.'icon_mt').'"><span class="label-wrap">'.__('Margin', 'siteitsob').':</span></label>
							<div class="admin-row small-padding mb0">
								<div class="col-md-3"><input class="widefat" id="'.$this->get_field_id($prefix.'icon_mt').'" name="'.$this->get_field_name($prefix.'icon_mt').'" type="number" value="'.esc_attr($instance[$prefix.'icon_mt']).'" placeholder="0" /></div>
								<div class="col-md-3"><input class="widefat" id="'.$this->get_field_id($prefix.'icon_mr').'" name="'.$this->get_field_name($prefix.'icon_mr').'" type="number" value="'.esc_attr($instance[$prefix.'icon_mr']).'" placeholder="0" /></div>
								<div class="col-md-3"><input class="widefat" id="'.$this->get_field_id($prefix.'icon_mb').'" name="'.$this->get_field_name($prefix.'icon_mb').'" type="number" value="'.esc_attr($instance[$prefix.'icon_mb']).'" placeholder="0" /></div>
								<div class="col-md-3"><input class="widefat" id="'.$this->get_field_id($prefix.'icon_ml').'" name="'.$this->get_field_name($prefix.'icon_ml').'" type="number" value="'.esc_attr($instance[$prefix.'icon_ml']).'" placeholder="0" /></div>
							</div>	
						</div>

					</div>
				</div>

            </div>

			<div class="col-md-4">

				<h4 class="row-title">'.__('Image Info', 'siteitsob').'  <span class="sitb-icon icon-cog icon-big"></span></h4>
				<div class="row-wrap">
					<div class="admin-row">
						<div class="col-md-4">
							<label for="'.$this->get_field_name($prefix.'image_url').'"><span class="label-wrap">'. __('Upload Your Image:', 'siteitsob').'</span></label>
							<input name="'.$this->get_field_name($prefix.'image_url').'" id="'.$this->get_field_id($prefix.'image_url').'" class="widefat imageUploader" type="text" size="36"  value="'.esc_url($instance[$prefix.'image_url']).'" style="width: 45%;" /> 
							<input data-input="#'.$this->get_field_id($prefix.'image_url').'" class="upload_image_button button button-primary" type="button" value="'.__('Upload', 'siteitsob').'" />
						</div>
						<div class="col-md-4">
							<label for="'.$this->get_field_id($prefix.'image_alt').'"><span class="label-wrap">'.__('Image Alt', 'siteitsob').':</span>
								<input class="widefat" id="'.$this->get_field_id($prefix.'image_alt').'" name="'.$this->get_field_name($prefix.'image_alt').'" type="text" value="" />
							</label>
						</div>
						<div class="col-md-4">
							<label for="'.$this->get_field_id($prefix.'image_nocls').'"><span class="label-wrap">'.__('Responsive Image?', 'siteitsob').':</span>
								<select class="widefat" id="'.$this->get_field_id($prefix.'image_nocls').'" name="'.$this->get_field_name($prefix.'image_nocls').'">
									'.$this->multi_select($instance[$prefix.'image_nocls'], '', 'yesno').'
								</select>
							</label>
						</div>
						<div class="col-md-4">
							<label for="'.$this->get_field_id($prefix.'image_border').'"><span class="label-wrap">'.__('Border Width (px)', 'siteitsob').':</span>
								<input class="widefat" id="'.$this->get_field_id($prefix.'image_border').'" name="'.$this->get_field_name($prefix.'image_border').'" type="number" value="'.(!$instance[$prefix.'image_border'] ? 0 : esc_attr($instance[$prefix.'image_border'])).'" />
							</label>
						</div>
						<div class="col-md-4">
							<label for="'.$this->get_field_id($prefix.'image_bordercolor').'"><span class="label-wrap">'.__('Border Color', 'siteitsob').':</span>
							<input class="widefat" id="'.$this->get_field_id($prefix.'image_bordercolor').'" name="'.$this->get_field_name($prefix.'image_bordercolor').'" type="color" value="'.esc_attr($instance[$prefix.'image_bordercolor']).'" />
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
								<div class="col-md-3"><input class="widefat" id="'.$this->get_field_id($prefix.'image_pt').'" name="'.$this->get_field_name($prefix.'image_pt').'" type="number" value="'.esc_attr($instance[$prefix.'image_pt']).'" placeholder="0" /></div>
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

				<h4 class="row-title">'.__('Title Settings', 'siteitsob').' <span class="sitb-icon icon-title icon-big"></span></h4>
				<div class="row-wrap">
					<div class="admin-row">
						<div class="col-md-12">
							<label for="'.$this->get_field_id($prefix.'widget_title').'"><span class="label-wrap">'.__('Title', 'siteitsob').':</span>
								<input class="widefat" id="'.$this->get_field_id($prefix.'widget_title').'" name="'.$this->get_field_name($prefix.'widget_title').'" type="text" value="'.$instance[$prefix.'widget_title'].'" />
							</label>
						</div>
						<div class="col-md-4">
							<label for="'.$this->get_field_id($prefix.'title_size').'"><span class="label-wrap">'.__('Font Size (PX)', 'siteitsob').':</span>
								<input class="widefat" id="'.$this->get_field_id($prefix.'title_size').'" name="'.$this->get_field_name($prefix.'title_size').'" type="number" value="'.esc_attr($instance[$prefix.'title_size']).'" />
							</label>	
						</div>
						<div class="col-md-4">
							<label for="'.$this->get_field_id($prefix.'title_fweight').'"><span class="label-wrap">'.__('Font Weight', 'siteitsob').':</span>
								<select class="widefat" id="'.$this->get_field_id($prefix.'title_fweight').'" name="'.$this->get_field_name($prefix.'title_fweight').'">
									'.$this->multi_select($instance[$prefix.'title_fweight'], __('Default', 'siteitsob'), 'fontweight').'
								</select>
							</label>
						</div>
						<div class="col-md-4">
							<label for="'.$this->get_field_id($prefix.'title_color').'"><span class="label-wrap">'.__('Title Color', 'siteitsob').':</span>
								<input class="widefat" id="'.$this->get_field_id($prefix.'title_color').'" name="'.$this->get_field_name($prefix.'title_color').'" type="color" value="'.esc_attr($instance[$prefix.'title_color']).'" />
							</label>
						</div>
						<div class="col-md-6 mb0">
							<label for="'.$this->get_field_id($prefix.'title_pt').'"><span class="label-wrap">'.$labelfix.__('Padding', 'siteitsob').':</span></label>
							<div class="admin-row mini-padding mb0">
								<div class="col-md-3"><input class="widefat" id="'.$this->get_field_id($prefix.'title_pt').'" name="'.$this->get_field_name($prefix.'title_pt').'" type="number" value="'.esc_attr($instance[$prefix.'title_pt']).'" placeholder="0" /> <span class="icon-small arrow-mt"></span></div>
								<div class="col-md-3"><input class="widefat" id="'.$this->get_field_id($prefix.'title_pr').'" name="'.$this->get_field_name($prefix.'title_pr').'" type="number" value="'.esc_attr($instance[$prefix.'title_pr']).'" placeholder="0" /></div>
								<div class="col-md-3"><input class="widefat" id="'.$this->get_field_id($prefix.'title_pb').'" name="'.$this->get_field_name($prefix.'title_pb').'" type="number" value="'.esc_attr($instance[$prefix.'title_pb']).'" placeholder="0" /></div>
								<div class="col-md-3"><input class="widefat" id="'.$this->get_field_id($prefix.'title_pl').'" name="'.$this->get_field_name($prefix.'title_pl').'" type="number" value="'.esc_attr($instance[$prefix.'title_pl']).'" placeholder="0" /></div>
							</div>
						</div>
						<div class="col-md-6 mb0">
							<label for="'.$this->get_field_id($prefix.'title_mt').'"><span class="label-wrap">'.$labelfix.__('Margin', 'siteitsob').':</span></label>
							<div class="admin-row mini-padding mb0">
								<div class="col-md-3"><input class="widefat" id="'.$this->get_field_id($prefix.'title_mt').'" name="'.$this->get_field_name($prefix.'title_mt').'" type="number" value="'.esc_attr($instance[$prefix.'title_mt']).'" placeholder="0" /></div>
								<div class="col-md-3"><input class="widefat" id="'.$this->get_field_id($prefix.'title_mr').'" name="'.$this->get_field_name($prefix.'title_mr').'" type="number" value="'.esc_attr($instance[$prefix.'title_mr']).'" placeholder="0" /></div>
								<div class="col-md-3"><input class="widefat" id="'.$this->get_field_id($prefix.'title_mb').'" name="'.$this->get_field_name($prefix.'title_mb').'" type="number" value="'.esc_attr($instance[$prefix.'title_mb']).'" placeholder="0" /></div>
								<div class="col-md-3"><input class="widefat" id="'.$this->get_field_id($prefix.'title_ml').'" name="'.$this->get_field_name($prefix.'title_ml').'" type="number" value="'.esc_attr($instance[$prefix.'title_ml']).'" placeholder="0" /></div>
							</div>
						</div>
					</div>
				</div>

				<h4 class="row-title">'.__('Sub Title Settings', 'siteitsob').' <span class="sitb-icon icon-title icon-big"></span></h4>
				<div class="row-wrap">
					<div class="admin-row">
						<div class="col-md-12">
							<label for="'.$this->get_field_id($prefix.'widget_subtitle').'"><span class="label-wrap">'.__('Sub Title', 'siteitsob').':</span>
								<input class="widefat" id="'.$this->get_field_id($prefix.'widget_subtitle').'" name="'.$this->get_field_name($prefix.'widget_subtitle').'" type="text" value="'.$instance[$prefix.'widget_subtitle'].'" />
							</label>
						</div>
						<div class="col-md-4">
							<label for="'.$this->get_field_id($prefix.'subtitle_size').'"><span class="label-wrap">'.__('Font Size (PX)', 'siteitsob').':</span>
								<input class="widefat" id="'.$this->get_field_id($prefix.'subtitle_size').'" name="'.$this->get_field_name($prefix.'subtitle_size').'" type="number" value="'.esc_attr($instance[$prefix.'subtitle_size']).'" />
							</label>	
						</div>
						<div class="col-md-4">
							<label for="'.$this->get_field_id($prefix.'subtitle_fweight').'"><span class="label-wrap">'.__('Font Weight', 'siteitsob').':</span>
								<select class="widefat" id="'.$this->get_field_id($prefix.'subtitle_fweight').'" name="'.$this->get_field_name($prefix.'subtitle_fweight').'">
									'.$this->multi_select($instance[$prefix.'subtitle_fweight'], __('Default', 'siteitsob'), 'fontweight').'
								</select>
							</label>
						</div>
						<div class="col-md-4">
							<label for="'.$this->get_field_id($prefix.'subtitle_color').'"><span class="label-wrap">'.__('Title Color', 'siteitsob').':</span>
								<input class="widefat" id="'.$this->get_field_id($prefix.'subtitle_color').'" name="'.$this->get_field_name($prefix.'subtitle_color').'" type="color" value="'.esc_attr($instance[$prefix.'subtitle_color']).'" />
							</label>
						</div>
						<div class="col-md-6 mb0">
							<label for="'.$this->get_field_id($prefix.'subtitle_pt').'"><span class="label-wrap">'.$labelfix.__('Padding', 'siteitsob').':</span></label>
							<div class="admin-row mini-padding mb0">
								<div class="col-md-3"><input class="widefat" id="'.$this->get_field_id($prefix.'subtitle_pt').'" name="'.$this->get_field_name($prefix.'subtitle_pt').'" type="number" value="'.esc_attr($instance[$prefix.'subtitle_pt']).'" placeholder="0" /> <span class="icon-small arrow-mt"></span></div>
								<div class="col-md-3"><input class="widefat" id="'.$this->get_field_id($prefix.'subtitle_pr').'" name="'.$this->get_field_name($prefix.'subtitle_pr').'" type="number" value="'.esc_attr($instance[$prefix.'subtitle_pr']).'" placeholder="0" /></div>
								<div class="col-md-3"><input class="widefat" id="'.$this->get_field_id($prefix.'subtitle_pb').'" name="'.$this->get_field_name($prefix.'subtitle_pb').'" type="number" value="'.esc_attr($instance[$prefix.'subtitle_pb']).'" placeholder="0" /></div>
								<div class="col-md-3"><input class="widefat" id="'.$this->get_field_id($prefix.'subtitle_pl').'" name="'.$this->get_field_name($prefix.'subtitle_pl').'" type="number" value="'.esc_attr($instance[$prefix.'subtitle_pl']).'" placeholder="0" /></div>
							</div>
						</div>
						<div class="col-md-6 mb0">
							<label for="'.$this->get_field_id($prefix.'subtitle_mt').'"><span class="label-wrap">'.$labelfix.__('Margin', 'siteitsob').':</span></label>
							<div class="admin-row mini-padding mb0">
								<div class="col-md-3"><input class="widefat" id="'.$this->get_field_id($prefix.'subtitle_mt').'" name="'.$this->get_field_name($prefix.'subtitle_mt').'" type="number" value="'.esc_attr($instance[$prefix.'subtitle_mt']).'" placeholder="0" /></div>
								<div class="col-md-3"><input class="widefat" id="'.$this->get_field_id($prefix.'subtitle_mr').'" name="'.$this->get_field_name($prefix.'subtitle_mr').'" type="number" value="'.esc_attr($instance[$prefix.'subtitle_mr']).'" placeholder="0" /></div>
								<div class="col-md-3"><input class="widefat" id="'.$this->get_field_id($prefix.'subtitle_mb').'" name="'.$this->get_field_name($prefix.'subtitle_mb').'" type="number" value="'.esc_attr($instance[$prefix.'subtitle_mb']).'" placeholder="0" /></div>
								<div class="col-md-3"><input class="widefat" id="'.$this->get_field_id($prefix.'subtitle_ml').'" name="'.$this->get_field_name($prefix.'subtitle_ml').'" type="number" value="'.esc_attr($instance[$prefix.'subtitle_ml']).'" placeholder="0" /></div>
							</div>
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



				<h4 class="row-title">'.__('Advance Settings', 'siteitsob').'  <span class="sitb-icon icon-settings2 icon-big"></span></h4>
                <div class="row-wrap">
					<div class="admin-row">
						<div class="col-md-4 mb0">
							<label for="'.$this->get_field_id($prefix.'image_classes').'"><span class="label-wrap">'.__('Image Classes', 'siteitsob').':</span>
							<input class="widefat" id="'.$this->get_field_id($prefix.'image_classes').'" name="'.$this->get_field_name($prefix.'image_classes').'" type="text" value="'.esc_attr($instance[$prefix.'image_classes']).'" placeholder="'.__('For example: mt0 mb0', 'siteitsob').'" />
							</label>
						</div>
						<div class="col-md-4 mb0">
							<label for="'.$this->get_field_id($prefix.'title_classes').'"><span class="label-wrap">'.__('Title Classes', 'siteitsob').':</span>
							<input class="widefat" id="'.$this->get_field_id($prefix.'title_classes').'" name="'.$this->get_field_name($prefix.'title_classes').'" type="text" value="'.esc_attr($instance[$prefix.'title_classes']).'" placeholder="'.__('For example: mt0 mb0', 'siteitsob').'" />
							</label>
						</div>
						<div class="col-md-4 mb0">
							<label for="'.$this->get_field_id($prefix.'widget_classes').'"><span class="label-wrap">'.__('Widget Classes', 'siteitsob').':</span>
							<input class="widefat" id="'.$this->get_field_id($prefix.'widget_classes').'" name="'.$this->get_field_name($prefix.'widget_classes').'" type="text" value="'.esc_attr($instance[$prefix.'widget_classes']).'" placeholder="'.__('For example: mt0 mb0', 'siteitsob').'" />
							</label>
						</div>
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
		<div>'.$this->form_fileds_looper($instance).'</div>

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
		$result					= '';
		$widget_cls				= '';
        $prefix = '';


		if(!isset($instance[$prefix.'eledesign'])) {$instance[$prefix.'eledesign'] =  'd001';}


		$before_widget 		= '<div id="widget-'.$this->id_base.'-'.$this->number.'" class="singleWidget sitExepDatWidget sobImageWidget '.$instance[$prefix.'widget_classes'].'">';
		$after_widget 		= '</div>';


		/*********************************************
		** IMAGE SETTINGS
		*********************************************/
		$img_css			= '';
		$img_cls			= '';

		if(!$instance[$prefix.'image_url']) {$instance[$prefix.'image_url'] = 'http://placehold.it/640x480/&text='.__('No Image', 'siteitsob'); $instance[$prefix.'image_alt'] = 'Placeholder Image';}
		if($instance[$prefix.'image_classes']) {$img_cls .= $instance[$prefix.'image_classes'].' ';}
		if($instance[$prefix.'image_nocls'] != 'no') {$img_cls .= 'img-responsive ';}
		if($instance[$prefix.'image_bradius']) {$img_cls .= $instance[$prefix.'image_bradius'].' ';}
		if( isset($instance[$prefix.'image_border']) && $instance[$prefix.'image_border'] < 1) {$img_css .= 'border: '.$instance[$prefix.'image_border'].'px solid '.$instance[$prefix.'image_bordercolor'].';';}

		$img_css .= $this->build_margpad_array($instance[$prefix.'image_pt'], $instance[$prefix.'image_pr'], $instance[$prefix.'image_pb'], $instance[$prefix.'image_pl'], 'padding');
		$img_css .= $this->build_margpad_array($instance[$prefix.'image_mt'], $instance[$prefix.'image_mr'], $instance[$prefix.'image_mb'], $instance[$prefix.'image_ml'], 'margin');



		/*********************************************
		** ICONS SETTINGS
		*********************************************/
		if( isset($instance[$prefix.'contracted_icon']) || isset($instance[$prefix.'contracted_icon_img'])  ) {
			$useIcon 	= 'yes';
			$icon_css 	= '';

			$icon_css .= $this->build_margpad_array($instance[$prefix.'icon_pt'], $instance[$prefix.'icon_pr'], $instance[$prefix.'icon_pb'], $instance[$prefix.'icon_pl'], 'padding');
			$icon_css .= $this->build_margpad_array($instance[$prefix.'icon_mt'], $instance[$prefix.'icon_mr'], $instance[$prefix.'icon_mb'], $instance[$prefix.'icon_ml'], 'margin');
	

			if($instance[$prefix.'contracted_icon_img'] != '') {
				$icon = '<img src="'.$instance[$prefix.'contracted_icon_img'].'" class="exIconImg" data-orgurl="'.$instance[$prefix.'contracted_icon_img'].'" data-alrurl="'.$instance[$prefix.'expended_icon_img'].'"> ';
			}
			else {
				$faIconsArr	= $this->return_fixed_farray();
				$icon 		= '<i class="fa '.$faIconsArr[ $instance[$prefix.'contracted_icon'] ].' closedIcon"></i> <i class="fa '.$faIconsArr[ $instance[$prefix.'expended_icon'] ] .' openIcon" style="display: none;"></i>';
			}

		} else {$icon = '';$useIcon = 'no';}



		/*********************************************
		** TITLE SETTINGS
		*********************************************/
		if( isset($instance[$prefix.'widget_title']) ) {
			$title_css				= '';
			$title_cls				= '';

			if( isset($instance[$prefix.'title_classes']) ) {$title_cls .= $instance[$prefix.'title_classes'].' ';}
			if( isset($instance[$prefix.'title_fweight']) ) {$title_cls .= $instance[$prefix.'title_fweight'].' ';}

			if( isset($instance[$prefix.'title_size']) ) {$title_css .= 'font-size: '.$instance[$prefix.'title_size'].'px; ';}
			if( isset($instance[$prefix.'title_color']) ) {$title_css .= 'color: '.$instance[$prefix.'title_color'].'; ';}

			$title_css .= $this->build_margpad_array($instance[$prefix.'title_pt'], $instance[$prefix.'title_pr'], $instance[$prefix.'title_pb'], $instance[$prefix.'title_pl'], 'padding');
			$title_css .= $this->build_margpad_array($instance[$prefix.'title_mt'], $instance[$prefix.'title_mr'], $instance[$prefix.'title_mb'], $instance[$prefix.'title_ml'], 'margin');
	
		}


		/*********************************************
		** SUB TITLE SETTINGS
		*********************************************/
		if( isset($instance[$prefix.'widget_subtitle']) ) {
			$subtitle_css = '';
			$subtitle_cls = '';

			if( isset($instance[$prefix.'subtitle_fweight']) ) {$subtitle_cls .= $instance[$prefix.'subtitle_fweight'].' ';}

			if( isset($instance[$prefix.'subtitle_size']) ) {$subtitle_css .= 'font-size: '.$instance[$prefix.'subtitle_size'].'px; ';}
			if( isset($instance[$prefix.'subtitle_color']) ) {$subtitle_css .= 'color: '.$instance[$prefix.'subtitle_color'].'; ';}

			$subtitle_css .= $this->build_margpad_array($instance[$prefix.'subtitle_pt'], $instance[$prefix.'subtitle_pr'], $instance[$prefix.'subtitle_pb'], $instance[$prefix.'subtitle_pl'], 'padding');
			$subtitle_css .= $this->build_margpad_array($instance[$prefix.'subtitle_mt'], $instance[$prefix.'subtitle_mr'], $instance[$prefix.'subtitle_mb'], $instance[$prefix.'subtitle_ml'], 'margin');
	
		}



		/*********************************************
		** FIX TEXT
		*********************************************/
		if( isset($instance[$prefix.'widget_text']) ) {
			$text_css = '';
			$text_cls = '';

			if( isset($instance[$prefix.'text_fweight']) ) { $text_cls .= $instance[$prefix.'text_fweight'].' '; }
			if( isset($instance[$prefix.'text_width']) ) { $text_cls .= $instance[$prefix.'text_width'].' '; }
			if( isset($instance[$prefix.'text_classes']) ) { $text_cls .= $instance[$prefix.'text_classes'].' '; }

			if( isset($instance[$prefix.'text_color']) ) { $text_css .= 'color: '.$instance[$prefix.'text_classes'].'; '; }
			if( isset($instance[$prefix.'text_size']) ) { $text_css .= 'font-size: '.$instance[$prefix.'text_size'].'px; '; }
			if( isset($instance[$prefix.'text_lheight']) ) { $text_css .= 'line-height: '.$instance[$prefix.'text_lheight'].'px; '; }

			$text_css .= $this->build_margpad_array($instance[$prefix.'text_pt'], $instance[$prefix.'text_pr'], $instance[$prefix.'text_pb'], $instance[$prefix.'text_pl'], 'padding');
			$text_css .= $this->build_margpad_array($instance[$prefix.'text_mt'], $instance[$prefix.'text_mr'], $instance[$prefix.'text_mb'], $instance[$prefix.'text_ml'], 'margin');
				
		}


		/*********************************************
		** BUILD WIDGET
		*********************************************/

		// DESIGN 01
		if($instance[$prefix.'eledesign'] == 'd001') {
			$result = '
			<div class="exDataEleBox design-'.$instance[$prefix.'eledesign'].'">
				<div class="xpEleHead" toggle="#text-'.$this->id_base.'-'.$this->number.'">
					'.($instance[$prefix.'image_url'] ? '<img src="'.$instance[$prefix.'image_url'].'" alt="'.$instance[$prefix.'image_alt'].'" class="'.$img_cls.'" style="'.$img_css.'">' : '').'
					<div class="title-wrap">
						'.($useIcon == 'yes' ? '<div class="icon-block" style="'.$icon_css.'">'.$icon.'</div>' : '').'
						'.($instance[$prefix.'widget_title'] ? '<div class="title-block '.$title_cls.'" style="'.$title_css.'">'.$instance[$prefix.'widget_title'].'</div>' : '').'
						'.($instance[$prefix.'widget_subtitle'] ? '<div class="subtitle-block '.$subtitle_cls.'" style="'.$subtitle_css.'">'.$instance[$prefix.'widget_subtitle'].'</div>' : '').'
					</div>
				</div>
				<div id="text-'.$this->id_base.'-'.$this->number.'" class="xpEleBody">
					<div class="text-block '.$subtitle_cls.'" style="'.$subtitle_css.'">'.$instance[$prefix.'widget_text'].'</div>
				</div>
			</div>
			';
		}
		else {
			$result = '';
		}



		echo $before_widget.$result.$after_widget;

		
	}


}
// add_action( 'widgets_init', create_function('', 'return register_widget("sgExpendingElementWidget");') );
add_action( 'widgets_init', function () {
    return register_widget('sgExpendingElementWidget');
}, 1 );
?>