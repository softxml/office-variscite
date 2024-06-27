<?php
class sgBtnWidget extends WP_Widget {


	
	// DEFINE THE WIDGET
	function __construct() {
		$widget_ops = array(
			'classname' 	=> 'sg_btn_widg', 
			'description'	=> __('Custom Button widget by SITEIT', 'siteitsob')
		);
		parent::__construct('sgBtnWidget', __('Button Widget', 'siteitsob'), $widget_ops);
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
		$arr 					= array_filter($arr, 'is_numeric');

		if(!empty($arr)) {
			foreach($arr as $key => $value) {
				$result .= $key.':'.$value.'px;';
			}
		}

		return $result;
	}
 
 
	// MULTI SELECT
	function sgDataArr($type) {

		include(plugin_dir_path( __FILE__ ).'../functions/cssAnimationArray.php');
		

		if($type == 'yesno') {
			$type = array('yes'=>__('Yes'), 'no'=>__('No'));
		}
		elseif($type == 'linkTargets') {
			$type = array('_blank'=>__('New Window', 'siteitsob'), '_self'=>__('Same Window', 'siteitsob'));
		}
		elseif($type == 'btnSizes') {
			$type = array('' => __('Standard', 'siteitsob'), 'btn-large'=>__('Large Button'), 'btn-huge'=>__('huge Button'));
		}
		elseif($type == 'btnColors') {
			$type = array( '' => __('No PreDefined Color'), 'btn-default' => __('White Button', 'siteitsob'), 'btn-blue' => __('Blue Button', 'siteitsob'), 'btn-green'=>__('Green Button', 'siteitsob'), 'btn-turkoise'=>__('Turkoise Button', 'siteitsob'), 'btn-orange'=>__('Orange Button', 'siteitsob'), 'btn-red'=>__('Red Button', 'siteitsob'), 'btn-dark'=>__('Dark Button', 'siteitsob'), 'btn-black'=>__('Black Button', 'siteitsob'), );
		}
		elseif($type == 'btnAlign') {
			$type = array('' => __('Default', 'siteitsob'), 'text-right'=>__('Right', 'siteitsob'), 'text-center'=>__('Center', 'siteitsob'), 'text-left' => __('Left', 'siteitsob'));
		}
		elseif($type == 'fontweight') {
			$type = array( 'light' => __('Light', 'siteitsob'), 'normal' => __('Normal', 'siteitsob'), 'bold' => __('Bold', 'siteitsob'));
		}
		elseif($type == 'borderadius') {
			$type = array('brad0'=>__('0px', 'siteitsob'), 'brad3'=>__('3px', 'siteitsob'), 'brad5'=>__('5px', 'siteitsob'), 'brad7' => __('7px', 'siteitsob'), 'brad10' => __('10px', 'siteitsob'), 'brad25' => __('25px', 'siteitsob'), 'brad50' => __('50px', 'siteitsob'), 'brad50p' => __('50%', 'siteitsob'));
		}
		elseif($type == 'iconside') {
			$type = array('pull-left'=>__('Left', 'siteitsob'), 'pull-right'=>__('Right', 'siteitsob'));
		}
		elseif($type == 'animations') {
			$type = $animationArray;
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


	function siteit_widget_fields() {
		$desktop_keys = array(
			'btn_label',
			'btn_link',
			'btn_target',
			'btn_size',
			'btn_fsize',
			'btn_fontweight',
			'btn_bordersize',
			'btn_borderadius',
			'btn_color',
			'btn_transbg',
			'btn_custom_bg',
			'btn_border_color',
			'btn_custom_color',
			'btn_custom_hoverbg',
			'btn_border_hovercolor',
			'btn_custom_hovercolor',
			'btn_mt',
			'btn_mr',
			'btn_mb',
			'btn_ml',
			'btn_pt',
			'btn_pr',
			'btn_pb',
			'btn_pl',
			'btn_textalign',
			'btn_align',
			'btn_icon',
			'btn_iconside',
			'image_icon',
			'image_iconhover',
			'image_icon_alt',
			'btn_iconside',
			'iconimg_pt',
			'iconimg_pr',
			'iconimg_pb',
			'iconimg_pl',
			'iconimg_mt',
			'iconimg_mr',
			'iconimg_mb',
			'iconimg_ml',
			'btn_classes',
			'btn_id',

			'btn_animation',
			'btn_animation_duration',
			'btn_animation_delay',	

			'widget_mt',
			'widget_mr',
			'widget_mb',
			'widget_ml',
			'widget_pt',
			'widget_pr',
			'widget_pb',
			'widget_pl',
			'widget_classes',

			'use_mobile',
		);

		return $desktop_keys;
	}


	function form_fileds_looper($instance) {

		// rtl fixes
		if(is_rtl()) {$floatDir = 'left';} else {$floatDir = 'right';}
        $prefix 		= '';

		$formFields = '
		<div class="admin-row">
			<div class="col-md-4">

				<h4 class="row-title">'.__('Button Settings', 'siteitsob').'  <span class="sitb-icon icon-cog icon-big"></span></h4>
				<div class="row-wrap">
					<div class="admin-row">
						<div class="col-md-6">
							<label for="'.$this->get_field_id($prefix.'btn_label').'"><span class="label-wrap">'.__('Button Text', 'siteitsob').':</span>
							<input class="widefat" id="'.$this->get_field_id($prefix.'btn_label').'" name="'.$this->get_field_name($prefix.'btn_label').'" type="text" value="'.esc_attr($instance[$prefix.'btn_label']).'" />
							</label>	
						</div>
						<div class="col-md-6">
							<label for="'.$this->get_field_id($prefix.'btn_textalign').'"><span class="label-wrap">'.__('Text Align', 'siteitsob').':</span>
								<select class="widefat" id="'.$this->get_field_id($prefix.'btn_textalign').'" name="'.$this->get_field_name($prefix.'btn_textalign').'">
									'.$this->multi_select($instance[$prefix.'btn_textalign'], '', 'btnAlign').'
								</select>	
							</label>
						</div>
						<div class="col-md-6">
							<label for="'.$this->get_field_id($prefix.'btn_link').'"><span class="label-wrap">'.__('Button Link (URL)', 'siteitsob').':</span>
								<input class="widefat" id="'.$this->get_field_id($prefix.'btn_link').'" name="'.$this->get_field_name($prefix.'btn_link').'" type="text" value="'.esc_attr($instance[$prefix.'btn_link']).'" placeholder="http://www.example.com" />
							</label>
						</div>
						<div class="col-md-6">
							<label for="'.$this->get_field_id($prefix.'btn_target').'"><span class="label-wrap">'.__('Link Target', 'siteitsob').':</span>
								<select class="widefat" id="'.$this->get_field_id($prefix.'btn_target').'" name="'.$this->get_field_name($prefix.'btn_target').'">
									'.$this->multi_select($instance[$prefix.'btn_target'], __('Default', 'siteitsob'), 'linkTargets').'
								</select>	
							</label>
						</div>
						<div class="col-md-6">
							<label for="'.$this->get_field_id($prefix.'btn_pt').'"><span class="label-wrap">'.__('Padding', 'siteitsob').':</span></label>
							<div class="admin-row mini-padding mb0">
								<div class="col-md-3"><input class="widefat" id="'.$this->get_field_id($prefix.'btn_pt').'" name="'.$this->get_field_name($prefix.'btn_pt').'" type="number" value="'.esc_attr($instance[$prefix.'btn_pt']).'" placeholder="0" /></div>
								<div class="col-md-3"><input class="widefat" id="'.$this->get_field_id($prefix.'btn_pr').'" name="'.$this->get_field_name($prefix.'btn_pr').'" type="number" value="'.esc_attr($instance[$prefix.'btn_pr']).'" placeholder="0" /></div>
								<div class="col-md-3"><input class="widefat" id="'.$this->get_field_id($prefix.'btn_pb').'" name="'.$this->get_field_name($prefix.'btn_pb').'" type="number" value="'.esc_attr($instance[$prefix.'btn_pb']).'" placeholder="0" /></div>
								<div class="col-md-3"><input class="widefat" id="'.$this->get_field_id($prefix.'btn_pl').'" name="'.$this->get_field_name($prefix.'btn_pl').'" type="number" value="'.esc_attr($instance[$prefix.'btn_pl']).'" placeholder="0" /></div>
							</div>	
						</div>
						<div class="col-md-6">
							<label for="'.$this->get_field_id($prefix.'btn_mt').'"><span class="label-wrap">'.__('Margin', 'siteitsob').':</span></label>
							<div class="admin-row mini-padding mb0">
								<div class="col-md-3"><input class="widefat" id="'.$this->get_field_id($prefix.'btn_mt').'" name="'.$this->get_field_name($prefix.'btn_mt').'" type="number" value="'.esc_attr($instance[$prefix.'btn_mt']).'" placeholder="0" /></div>
								<div class="col-md-3"><input class="widefat" id="'.$this->get_field_id($prefix.'btn_mr').'" name="'.$this->get_field_name($prefix.'btn_mr').'" type="number" value="'.esc_attr($instance[$prefix.'btn_mr']).'" placeholder="0" /></div>
								<div class="col-md-3"><input class="widefat" id="'.$this->get_field_id($prefix.'btn_mb').'" name="'.$this->get_field_name($prefix.'btn_mb').'" type="number" value="'.esc_attr($instance[$prefix.'btn_mb']).'" placeholder="0" /></div>
								<div class="col-md-3"><input class="widefat" id="'.$this->get_field_id($prefix.'btn_ml').'" name="'.$this->get_field_name($prefix.'btn_ml').'" type="number" value="'.esc_attr($instance[$prefix.'btn_ml']).'" placeholder="0" /></div>
							</div>	
						</div>
						<div class="col-md-4">
							<label for="'.$this->get_field_id($prefix.'btn_align').'"><span class="label-wrap">'.__('Button Align', 'siteitsob').':</span>
								<select class="widefat" id="'.$this->get_field_id($prefix.'btn_align').'" name="'.$this->get_field_name($prefix.'btn_align').'">
									'.$this->multi_select($instance[$prefix.'btn_align'], '', 'btnAlign').'
								</select>	
							</label>
						</div>
						<div class="col-md-4">
							<label for="'.$this->get_field_id($prefix.'btn_id').'"><span class="label-wrap">'.__('Button ID', 'siteitsob').':</span>
								<input class="widefat" id="'.$this->get_field_id($prefix.'btn_id').'" name="'.$this->get_field_name($prefix.'btn_id').'" type="text" value="'.esc_attr($instance[$prefix.'btn_id']).'" />
							</label>							
						</div>
						<div class="col-md-4">
							<label for="'.$this->get_field_id($prefix.'btn_classes').'"><span class="label-wrap">'.__('Button Classes', 'siteitsob').':</span>
								<input class="widefat" id="'.$this->get_field_id($prefix.'btn_classes').'" name="'.$this->get_field_name($prefix.'btn_classes').'" type="text" value="'.esc_attr($instance[$prefix.'btn_classes']).'" />
							</label>							
						</div>
					</div>
				</div>

			</div>
			<div class="col-md-4">

				<h4 class="row-title">'.__('Styling', 'siteitsob').'  <span class="sitb-icon icon-styling icon-big"></span></h4>
				<div class="row-wrap">
					<div class="admin-row">

						<div class="col-md-4">
							<label for="'.$this->get_field_id($prefix.'btn_color').'"><span class="label-wrap">'.__('Preset Button Color', 'siteitsob').':</span>
								<select class="widefat" id="'.$this->get_field_id($prefix.'btn_color').'" name="'.$this->get_field_name($prefix.'btn_color').'">
									'.$this->multi_select($instance[$prefix.'btn_color'], '', 'btnColors').'
								</select>	
							</label>	
						</div>
						<div class="col-md-4">
							<label for="'.$this->get_field_id($prefix.'btn_fsize').'"><span class="label-wrap">'.__('Font Size', 'siteitsob').':</span>
								<input class="widefat" id="'.$this->get_field_id($prefix.'btn_fsize').'" name="'.$this->get_field_name($prefix.'btn_fsize').'" type="number" value="'.esc_attr($instance[$prefix.'btn_fsize']).'" placeholder="'.__('Empty means default', 'siteitsob').'" />
							</label>
						</div>
						<div class="col-md-4">
							<label for="'.$this->get_field_id($prefix.'btn_fontweight').'"><span class="label-wrap">'.__('Font Weight', 'siteitsob').':</span>
								<select class="widefat" id="'.$this->get_field_id($prefix.'btn_fontweight').'" name="'.$this->get_field_name($prefix.'btn_fontweight').'">
									'.$this->multi_select($instance[$prefix.'btn_fontweight'], __('Default', 'siteitsob'), 'fontweight').'
								</select>
							</label>
						</div>
						<div class="col-md-4">
							<label for="'.$this->get_field_id($prefix.'btn_size').'"><span class="label-wrap">'.__('Button Size', 'siteitsob').':</span>
								<select class="widefat" id="'.$this->get_field_id($prefix.'btn_size').'" name="'.$this->get_field_name($prefix.'btn_size').'">
									'.$this->multi_select($instance[$prefix.'btn_size'], __('Default', 'siteitsob'), 'btnSizes').'
								</select>	
							</label>						
						</div>
						<div class="col-md-4">
							<label for="'.$this->get_field_id($prefix.'btn_bordersize').'"><span class="label-wrap">'.__('Border Width (PX)', 'siteitsob').':</span>
								<input class="widefat" id="'.$this->get_field_id($prefix.'btn_bordersize').'" name="'.$this->get_field_name($prefix.'btn_bordersize').'" type="number" value="'.esc_attr($instance[$prefix.'btn_bordersize']).'" placeholder="" />
							</label>
						</div>
						<div class="col-md-4">
							<label for="'.$this->get_field_id($prefix.'btn_borderadius').'"><span class="label-wrap">'.__('Border Radius', 'siteitsob').':</span>
								<select class="widefat" id="'.$this->get_field_id($prefix.'btn_borderadius').'" name="'.$this->get_field_name($prefix.'btn_borderadius').'">
									'.$this->multi_select($instance[$prefix.'btn_borderadius'], '', 'borderadius').'
								</select>	
							</label>						
						</div>
						<div class="col-md-12">
							<div class=" fullbED" style="padding: 15px 20px 0; ">
								<div class="admin-row">
									<div class="col-md-12 tooltip" data-tip="'.__('Reset Pre Defined color to use this', 'siteitsob').'">
										
										<div style="'.(is_rtl() ? 'float: left' : 'float: right').';" >
											<label for="'.$this->get_field_id($prefix.'btn_transbg').'"><input class="widefat" id="'.$this->get_field_id($prefix.'btn_transbg').'" type="checkbox" name="'.$this->get_field_name($prefix.'btn_transbg').'" '.($instance[$prefix.'btn_transbg'] == 'on' ? 'checked' : '').' /> '.__('Use Transparent Background', 'siteitsob').'
											</label>										
										</div>

										<h5 class="bold m0">'.__('Custom Colors', 'siteitsob').'</h5>
									</div>
									<div class="col-md-6">
										<h6><strong>'.__('Base State').'</strong></h6>
										<div class="form-group">
											<label for="'.$this->get_field_id($prefix.'btn_custom_bg').'"><span class="label-wrap">'.__('Background Color', 'siteitsob').':</span></label> <br />
											<input class="widefat colorPicker" id="'.$this->get_field_id($prefix.'btn_custom_bg').'" type="text" name="'.$this->get_field_name($prefix.'btn_custom_bg').'" value="'.(esc_attr($instance[$prefix.'btn_custom_bg']) ? esc_attr($instance[$prefix.'btn_custom_bg']) : '#ECF0F1').'" />
										</div>
										<div class="form-group">
											<label for="'.$this->get_field_id($prefix.'btn_border_color').'"><span class="label-wrap">'.__('Border Color', 'siteitsob').':</span></label> <br />
											<input class="widefat colorPicker" id="'.$this->get_field_id($prefix.'btn_border_color').'" type="text" name="'.$this->get_field_name($prefix.'btn_border_color').'" value="'.esc_attr($instance[$prefix.'btn_border_color']).'" />
										</div>
										<div class="form-group">
											<label for="'.$this->get_field_id($prefix.'btn_custom_color').'"><span class="label-wrap">'.__('Text Color', 'siteitsob').':</span></label> <br />
											<input class="widefat colorPicker" id="'.$this->get_field_id($prefix.'btn_custom_color').'" type="text" name="'.$this->get_field_name($prefix.'btn_custom_color').'" value="'.esc_attr($instance[$prefix.'btn_custom_color']).'" />
										</div>
									</div>
									<div class="col-md-6">
										<h6><strong>'.__('Hover State').'</strong></h6>
										<div class="form-group">
											<label for="'.$this->get_field_id($prefix.'btn_custom_hoverbg').'"><span class="label-wrap">'.__('Background Color', 'siteitsob').':</span></label> <br />
											<input class="widefat colorPicker" id="'.$this->get_field_id($prefix.'btn_custom_hoverbg').'" type="text" name="'.$this->get_field_name($prefix.'btn_custom_hoverbg').'" value="'.(esc_attr($instance[$prefix.'btn_custom_hoverbg']) ? esc_attr($instance[$prefix.'btn_custom_hoverbg']) : '#ECF0F1').'" />
										</div>
										<div class="form-group">
											<label for="'.$this->get_field_id($prefix.'btn_border_hovercolor').'"><span class="label-wrap">'.__('Border Color', 'siteitsob').':</span></label> <br />
											<input class="widefat colorPicker" id="'.$this->get_field_id($prefix.'btn_border_hovercolor').'" type="text" name="'.$this->get_field_name($prefix.'btn_border_hovercolor').'" value="'.esc_attr($instance[$prefix.'btn_border_hovercolor']).'" />
										</div>
										<div class="form-group">
											<label for="'.$this->get_field_id($prefix.'btn_custom_hovercolor').'"><span class="label-wrap">'.__('Text Color', 'siteitsob').':</span></label> <br />
											<input class="widefat colorPicker" id="'.$this->get_field_id($prefix.'btn_custom_hovercolor').'" type="text" name="'.$this->get_field_name($prefix.'btn_custom_hovercolor').'" value="'.esc_attr($instance[$prefix.'btn_custom_hovercolor']).'" />
										</div>
									</div>
								</div>
							</div>
						</div>


					</div>
				</div>

			</div>
			<div class="col-md-4">

				<h4 class="row-title">'.__('Button Icon', 'siteitsob').'  <span class="sitb-icon icon-pin icon-big"></span></h4>
				<div class="row-wrap">
					<div class="admin-row">
						<div class="col-md-4">
							<label for="'.$this->get_field_id($prefix.'btn_icon').'"><span class="label-wrap">'.__('Button Icon', 'siteitsob').'</span>:
								<select class="fa-select widefat" id="'.$this->get_field_id($prefix.'btn_icon').'" name="'.$this->get_field_name($prefix.'btn_icon').'">
									'.$this->faicons_select($instance[$prefix.'btn_icon'], __('No Icon', 'siteitsob')).'
								</select>
							</label>
						</div>
						<div class="col-md-4">
							<label for="'.$this->get_field_name($prefix.'image_icon').'"><span class="label-wrap">'. __('Icon Image Instead?', 'siteitsob').'</span></label>
							<input name="'.$this->get_field_name($prefix.'image_icon').'" id="'.$this->get_field_id($prefix.'image_icon').'" class="widefat imageUploader" type="text" size="36"  value="'.esc_url($instance[$prefix.'image_icon']).'" style="width: 47%;" /> 
							<input data-input="#'.$this->get_field_id($prefix.'image_icon').'" class="upload_image_button button button-primary" type="button" value="'.__('Upload', 'siteitsob').'" />
						</div>
						<div class="col-md-4">
							<label for="'.$this->get_field_name($prefix.'image_iconhover').'"><span class="label-wrap">'. __('Icon Image Hover', 'siteitsob').'</span></label>
							<input name="'.$this->get_field_name($prefix.'image_iconhover').'" id="'.$this->get_field_id($prefix.'image_iconhover').'" class="widefat imageUploader" type="text" size="36"  value="'.esc_url($instance[$prefix.'image_iconhover']).'" style="width: 47%;" /> 
							<input data-input="#'.$this->get_field_id($prefix.'image_iconhover').'" class="upload_image_button button button-primary" type="button" value="'.__('Upload', 'siteitsob').'" />
						</div>
						<div class="col-md-4">
							<label for="'.$this->get_field_id($prefix.'image_icon_alt').'"><span class="label-wrap">'.__('Image Alt', 'siteitsob').':</span>
								<input class="widefat" id="'.$this->get_field_id($prefix.'image_icon_alt').'" type="text" name="'.$this->get_field_name($prefix.'image_icon_alt').'" value="'.esc_attr($instance[$prefix.'image_icon_alt']).'" />
							</label>											
						</div>
						<div class="col-md-4">
							<label for="'.$this->get_field_id($prefix.'btn_iconside').'"><span class="label-wrap">'.__('Icon Side', 'siteitsob').'</span>:
								<select class="widefat" id="'.$this->get_field_id($prefix.'btn_iconside').'" name="'.$this->get_field_name($prefix.'btn_iconside').'">
									'.$this->multi_select($instance[$prefix.'btn_iconside'], '', 'iconside').'
								</select>
							</label>
						</div>
						<div class="col-md-12"> </div>
						<div class="col-md-6">
							<label for="'.$this->get_field_id($prefix.'iconimg_pt').'"><span class="label-wrap">'.__('Icon Padding', 'siteitsob').':</span></label>
							<div class="admin-row small-padding mb0">
								<div class="col-md-3"><input class="widefat" id="'.$this->get_field_id($prefix.'iconimg_pt').'" name="'.$this->get_field_name($prefix.'iconimg_pt').'" type="number" value="'.esc_attr($instance[$prefix.'iconimg_pt']).'" placeholder="0" /></div>
								<div class="col-md-3"><input class="widefat" id="'.$this->get_field_id($prefix.'iconimg_pr').'" name="'.$this->get_field_name($prefix.'iconimg_pr').'" type="number" value="'.esc_attr($instance[$prefix.'iconimg_pr']).'" placeholder="0" /></div>
								<div class="col-md-3"><input class="widefat" id="'.$this->get_field_id($prefix.'iconimg_pb').'" name="'.$this->get_field_name($prefix.'iconimg_pb').'" type="number" value="'.esc_attr($instance[$prefix.'iconimg_pb']).'" placeholder="0" /></div>
								<div class="col-md-3"><input class="widefat" id="'.$this->get_field_id($prefix.'iconimg_pl').'" name="'.$this->get_field_name($prefix.'iconimg_pl').'" type="number" value="'.esc_attr($instance[$prefix.'iconimg_pl']).'" placeholder="0" /></div>
							</div>	
						</div>	
						<div class="col-md-6">
							<label for="'.$this->get_field_id($prefix.'iconimg_mt').'"><span class="label-wrap">'.__('Icon Margin', 'siteitsob').':</span></label>
							<div class="admin-row small-padding mb0">
								<div class="col-md-3"><input class="widefat" id="'.$this->get_field_id($prefix.'iconimg_mt').'" name="'.$this->get_field_name($prefix.'iconimg_mt').'" type="number" value="'.esc_attr($instance[$prefix.'iconimg_mt']).'" placeholder="0" /></div>
								<div class="col-md-3"><input class="widefat" id="'.$this->get_field_id($prefix.'iconimg_mr').'" name="'.$this->get_field_name($prefix.'iconimg_mr').'" type="number" value="'.esc_attr($instance[$prefix.'iconimg_mr']).'" placeholder="0" /></div>
								<div class="col-md-3"><input class="widefat" id="'.$this->get_field_id($prefix.'iconimg_mb').'" name="'.$this->get_field_name($prefix.'iconimg_mb').'" type="number" value="'.esc_attr($instance[$prefix.'iconimg_mb']).'" placeholder="0" /></div>
								<div class="col-md-3"><input class="widefat" id="'.$this->get_field_id($prefix.'iconimg_ml').'" name="'.$this->get_field_name($prefix.'iconimg_ml').'" type="number" value="'.esc_attr($instance[$prefix.'iconimg_ml']).'" placeholder="0" /></div>
							</div>	
						</div>	

					</div>
				</div>


				<h4 class="row-title">'.__('Button Animation', 'siteitsob').'  <span class="sitb-icon icon-pazel icon-big"></span></h4>
				<div class="row-wrap">
					<div class="admin-row">

						<div class="col-md-4">
							<label for="'.$this->get_field_id($prefix.'btn_animation').'"><span class="label-wrap">'.__('Animation Type', 'siteitsob').'</span>:
								<select class="widefat" id="'.$this->get_field_id($prefix.'btn_animation').'" name="'.$this->get_field_name($prefix.'btn_animation').'">
									'.$this->multi_select($instance[$prefix.'btn_animation'], '', 'animations').'
								</select>
							</label>						
						</div>
						<div class="col-md-4">
							<label for="'.$this->get_field_id($prefix.'btn_animation_duration').'"><span class="label-wrap">'.__('Animation Duration', 'siteitsob').':</span>
								<div class="range-counter counter-'.$this->get_field_id($prefix.'btn_animation_duration').'" style="margin-top: 0;">'.($instance[$prefix.'btn_animation_duration'] ? esc_attr($instance[$prefix.'btn_animation_duration']) : 1).'</div>
								<input class="widefat range-input" data-counter="counter-'.$this->get_field_id($prefix.'btn_animation_duration').'" id="'.$this->get_field_id($prefix.'btn_animation_duration').'" name="'.$this->get_field_name($prefix.'btn_animation_duration').'" type="range" value="'.($instance[$prefix.'btn_animation_duration'] ? esc_attr($instance[$prefix.'btn_animation_duration']) : 1).'" min="0.5" max="10" step="0.5" />
							</label>
						</div>
						<div class="col-md-4">
							<label for="'.$this->get_field_id($prefix.'btn_animation_delay').'"><span class="label-wrap">'.__('Animation Delay', 'siteitsob').':</span>
								<div class="range-counter counter-'.$this->get_field_id($prefix.'btn_animation_delay').'" style="margin-top: 0;">'.($instance[$prefix.'btn_animation_delay'] ? esc_attr($instance[$prefix.'btn_animation_delay']) : 0.25).'</div>
								<input class="widefat range-input" data-counter="counter-'.$this->get_field_id($prefix.'btn_animation_delay').'" id="'.$this->get_field_id($prefix.'btn_animation_delay').'" name="'.$this->get_field_name($prefix.'btn_animation_delay').'" type="range" value="'.($instance[$prefix.'btn_animation_delay'] ? esc_attr($instance[$prefix.'btn_animation_delay']) : 0.5).'" min="0" max="10" step="0.25" />
							</label>
						</div>
					</div>
				</div>

			</div>

			<div class="col-md-12">
				<h4 class="row-title">'.__('Widget Advanced Settings', 'siteitsob').'  <span class="sitb-icon icon-cog icon-big"></span></h4>
				<div class="row-wrap">
					<div class="admin-row">
						<div class="col-md-4">
							<label for="'.$this->get_field_id($prefix.'widget_classes').'"><span class="label-wrap">'.__('Widget Classes', 'siteitsob').':</span>
							<input class="widefat" id="'.$this->get_field_id($prefix.'widget_classes').'" name="'.$this->get_field_name($prefix.'widget_classes').'" type="text" value="'.esc_attr($instance[$prefix.'widget_classes']).'" placeholder="'.__('For example: mt0 mb0', 'siteitsob').'" />
							</label>
						</div>
						<div class="col-md-4">
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

		
		<script>
		jQuery(function($){
		   $(".colorPicker").wpColorPicker();
		});
		</script>
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
		<div>'.$this->form_fileds_looper($instance).'</div>';
		
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

        $prefix = '';
		$unqiueID 	= $instance['panels_info']['widget_id'].'-'.$instance['panels_info']['widget_index'];
		$btnID 		= !empty($instance[$prefix.'btn_id']) ? $instance[$prefix.'btn_id'] : 'btn-'.$unqiueID;



		// FIX MARGINS & PADDINGS
		$widgetCss 		= '';
		$widgetCss 		.= easy_margpad_array($instance, $prefix, 'widget', 'padding');
		$widgetCss 		.= easy_margpad_array($instance, $prefix, 'widget', 'margin');




		// BASIC SETTINGS
		$result					= '';
		$widget_classes 		= !empty($instance[$prefix.'widget_classes']) ? $instance[$prefix.'widget_classes'] : '';
		$before_widget 			= '<div class="singleWidget sitBuilderWidget sitBuilderBtnWidget '.$widget_classes.' '.$instance[$prefix.'btn_align'].'" '.($widgetCss ? 'style="'.$widgetCss.'"' : '').' >';
		$after_widget 			= '</div>';

		$btnStyles 	= '';
		$btnClasses = array();


		// BUILD MARGIN
		$btnStyles 	.= easy_margpad_array($instance, $prefix, 'btn', 'padding');
		$btnStyles 	.= easy_margpad_array($instance, $prefix, 'btn', 'margin');

		
		if( !isset($instance[$prefix.'btn_transbg']) ) {$instance[$prefix.'btn_transbg'] = '';}

		// SETUP BG COLOR & BORDER
		if(!$instance[$prefix.'btn_color']) {

			// if border set
			$border = '';
			$borderHover = '';
			if( empty($instance[$prefix.'btn_borderwide']) ) {$bw = 1;} else {$bw = $instance[$prefix.'btn_borderwide'];}
			if( !empty($instance[$prefix.'btn_border_color']) ) { $border = 'border: '.$bw.'px solid '.$instance[$prefix.'btn_border_color'].';'; }
			if( !empty($instance[$prefix.'btn_border_hovercolor']) ) { $borderHover = 'border: '.$bw.'px solid '.$instance[$prefix.'btn_border_hovercolor'].';'; }
				
			// apply css
			$btnStyles .= 'background: '.($instance[$prefix.'btn_transbg'] == 'on' ? 'transparent' : $instance[$prefix.'btn_custom_bg']).'; color: '.$instance[$prefix.'btn_custom_color'].'; '.($border ? $border : '').' ';
			$hoverStyles = '<style>#btn-'.$unqiueID.':hover {background: '.($instance[$prefix.'btn_transbg'] == 'on' ? 'transparent' : $instance[$prefix.'btn_custom_hoverbg']).' !important; color: '.$instance[$prefix.'btn_custom_hovercolor'].' !important; '.($borderHover ? $borderHover : '').' }</style>';
		}
		else { $btnClasses[] = $instance[$prefix.'btn_color']; $hoverStyles = '';}



		if( !empty($instance[$prefix.'btn_fsize']) ) {$btnStyles .= 'font-size: '.$instance[$prefix.'btn_fsize'].'px;';} 	// BUILD FONT SIZE
		if( !empty($instance[$prefix.'btn_classes']) ) {$btnClasses[] = $instance[$prefix.'btn_classes'];}					// ATTACH CLASSES TO BUTTON
		if( !empty($instance[$prefix.'btn_align']) ) {$btnClasses[] = $instance[$prefix.'btn_align'];}						// ATTACH TEXT SIDE
		if( !empty($instance[$prefix.'btn_borderadius']) ) {$btnClasses[] = $instance[$prefix.'btn_borderadius'];}			// ATTACH CLASSES TO BUTTON
		if( !empty($instance[$prefix.'btn_fontweight']) ) {$btnClasses[] = $instance[$prefix.'btn_fontweight'];}			// ATTACH CLASSES TO BUTTON
		if( !empty($instance[$prefix.'btn_textalign']) ) {$btnClasses[] = $instance[$prefix.'btn_textalign'];}				// ATTACH CLASSES TO BUTTON


		// ICON SIDE
		if( !empty($instance[$prefix.'btn_icon']) || !empty($instance[$prefix.'image_icon']) ) {
			$iconCss 	 	= '';
			$faIconsArr 	= $this->return_fixed_farray();
			$iconCss 		.= $this->build_margpad_array($instance[$prefix.'iconimg_pt'], $instance[$prefix.'iconimg_pr'], $instance[$prefix.'iconimg_pb'], $instance[$prefix.'iconimg_pl'], 'padding');
			$iconCss 		.= $this->build_margpad_array($instance[$prefix.'iconimg_mt'], $instance[$prefix.'iconimg_mr'], $instance[$prefix.'iconimg_mb'], $instance[$prefix.'iconimg_ml'], 'margin');
			$iconClasses	= $instance[$prefix.'btn_iconside'];

			if(!empty($instance[$prefix.'image_icon'])) {$icon = '<img src="'.$instance[$prefix.'image_icon'].'" alt="'.$instance[$prefix.'image_icon_alt'].'" class="iconImg '.$iconClasses.'" style="'.$iconCss.'" data-org="'.$instance[$prefix.'image_icon'].'" data-hover="'.$instance[$prefix.'image_iconhover'].'">';}
			else {$icon	= '<i class="fa '.$faIconsArr[ $instance[$prefix.'btn_icon'] ].' '.$iconClasses.'" style="'.$iconCss.'"></i>';}
		} else {$icon	= '';}


		// BUILD ANIMATION
		if($instance[$prefix.'btn_animation']) {
			$btnClasses[] = 'wow '.$instance[$prefix.'btn_animation'];
			$animationData 	= 'data-wow-duration="'.$instance[$prefix.'btn_animation_duration'].'s" data-wow-delay="'.$instance[$prefix.'btn_animation_delay'].'s" ';
		} else {$animationData = '';}

		

		$result = '<a href="'.$instance[$prefix.'btn_link'].'" id="'.$btnID.'" class="btn '.($instance[$prefix.'image_icon'] ? 'imgIconBtn' : '').' '.$instance[$prefix.'btn_size'].' '.(!empty($btnClasses) ? implode(' ', $btnClasses) : '').'" style="'.$btnStyles.'" target="'.$instance[$prefix.'btn_target'].'" '.$animationData.' '.(strpos(implode(' ', $btnClasses), 'videoButton') !== false ? 'data-lity' : '').'  >'.$icon.' '.$instance[$prefix.'btn_label'].'</a>';
	

		echo $before_widget.$result.$hoverStyles.$after_widget;

	}
 
}
// add_action( 'widgets_init', create_function('', 'return register_widget("sgBtnWidget");') );
add_action( 'widgets_init', function () {
    return register_widget('sgBtnWidget');
}, 1 );
?>