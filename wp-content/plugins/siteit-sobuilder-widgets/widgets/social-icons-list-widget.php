<?php
class sgSocialIconList extends WP_Widget {


    /*************************************************
    ** XXX
    ** XXX
    ** XXX
    ** XXX
    ** XXX
    ** XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX
    ** XXX
    ** XXX  UNDER CONSTUCTION!
    ** XXX  https://stackoverflow.com/questions/18997774/add-draggable-sections-in-wordpress-plugin-page
    ** XXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXXX
    ** XXX
    ** XXX
    ** XXX
    *************************************************/

	
	// DEFINE THE WIDGET
	function __construct() {
		$widget_ops = array(
			'classname' 	=> 'sg_btn_widg',
			'description'	=> __('Easy To Use social icons widget by SITEIT', 'siteitsob')
		);
		parent::__construct('sgSocialIconList', __('Social Icons Widget (UC)', 'siteitsob'), $widget_ops);
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
 
 
	// MULTI SELECT
	function sgDataArr($type) {
		
		if($type == 'yesno') {
			$type = array('yes'=>__('Yes'), 'no'=>__('No'));
		}
		elseif($type == 'linkTargets') {
			$type = array('_blank'=>__('New Window'), '_self'=>__('Same Window'));
		}
		elseif($type == 'fontweight') {
			$type = array( 'light' => __('Light', 'siteitsob'), 'normal' => __('Normal', 'siteitsob'), 'bold' => __('Bold', 'siteitsob'));
		}
		elseif($type == 'listyles') {
			$type = array( 'list-inline' => __('Horizontal', 'siteitsob'), 'lsnone' => __('Vertical', 'siteitsob'));
		}
		elseif($type == 'aligns') {
			$type = array('' => __('Default', 'siteitsob'), 'text-right'=>__('Right', 'siteitsob'), 'text-center'=>__('Center', 'siteitsob'), 'text-left' => __('Left', 'siteitsob'));
		}
		return $type;
	}



	
	function multi_select($value, $label, $type) {
		$data = '';
		
		$typeArr = $this->sgDataArr($type);
		if($label) { $data .= '<option value="">'.$label.'</option>'; }
		
		foreach($typeArr as $key => $opt) {
			if($key == $value) {$selected = 'selected';} else {$selected = '';}
			$data .= '<option value="'.$key.'" '.$selected.'>'.$opt.'</option>';
		}
		
		return $data;
	}


	function return_fixed_farray() {
		include( plugin_dir_path( __FILE__ ) . '../functions/fontAwesomeSocialArray.php');
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
			$data .= '<option value="'.$key.'" '.$selected.'>&#x'.$key.' '.str_replace('fa-', '', $icon).'</option>';
		}
		
		return $data;
	}


	function siteit_widget_fields() {
		return array(
			'social_icon_1',
			'social_iconimg_1',
			'social_link_1',
			'social_target_1',
			'social_color_1',
			'social_hovercolor_1',
			
			'social_icon_2',
			'social_iconimg_2',
			'social_link_2',
			'social_target_2',
			'social_color_2',
			'social_hovercolor_2',
			
			
			'social_icon_3',
			'social_iconimg_3',
			'social_link_3',
			'social_target_3',
			'social_color_3',
			'social_hovercolor_3',
			
			'social_icon_4',
			'social_iconimg_4',
			'social_link_4',
			'social_target_4',
			'social_color_4',
			'social_hovercolor_4',
			
			'social_icon_5',
			'social_iconimg_5',
			'social_link_5',
			'social_target_5',
			'social_color_5',
			'social_hovercolor_5',
			
			'social_icon_6',
			'social_iconimg_6',
			'social_link_6',
			'social_target_6',
			'social_color_6',
			'social_hovercolor_6',
			
			'social_icon_7',
			'social_iconimg_7',
			'social_link_7',
			'social_target_7',
			'social_color_7',
			'social_hovercolor_7',
			
			'social_icon_8',
			'social_iconimg_8',
			'social_link_8',
			'social_target_8',
			'social_color_8',
			'social_hovercolor_8',
			
			'social_icon_9',
			'social_iconimg_9',
			'social_link_9',
			'social_target_9',
			'social_color_9',
			'social_hovercolor_9',
			
			'social_icon_10',
			'social_iconimg_10',
			'social_link_10',
			'social_target_10',
			'social_color_10',
			'social_hovercolor_10',

			'social_font_size',
			'social_bgcolor',
			'social_listyle',
			'social_listalign',
			'item_pt',
			'item_pr',
			'item_pb',
			'item_pl',
			'item_mt',
			'item_mr',
			'item_mb',
			'item_ml',
			'widget_pt',
			'widget_pr',
			'widget_pb',
			'widget_pl',
			'widget_mt',
			'widget_mr',
			'widget_mb',
			'widget_ml',
			'use_mobile',
		);
	}



	function icon_fields_loop($prefix, $instance) {

		$i 				= 1;
		$icon_items 	= '';

		while($i < 11) {

			$icon_items .= '
			<div class="fullbED" style="padding: 0 10px; margin-bottom: 7px;">
				<div class="admin-row">
					<div class="col-md-1 '.(is_rtl() ? 'pl0' : 'pr0').'">
						<span style="font-size: 14px;line-height: 28px;background: #f1f4f6;font-weight: bold;padding: 0px 10px;display: inline-block;margin: 23px 0 0;border: 1px solid #ddd;"><span class="widgetsPage-hide">'.__('Site', THEME_NAME).'</span> '.$i.'</span>
					</div>
					<div class="col-md-11 mb0">
						<div class="admin-row">
							<div class="col-md-2 widgetsPage-col-6">
								<label for="'.$this->get_field_id($prefix.'social_icon_'.$i).'"><span class="label-wrap">'.__('Icon', 'siteitsob').'</span>:
									<select class="fa-select widefat" id="'.$this->get_field_id($prefix.'social_icon_'.$i).'" name="'.$this->get_field_name($prefix.'social_icon_'.$i).'">
										'.$this->faicons_select($instance[$prefix.'social_icon_'.$i], __('No Icon', 'siteitsob')).'
									</select>
								</label>
							</div>
							<div class="col-md-2 widgetsPage-col-6">
								<label for="'.$this->get_field_name($prefix.'social_iconimg_'.$i).'"><span class="label-wrap">'. __('Image Icon', 'siteitsob').'</span>:</label><br>
								<input name="'.$this->get_field_name($prefix.'social_iconimg_'.$i).'" id="'.$this->get_field_id($prefix.'social_iconimg_'.$i).'" class="widefat imageUploader" type="text" size="36"  value="'.esc_url($instance[$prefix.'social_iconimg_'.$i]).'" style="width: 50%;" />
								<input data-input="#'.$this->get_field_id($prefix.'social_iconimg_'.$i).'" class="upload_image_button button button-primary" type="button" value="'.__('Upload', 'siteitsob').'" />
							</div>
							<div class="col-md-2 widgetsPage-col-6">
								<label for="'.$this->get_field_id($prefix.'social_link_'.$i).'"><span class="label-wrap">'.__('Link', 'siteitsob').':</span>
									<input class="widefat" id="'.$this->get_field_id($prefix.'social_link_'.$i).'" name="'.$this->get_field_name($prefix.'social_link_'.$i).'" type="text" value="'.esc_attr($instance[$prefix.'social_link_'.$i]).'" />
								</label>
							</div>
							<div class="col-md-2 widgetsPage-col-6">
								<label for="'.$this->get_field_id($prefix.'social_target_'.$i).'"><span class="label-wrap">'.__('Target', 'siteitsob').':</span>
									<select class="widefat" id="'.$this->get_field_id($prefix.'social_target_'.$i).'" name="'.$this->get_field_name($prefix.'social_target_'.$i).'">
										'.$this->multi_select($instance[$prefix.'social_target_'.$i], '', 'linkTargets').'
									</select>
								</label>
							</div>
							<div class="col-md-2 widgetsPage-col-6">
								<label for="'.$this->get_field_id($prefix.'social_color_'.$i).'"><span class="label-wrap">'.__('Color', 'siteitsob').':</span>
									<input class="widefat sgColorPicker" id="'.$this->get_field_id($prefix.'social_color_'.$i).'" name="'.$this->get_field_name($prefix.'social_color_'.$i).'" type="text" value="'.esc_attr($instance[$prefix.'social_color_'.$i]).'" />
								</label>
							</div>
							<div class="col-md-2 widgetsPage-col-6">
								<label for="'.$this->get_field_id($prefix.'social_hovercolor_'.$i).'"><span class="label-wrap">'.__('Hover Color', 'siteitsob').':</span>
									<input class="widefat sgColorPicker" id="'.$this->get_field_id($prefix.'social_hovercolor_'.$i).'" name="'.$this->get_field_name($prefix.'social_hovercolor_'.$i).'" type="text" value="'.esc_attr($instance[$prefix.'social_hovercolor_'.$i]).'" />
								</label>
							</div>
						</div>
					</div>
				</div>
			</div>
			';
			
			$i = $i + 1;
		}

		return $icon_items;
	}


	function form_fileds_looper($instance) {

		// rtl fixes
		if(is_rtl()) {$floatDir = 'left';} else {$floatDir = 'right';}
        $prefix = '';
        $icons_blocks = array( 'facebook', 'twitter', 'pinterest', 'google-plus', 'youtube', 'linkedin');


        $formFields = '
		<div class="admin-row">

			<div class="col-md-9">
				<h4 class="row-title">'.__('Social Networks', 'siteitsob').'  <span class="sitb-icon icon-social icon-big"></span></h4>
				<div class="row-wrap">
					'.$this->icon_fields_loop($prefix, $instance).'
				</div>
			</div>
			<div class="col-md-3">

				<h4 class="row-title">'.__('Icons Settings', 'siteitsob').'  <span class="sitb-icon icon-cog icon-big"></span></h4>
				<div class="row-wrap">
					<div class="admin-row">
						<div class="col-md-6">
							<label for="'.$this->get_field_id($prefix.'social_font_size').'"><span class="label-wrap">'.__('Font Size (PX)', 'siteitsob').':</span>
								<input class="widefat" id="'.$this->get_field_id($prefix.'social_font_size').'" name="'.$this->get_field_name($prefix.'social_font_size').'" type="number" value="'.esc_attr($instance[$prefix.'social_font_size']).'" />
							</label>
						</div>
						<div class="col-md-6">
							<label for="'.$this->get_field_id($prefix.'social_bgcolor').'"><span class="label-wrap">'.__('Background Color', 'siteitsob').':</span>
								<input class="widefat sgColorPicker" id="'.$this->get_field_id($prefix.'social_bgcolor').'" name="'.$this->get_field_name($prefix.'social_bgcolor').'" type="text" value="'.esc_attr($instance[$prefix.'social_bgcolor']).'" />
							</label>
						</div>
						<div class="col-md-6">
							<label for="'.$this->get_field_id($prefix.'social_listyle').'"><span class="label-wrap">'.$labelfix.__('List Style', 'siteitsob').':</span>
								<select class="widefat" id="'.$this->get_field_id($prefix.'social_listyle').'" name="'.$this->get_field_name($prefix.'social_listyle').'">
									'.$this->multi_select($instance[$prefix.'social_listyle'], '', 'listyles').'
								</select>
							</label>
						</div>
						<div class="col-md-6">
							<label for="'.$this->get_field_id($prefix.'social_listalign').'"><span class="label-wrap">'.$labelfix.__('List Align', 'siteitsob').':</span>
								<select class="widefat" id="'.$this->get_field_id($prefix.'social_listalign').'" name="'.$this->get_field_name($prefix.'social_listalign').'">
									'.$this->multi_select($instance[$prefix.'social_listalign'], '', 'aligns').'
								</select>
							</label>
						</div>
					</div>
				</div>

				<h4 class="row-title">'.__('Icons Spacing', 'siteitsob').'  <span class="sitb-icon icon-settings icon-big"></span></h4>
				<div class="row-wrap">
					<div class="admin-row">
						<div class="col-md-12">
							<p class="mini-desc">'.__('Padding & Margin between items', 'siteitsob').'</p>
						</div>
						<div class="col-md-6" style="padding-right: 5px;">
							<label for="'.$this->get_field_id($prefix.'item_pt').'"><span class="label-wrap">'.$labelfix.__('Padding', 'siteitsob').':</span></label>
							<div class="admin-row mini-padding tight-number mb0">
								<div class="col-md-3"><input class="widefat" id="'.$this->get_field_id($prefix.'item_pt').'" name="'.$this->get_field_name($prefix.'item_pt').'" type="number" value="'.esc_attr($instance[$prefix.'item_pt']).'" placeholder="0" /></div>
								<div class="col-md-3"><input class="widefat" id="'.$this->get_field_id($prefix.'item_pr').'" name="'.$this->get_field_name($prefix.'item_pr').'" type="number" value="'.esc_attr($instance[$prefix.'item_pr']).'" placeholder="0" /></div>
								<div class="col-md-3"><input class="widefat" id="'.$this->get_field_id($prefix.'item_pb').'" name="'.$this->get_field_name($prefix.'item_pb').'" type="number" value="'.esc_attr($instance[$prefix.'item_pb']).'" placeholder="0" /></div>
								<div class="col-md-3"><input class="widefat" id="'.$this->get_field_id($prefix.'item_pl').'" name="'.$this->get_field_name($prefix.'item_pl').'" type="number" value="'.esc_attr($instance[$prefix.'item_pl']).'" placeholder="0" /></div>
							</div>
						</div>
						<div class="col-md-6" style="padding-left: 5px;">
							<label for="'.$this->get_field_id($prefix.'item_mt').'"><span class="label-wrap">'.$labelfix.__('Margin', 'siteitsob').':</span></label>
							<div class="admin-row mini-padding tight-number mb0">
								<div class="col-md-3"><input class="widefat" id="'.$this->get_field_id($prefix.'item_mt').'" name="'.$this->get_field_name($prefix.'item_mt').'" type="number" value="'.esc_attr($instance[$prefix.'item_mt']).'" placeholder="0" /></div>
								<div class="col-md-3"><input class="widefat" id="'.$this->get_field_id($prefix.'item_mr').'" name="'.$this->get_field_name($prefix.'item_mr').'" type="number" value="'.esc_attr($instance[$prefix.'item_mr']).'" placeholder="0" /></div>
								<div class="col-md-3"><input class="widefat" id="'.$this->get_field_id($prefix.'item_mb').'" name="'.$this->get_field_name($prefix.'item_mb').'" type="number" value="'.esc_attr($instance[$prefix.'item_mb']).'" placeholder="0" /></div>
								<div class="col-md-3"><input class="widefat" id="'.$this->get_field_id($prefix.'item_ml').'" name="'.$this->get_field_name($prefix.'item_ml').'" type="number" value="'.esc_attr($instance[$prefix.'item_ml']).'" placeholder="0" /></div>
							</div>
						</div>
					</div>
				</div>

				<h4 class="row-title">'.__('Advanced Setting', 'siteitsob').'  <span class="sitb-icon icon-settings2 icon-big"></span></h4>
				<div class="row-wrap">
					<div class="admin-row">
						<div class="col-md-6" style="padding-right: 5px;">
							<label for="'.$this->get_field_id($prefix.'widget_pt').'"><span class="label-wrap">'.$labelfix.__('Widget Padding', 'siteitsob').':</span></label>
							<div class="admin-row mini-padding tight-number mb0">
								<div class="col-md-3"><input class="widefat" id="'.$this->get_field_id($prefix.'widget_pt').'" name="'.$this->get_field_name($prefix.'widget_pt').'" type="number" value="'.esc_attr($instance[$prefix.'widget_pt']).'" placeholder="0" /></div>
								<div class="col-md-3"><input class="widefat" id="'.$this->get_field_id($prefix.'widget_pr').'" name="'.$this->get_field_name($prefix.'widget_pr').'" type="number" value="'.esc_attr($instance[$prefix.'widget_pr']).'" placeholder="0" /></div>
								<div class="col-md-3"><input class="widefat" id="'.$this->get_field_id($prefix.'widget_pb').'" name="'.$this->get_field_name($prefix.'widget_pb').'" type="number" value="'.esc_attr($instance[$prefix.'widget_pb']).'" placeholder="0" /></div>
								<div class="col-md-3"><input class="widefat" id="'.$this->get_field_id($prefix.'widget_pl').'" name="'.$this->get_field_name($prefix.'widget_pl').'" type="number" value="'.esc_attr($instance[$prefix.'widget_pl']).'" placeholder="0" /></div>
							</div>
						</div>
						<div class="col-md-6" style="padding-left: 5px;">
							<label for="'.$this->get_field_id($prefix.'widget_mt').'"><span class="label-wrap">'.$labelfix.__('Widget Margin', 'siteitsob').':</span></label>
							<div class="admin-row mini-padding tight-number mb0">
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
		jQuery(document).ready(function($) {
			$(".sgColorPicker").wpColorPicker();
		});
		</script>

		<style>input::-webkit-inner-spin-button {display: none !important;}</style>
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
		<div class="socialIconsWidget">'.$this->form_fileds_looper($instance).'</div>';
		
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


		/*********************************************
		** LOOP SOCIAL ICONS
		*********************************************/
		$widget_css		= '';
		$styles 		= '';
		$list_item_css	= '';
		$item_css		= '';
		// $itemsArr		= '';
		$itemsArr		= array();
		$items			= '';
		$cnt 			= 1;
		$faIcons		= $this->return_fixed_farray();


		// WIDGET WRAP STUFF
		$widget_css 	.= $this->build_margpad_array($instance[$prefix.'widget_pt'], $instance[$prefix.'widget_pr'], $instance[$prefix.'widget_pb'], $instance[$prefix.'widget_pl'], 'padding');
		$widget_css 	.= $this->build_margpad_array($instance[$prefix.'widget_mt'], $instance[$prefix.'widget_mr'], $instance[$prefix.'widget_mb'], $instance[$prefix.'widget_ml'], 'margin');


		// BASIC SETTINGS
		$result					= '';
		$before_widget 			= '<div class="singleWidget  sitBuilderWidget sitBuilderSocialIconsWidget '.$instance[$prefix.'social_listalign'].'" style="'.$widget_css.'">';
		$after_widget 			= '</div>';



		while($cnt < 11) {

			if( isset($instance[$prefix.'social_icon_'.$cnt]) || isset($instance[$prefix.'social_iconimg_'.$cnt]) ) {

				if( isset($instance[$prefix.'social_icon_'.$cnt]) && $instance[$prefix.'social_icon_'.$cnt] != '' ) {$iconCode = $faIcons[ $instance[$prefix.'social_icon_'.$cnt] ];} else {$iconCode = '';}
				
				$itemsArr[] = array(
					'social_icon'		=> $iconCode,
					'social_iconimg'	=> $instance[$prefix.'social_iconimg_'.$cnt],
					'social_link'		=> $instance[$prefix.'social_link_'.$cnt],
					'social_target'		=> $instance[$prefix.'social_target_'.$cnt],
					'social_color' 		=> $instance[$prefix.'social_color_'.$cnt],
					'social_hovercolor'	=> $instance[$prefix.'social_hovercolor_'.$cnt],
				);
			}

			$cnt++;
		}


		foreach($itemsArr as $key => $iarr) {

			if( $iarr['social_icon'] != '' || $iarr['social_iconimg'] != '' ) {

				// if no color selected
				if(!$iarr['social_color']) {$iarr['social_color'] = '#000';}

				
				// build hover color
				if(!$iarr['social_color']) {$iarr['social_color'] = '#666';}
				$styles .= '.icon-'.$key.'{ color: '.$iarr['social_color'].'; }';

				if($iarr['social_hovercolor']){
					$styles .= '.icon-'.$key.':hover{ color: '.$iarr['social_hovercolor'].' !important; }';
				}

				
				// font size
				if(!$instance[$prefix.'social_font_size']) {$instance[$prefix.'social_font_size'] = 14;}


				// fix image
				if($iarr['social_iconimg']) {$icon = '<img src="'.$iarr['social_iconimg'].'" alt="'.str_replace( array('.jpg', '.png', '.svg'), '', $iarr['social_iconimg'] ).'">';}
				else {$icon = '<i class="fa '.$iarr['social_icon'].'"></i>';}


				// margin & padding
				if($iarr['social_color'] != '') {$item_css 	.= 'color: '.$iarr['social_color'].';';}
				if($instance[$prefix.'social_font_size'] != '') {$item_css 	.= 'font-size: '.$instance[$prefix.'social_font_size'].'px;';}

				$list_item_css 	.= $this->build_margpad_array($instance[$prefix.'item_pt'], $instance[$prefix.'item_pr'], $instance[$prefix.'item_pb'], $instance[$prefix.'item_pl'], 'padding');
				$list_item_css 	.= $this->build_margpad_array($instance[$prefix.'item_mt'], $instance[$prefix.'item_mr'], $instance[$prefix.'item_mb'], $instance[$prefix.'item_ml'], 'margin');


				// build list items
				$items .= '<li style="'.$list_item_css.'"><a href="'.$iarr['social_link'].'" class="icon-'.$key.'" style="'.$item_css.'" target="'.$iarr['social_target'].'">'.$icon.'</a></li>';

			}

		}


		$result 		= '<ul class="p0 m0 '.$instance[$prefix.'social_listyle'].'">'.$items.'</ul>';
		$hoverStyles 	= '<style>'.$styles.'</style>';

		echo $before_widget.$result.$hoverStyles.$after_widget;

	}
 
}
// add_action( 'widgets_init', create_function('', 'return register_widget("sgSocialIconList");') );
add_action( 'widgets_init', function () {
    return register_widget('sgSocialIconList');
}, 1 );
?>