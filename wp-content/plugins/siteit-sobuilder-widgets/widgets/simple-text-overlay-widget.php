<?php
class sgSimpleOverlayWidget extends WP_Widget {


	
	// DEFINE THE WIDGET
	function __construct() {
		$widget_ops = array(
			'classname' 	=> 'sg_textoverlay_widg', 
			'description'	=> __('text with background color overlay SITEIT', 'siteitsob')
		);

		parent::__construct('sgSimpleOverlayWidget', __('Text Bg Overlay Widget (SiteIT)', 'siteitsob'), $widget_ops);
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
			$type = array('_blank'=>__('New Window', 'siteitsob'), '_self'=>__('Same Window', 'siteitsob'));
		}
		elseif($type == 'btnSizes') {
			$type = array('' => __('Standard', 'siteitsob'), 'btn-large'=>__('Large Button'), 'btn-huge'=>__('huge Button'));
		}
		elseif($type == 'btnColors') {
			$type = array( '' => __('No PreDefined Color'), 'btn-blue' => __('Blue Button', 'siteitsob'), 'btn-green'=>__('Green Button', 'siteitsob'), 'btn-turkoise'=>__('Turkoise Button', 'siteitsob'), 'btn-orange'=>__('Orange Button', 'siteitsob'), 'btn-red'=>__('Red Button', 'siteitsob'), 'btn-dark'=>__('Dark Button', 'siteitsob'), 'btn-black'=>__('Black Button', 'siteitsob'), );
		}
		elseif($type == 'btnAlign') {
			$type = array('' => __('Default', 'siteitsob'), 'text-right'=>__('Right', 'siteitsob'), 'text-center'=>__('Center', 'siteitsob'), 'text-left' => __('Left', 'siteitsob'));
		}
		elseif($type == 'borderadius') {
			$type = array('brad0'=>__('0px', 'siteitsob'), 'brad3'=>__('3px', 'siteitsob'), 'brad5'=>__('5px', 'siteitsob'), 'brad7' => __('7px', 'siteitsob'), 'brad10' => __('10px', 'siteitsob'), 'brad25' => __('25px', 'siteitsob'), 'brad50' => __('50px', 'siteitsob'), 'brad50p' => __('50%', 'siteitsob'));
		}
		elseif($type == 'iconside') {
			$type = array('pull-left'=>__('Left', 'siteitsob'), 'pull-right'=>__('Right', 'siteitsob'));
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
		return array(
			'widget_title', 
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
            
			'btn_label',
			'btn_link',
			'btn_target',
			'btn_size',
			'btn_fsize',
			'btn_borderadius',
			'btn_color',
			'btn_transbg',
			'btn_custom_bg',
			'btn_custom_color',
			'btn_custom_hoverbg',
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
            
			'image_overlay',
			'overlay_color',
            'overlay_opacity',
            
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
                            <div class="admin-row mini-padding tight-number mb0">
                                <div class="col-md-3"><input class="widefat" id="'.$this->get_field_id($prefix.'text_mt').'" name="'.$this->get_field_name($prefix.'text_mt').'" type="number" value="'.esc_attr($instance[$prefix.'text_mt']).'" placeholder="0" /></div>
                                <div class="col-md-3"><input class="widefat" id="'.$this->get_field_id($prefix.'text_mr').'" name="'.$this->get_field_name($prefix.'text_mr').'" type="number" value="'.esc_attr($instance[$prefix.'text_mr']).'" placeholder="0" /></div>
                                <div class="col-md-3"><input class="widefat" id="'.$this->get_field_id($prefix.'text_mb').'" name="'.$this->get_field_name($prefix.'text_mb').'" type="number" value="'.esc_attr($instance[$prefix.'text_mb']).'" placeholder="0" /></div>
                                <div class="col-md-3"><input class="widefat" id="'.$this->get_field_id($prefix.'text_ml').'" name="'.$this->get_field_name($prefix.'text_ml').'" type="number" value="'.esc_attr($instance[$prefix.'text_ml']).'" placeholder="0" /></div>
                            </div>
                        </div>
                        <div class="col-md-6 mb0">
                            <label for="'.$this->get_field_id($prefix.'text_pt').'"><span class="label-wrap">'.$labelfix.__('Padding', 'siteitsob').':</span></label>
                            <div class="admin-row mini-padding tight-number mb0">
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

                <h4 class="row-title">'.__('Button Settings', 'siteitsob').'  <span class="sitb-icon icon-cog icon-big"></span></h4>
                <div class="row-wrap">
                    <div class="admin-row">
                        <div class="col-md-4">
                            <label for="'.$this->get_field_id($prefix.'btn_label').'"><span class="label-wrap">'.$labelfix.__('Button Text', 'siteitsob').':</span>
                            <input class="widefat" id="'.$this->get_field_id($prefix.'btn_label').'" name="'.$this->get_field_name($prefix.'btn_label').'" type="text" value="'.esc_attr($instance[$prefix.'btn_label']).'" />
                            </label>	
                        </div>
                        <div class="col-md-4">
                            <label for="'.$this->get_field_id($prefix.'btn_textalign').'"><span class="label-wrap">'.$labelfix.__('Text Align', 'siteitsob').':</span>
                                <select class="widefat" id="'.$this->get_field_id($prefix.'btn_textalign').'" name="'.$this->get_field_name($prefix.'btn_textalign').'">
                                    '.$this->multi_select($instance[$prefix.'btn_textalign'], '', 'btnAlign').'
                                </select>	
                            </label>
                        </div>
                        <div class="col-md-4">
                            <label for="'.$this->get_field_id($prefix.'btn_align').'"><span class="label-wrap">'.$labelfix.__('Button Align', 'siteitsob').':</span>
                                <select class="widefat" id="'.$this->get_field_id($prefix.'btn_align').'" name="'.$this->get_field_name($prefix.'btn_align').'">
                                    '.$this->multi_select($instance[$prefix.'btn_align'], '', 'btnAlign').'
                                </select>	
                            </label>
                        </div>
                        <div class="col-md-4">
                            <label for="'.$this->get_field_id($prefix.'btn_fsize').'"><span class="label-wrap">'.$labelfix.__('Font Size', 'siteitsob').':</span>
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
                            <label for="'.$this->get_field_id($prefix.'btn_classes').'"><span class="label-wrap">'.$labelfix.__('Button Classes', 'siteitsob').':</span>
                                <input class="widefat" id="'.$this->get_field_id($prefix.'btn_classes').'" name="'.$this->get_field_name($prefix.'btn_classes').'" type="text" value="'.esc_attr($instance[$prefix.'btn_classes']).'" />
                            </label>							
                        </div>
                        <div class="col-md-4">
                            <label for="'.$this->get_field_id($prefix.'btn_link').'"><span class="label-wrap">'.$labelfix.__('Button Link (URL)', 'siteitsob').':</span>
                                <input class="widefat" id="'.$this->get_field_id($prefix.'btn_link').'" name="'.$this->get_field_name($prefix.'btn_link').'" type="text" value="'.esc_attr($instance[$prefix.'btn_link']).'" placeholder="http://www.example.com" />
                            </label>
                        </div>
                        <div class="col-md-4">
                            <label for="'.$this->get_field_id($prefix.'btn_target').'"><span class="label-wrap">'.$labelfix.__('Link Target', 'siteitsob').':</span>
                                <select class="widefat" id="'.$this->get_field_id($prefix.'btn_target').'" name="'.$this->get_field_name($prefix.'btn_target').'">
                                    '.$this->multi_select($instance[$prefix.'btn_target'], __('Default', 'siteitsob'), 'linkTargets').'
                                </select>	
                            </label>
                        </div>
                        <div class="col-md-6">
                            <label for="'.$this->get_field_id($prefix.'btn_pt').'"><span class="label-wrap">'.$labelfix.__('Padding', 'siteitsob').':</span></label>
                            <div class="admin-row mini-padding mb0">
                                <div class="col-md-3"><input class="widefat" id="'.$this->get_field_id($prefix.'btn_pt').'" name="'.$this->get_field_name($prefix.'btn_pt').'" type="number" value="'.esc_attr($instance[$prefix.'btn_pt']).'" placeholder="0" /></div>
                                <div class="col-md-3"><input class="widefat" id="'.$this->get_field_id($prefix.'btn_pr').'" name="'.$this->get_field_name($prefix.'btn_pr').'" type="number" value="'.esc_attr($instance[$prefix.'btn_pr']).'" placeholder="0" /></div>
                                <div class="col-md-3"><input class="widefat" id="'.$this->get_field_id($prefix.'btn_pb').'" name="'.$this->get_field_name($prefix.'btn_pb').'" type="number" value="'.esc_attr($instance[$prefix.'btn_pb']).'" placeholder="0" /></div>
                                <div class="col-md-3"><input class="widefat" id="'.$this->get_field_id($prefix.'btn_pl').'" name="'.$this->get_field_name($prefix.'btn_pl').'" type="number" value="'.esc_attr($instance[$prefix.'btn_pl']).'" placeholder="0" /></div>
                            </div>	
                        </div>
                        <div class="col-md-6">
                            <label for="'.$this->get_field_id($prefix.'btn_mt').'"><span class="label-wrap">'.$labelfix.__('Margin', 'siteitsob').':</span></label>
                            <div class="admin-row mini-padding mb0">
                                <div class="col-md-3"><input class="widefat" id="'.$this->get_field_id($prefix.'btn_mt').'" name="'.$this->get_field_name($prefix.'btn_mt').'" type="number" value="'.esc_attr($instance[$prefix.'btn_mt']).'" placeholder="0" /></div>
                                <div class="col-md-3"><input class="widefat" id="'.$this->get_field_id($prefix.'btn_mr').'" name="'.$this->get_field_name($prefix.'btn_mr').'" type="number" value="'.esc_attr($instance[$prefix.'btn_mr']).'" placeholder="0" /></div>
                                <div class="col-md-3"><input class="widefat" id="'.$this->get_field_id($prefix.'btn_mb').'" name="'.$this->get_field_name($prefix.'btn_mb').'" type="number" value="'.esc_attr($instance[$prefix.'btn_mb']).'" placeholder="0" /></div>
                                <div class="col-md-3"><input class="widefat" id="'.$this->get_field_id($prefix.'btn_ml').'" name="'.$this->get_field_name($prefix.'btn_ml').'" type="number" value="'.esc_attr($instance[$prefix.'btn_ml']).'" placeholder="0" /></div>
                            </div>	
                        </div>
                    </div>
                </div>

				<h4 class="row-title">'.__('Styling', 'siteitsob').'  <span class="sitb-icon icon-styling icon-big"></span></h4>
				<div class="row-wrap">
					<div class="admin-row">

                        <div class="float: '.(is_rtl() ? 'left' : 'right').';">
							<label for="'.$this->get_field_id($prefix.'btn_color').'"><span class="label-wrap">'.$labelfix.__('Preset Button Color', 'siteitsob').':</span>
								<select class="widefat" id="'.$this->get_field_id($prefix.'btn_color').'" name="'.$this->get_field_name($prefix.'btn_color').'">
									'.$this->multi_select($instance[$prefix.'btn_color'], '', 'btnColors').'
								</select>	
							</label>	
						</div>



						<div class="col-md-6">
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
											<label for="'.$this->get_field_id($prefix.'btn_custom_bg').'"><span class="label-wrap">'.__('Background Color', 'siteitsob').':</span>
												<input class="widefat colorpicker" id="'.$this->get_field_id($prefix.'btn_custom_bg').'" type="color" name="'.$this->get_field_name($prefix.'btn_custom_bg').'" value="'.(esc_attr($instance[$prefix.'btn_custom_bg']) ? esc_attr($instance[$prefix.'btn_custom_bg']) : '#ECF0F1').'" />
											</label>
										</div>
										<div class="form-group">
											<label for="'.$this->get_field_id($prefix.'btn_custom_color').'"><span class="label-wrap">'.__('Text Color', 'siteitsob').':</span>
												<input class="widefat colorpicker" id="'.$this->get_field_id($prefix.'btn_custom_color').'" type="color" name="'.$this->get_field_name($prefix.'btn_custom_color').'" value="'.esc_attr($instance[$prefix.'btn_custom_color']).'" />
											</label>											
										</div>
									</div>
									<div class="col-md-6">
										<h6><strong>'.__('Hover State').'</strong></h6>
										<div class="form-group">
											<label for="'.$this->get_field_id($prefix.'btn_custom_hoverbg').'"><span class="label-wrap">'.$labelfix.__('Background Color', 'siteitsob').':</span>
												<input class="widefat colorpicker" id="'.$this->get_field_id($prefix.'btn_custom_hoverbg').'" type="color" name="'.$this->get_field_name($prefix.'btn_custom_hoverbg').'" value="'.(esc_attr($instance[$prefix.'btn_custom_hoverbg']) ? esc_attr($instance[$prefix.'btn_custom_hoverbg']) : '#ECF0F1').'" />
											</label>
										</div>
										<div class="form-group">
											<label for="'.$this->get_field_id($prefix.'btn_custom_hovercolor').'"><span class="label-wrap">'.$labelfix.__('Text Color', 'siteitsob').':</span>
												<input class="widefat colorpicker" id="'.$this->get_field_id($prefix.'btn_custom_hovercolor').'" type="color" name="'.$this->get_field_name($prefix.'btn_custom_hovercolor').'" value="'.esc_attr($instance[$prefix.'btn_custom_hovercolor']).'" />
											</label>											
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>

				<h4 class="row-title">'.__('Button Icon', 'siteitsob').'  <span class="sitb-icon icon-styling icon-big"></span></h4>
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
							<label for="'.$this->get_field_id($prefix.'image_icon_alt').'"><span class="label-wrap">'.$labelfix.__('Image Alt', 'siteitsob').':</span>
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


            </div>
            
            <div class="col-md-4">
                <h4 class="row-title">'.__('Background Overlay (optional)', 'siteitsob').'  <span class="sitb-icon icon-pin icon-big"></span></h4>
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
            </div>


			<div class="col-md-12">
				<h4 class="row-title">'.__('Widget Advanced Settings', 'siteitsob').'  <span class="sitb-icon icon-cog icon-big"></span></h4>
				<div class="row-wrap">
					<div class="admin-row">

						<div class="col-md-3 mb0">
							<label for="'.$this->get_field_id($prefix.'title_classes').'">'.$labelfix.__('Title Classes', 'siteitsob').':
							<input class="widefat" id="'.$this->get_field_id($prefix.'title_classes').'" name="'.$this->get_field_name($prefix.'title_classes').'" type="text" value="'.esc_attr($instance[$prefix.'title_classes']).'" />
							</label>
						</div>
						<div class="col-md-3 mb0">
							<label for="'.$this->get_field_id($prefix.'widget_classes').'">'.__('Widget Classes', 'siteitsob').':
							<input class="widefat" id="'.$this->get_field_id($prefix.'widget_classes').'" name="'.$this->get_field_name($prefix.'widget_classes').'" type="text" value="'.esc_attr($instance[$prefix.'widget_classes']).'" placeholder="'.__('For example: mt0 mb0', 'siteitsob').'" />
							</label>
						</div>
						<div class="col-md-3 mb0">
							<label for="'.$this->get_field_id($prefix.'widget_pt').'"><span class="label-wrap">'.$labelfix.__('Widget Padding', 'siteitsob').':</span></label>
							<div class="admin-row small-padding mb0">
								<div class="col-md-3"><input class="widefat" id="'.$this->get_field_id($prefix.'widget_pt').'" name="'.$this->get_field_name($prefix.'widget_pt').'" type="number" value="'.esc_attr($instance[$prefix.'widget_pt']).'" placeholder="0" /></div>
								<div class="col-md-3"><input class="widefat" id="'.$this->get_field_id($prefix.'widget_pr').'" name="'.$this->get_field_name($prefix.'widget_pr').'" type="number" value="'.esc_attr($instance[$prefix.'widget_pr']).'" placeholder="0" /></div>
								<div class="col-md-3"><input class="widefat" id="'.$this->get_field_id($prefix.'widget_pb').'" name="'.$this->get_field_name($prefix.'widget_pb').'" type="number" value="'.esc_attr($instance[$prefix.'widget_pb']).'" placeholder="0" /></div>
								<div class="col-md-3"><input class="widefat" id="'.$this->get_field_id($prefix.'widget_pl').'" name="'.$this->get_field_name($prefix.'widget_pl').'" type="number" value="'.esc_attr($instance[$prefix.'widget_pl']).'" placeholder="0" /></div>
							</div>
						</div>
						<div class="col-md-3 mb0">
							<label for="'.$this->get_field_id($prefix.'widget_mt').'"><span class="label-wrap">'.$labelfix.__('Widget Margin', 'siteitsob').':</span></label>
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
		$result 		= '';
        $prefix = '';


        /*************************************************
        ** FIX TITLE & TEXT
        *************************************************/
		// FIX TITLE 
		if(isset($instance[$prefix.'widget_title'])) {

			$titleCss 		= '';
			$titleCss 		.= $this->build_margpad_array($instance[$prefix.'title_pt'], $instance[$prefix.'title_pr'], $instance[$prefix.'title_pb'], $instance[$prefix.'title_pl'], 'padding');
			$titleCss 		.= $this->build_margpad_array($instance[$prefix.'title_mt'], $instance[$prefix.'title_mr'], $instance[$prefix.'title_mb'], $instance[$prefix.'title_ml'], 'margin');

			if(!$instance[$prefix.'title_type']) {$instance[$prefix.'title_type'] = 'h3';}
			if(!$instance[$prefix.'title_size']) {$instance[$prefix.'title_size'] = '30'; }
			if($instance[$prefix.'title_color']) {$titleCss .= 'color:'.$instance[$prefix.'title_color'].';';}
			if($instance[$prefix.'title_width']) {$titleCss .= 'width:'.$instance[$prefix.'title_width'].';'; }


			$widget_title = '<div class="title-box '.$instance[$prefix.'title_align'].' "><'.$instance[$prefix.'title_type'].' class="widgettitle '.$instance[$prefix.'title_fweight'].' '.$instance[$prefix.'title_classes'].' " style="'.$titleCss.'; font-size: '.$instance[$prefix.'title_size'].'px; display: inline-block;">'.$instance[$prefix.'widget_title'].'</'.$instance[$prefix.'title_type'].'></div>';
		}
		else {$instance[$prefix.'widget_title'] = '';}


		
		// FIX TEXT CSS
		if(isset($instance[$prefix.'widget_text'])) {
			$textCss 		= '';
			$textCss 		.= $this->build_margpad_array($instance[$prefix.'text_pt'], $instance[$prefix.'text_pr'], $instance[$prefix.'text_pb'], $instance[$prefix.'text_pl'], 'padding');
			$textCss 		.= $this->build_margpad_array($instance[$prefix.'text_mt'], $instance[$prefix.'text_mr'], $instance[$prefix.'text_mb'], $instance[$prefix.'text_ml'], 'margin');

			if($instance[$prefix.'text_width']) {$textCss .= 'width: '.$instance[$prefix.'text_width'].'; display: inline-block;';}
			if($instance[$prefix.'text_size']) {$textCss .= 'font-size: '.$instance[$prefix.'text_size'].'px;';}
			if($instance[$prefix.'text_color']) {$textCss .= 'color: '.$instance[$prefix.'text_color'].';';} 

			$text = '<div class="text-box '.$instance[$prefix.'text_size'].' '.$instance[$prefix.'text_fweight'].' '.$instance[$prefix.'text_classes'].'" style="'.$textCss  .'">'.$instance[$prefix.'widget_text'].'</div>';
        }
        


        /*************************************************
        ** FIX BUTTON
        *************************************************/
		$btnStyles 	= '';
		$btnClasses = '';


		// BUILD MARGIN
		$btnStyles 	.= $this->build_margpad_array($instance[$prefix.'btn_pt'], $instance[$prefix.'btn_pr'], $instance[$prefix.'btn_pb'], $instance[$prefix.'btn_pl'], 'padding');
		$btnStyles 	.= $this->build_margpad_array($instance[$prefix.'btn_mt'], $instance[$prefix.'btn_mr'], $instance[$prefix.'btn_mb'], $instance[$prefix.'btn_ml'], 'margin');

		
		if( !isset($instance[$prefix.'btn_transbg']) ) {$instance[$prefix.'btn_transbg'] = '';}

		// SETUP BG COLOR
		if(!$instance[$prefix.'btn_color']) {
			$btnStyles .= 'background: '.($instance[$prefix.'btn_transbg'] == 'on' ? 'transparent' : $instance[$prefix.'btn_custom_bg']).'; color: '.$instance[$prefix.'btn_custom_color'].';';
			$hoverStyles = '<style>#btn-'.$this->id_base.'-'.$this->number.':hover {background: '.($instance[$prefix.'btn_transbg'] == 'on' ? 'transparent' : $instance[$prefix.'btn_custom_hoverbg']).' !important; color: '.$instance[$prefix.'btn_custom_hovercolor'].' !important;}</style>';
		}
		else { $btnClasses .= $instance[$prefix.'btn_color'].' '; $hoverStyles = '';}


		if( isset($instance[$prefix.'btn_fsize']) ) {$btnStyles .= 'font-size: '.$instance[$prefix.'btn_fsize'].'px;';} 	// BUILD FONT SIZE
		if( isset($instance[$prefix.'btn_classes']) ) {$btnClasses .= $instance[$prefix.'btn_classes'].' ';}				// ATTACH CLASSES TO BUTTON
		if( isset($instance[$prefix.'btn_align']) ) {$btnClasses .= $instance[$prefix.'btn_align'].' ';}					// ATTACH TEXT SIDE
		if( isset($instance[$prefix.'btn_borderadius']) ) {$btnClasses .= $instance[$prefix.'btn_borderadius'].' ';}		// ATTACH CLASSES TO BUTTON
		if( isset($instance[$prefix.'btn_fontweight']) ) {$btnClasses .= $instance[$prefix.'btn_fontweight'].' ';}		// ATTACH CLASSES TO BUTTON
		if( isset($instance[$prefix.'btn_textalign']) ) {$btnClasses .= $instance[$prefix.'btn_textalign'].' ';}		// ATTACH CLASSES TO BUTTON


		// ICON SIDE
		if( isset($instance[$prefix.'btn_icon']) || isset($instance[$prefix.'image_icon']) ) {
			$iconCss 	 	= '';
			$faIconsArr 	= $this->return_fixed_farray();
			$iconCss 		.= $this->build_margpad_array($instance[$prefix.'iconimg_pt'], $instance[$prefix.'iconimg_pr'], $instance[$prefix.'iconimg_pb'], $instance[$prefix.'iconimg_pl'], 'padding');
			$iconCss 		.= $this->build_margpad_array($instance[$prefix.'iconimg_mt'], $instance[$prefix.'iconimg_mr'], $instance[$prefix.'iconimg_mb'], $instance[$prefix.'iconimg_ml'], 'margin');
			$iconClasses	= $instance[$prefix.'btn_iconside'];

			if(isset($instance[$prefix.'image_icon'])) {$icon = '<img src="'.$instance[$prefix.'image_icon'].'" alt="'.$instance[$prefix.'image_icon_alt'].'" class="'.$iconClasses.'" style="'.$iconCss.'">';}
			else {$icon	= '<i class="fa '.$faIconsArr[ $instance[$prefix.'btn_icon'] ].' '.$iconClasses.'" style="'.$iconCss.'"></i>';}
		}




		/*************************************************
		** FIX OVERLAY
        *************************************************/
        if($instance[$prefix.'image_overlay'] == 'yes') {
            $overlay = $instance[$prefix.'overlay_color'];
            $opacity = $instance[$prefix.'overlay_opacity'];
        }



        /*************************************************
        ** BUILD FINAL WIDGET
        *************************************************/
		$before_widget 			= '<div class="singleWidget  sitBuilderWidget sitTextBgOverlayWidget '.$instance[$prefix.'btn_align'].'">';
		$after_widget 			= '</div>';



		// FIX MARGINS & PADDINGS
		$widgetCss 		= '';
		$widgetCss 		.= $this->build_margpad_array($instance[$prefix.'widget_pt'], $instance[$prefix.'widget_pr'], $instance[$prefix.'widget_pb'], $instance[$prefix.'widget_pl'], 'padding');
		$widgetCss 		.= $this->build_margpad_array($instance[$prefix.'widget_mt'], $instance[$prefix.'widget_mr'], $instance[$prefix.'widget_mb'], $instance[$prefix.'widget_ml'], 'margin');


		$result = '
		<div class="singleWidget sitBuilderWidget sitBuilderTextWidget '.$instance[$prefix.'widget_classes'].'" style="'.$widgetCss.'">
			'.$widget_title.'
			'.$text.'
		</div>
		';
		
	
		echo $before_widget.$result.$after_widget;

	}
 
}
// add_action( 'widgets_init', create_function('', 'return register_widget("sgSimpleOverlayWidget");') );
add_action( 'widgets_init', function () {
    return register_widget('sgSimpleOverlayWidget');
}, 1 );
?>