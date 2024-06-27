<?php
class sgLocalServicesWidget extends WP_Widget {


	// DEFINE THE WIDGET
	function __construct() {
		$widget_ops = array(
			'classname' 	=> 'sg_imagetxt_widg', 
			'description'	=> __('A custom widget for services page By SITEIT', 'siteitsob')
		);

		parent::__construct('sgLocalServicesWidget', __('Service Widget (SiteIT)', 'siteitsob'), $widget_ops);
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
		elseif($type == 'ttype') {
			$type = array('h3'=>__('h3'),'h1'=>__('h1'),'h2'=>__('h2'), 'h4'=>__('h4'), 'h5'=>__('h5'));
		}
		elseif($type == 'talign') {
			$type = array('text-left' => __('Left', 'siteitsob'), 'text-center' => __('Center', 'siteitsob'), 'text-right' => __('Right', 'siteitsob'));
		}
		elseif($type == 'valigns') {
			$type = array('top' => __('Top', 'siteitsob'), 'middle' => __('Middle', 'siteitsob'), 'bottom' => __('Bottom', 'siteitsob'));
		}
		elseif($type == 'fontweight') {
			$type = array( 'light' => __('Light', 'siteitsob'), 'normal' => __('Normal', 'siteitsob'), 'bold' => __('Bold', 'siteitsob'));
		}
		elseif($type == 'imagePositions') {
			$type = array('above'=>__('Above Title', 'siteitsob'), 'under'=>__('Under Title', 'siteitsob'));
		}
		return $type;
	}



	
	function multi_select($value, $label, $type) {
		$data = '';
		
		$typeArr = $this->sgDataArr($type);
		
		if($label) {
			$data .= '<option value="">'.$label.'</option>';
		}
		
		if( is_array( $typeArr ) && !empty( $typeArr ) ){
			foreach($typeArr as $key => $opt) {
				if($key == $value) {$selected = 'selected';} else {$selected = '';}
				$data .= '<option value="'.$key.'" '.$selected.'>'.$opt.'</option>';
			}
		}
		return $data;
	}





	function siteit_widget_fields() {
		return array(
			'widget_title', 
			'image_url',
			'image_alt',
			'image_pt',
			'image_pr',
			'image_pb',
			'image_pl',
			'image_mt',
			'image_mr', 
			'image_mb',
			'image_ml',
			'image_align',
			'image_valign',
			'image_classes',
			'title_icon', 
			'title_type', 
			'title_size', 
			'title_width', 
			'title_align', 
			'title_color', 
			'title_fweight', 
			'title_mt',
			'title_mr',
			'title_mb',
			'title_ml',
			'title_pt',
			'title_pr',
			'title_pb',
			'title_pl',  
            'title_classes', 
            'link_url',
            'link_target',
            'widget_text',
			'text_size',
			'text_lheight',
			'text_fweight',
			'text_color',
			'text_classes',
			'text_width',
			'text_mt',
			'text_mr',
			'text_mb',
			'text_ml',
			'text_pt',
			'text_pr',
			'text_pb',
			'text_pl',  
			'widget_bgcolor',
			'widget_classes',
			'widget_mt',
			'widget_mr',
			'widget_mb',
			'widget_ml',
			'widget_pt',
			'widget_pr',
			'widget_pb',
			'widget_pl',
			'use_mobile', 
		);
	}


	function form_fileds_looper($instance) {


		// rtl fixes
		if(is_rtl()) {$floatDir = 'left';} else {$floatDir = 'right';}
        $prefix = '';


		$formFields = '
		<div class="admin-row">
			<div class="col-md-4">


				<h4 class="row-title">'.__('Image Settings', 'siteitsob').'  <span class="sitb-icon icon-image icon-big"></span></h4>
				<div class="row-wrap">
					<div class="admin-row">
						<div class="col-md-8">
							<label for="'.$this->get_field_name($prefix.'image_url').'"><span class="label-wrap">'. __('Upload Your Image:', 'siteitsob').'</span></label>
							<input name="'.$this->get_field_name($prefix.'image_url').'" id="'.$this->get_field_id($prefix.'image_url').'" class="widefat imageUploader" type="text" size="36"  value="'.esc_url($instance[$prefix.'image_url']).'" style="width: 62%;" /> 
							<input data-input="#'.$this->get_field_id($prefix.'image_url').'" class="upload_image_button button button-primary" type="button" value="'.__('Upload Image', 'siteitsob').'" />
						</div>
						<div class="col-md-4">
							<label for="'.$this->get_field_id($prefix.'image_alt').'"><span class="label-wrap">'.__('Image Alt', 'siteitsob').':</span>
							<input class="widefat" id="'.$this->get_field_id($prefix.'image_alt').'" name="'.$this->get_field_name($prefix.'image_alt').'" type="text" value="'.esc_attr($instance[$prefix.'image_alt']).'" placeholder="'.__('Enter Image Alt', 'siteitsob').'" />
							</label>
						</div>

						<div class="col-md-4">
							<label for="'.$this->get_field_id($prefix.'image_align').'"><span class="label-wrap">'.__('Image Align', 'siteitsob').':</span>
							<select class="widefat" id="'.$this->get_field_id($prefix.'image_align').'" name="'.$this->get_field_name($prefix.'image_align').'">
								'.$this->multi_select($instance[$prefix.'image_align'], __('Default', 'siteitsob'), 'talign').'
							</select>	
							</label>	
						</div>
						<div class="col-md-4">
							<label for="'.$this->get_field_id($prefix.'image_valign').'"><span class="label-wrap">'.__('Vertical Align', 'siteitsob').':</span>
								<select class="widefat imgValign" id="'.$this->get_field_id($prefix.'image_valign').'" name="'.$this->get_field_name($prefix.'image_valign').'">
									'.$this->multi_select($instance[$prefix.'image_valign'], __('Default', 'siteitsob'), 'valigns').'
								</select>
							</label>
						</div>		
						<div class="col-md-6">
							<label for="'.$this->get_field_id($prefix.'image_pt').'"><span class="label-wrap">'.__('Padding', 'siteitsob').':</span></label>
							<div class="admin-row small-padding mb0">
								<div class="col-md-3"><input class="widefat" id="'.$this->get_field_id($prefix.'image_pt').'" name="'.$this->get_field_name($prefix.'btn_pt').'" type="number" value="'.esc_attr($instance[$prefix.'btn_pt']).'" placeholder="0" /></div>
								<div class="col-md-3"><input class="widefat" id="'.$this->get_field_id($prefix.'image_pr').'" name="'.$this->get_field_name($prefix.'image_pr').'" type="number" value="'.esc_attr($instance[$prefix.'image_pr']).'" placeholder="0" /></div>
								<div class="col-md-3"><input class="widefat" id="'.$this->get_field_id($prefix.'image_pb').'" name="'.$this->get_field_name($prefix.'image_pb').'" type="number" value="'.esc_attr($instance[$prefix.'image_pb']).'" placeholder="0" /></div>
								<div class="col-md-3"><input class="widefat" id="'.$this->get_field_id($prefix.'image_pl').'" name="'.$this->get_field_name($prefix.'image_pl').'" type="number" value="'.esc_attr($instance[$prefix.'image_pl']).'" placeholder="0" /></div>
							</div>	
						</div>
						<div class="col-md-6">
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


			</div>
			<div class="col-md-4">

				<h4 class="row-title">'.__('Title Settings', 'siteitsob').' <span class="sitb-icon icon-title icon-big"></span></h4>
				<div class="row-wrap">
					<div class="admin-row">
						<div class="col-md-6">
							<label for="'.$this->get_field_id($prefix.'widget_title').'"><span class="label-wrap">'.__('Title', 'siteitsob').':</span>
								<input class="widefat" id="'.$this->get_field_id($prefix.'widget_title').'" name="'.$this->get_field_name($prefix.'widget_title').'" type="text" value="'.$instance[$prefix.'widget_title'].'" />
							</label>
                        </div>
						<div class="col-md-6">
							<label for="'.$this->get_field_name($prefix.'title_icon').'"><span class="label-wrap">'. __('Title Icon:', 'siteitsob').'</span><br></label>
							<input name="'.$this->get_field_name($prefix.'title_icon').'" id="'.$this->get_field_id($prefix.'title_icon').'" class="widefat imageUploader" type="text" size="36"  value="'.esc_url($instance[$prefix.'title_icon']).'" style="width: 62%;" /> 
							<input data-input="#'.$this->get_field_id($prefix.'title_icon').'" class="upload_image_button button button-primary" type="button" value="'.__('Upload', 'siteitsob').'" />
						</div>
						<div class="col-md-4">
							<label for="'.$this->get_field_id($prefix.'title_type').'"><span class="label-wrap">'.__('Title Type', 'siteitsob').':</span>
							<select class="widefat" id="'.$this->get_field_id($prefix.'title_type').'" name="'.$this->get_field_name($prefix.'title_type').'">
								'.$this->multi_select($instance[$prefix.'title_type'], '', 'ttype').'
							</select>	
							</label>	
						</div>
						<div class="col-md-4">
							<label for="'.$this->get_field_id($prefix.'title_size').'"><span class="label-wrap">'.__('Font Size (PX)', 'siteitsob').':</span>
								<input class="widefat" id="'.$this->get_field_id($prefix.'title_size').'" name="'.$this->get_field_name($prefix.'title_size').'" type="number" value="'.esc_attr($instance[$prefix.'title_size']).'" />
							</label>	
						</div>
						<div class="col-md-4">
							<label for="'.$this->get_field_id($prefix.'title_align').'"><span class="label-wrap">'.__('Title Align', 'siteitsob').':</span>
								<select class="widefat" id="'.$this->get_field_id($prefix.'title_align').'" name="'.$this->get_field_name($prefix.'title_align').'">
									'.$this->multi_select($instance[$prefix.'title_align'], __('Default', 'siteitsob'), 'talign').'
								</select>	
							</label>	
						</div>
						<div class="col-md-4">
							<label for="'.$this->get_field_id($prefix.'title_width').'"><span class="label-wrap">'.__('Width (PX or %)', 'siteitsob').':</span>
								<input class="widefat" id="'.$this->get_field_id($prefix.'title_width').'" name="'.$this->get_field_name($prefix.'title_width').'" type="text" value="'.esc_attr($instance[$prefix.'title_width']).'" />
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
							<label for="'.$this->get_field_id($prefix.'title_pt').'"><span class="label-wrap">'.__('Padding', 'siteitsob').':</span></label>
							<div class="admin-row mini-padding mb0">
								<div class="col-md-3 mb0"><input class="widefat" id="'.$this->get_field_id($prefix.'title_pt').'" name="'.$this->get_field_name($prefix.'title_pt').'" type="number" value="'.esc_attr($instance[$prefix.'title_pt']).'" placeholder="0" /> <span class="icon-small arrow-mt"></span></div>
								<div class="col-md-3 mb0"><input class="widefat" id="'.$this->get_field_id($prefix.'title_pr').'" name="'.$this->get_field_name($prefix.'title_pr').'" type="number" value="'.esc_attr($instance[$prefix.'title_pr']).'" placeholder="0" /></div>
								<div class="col-md-3 mb0"><input class="widefat" id="'.$this->get_field_id($prefix.'title_pb').'" name="'.$this->get_field_name($prefix.'title_pb').'" type="number" value="'.esc_attr($instance[$prefix.'title_pb']).'" placeholder="0" /></div>
								<div class="col-md-3 mb0"><input class="widefat" id="'.$this->get_field_id($prefix.'title_pl').'" name="'.$this->get_field_name($prefix.'title_pl').'" type="number" value="'.esc_attr($instance[$prefix.'title_pl']).'" placeholder="0" /></div>
							</div>
						</div>
						<div class="col-md-6 mb0">
							<label for="'.$this->get_field_id($prefix.'title_mt').'"><span class="label-wrap">'.__('Margin', 'siteitsob').':</span></label>
							<div class="admin-row mini-padding mb0">
								<div class="col-md-3 mb0"><input class="widefat" id="'.$this->get_field_id($prefix.'title_mt').'" name="'.$this->get_field_name($prefix.'title_mt').'" type="number" value="'.esc_attr($instance[$prefix.'title_mt']).'" placeholder="0" /></div>
								<div class="col-md-3 mb0"><input class="widefat" id="'.$this->get_field_id($prefix.'title_mr').'" name="'.$this->get_field_name($prefix.'title_mr').'" type="number" value="'.esc_attr($instance[$prefix.'title_mr']).'" placeholder="0" /></div>
								<div class="col-md-3 mb0"><input class="widefat" id="'.$this->get_field_id($prefix.'title_mb').'" name="'.$this->get_field_name($prefix.'title_mb').'" type="number" value="'.esc_attr($instance[$prefix.'title_mb']).'" placeholder="0" /></div>
								<div class="col-md-3 mb0"><input class="widefat" id="'.$this->get_field_id($prefix.'title_ml').'" name="'.$this->get_field_name($prefix.'title_ml').'" type="number" value="'.esc_attr($instance[$prefix.'title_ml']).'" placeholder="0" /></div>
							</div>
						</div>
					</div>
                </div>
                

				<h4 class="row-title">'.__('Link Settings', 'siteitsob').' <span class="sitb-icon icon-title icon-big"></span></h4>
				<div class="row-wrap">
					<div class="admin-row">
						<div class="col-md-4 mb0">
							<label for="'.$this->get_field_id($prefix.'link_url').'"><span class="label-wrap">'.__('Read More URL', 'siteitsob').':</span>
							<input class="widefat" id="'.$this->get_field_id($prefix.'link_url').'" name="'.$this->get_field_name($prefix.'link_url').'" type="text" value="'.esc_attr($instance[$prefix.'link_url']).'" />
							</label>
						</div>
						<div class="col-md-4">
							<label for="'.$this->get_field_id($prefix.'link_target').'"><span class="label-wrap">'.__('Link Target', 'siteitsob').':</span>
								<select class="widefat" id="'.$this->get_field_id($prefix.'link_target').'" name="'.$this->get_field_name($prefix.'link_target').'">
									'.$this->multi_select($instance[$prefix.'link_target'], __('Default'), 'linkTargets').'
								</select>
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
							<label for="'.$this->get_field_id($prefix.'widget_text').'"><span class="label-wrap">'.__('Widget Text', 'siteitsob').':</span></label>
							<textarea class="widefat main_text" id="'.$this->get_field_id($prefix.'widget_text').'" name="'.$this->get_field_name($prefix.'widget_text').'" rows="5">'.esc_attr($instance[$prefix.'widget_text']).'</textarea>
						</div>
						<div class="col-md-4">
							<label for="'.$this->get_field_id($prefix.'text_fweight').'"><span class="label-wrap">'.__('Font Weight', 'siteitsob').':</span>
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
							<label for="'.$this->get_field_id($prefix.'text_mt').'"><span class="label-wrap">'.__('Margin', 'siteitsob').':</span></label>
							<div class="admin-row mini-padding mb0">
								<div class="col-md-3"><input class="widefat" id="'.$this->get_field_id($prefix.'text_mt').'" name="'.$this->get_field_name($prefix.'text_mt').'" type="number" value="'.esc_attr($instance[$prefix.'text_mt']).'" placeholder="0" /></div>
								<div class="col-md-3"><input class="widefat" id="'.$this->get_field_id($prefix.'text_mr').'" name="'.$this->get_field_name($prefix.'text_mr').'" type="number" value="'.esc_attr($instance[$prefix.'text_mr']).'" placeholder="0" /></div>
								<div class="col-md-3"><input class="widefat" id="'.$this->get_field_id($prefix.'text_mb').'" name="'.$this->get_field_name($prefix.'text_mb').'" type="number" value="'.esc_attr($instance[$prefix.'text_mb']).'" placeholder="0" /></div>
								<div class="col-md-3"><input class="widefat" id="'.$this->get_field_id($prefix.'text_ml').'" name="'.$this->get_field_name($prefix.'text_ml').'" type="number" value="'.esc_attr($instance[$prefix.'text_ml']).'" placeholder="0" /></div>
							</div>
						</div>
						<div class="col-md-6 mb0">
							<label for="'.$this->get_field_id($prefix.'text_pt').'"><span class="label-wrap">'.__('Padding', 'siteitsob').':</span></label>
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
			<div class="col-md-12">
				<h4 class="row-title">'.__('Widget Advanced Settings', 'siteitsob').'  <span class="sitb-icon icon-cog icon-big"></span></h4>
				<div class="row-wrap">
					<div class="admin-row">
						<div class="col-md-6">
							<div class="admin-row">
								<div class="col-md-3 mb0">
									<label for="'.$this->get_field_id($prefix.'image_classes').'"><span class="label-wrap">'.__('Image Classes', 'siteitsob').':</span>
									<input class="widefat" id="'.$this->get_field_id($prefix.'image_classes').'" name="'.$this->get_field_name($prefix.'image_classes').'" type="text" value="'.esc_attr($instance[$prefix.'image_classes']).'" />
									</label>
								</div>
								<div class="col-md-3 mb0">
									<label for="'.$this->get_field_id($prefix.'title_classes').'"><span class="label-wrap">'.__('Title Classes', 'siteitsob').':</span>
									<input class="widefat" id="'.$this->get_field_id($prefix.'title_classes').'" name="'.$this->get_field_name($prefix.'title_classes').'" type="text" value="'.esc_attr($instance[$prefix.'title_classes']).'" />
									</label>
								</div>
								<div class="col-md-3 mb0">
									<label for="'.$this->get_field_id($prefix.'widget_classes').'"><span class="label-wrap">'.__('Widget Classes', 'siteitsob').':</span>
									<input class="widefat" id="'.$this->get_field_id($prefix.'widget_classes').'" name="'.$this->get_field_name($prefix.'widget_classes').'" type="text" value="'.esc_attr($instance[$prefix.'widget_classes']).'" placeholder="'.__('For example: mt0 mb0', 'siteitsob').'" />
									</label>
								</div>
								<div class="col-md-3 mb0">
									<label for="'.$this->get_field_id($prefix.'widget_bgcolor').'"><span class="label-wrap">'.__('Background Color', 'siteitsob').':</span>
										<input class="widefat sgColorPicker" id="'.$this->get_field_id($prefix.'widget_bgcolor').'" name="'.$this->get_field_name($prefix.'widget_bgcolor').'" type="text" value="'.esc_attr($instance[$prefix.'widget_bgcolor']).'" />
									</label>
								</div>
							</div>
						</div>
						<div class="col-md-6">
							<div class="admin-row">
								<div class="col-md-6 mb0">
									<label for="'.$this->get_field_id($prefix.'widget_pt').'"><span class="label-wrap">'.__('Widget Padding', 'siteitsob').':</span></label>
									<div class="admin-row small-padding mb0">
										<div class="col-md-3"><input class="widefat" id="'.$this->get_field_id($prefix.'widget_pt').'" name="'.$this->get_field_name($prefix.'widget_pt').'" type="number" value="'.esc_attr($instance[$prefix.'widget_pt']).'" placeholder="0" /></div>
										<div class="col-md-3"><input class="widefat" id="'.$this->get_field_id($prefix.'widget_pr').'" name="'.$this->get_field_name($prefix.'widget_pr').'" type="number" value="'.esc_attr($instance[$prefix.'widget_pr']).'" placeholder="0" /></div>
										<div class="col-md-3"><input class="widefat" id="'.$this->get_field_id($prefix.'widget_pb').'" name="'.$this->get_field_name($prefix.'widget_pb').'" type="number" value="'.esc_attr($instance[$prefix.'widget_pb']).'" placeholder="0" /></div>
										<div class="col-md-3"><input class="widefat" id="'.$this->get_field_id($prefix.'widget_pl').'" name="'.$this->get_field_name($prefix.'widget_pl').'" type="number" value="'.esc_attr($instance[$prefix.'widget_pl']).'" placeholder="0" /></div>
									</div>
								</div>
								<div class="col-md-6 mb0">
									<label for="'.$this->get_field_id($prefix.'widget_mt').'"><span class="label-wrap">'.__('Widget Margin', 'siteitsob').':</span></label>
									<div class="admin-row small-padding mb0">
										<div class="col-md-3"><input class="widefat" id="'.$this->get_field_id($prefix.'widget_mt').'" name="'.$this->get_field_name($prefix.'widget_mt').'" type="number" value="'.esc_attr($instance[$prefix.'widget_mt']).'" placeholder="0" /></div>
										<div class="col-md-3"><input class="widefat" id="'.$this->get_field_id($prefix.'widget_mr').'" name="'.$this->get_field_name($prefix.'widget_mr').'" type="number" value="'.esc_attr($instance[$prefix.'widget_mr']).'" placeholder="0" /></div>
										<div class="col-md-3"><input class="widefat" id="'.$this->get_field_id($prefix.'widget_mb').'" name="'.$this->get_field_name($prefix.'widget_mb').'" type="number" value="'.esc_attr($instance[$prefix.'widget_mb']).'" placeholder="0" /></div>
										<div class="col-md-3"><input class="widefat" id="'.$this->get_field_id($prefix.'widget_ml').'" name="'.$this->get_field_name($prefix.'widget_ml').'" type="number" value="'.esc_attr($instance[$prefix.'widget_ml']).'" placeholder="0" /></div>
									</div>
								</div>							
							</div>
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

		jQuery(document).ready(function($) {
			$(".sgColorPicker").wpColorPicker();
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
		$result 		= '';
		$before_widget 	= '';
		$after_widget  	= '';
        $prefix = '';


		// FIX IMAGE
		if(isset($instance[$prefix.'image_url'])) {
			$imgStyles 	= '';
			$imgClasses = 'img-responsive ';
	
	
			if($instance[$prefix.'image_classes']) {$imgClasses .= $instance[$prefix.'image_classes'].' ';}
	
			$imgStyles 	.= $this->build_margpad_array($instance[$prefix.'image_pt'], $instance[$prefix.'image_pr'], $instance[$prefix.'image_pb'], $instance[$prefix.'image_pl'], 'padding');
			$imgStyles 	.= $this->build_margpad_array($instance[$prefix.'image_mt'], $instance[$prefix.'image_mr'], $instance[$prefix.'image_mb'], $instance[$prefix.'image_ml'], 'margin');
	
			$image 		= '<div class="img-box '.$instance[$prefix.'image_align'].'">'.($instance[$prefix.'link_url'] ? '<a href="'.$instance[$prefix.'link_url'].'">' : '').'<img src="'.$instance[$prefix.'image_url'].'" alt="'.$instance[$prefix.'image_alt'].'" class="'.$imgClasses.'" style="'.$imgStyles.'" /> '.($instance[$prefix.'link_url'] ? '</a>' : '').'</div>';				
		}



		// FIX TITLE 
		if(isset($instance[$prefix.'widget_title'])) {

			$titleCss 		= '';
			$titleCss 		.= $this->build_margpad_array($instance[$prefix.'title_pt'], $instance[$prefix.'title_pr'], $instance[$prefix.'title_pb'], $instance[$prefix.'title_pl'], 'padding');
			$titleCss 		.= $this->build_margpad_array($instance[$prefix.'title_mt'], $instance[$prefix.'title_mr'], $instance[$prefix.'title_mb'], $instance[$prefix.'title_ml'], 'margin');

			if(!$instance[$prefix.'title_type']) {$instance[$prefix.'title_type'] = 'h3';}
			if(!$instance[$prefix.'title_size']) {$instance[$prefix.'title_size'] = '30'; }
			if($instance[$prefix.'title_color']) {$titleCss .= 'color:'.$instance[$prefix.'title_color'].';';}
			if($instance[$prefix.'title_width']) {$titleCss .= 'width:'.$instance[$prefix.'title_width'].';'; }


            $widget_title = '
            <div class="title-box '.$instance[$prefix.'title_align'].' ">
                '.($instance[$prefix.'title_icon'] ? '<div class="title-icon"><img src="'.$instance[$prefix.'title_icon'].'" alt=""></div>' : '').'
				<'.$instance[$prefix.'title_type'].' class="widgettitle '.$instance[$prefix.'title_fweight'].' '.$instance[$prefix.'title_classes'].' " style="'.$titleCss.'; font-size: '.$instance[$prefix.'title_size'].'px; display: inline-block;"> <a href="'.$instance[$prefix.'link_url'].'">'.$instance[$prefix.'widget_title'].'</a> </'.$instance[$prefix.'title_type'].'>
			</div>
			';
		}
		else {$instance[$prefix.'widget_title'] = '';}


		
		// FIX TEXT CSS
		if(isset($instance[$prefix.'widget_text'])) {
			$textCss 		= '';
			$textCss 		.= $this->build_margpad_array($instance[$prefix.'text_pt'], $instance[$prefix.'text_pr'], $instance[$prefix.'text_pb'], $instance[$prefix.'text_pl'], 'padding');
			$textCss 		.= $this->build_margpad_array($instance[$prefix.'text_mt'], $instance[$prefix.'text_mr'], $instance[$prefix.'text_mb'], $instance[$prefix.'text_ml'], 'margin');

			if($instance[$prefix.'text_width']) {$textCss .= 'width: '.$instance[$prefix.'text_width'].';';}
			if($instance[$prefix.'text_size']) {$textCss .= 'font-size: '.$instance[$prefix.'text_size'].'px;';}
			if($instance[$prefix.'text_color']) {$textCss .= 'color: '.$instance[$prefix.'text_color'].';';} 

			$text = '<div class="text-box '.$instance[$prefix.'text_size'].' '.$instance[$prefix.'text_fweight'].' '.$instance[$prefix.'text_classes'].'" style="'.$textCss  .'">'.$instance[$prefix.'widget_text'].' '.($instance[$prefix.'link_url'] ? '<p><a href="'.$instance[$prefix.'link_url'].'" class="rm">'.__('Read More', THEME_NAME).'</a></p>' : '').'</div>';
		}



		// FIX MARGINS & PADDINGS
		$widgetCss 		= '';
		$widgetCss 		.= $this->build_margpad_array($instance[$prefix.'widget_pt'], $instance[$prefix.'widget_pr'], $instance[$prefix.'widget_pb'], $instance[$prefix.'widget_pl'], 'padding');
		$widgetCss 		.= $this->build_margpad_array($instance[$prefix.'widget_mt'], $instance[$prefix.'widget_mr'], $instance[$prefix.'widget_mb'], $instance[$prefix.'widget_ml'], 'margin');


		// WIDGET BACKGROUND COLOR
		if($instance[$prefix.'widget_bgcolor']){
			$widgetCss	.= 'background: '.$instance[$prefix.'widget_bgcolor'].';';
		}

        


		$result = '
		<div class="singleWidget sitBuilderWidget sitBuilderServicesWidget '.$instance[$prefix.'widget_classes'].'" style="'.$widgetCss.'">
			'.$image.'
            <div class="widgetTxtBlock">
                <div class="row">
                    <div class="col-md-6 col-xs-12">'.$widget_title.'</div>
                    <div class="col-md-6 col-xs-12">'.$text.'</div>
                </div>
            </div>
		</div>
		';
		
	
		echo $before_widget.$result.$after_widget;

	}
 
}
//add_action( 'widgets_init', create_function('', 'return register_widget("sgLocalServicesWidget");') );
add_action( 'widgets_init', function () {
    return register_widget('sgLocalServicesWidget');
}, 1 );
?>