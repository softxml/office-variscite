<?php
class sgTextWidget extends WP_Widget {


	
	// DEFINE THE WIDGET
	function __construct() {
		$widget_ops = array(
			'classname' 	=> 'sg_title_widg', 
			'description'	=> __('Easy to use super smart Text Widget By SITEIT', 'siteitsob')
		);

		parent::__construct('sgTextWidget', __('Text Widget (SiteIT)', 'siteitsob'), $widget_ops);
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
		elseif($type == 'imgstyle') {
			$type = array('parallax'=>__('Parallax', 'siteitsob'), 'fixed'=>__('Fixed', 'siteitsob'));
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
		elseif($type == 'topmarg') {
			$type = array( 'mt0' => __('0 (no margin)', 'siteitsob'), 'mt1' => __('1% from top', 'siteitsob'),'mt2' => __('2% from top', 'siteitsob'),'mt3' => __('3% from top', 'siteitsob'),'mt4' => __('4% from top', 'siteitsob'),'mt5' => __('5% from top', 'siteitsob'),'mt10' => __('10% from top', 'siteitsob'),'mt15' => __('15% from top', 'siteitsob'),'mt20' => __('20% from top', 'siteitsob'),'mt30' => __('30% from top', 'siteitsob'),'mt20x' => __('20px from top', 'siteitsob'),'mt30x' => __('30px from top', 'siteitsob'),'mt40x' => __('40px from top', 'siteitsob'),'mt50x' => __('50px from top', 'siteitsob'),'mt60x' => __('60px from top', 'siteitsob'),'mt70x' => __('70px from top', 'siteitsob'),'mt80x' => __('80px from top', 'siteitsob'),'mt90x' => __('90px from top', 'siteitsob'),'mt100x' => __('100px from top', 'siteitsob'),'mt120x' => __('120px from top', 'siteitsob'),'mt150x' => __('150px from top', 'siteitsob'),'mt170x' => __('170px from top', 'siteitsob'),'mt200x' => __('200px from top', 'siteitsob'), );
		}
		elseif($type == 'botmarg') {
			$type = array( 'mb0' => __('0 (no margin)', 'siteitsob'), 'mb1' => __('1% from bottom', 'siteitsob'),'mb2' => __('2% from bottom', 'siteitsob'),'mb3' => __('3% from bottom', 'siteitsob'),'mb4' => __('4% from bottom', 'siteitsob'),'mb5' => __('5% from bottom', 'siteitsob'),'mb10' => __('10% from bottom', 'siteitsob'),'mb15' => __('15% from bottom', 'siteitsob'),'mb20' => __('20% from bottom', 'siteitsob'),'mb30' => __('30% from bottom', 'siteitsob'),'mb20x' => __('20px from bottom', 'siteitsob'),'mb30x' => __('30px from bottom', 'siteitsob'),'mb40x' => __('40px from bottom', 'siteitsob'),'mb50x' => __('50px from bottom', 'siteitsob'),'mb60x' => __('60px from bottom', 'siteitsob'),'mb70x' => __('70px from bottom', 'siteitsob'),'mb80x' => __('80px from bottom', 'siteitsob'),'mb90x' => __('90px from bottom', 'siteitsob'),'mb100x' => __('100px from bottom', 'siteitsob'),'mb120x' => __('120px from bottom', 'siteitsob'),'mb150x' => __('150px from bottom', 'siteitsob'),'mb170x' => __('170px from bottom', 'siteitsob'),'mb200x' => __('200px from bottom', 'siteitsob'), );
		}
		elseif($type == 'fontweight') {
			$type = array( 'light' => __('Light', 'siteitsob'), 'normal' => __('Normal', 'siteitsob'), 'bold' => __('Bold', 'siteitsob'));
		}
		elseif($type == 'borderColors') {
			$type = array('fullbDark'=>__('Black', 'siteitsob'), 'fullbCC'=>__('Grey', 'siteitsob'), 'fullbED'=>__('Light Grey', 'siteitsob'), 'fullbRed'=>__('Red', 'siteitsob'), 'fullbBlue'=>__('Blue', 'siteitsob'), 'fullbGreen'=>__('Green', 'siteitsob'));
		}
		elseif($type == 'imagePositions') {
			$type = array('above'=>__('Above Title', 'siteitsob'), 'under'=>__('Under Title', 'siteitsob'));
		}
		elseif($type == 'linkTargets') {
			$type = array('_self'=>__('Same Window', 'siteitsob'), '_blank'=>__('New Window', 'siteitsob'));
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


	function siteit_widget_fields() {
		return array(
			'widget_title', 
			'title_type', 
			'title_size', 
			'title_width', 
			'title_align', 
			'title_color', 
			'title_fweight', 
			'title_link', 
			'title_link_target', 
			'title_animation',
			'title_animation_duration',
			'title_animation_delay',
			'title_mt',
			'title_mr',
			'title_mb',
			'title_ml',
			'title_pt',
			'title_pr',
			'title_pb',
			'title_pl',  
			'title_classes', 
			'widget_text',
			'text_size',
			'text_lheight',
			'text_fweight',
			'text_color',
			'text_classes',
			'text_width',
			'text_animation',
			'text_animation_duration',
			'text_animation_delay',
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
        $prefix 		= '';

		$formFields = '
		<div class="admin-row">
			<div class="col-md-4">

				<h4 class="row-title">'.__('Title Settings', 'siteitsob').' <span class="sitb-icon icon-title icon-big"></span></h4>
				<div class="row-wrap">
					<div class="admin-row">
						<div class="col-md-12">
							<label for="'.$this->get_field_id($prefix.'widget_title').'"><span class="label-wrap">'.__('Title', 'siteitsob').':</span>
								<input class="widefat" id="'.$this->get_field_id($prefix.'widget_title').'" name="'.$this->get_field_name($prefix.'widget_title').'" type="text" value="'.$instance[$prefix.'widget_title'].'" />
							</label>
						</div>
						<div class="col-md-4 col-xs-6">
							<label for="'.$this->get_field_id($prefix.'title_type').'"><span class="label-wrap">'.__('Title Type', 'siteitsob').':</span>
							<select class="widefat" id="'.$this->get_field_id($prefix.'title_type').'" name="'.$this->get_field_name($prefix.'title_type').'">
								'.$this->multi_select($instance[$prefix.'title_type'], '', 'ttype').'
							</select>	
							</label>	
						</div>
						<div class="col-md-4 col-xs-6">
							<label for="'.$this->get_field_id($prefix.'title_size').'"><span class="label-wrap">'.__('Font Size (PX)', 'siteitsob').':</span>
								<input class="widefat" id="'.$this->get_field_id($prefix.'title_size').'" name="'.$this->get_field_name($prefix.'title_size').'" type="number" value="'.esc_attr($instance[$prefix.'title_size']).'" />
							</label>	
						</div>
						<div class="col-md-4 col-xs-6">
							<label for="'.$this->get_field_id($prefix.'title_align').'"><span class="label-wrap">'.__('Title Align', 'siteitsob').':</span>
								<select class="widefat" id="'.$this->get_field_id($prefix.'title_align').'" name="'.$this->get_field_name($prefix.'title_align').'">
									'.$this->multi_select($instance[$prefix.'title_align'], __('Default', 'siteitsob'), 'talign').'
								</select>	
							</label>	
						</div>
						<div class="col-md-4 col-xs-6">
							<label for="'.$this->get_field_id($prefix.'title_width').'"><span class="label-wrap">'.__('Width (PX or %)', 'siteitsob').':</span>
								<input class="widefat" id="'.$this->get_field_id($prefix.'title_width').'" name="'.$this->get_field_name($prefix.'title_width').'" type="text" value="'.esc_attr($instance[$prefix.'title_width']).'" />
							</label>	
						</div>
						<div class="col-md-4 col-xs-6">
							<label for="'.$this->get_field_id($prefix.'title_fweight').'"><span class="label-wrap">'.__('Font Weight', 'siteitsob').':</span>
								<select class="widefat" id="'.$this->get_field_id($prefix.'title_fweight').'" name="'.$this->get_field_name($prefix.'title_fweight').'">
									'.$this->multi_select($instance[$prefix.'title_fweight'], __('Default', 'siteitsob'), 'fontweight').'
								</select>
							</label>
						</div>
						<div class="col-md-4 col-xs-6">
							<label for="'.$this->get_field_id($prefix.'title_color').'"><span class="label-wrap">'.__('Title Color', 'siteitsob').':</span></label>
							<input class="widefat colorPicker" id="'.$this->get_field_id($prefix.'title_color').'" name="'.$this->get_field_name($prefix.'title_color').'" type="text" value="'.esc_attr($instance[$prefix.'title_color']).'" />							
						</div>
						<div class="col-md-4 col-xs-6">
							<label for="'.$this->get_field_id($prefix.'title_link').'"><span class="label-wrap">'.__('Title Link (optional)', 'siteitsob').':</span>
								<input class="widefat" id="'.$this->get_field_id($prefix.'title_link').'" name="'.$this->get_field_name($prefix.'title_link').'" type="text" value="'.esc_attr($instance[$prefix.'title_link']).'" />
							</label>
						</div>
						<div class="col-md-4 col-xs-6">
							<label for="'.$this->get_field_id($prefix.'title_link_target').'"><span class="label-wrap">'.__('Link Target', 'siteitsob').'</span>:
								<select class="widefat" id="'.$this->get_field_id($prefix.'title_link_target').'" name="'.$this->get_field_name($prefix.'title_link_target').'">
									'.$this->multi_select($instance[$prefix.'title_link_target'], '', 'linkTargets').'
								</select>
							</label>
						</div>
						<div class="col-md-4 col-xs-6">
							<label for="'.$this->get_field_id($prefix.'title_classes').'">'.__('Title Classes', 'siteitsob').':
							<input class="widefat" id="'.$this->get_field_id($prefix.'title_classes').'" name="'.$this->get_field_name($prefix.'title_classes').'" type="text" value="'.esc_attr($instance[$prefix.'title_classes']).'" />
							</label>
						</div>
						<div class="col-md-4 hidden-xs"><!-- SPACER --></div>
						<div class="col-md-4 col-xs-6">
							<label for="'.$this->get_field_id($prefix.'title_animation').'"><span class="label-wrap">'.__('Animation Type', 'siteitsob').'</span>:
								<select class="widefat" id="'.$this->get_field_id($prefix.'title_animation').'" name="'.$this->get_field_name($prefix.'title_animation').'">
									'.$this->multi_select($instance[$prefix.'title_animation'], '', 'animations').'
								</select>
							</label>
						</div>
						<div class="col-md-4 col-xs-6">
							<label for="'.$this->get_field_id($prefix.'title_animation_duration').'"><span class="label-wrap">'.__('Animation Duration', 'siteitsob').':</span>
								<div class="range-counter counter-'.$this->get_field_id($prefix.'title_animation_duration').'" style="margin-top: 0;">'.($instance[$prefix.'title_animation_duration'] ? esc_attr($instance[$prefix.'title_animation_duration']) : 1).'</div>
								<input class="widefat range-input" data-counter="counter-'.$this->get_field_id($prefix.'title_animation_duration').'" id="'.$this->get_field_id($prefix.'title_animation_duration').'" name="'.$this->get_field_name($prefix.'title_animation_duration').'" type="range" value="'.($instance[$prefix.'title_animation_duration'] ? esc_attr($instance[$prefix.'title_animation_duration']) : 1).'" min="0.5" max="10" step="0.5" />
							</label>
						</div>
						<div class="col-md-4 col-xs-6">
							<label for="'.$this->get_field_id($prefix.'title_animation_delay').'"><span class="label-wrap">'.__('Animation Delay', 'siteitsob').':</span>
								<div class="range-counter counter-'.$this->get_field_id($prefix.'title_animation_delay').'" style="margin-top: 0;">'.($instance[$prefix.'title_animation_delay'] ? esc_attr($instance[$prefix.'title_animation_delay']) : 0.25).'</div>
								<input class="widefat range-input" data-counter="counter-'.$this->get_field_id($prefix.'title_animation_delay').'" id="'.$this->get_field_id($prefix.'title_animation_delay').'" name="'.$this->get_field_name($prefix.'title_animation_delay').'" type="range" value="'.($instance[$prefix.'title_animation_delay'] ? esc_attr($instance[$prefix.'title_animation_delay']) : 0.5).'" min="0" max="10" step="0.25" />
							</label>
						</div>
						<div class="col-md-6 col-xs-12 mb0">
							<label for="'.$this->get_field_id($prefix.'title_pt').'"><span class="label-wrap">'.__('Padding', 'siteitsob').':</span></label>
							<div class="admin-row mini-padding mb0">
								<div class="col-md-3 mb0"><input class="widefat" id="'.$this->get_field_id($prefix.'title_pt').'" name="'.$this->get_field_name($prefix.'title_pt').'" type="number" value="'.esc_attr($instance[$prefix.'title_pt']).'" placeholder="0" /></div>
								<div class="col-md-3 mb0"><input class="widefat" id="'.$this->get_field_id($prefix.'title_pr').'" name="'.$this->get_field_name($prefix.'title_pr').'" type="number" value="'.esc_attr($instance[$prefix.'title_pr']).'" placeholder="0" /></div>
								<div class="col-md-3 mb0"><input class="widefat" id="'.$this->get_field_id($prefix.'title_pb').'" name="'.$this->get_field_name($prefix.'title_pb').'" type="number" value="'.esc_attr($instance[$prefix.'title_pb']).'" placeholder="0" /></div>
								<div class="col-md-3 mb0"><input class="widefat" id="'.$this->get_field_id($prefix.'title_pl').'" name="'.$this->get_field_name($prefix.'title_pl').'" type="number" value="'.esc_attr($instance[$prefix.'title_pl']).'" placeholder="0" /></div>
							</div>
						</div>
						<div class="col-md-6 col-xs-12 mb0">
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

			</div>
			
			<div class="col-md-8">
				<h4 class="row-title">'.__('Widget Text (optional)', 'siteitsob').'  <span class="sitb-icon icon-text icon-big"></span></h4>
				<div class="row-wrap">
					<div class="admin-row">
						<div class="col-md-7 col-xs-12 mb0">
							<label for="'.$this->get_field_id($prefix.'widget_text').'"><span class="label-wrap">'.__('Widget Text', 'siteitsob').':</span></label>
							<textarea class="widefat main_text" id="'.$this->get_field_id($prefix.'widget_text').'" name="'.$this->get_field_name($prefix.'widget_text').'" rows="5">'.esc_attr($instance[$prefix.'widget_text']).'</textarea>

							<div class="col-md-12 col-xs-12 fullbED p2 mt10x">
								<div class="admin-row">
									<div class="col-md-4 col-xs-6">
										<label for="'.$this->get_field_id($prefix.'text_animation').'"><span class="label-wrap">'.__('Animation Type', 'siteitsob').'</span>:
											<select class="widefat" id="'.$this->get_field_id($prefix.'text_animation').'" name="'.$this->get_field_name($prefix.'text_animation').'">
												'.$this->multi_select($instance[$prefix.'text_animation'], '', 'animations').'
											</select>
										</label>
									</div>
									<div class="col-md-4 col-xs-6">
										<label for="'.$this->get_field_id($prefix.'text_animation_duration').'"><span class="label-wrap">'.__('Animation Duration', 'siteitsob').':</span>
											<div class="range-counter counter-'.$this->get_field_id($prefix.'text_animation_duration').'">'.($instance[$prefix.'text_animation_duration'] ? esc_attr($instance[$prefix.'text_animation_duration']) : 1).'</div>
											<input class="widefat range-input" data-counter="counter-'.$this->get_field_id($prefix.'text_animation_duration').'" id="'.$this->get_field_id($prefix.'text_animation_duration').'" name="'.$this->get_field_name($prefix.'text_animation_duration').'" type="range" value="'.($instance[$prefix.'text_animation_duration'] ? esc_attr($instance[$prefix.'text_animation_duration']) : 1).'" min="0.5" max="10" step="0.5" />
										</label>
									</div>
									<div class="col-md-4 col-xs-6">
										<label for="'.$this->get_field_id($prefix.'text_animation_delay').'"><span class="label-wrap">'.__('Animation Delay', 'siteitsob').':</span>
											<div class="range-counter counter-'.$this->get_field_id($prefix.'text_animation_delay').'">'.($instance[$prefix.'text_animation_delay'] ? esc_attr($instance[$prefix.'text_animation_delay']) : 0.25).'</div>
											<input class="widefat range-input" data-counter="counter-'.$this->get_field_id($prefix.'text_animation_delay').'" id="'.$this->get_field_id($prefix.'text_animation_delay').'" name="'.$this->get_field_name($prefix.'text_animation_delay').'" type="range" value="'.($instance[$prefix.'text_animation_delay'] ? esc_attr($instance[$prefix.'text_animation_delay']) : 0.5).'" min="0" max="10" step="0.25" />
										</label>
									</div>
								</div>
							</div>
						</div>
						<div class="col-md-5 col-xs-12 mb0">
							<div class="admin-row">
								<div class="col-md-4 col-xs-6">
									<label for="'.$this->get_field_id($prefix.'text_fweight').'"><span class="label-wrap">'.__('Font Weight', 'siteitsob').':</span>
										<select class="widefat" id="'.$this->get_field_id($prefix.'text_fweight').'" name="'.$this->get_field_name($prefix.'text_fweight').'">
											'.$this->multi_select($instance[$prefix.'text_fweight'], __('Default', 'siteitsob'), 'fontweight').'
										</select>
									</label>
								</div>
								<div class="col-md-4 col-xs-6">
									<label for="'.$this->get_field_id($prefix.'text_size').'"><span class="label-wrap">'.__('Font Size (px)', 'siteitsob').':</span>
									<input class="widefat" id="'.$this->get_field_id($prefix.'text_size').'" name="'.$this->get_field_name($prefix.'text_size').'" type="number" value="'.esc_attr($instance[$prefix.'text_size']).'" placeholder="'.__('For example: 60', 'siteitsob').'" />
									</label>
								</div>
								<div class="col-md-4 col-xs-6">
									<label for="'.$this->get_field_id($prefix.'text_lheight').'"><span class="label-wrap">'.__('Line Height (px)', 'siteitsob').':</span>
									<input class="widefat" id="'.$this->get_field_id($prefix.'text_lheight').'" name="'.$this->get_field_name($prefix.'text_lheight').'" type="number" value="'.esc_attr($instance[$prefix.'text_lheight']).'" placeholder="'.__('For example: 60', 'siteitsob').'" />
									</label>
								</div>
								<div class="col-md-4 col-xs-6">
									<label for="'.$this->get_field_id($prefix.'text_width').'"><span class="label-wrap">'.__('Width (px or %)', 'siteitsob').':</span>
										<input class="widefat" id="'.$this->get_field_id($prefix.'text_width').'" name="'.$this->get_field_name($prefix.'text_width').'" type="text" value="'.esc_attr($instance[$prefix.'text_width']).'" placeholder="'.__('For example: 250px or 80%', 'siteitsob').'" />
									</label>								
								</div>
								<div class="col-md-4 col-xs-6">
									<label for="'.$this->get_field_id($prefix.'text_color').'"><span class="label-wrap">'.__('Color', 'siteitsob').':</span></label>
									<input class="widefat colorPicker" id="'.$this->get_field_id($prefix.'text_color').'" name="'.$this->get_field_name($prefix.'text_color').'" type="text" value="'.esc_attr($instance[$prefix.'text_color']).'" />									
								</div>
								<div class="col-md-4 col-xs-6">
									<label for="'.$this->get_field_id($prefix.'text_classes').'"><span class="label-wrap">'.__('Text Classes', 'siteitsob').':</span>
									<input class="widefat" id="'.$this->get_field_id($prefix.'text_classes').'" name="'.$this->get_field_name($prefix.'text_classes').'" type="text" value="'.esc_attr($instance[$prefix.'text_classes']).'" placeholder="'.__('For example: mt0 mb0', 'siteitsob').'" />
									</label>
								</div>
								<div class="col-md-12 col-xs-12 mb0">
									<label for="'.$this->get_field_id($prefix.'text_mt').'"><span class="label-wrap">'.__('Margin', 'siteitsob').':</span></label>
									<div class="admin-row mini-padding mb0">
										<div class="col-md-3"><input class="widefat" id="'.$this->get_field_id($prefix.'text_mt').'" name="'.$this->get_field_name($prefix.'text_mt').'" type="number" value="'.esc_attr($instance[$prefix.'text_mt']).'" placeholder="0" /></div>
										<div class="col-md-3"><input class="widefat" id="'.$this->get_field_id($prefix.'text_mr').'" name="'.$this->get_field_name($prefix.'text_mr').'" type="number" value="'.esc_attr($instance[$prefix.'text_mr']).'" placeholder="0" /></div>
										<div class="col-md-3"><input class="widefat" id="'.$this->get_field_id($prefix.'text_mb').'" name="'.$this->get_field_name($prefix.'text_mb').'" type="number" value="'.esc_attr($instance[$prefix.'text_mb']).'" placeholder="0" /></div>
										<div class="col-md-3"><input class="widefat" id="'.$this->get_field_id($prefix.'text_ml').'" name="'.$this->get_field_name($prefix.'text_ml').'" type="number" value="'.esc_attr($instance[$prefix.'text_ml']).'" placeholder="0" /></div>
									</div>
								</div>
								<div class="col-md-12 col-xs-12 mb0">
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
				</div>
			</div>
			<div class="col-md-12 col-xs-12">
				<h4 class="row-title">'.__('Widget Advanced Settings', 'siteitsob').'  <span class="sitb-icon icon-cog icon-big"></span></h4>
				<div class="row-wrap">
					<div class="admin-row">
						<div class="col-md-6">
							<div class="admin-row">
								<div class="col-md-6 col-xs-6 mb0">
									<label for="'.$this->get_field_id($prefix.'widget_bgcolor').'">'.__('Widget Background Color', 'siteitsob').':</label><br>
									<input class="widefat wpColorPicker" id="'.$this->get_field_id($prefix.'widget_bgcolor').'" name="'.$this->get_field_name($prefix.'widget_bgcolor').'" type="text" value="'.esc_attr($instance[$prefix.'widget_bgcolor']).'" />
								</div>
								<div class="col-md-6 col-xs-6 mb0">
									<label for="'.$this->get_field_id($prefix.'widget_classes').'">'.__('Widget Classes', 'siteitsob').':
									<input class="widefat" id="'.$this->get_field_id($prefix.'widget_classes').'" name="'.$this->get_field_name($prefix.'widget_classes').'" type="text" value="'.esc_attr($instance[$prefix.'widget_classes']).'" placeholder="'.__('For example: mt0 mb0', 'siteitsob').'" />
									</label>
								</div>
							</div>
						</div>
						<div class="col-md-6">
							<div class="admin-row">
							
							</div>
						</div>


						<div class="col-md-3 col-xs-12 mb0">
							<label for="'.$this->get_field_id($prefix.'widget_pt').'"><span class="label-wrap">'.__('Widget Padding', 'siteitsob').':</span></label>
							<div class="admin-row small-padding mb0">
								<div class="col-md-3"><input class="widefat" id="'.$this->get_field_id($prefix.'widget_pt').'" name="'.$this->get_field_name($prefix.'widget_pt').'" type="number" value="'.esc_attr($instance[$prefix.'widget_pt']).'" placeholder="0" /></div>
								<div class="col-md-3"><input class="widefat" id="'.$this->get_field_id($prefix.'widget_pr').'" name="'.$this->get_field_name($prefix.'widget_pr').'" type="number" value="'.esc_attr($instance[$prefix.'widget_pr']).'" placeholder="0" /></div>
								<div class="col-md-3"><input class="widefat" id="'.$this->get_field_id($prefix.'widget_pb').'" name="'.$this->get_field_name($prefix.'widget_pb').'" type="number" value="'.esc_attr($instance[$prefix.'widget_pb']).'" placeholder="0" /></div>
								<div class="col-md-3"><input class="widefat" id="'.$this->get_field_id($prefix.'widget_pl').'" name="'.$this->get_field_name($prefix.'widget_pl').'" type="number" value="'.esc_attr($instance[$prefix.'widget_pl']).'" placeholder="0" /></div>
							</div>
						</div>
						<div class="col-md-3 col-xs-12 mb0">
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
		<div>'.$this->form_fileds_looper($instance).'</div>			

		<script src="'.plugin_dir_url( __FILE__ ) . '../lib/backend/inside-widget.js"></script>
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


		// FIX TITLE 
		if(!empty($instance[$prefix.'widget_title'])) {

			$titleCls 		= '';
			$titleCss 		= '';
			$titleCss 		.= $this->build_margpad_array($instance[$prefix.'title_pt'], $instance[$prefix.'title_pr'], $instance[$prefix.'title_pb'], $instance[$prefix.'title_pl'], 'padding');
			$titleCss 		.= $this->build_margpad_array($instance[$prefix.'title_mt'], $instance[$prefix.'title_mr'], $instance[$prefix.'title_mb'], $instance[$prefix.'title_ml'], 'margin');

			if(!$instance[$prefix.'title_type']) {$instance[$prefix.'title_type'] = 'h3';}
			if(!$instance[$prefix.'title_size']) {$instance[$prefix.'title_size'] = '30'; }
			if($instance[$prefix.'title_color']) {$titleCss .= 'color:'.$instance[$prefix.'title_color'].';';}
			if($instance[$prefix.'title_width']) {$titleCss .= 'width:'.$instance[$prefix.'title_width'].';'; }

			// BUILD TITLE ANIMATION
			if($instance[$prefix.'title_animation']) {
				$titleCls			.= ' wow '.$instance[$prefix.'title_animation'].' ';
				$titleAnimation 	= 'data-wow-duration="'.$instance[$prefix.'title_animation_duration'].'s" data-wow-delay="'.$instance[$prefix.'title_animation_delay'].'s" ';
			} else {$titleAnimation = '';}


			// IF TITLE IS LINK
			if($instance[$prefix.'title_link']) {
				$preTitle = '<a href="'.$instance[$prefix.'title_link'].'" target="'.$instance[$prefix.'title_link_target'].'">';
				$aftTitle = '</a>';
			} else {$preTitle = ''; $aftTitle = '';}



			$widget_title = '<div class="title-box '.$instance[$prefix.'title_align'].' "><'.$instance[$prefix.'title_type'].' class="widgettitle '.$instance[$prefix.'title_fweight'].' '.$instance[$prefix.'title_classes'].' '.$titleCls.' " style="'.$titleCss.'; font-size: '.$instance[$prefix.'title_size'].'px; display: inline-block;" '.$titleAnimation.' >'.$preTitle.$instance[$prefix.'widget_title'].$aftTitle.'</'.$instance[$prefix.'title_type'].'></div>';
		}
		else {$widget_title = '';}


		
		// FIX TEXT CSS
		if(isset($instance[$prefix.'widget_text']) && $instance[$prefix.'widget_text'] != '') {
			$textCls 		= '';
			$textCss 		= '';
			$textCss 		.= $this->build_margpad_array($instance[$prefix.'text_pt'], $instance[$prefix.'text_pr'], $instance[$prefix.'text_pb'], $instance[$prefix.'text_pl'], 'padding');
			$textCss 		.= $this->build_margpad_array($instance[$prefix.'text_mt'], $instance[$prefix.'text_mr'], $instance[$prefix.'text_mb'], $instance[$prefix.'text_ml'], 'margin');

			if($instance[$prefix.'text_width']) {$textCss .= 'width: '.$instance[$prefix.'text_width'].'; display: inline-block;';}
			if($instance[$prefix.'text_size']) {$textCss .= 'font-size: '.$instance[$prefix.'text_size'].'px;';}
			if( !empty($instance[$prefix.'text_color']) ) {$textCss .= 'color: '.$instance[$prefix.'text_color'].';';} 
			if($instance[$prefix.'text_lheight']) {$textCss .= 'line-height: '.$instance[$prefix.'text_lheight'].'px;';} 

			// BUILD TITLE ANIMATION
			if($instance[$prefix.'text_animation']) {
				$textCls			.= ' wow '.$instance[$prefix.'text_animation'].' ';
				$textAnimation 		= 'data-wow-duration="'.$instance[$prefix.'text_animation_duration'].'s" data-wow-delay="'.$instance[$prefix.'text_animation_delay'].'s" ';
			} else {$textAnimation = '';}


			$text = '<div class="text-box '.$instance[$prefix.'text_size'].' '.$instance[$prefix.'text_fweight'].' '.$instance[$prefix.'text_classes'].' '.$textCls.' " style="'.$textCss .'" '.$textAnimation.' >'.$instance[$prefix.'widget_text'].'</div>';
		} else {$text = '';}





		// FIX MARGINS & PADDINGS
		$widgetCss 		= '';
		$widgetCss 		.= $this->build_margpad_array($instance[$prefix.'widget_pt'], $instance[$prefix.'widget_pr'], $instance[$prefix.'widget_pb'], $instance[$prefix.'widget_pl'], 'padding');
		$widgetCss 		.= $this->build_margpad_array($instance[$prefix.'widget_mt'], $instance[$prefix.'widget_mr'], $instance[$prefix.'widget_mb'], $instance[$prefix.'widget_ml'], 'margin');

		if( !empty($instance[$prefix.'widget_bgcolor']) ) {$widgetCss .= 'background-color:'.$instance[$prefix.'widget_bgcolor'].';';}


		$result = '
		<div class="singleWidget sitBuilderWidget sitBuilderTextWidget '.$instance[$prefix.'widget_classes'].'" style="'.$widgetCss.'">
			'.$widget_title.'
			'.$text.'
		</div>
		';
		
	
		echo $before_widget.$result.$after_widget;

	}
 
}
// add_action( 'widgets_init', create_function('', 'return register_widget("sgTextWidget");') );
add_action( 'widgets_init', function () {
    return register_widget('sgTextWidget');
}, 1 );
?>