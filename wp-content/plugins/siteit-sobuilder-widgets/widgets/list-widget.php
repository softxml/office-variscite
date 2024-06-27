<?php
class sgListWidget extends WP_Widget {



    // DEFINE THE WIDGET
    function __construct() {
        $widget_ops = array(
            'classname' 	=> 'sg_list_widget',
            'description'	=> __('Easy to use list widget By SITEIT', 'siteitsob')
        );

        parent::__construct('sgListWidget', __('Repeatble List Widget (SiteIT)', 'siteitsob'), $widget_ops);
    }


    // BUILD MARGIN or PADDING ARRAY
    function build_margpad_array($top, $right, $bottom, $left, $type) {
        $result  				= '';
        $arr[$type.'-top'] 		= $top;
        $arr[$type.'-right'] 	= $right;
        $arr[$type.'-bottom'] 	= $bottom;
        $arr[$type.'-left'] 	= $left;

        if(!empty($arr)) {
            $arr = array_filter($arr);
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
        elseif($type == 'fontweight') {
            $type = array( 'light' => __('Light', 'siteitsob'), 'normal' => __('Normal', 'siteitsob'), 'bold' => __('Bold', 'siteitsob'));
        }
        elseif($type == 'linkTargets') {
            $type = array('_self'=>__('Same Window', 'siteitsob'), '_blank'=>__('New Window', 'siteitsob'));
        }
        elseif($type == 'listyles') {
            $type = array('lsnone'=>__('None', 'siteitsob'), 'disc' => __('Disc', 'siteitsob'), 'circle'=>__('Circle', 'siteitsob'), 'square' => __('Square', 'siteitsob'), 'decimal'=>__('Decimal', 'siteitsob'), 'decimal-leading-zero'=>__('Decimal With Zero', 'siteitsob'), 'decimal'=>__('Decimal', 'siteitsob'), 'decimal'=>__('Decimal', 'siteitsob') );
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

            'list_items',

            'list_fsize',
            'list_lheight',
            'list_fweight',
            'list_style',
            'listitems_align',
            'list_align',
            'list_faicon',
            'list_imgicon',
            'list_txtclr',
            'list_icnclr',

            'listicon_pt',
            'listicon_pr',
            'listicon_pb',
            'listicon_pl',
            'listicon_mt',
            'listicon_mr',
            'listicon_mb',
            'listicon_ml',

            'list_pt',
            'list_pr',
            'list_pb',
            'list_pl',
            'list_mt',
            'list_mr',
            'list_mb',
            'list_ml',

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
    }


    function form_fileds_looper($instance) {


        // rtl fixes
        if(is_rtl()) {$floatDir = 'left';} else {$floatDir = 'right';}
        $prefix 		= '';


        // LOOP LIST ITEMS
        $listIndex      = '0';
        $listItemsArr   = $instance[$prefix.'list_items'];
        $listItemsFields = '';


        if(!empty($listItemsArr)) {
            $listItemsArr   = array_filter($listItemsArr);
            foreach($listItemsArr as $item) {

                if( trim($item['text']) ) {
                    $listItemsFields .= '
					<tr>
						<td><input class="widefat" id="'.$this->get_field_id($prefix.'list_items').'['.$listIndex.'][text]" name="'.$this->get_field_name($prefix.'list_items').'['.$listIndex.'][text]" type="text" value="'.$instance[$prefix.'list_items'][$listIndex]['text'].'" /></td>
						<td><input class="widefat" id="'.$this->get_field_id($prefix.'list_items').'['.$listIndex.'][link]" name="'.$this->get_field_name($prefix.'list_items').'['.$listIndex.'][link]" type="text" value="'.$instance[$prefix.'list_items'][$listIndex]['link'].'" /></td>
						<td><button class="button-secondary removeListItem">Remove</button></td>
					</tr>
					';

                    $listIndex++;
                }
            }
        }
        else {
            $listItemsFields .= '
			<tr>
				<td><input class="widefat" id="'.$this->get_field_id($prefix.'list_items').'['.$listIndex.'][text]" name="'.$this->get_field_name($prefix.'list_items').'['.$listIndex.'][text]" type="text" value="'.$instance[$prefix.'list_items'][$listIndex]['text'].'" /></td>
				<td><input class="widefat" id="'.$this->get_field_id($prefix.'list_items').'['.$listIndex.'][link]" name="'.$this->get_field_name($prefix.'list_items').'['.$listIndex.'][link]" type="text" value="'.$instance[$prefix.'list_items'][$listIndex]['link'].'" /></td>
				<td><button class="button-secondary removeListItem">Remove</button></td>
			</tr>
            ';
        }



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
            

			<div class="col-md-4">
				<h4 class="row-title">'.__('List Items)', 'siteitsob').'  <span class="sitb-icon icon-text icon-big"></span></h4>
				<div class="row-wrap list-items-wrap">
					<table id="listItemsTable" class="widefat">
						<thead>
							<tr>
								<th>'.__('Text', THEME_NAME).'</th>
								<th>'.__('Link', THEME_NAME).'</th>
								<th>'.__('Actions', THEME_NAME).'</th>
							</tr>
						</thead>
						<tbody>
							'.$listItemsFields.'
						</tbody>
					</table>
					
					
					<!--=== DEMO ELEMENT FOR CLONING ===-->
					<table class="listItemBox dnone">
						<tr>
							<td><input class="widefat" id="'.$this->get_field_id($prefix.'list_items').'[PH][text]" name="'.$this->get_field_name($prefix.'list_items').'[PH][text]" type="text" value="'.$instance[$prefix.'list_items']['PH']['text'].' " /></td>
							<td><input class="widefat" id="'.$this->get_field_id($prefix.'list_items').'[PH][link]" name="'.$this->get_field_name($prefix.'list_items').'[PH][link]" type="text" value="'.$instance[$prefix.'list_items']['PH']['link'].' " /></td>
							<td><button class="button-secondary removeListItem">Remove</button></td>
						</tr>
					</table>
					<!--=== DEMO ELEMENT FOR CLONING ===-->
					
                	<button class="button-primary addListItem" style="vertical-align: baseline !important;">Add New</button>
				</div>
			</div>
			
			<div class="col-md-4">
				<h4 class="row-title">'.__('List Settings', 'siteitsob').'  <span class="sitb-icon icon-text icon-big"></span></h4>
				<div class="row-wrap list-items-wrap">
					<div class="admin-row">

						<div class="col-md-4">
							<label for="'.$this->get_field_id($prefix.'list_fsize').'"><span class="label-wrap">'.__('Font Size (PX)', 'siteitsob').':</span>
								<input class="widefat" id="'.$this->get_field_id($prefix.'list_fsize').'" name="'.$this->get_field_name($prefix.'list_fsize').'" type="number" value="'.esc_attr($instance[$prefix.'list_fsize']).'" />
							</label>
						</div>
						<div class="col-md-4">
							<label for="'.$this->get_field_id($prefix.'list_lheight').'"><span class="label-wrap">'.__('Line Height (PX)', 'siteitsob').':</span>
								<input class="widefat" id="'.$this->get_field_id($prefix.'list_lheight').'" name="'.$this->get_field_name($prefix.'list_lheight').'" type="number" value="'.esc_attr($instance[$prefix.'list_lheight']).'" />
							</label>
						</div>
						<div class="col-md-4">
							<label for="'.$this->get_field_id($prefix.'list_fweight').'"><span class="label-wrap">'.__('Font Weight', 'siteitsob').':</span>
								<select class="widefat" id="'.$this->get_field_id($prefix.'list_fweight').'" name="'.$this->get_field_name($prefix.'list_fweight').'">
								'.$this->multi_select($instance[$prefix.'list_fweight'], __('Default', 'siteitsob'), 'fontweight').'
								</select>
							</label>
						</div>

						<div class="col-md-4">
							<label for="'.$this->get_field_id($prefix.'list_style').'"><span class="label-wrap">'.__('List Style', 'siteitsob').':</span>
								<select class="widefat" id="'.$this->get_field_id($prefix.'list_style').'" name="'.$this->get_field_name($prefix.'list_style').'">
								'.$this->multi_select($instance[$prefix.'list_style'], __('Default', 'siteitsob'), 'listyles').'
								</select>
							</label>
						</div>
						<div class="col-md-4">
							<label for="'.$this->get_field_id($prefix.'listitems_align').'"><span class="label-wrap">'.__('Items Align', 'siteitsob').':</span>
								<select class="widefat" id="'.$this->get_field_id($prefix.'listitems_align').'" name="'.$this->get_field_name($prefix.'listitems_align').'">
								'.$this->multi_select($instance[$prefix.'listitems_align'], __('Default', 'siteitsob'), 'talign').'
								</select>
							</label>
						</div>
						<div class="col-md-4">
							<label for="'.$this->get_field_id($prefix.'list_align').'"><span class="label-wrap">'.__('List Align', 'siteitsob').':</span>
								<select class="widefat" id="'.$this->get_field_id($prefix.'list_align').'" name="'.$this->get_field_name($prefix.'list_align').'">
								'.$this->multi_select($instance[$prefix.'list_align'], __('Default', 'siteitsob'), 'talign').'
								</select>
							</label>
						</div>

						<div class="col-md-4">
							<label for="'.$this->get_field_id($prefix.'list_faicon').'"><span class="label-wrap">'.__('Use Icon?', 'siteitsob').'</span>:
								<select class="fa-select widefat" id="'.$this->get_field_id($prefix.'list_faicon').'" name="'.$this->get_field_name($prefix.'list_faicon').'">
									'.$this->faicons_select($instance[$prefix.'list_faicon'], __('No Icon', 'siteitsob')).'
								</select>
							</label>
						</div>
						<div class="col-md-4">
							<label for="'.$this->get_field_name($prefix.'list_imgicon').'"><span class="label-wrap">'. __('Image Icon Instead?', 'siteitsob').'</span></label>
							<input name="'.$this->get_field_name($prefix.'list_imgicon').'" id="'.$this->get_field_id($prefix.'list_imgicon').'" class="widefat imageUploader" type="text" size="36"  value="'.esc_url($instance[$prefix.'list_imgicon']).'" style="width: 47%;" /> 
							<input data-input="#'.$this->get_field_id($prefix.'list_imgicon').'" class="upload_image_button button button-primary" type="button" value="'.__('Upload', 'siteitsob').'" />
						</div>
						<div class="col-md-4 col-empty"><!-- EMPTY -->
						
						</div>


						<div class="col-md-4">
							<label for="'.$this->get_field_id($prefix.'list_txtclr').'"><span class="label-wrap">'.__('Text Color', 'siteitsob').':</span></label> <br />
							<input class="widefat colorPicker" id="'.$this->get_field_id($prefix.'list_txtclr').'" type="text" name="'.$this->get_field_name($prefix.'list_txtclr').'" value="'.esc_attr($instance[$prefix.'list_txtclr']).'" />
						</div>
						<div class="col-md-4">
							<label for="'.$this->get_field_id($prefix.'list_icnclr').'"><span class="label-wrap">'.__('Icons Color <small style="float: right">(optional)</small>', 'siteitsob').'</span></label> <br />
							<input class="widefat colorPicker" id="'.$this->get_field_id($prefix.'list_icnclr').'" type="text" name="'.$this->get_field_name($prefix.'list_icnclr').'" value="'.(esc_attr($instance[$prefix.'btn_custom_bg']) ? esc_attr($instance[$prefix.'btn_custom_bg']) : '#ECF0F1').'" />
						</div>
						<div class="col-md-4 col-empty"><!-- EMPTY -->
						
						</div>

						<div class="col-md-6 col-xs-12">
							<label for="'.$this->get_field_id($prefix.'listicon_pt').'"><span class="label-wrap">'.__('Icon Padding', 'siteitsob').':</span></label>
							<div class="admin-row mini-padding mb0">
								<div class="col-md-3 mb0"><input class="widefat" id="'.$this->get_field_id($prefix.'listicon_pt').'" name="'.$this->get_field_name($prefix.'listicon_pt').'" type="number" value="'.esc_attr($instance[$prefix.'listicon_pt']).'" placeholder="0" /></div>
								<div class="col-md-3 mb0"><input class="widefat" id="'.$this->get_field_id($prefix.'listicon_pr').'" name="'.$this->get_field_name($prefix.'listicon_pr').'" type="number" value="'.esc_attr($instance[$prefix.'listicon_pr']).'" placeholder="0" /></div>
								<div class="col-md-3 mb0"><input class="widefat" id="'.$this->get_field_id($prefix.'listicon_pb').'" name="'.$this->get_field_name($prefix.'listicon_pb').'" type="number" value="'.esc_attr($instance[$prefix.'listicon_pb']).'" placeholder="0" /></div>
								<div class="col-md-3 mb0"><input class="widefat" id="'.$this->get_field_id($prefix.'listicon_pl').'" name="'.$this->get_field_name($prefix.'listicon_pl').'" type="number" value="'.esc_attr($instance[$prefix.'listicon_pl']).'" placeholder="0" /></div>
							</div>
						</div>
						<div class="col-md-6 col-xs-12">
							<label for="'.$this->get_field_id($prefix.'listicon_mt').'"><span class="label-wrap">'.__('Icon Margin', 'siteitsob').':</span></label>
							<div class="admin-row mini-padding mb0">
								<div class="col-md-3 mb0"><input class="widefat" id="'.$this->get_field_id($prefix.'listicon_mt').'" name="'.$this->get_field_name($prefix.'listicon_mt').'" type="number" value="'.esc_attr($instance[$prefix.'listicon_mt']).'" placeholder="0" /></div>
								<div class="col-md-3 mb0"><input class="widefat" id="'.$this->get_field_id($prefix.'listicon_mr').'" name="'.$this->get_field_name($prefix.'listicon_mr').'" type="number" value="'.esc_attr($instance[$prefix.'listicon_mr']).'" placeholder="0" /></div>
								<div class="col-md-3 mb0"><input class="widefat" id="'.$this->get_field_id($prefix.'listicon_mb').'" name="'.$this->get_field_name($prefix.'listicon_mb').'" type="number" value="'.esc_attr($instance[$prefix.'listicon_mb']).'" placeholder="0" /></div>
								<div class="col-md-3 mb0"><input class="widefat" id="'.$this->get_field_id($prefix.'listicon_ml').'" name="'.$this->get_field_name($prefix.'listicon_ml').'" type="number" value="'.esc_attr($instance[$prefix.'listicon_ml']).'" placeholder="0" /></div>
							</div>
						</div>

						<div class="col-md-6 col-xs-12 mb0">
							<label for="'.$this->get_field_id($prefix.'list_pt').'"><span class="label-wrap">'.__('List Padding', 'siteitsob').':</span></label>
							<div class="admin-row mini-padding mb0">
								<div class="col-md-3 mb0"><input class="widefat" id="'.$this->get_field_id($prefix.'list_pt').'" name="'.$this->get_field_name($prefix.'list_pt').'" type="number" value="'.esc_attr($instance[$prefix.'list_pt']).'" placeholder="0" /></div>
								<div class="col-md-3 mb0"><input class="widefat" id="'.$this->get_field_id($prefix.'list_pr').'" name="'.$this->get_field_name($prefix.'list_pr').'" type="number" value="'.esc_attr($instance[$prefix.'list_pr']).'" placeholder="0" /></div>
								<div class="col-md-3 mb0"><input class="widefat" id="'.$this->get_field_id($prefix.'list_pb').'" name="'.$this->get_field_name($prefix.'list_pb').'" type="number" value="'.esc_attr($instance[$prefix.'list_pb']).'" placeholder="0" /></div>
								<div class="col-md-3 mb0"><input class="widefat" id="'.$this->get_field_id($prefix.'list_pl').'" name="'.$this->get_field_name($prefix.'list_pl').'" type="number" value="'.esc_attr($instance[$prefix.'list_pl']).'" placeholder="0" /></div>
							</div>
						</div>
						<div class="col-md-6 col-xs-12 mb0">
							<label for="'.$this->get_field_id($prefix.'list_mt').'"><span class="label-wrap">'.__('List Margin', 'siteitsob').':</span></label>
							<div class="admin-row mini-padding mb0">
								<div class="col-md-3 mb0"><input class="widefat" id="'.$this->get_field_id($prefix.'list_mt').'" name="'.$this->get_field_name($prefix.'list_mt').'" type="number" value="'.esc_attr($instance[$prefix.'list_mt']).'" placeholder="0" /></div>
								<div class="col-md-3 mb0"><input class="widefat" id="'.$this->get_field_id($prefix.'list_mr').'" name="'.$this->get_field_name($prefix.'list_mr').'" type="number" value="'.esc_attr($instance[$prefix.'list_mr']).'" placeholder="0" /></div>
								<div class="col-md-3 mb0"><input class="widefat" id="'.$this->get_field_id($prefix.'list_mb').'" name="'.$this->get_field_name($prefix.'list_mb').'" type="number" value="'.esc_attr($instance[$prefix.'list_mb']).'" placeholder="0" /></div>
								<div class="col-md-3 mb0"><input class="widefat" id="'.$this->get_field_id($prefix.'list_ml').'" name="'.$this->get_field_name($prefix.'list_ml').'" type="number" value="'.esc_attr($instance[$prefix.'list_ml']).'" placeholder="0" /></div>
							</div>
						</div>

					</div>
				</div>
			</div>
            


			<div class="col-md-12 col-xs-12">
				<h4 class="row-title">'.__('Widget Advanced Settings', 'siteitsob').'  <span class="sitb-icon icon-cog icon-big"></span></h4>
				<div class="row-wrap">
					<div class="admin-row">

						<div class="col-md-3 col-xs-6 mb0">
							<label for="'.$this->get_field_id($prefix.'title_classes').'">'.__('Title Classes', 'siteitsob').':
							<input class="widefat" id="'.$this->get_field_id($prefix.'title_classes').'" name="'.$this->get_field_name($prefix.'title_classes').'" type="text" value="'.esc_attr($instance[$prefix.'title_classes']).'" />
							</label>
						</div>
						<div class="col-md-3 col-xs-6 mb0">
							<label for="'.$this->get_field_id($prefix.'widget_classes').'">'.__('Widget Classes', 'siteitsob').':
							<input class="widefat" id="'.$this->get_field_id($prefix.'widget_classes').'" name="'.$this->get_field_name($prefix.'widget_classes').'" type="text" value="'.esc_attr($instance[$prefix.'widget_classes']).'" placeholder="'.__('For example: mt0 mb0', 'siteitsob').'" />
							</label>
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
        $instance 			= wp_parse_args( array($instance, $instanceArray) );

        // widget title
        echo '
		<div>'.$this->form_fileds_looper($instance).'</div>

		<script>
		jQuery(function($){

            $(".colorPicker").wpColorPicker();
			

            $(".addListItem").click(function() {
                var ccount 	= $(".active #listItemsTable > tbody > tr").length;
				var ele 	= $(".active .listItemBox.dnone tr", $(this).parents() ).clone();

				$(ele).find("input").each(function() {
					$(this).attr( "name", $(this).attr("name").replace("PH", ccount) );
					$(this).attr( "id", $(this).attr("id").replace("PH", ccount) );
				});

				clone = "<tr>"+$(ele)+"</tr>";
				$(".active #listItemsTable tbody").append( $(ele) );
			});
			
			$(".active .removeListItem").click(function(){
				$(this).parent().parent().remove();
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
            $instance[$field] = $new_instance[$field];
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
        if(isset($instance[$prefix.'widget_title'])) {

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


            if($instance[$prefix.'widget_title']) {
                $widget_title = '<div class="title-box '.$instance[$prefix.'title_align'].' "><'.$instance[$prefix.'title_type'].' class="widgettitle '.$instance[$prefix.'title_fweight'].' '.$instance[$prefix.'title_classes'].' '.$titleCls.' " style="'.$titleCss.'; font-size: '.$instance[$prefix.'title_size'].'px; display: inline-block;" '.$titleAnimation.' >'.$preTitle.$instance[$prefix.'widget_title'].$aftTitle.'</'.$instance[$prefix.'title_type'].'></div>';
            } else {$widget_title = '';}
        }
        else {$instance[$prefix.'widget_title'] = '';}

        // 'list_items',

        // 'list_fsize',
        // 'list_lheight',
        // 'list_fweight',
        // 'list_style',
        // 'listitems_align',
        // 'list_align',
        // 'list_faicon',
        // 'list_imgicon',
        // 'list_txtclr',
        // 'list_icnclr',

        // 'listicon_pt',
        // 'listicon_pr',
        // 'listicon_pb',
        // 'listicon_pl',
        // 'listicon_mt',
        // 'listicon_mr',
        // 'listicon_mb',
        // 'listicon_ml',

        // 'list_pt',
        // 'list_pr',
        // 'list_pb',
        // 'list_pl',
        // 'list_mt',
        // 'list_mr',
        // 'list_mb',
        // 'list_ml',

        // BUILD LIST
        if( !empty($instance[$prefix.'list_items']) ) {

            unset($instance[$prefix.'list_items']['PH']);	// remove placeholder

            $liststyle  = '';
            $list_items = '';

            $list_css = '';
            $list_cls = '';

            $item_css = '';
            $item_cls = '';

            $faIconsArr	  = $this->return_fixed_farray();

            $icon_padding = $this->build_margpad_array($instance[$prefix.'listicon_pt'], $instance[$prefix.'listicon_pr'], $instance[$prefix.'listicon_pb'], $instance[$prefix.'listicon_pl'], 'padding');
            $icon_margin  = $this->build_margpad_array($instance[$prefix.'listicon_pt'], $instance[$prefix.'listicon_pr'], $instance[$prefix.'listicon_pb'], $instance[$prefix.'listicon_pl'], 'margin');


            // list type
            if( !empty($instance[$prefix.'list_imgicon']) ) {$icon = '<img src="'.$instance[$prefix.'list_imgicon'].'" alt="'.str_replace( array('-', '_'), ' ', str_replace(array('.png, .jpg, .gif, .svg'), '', $instance[$prefix.'list_imgicon']) ).'" style="'.$icon_padding.' '.$icon_margin.'">'; $list_cls .= 'img-ul '; } // IMG ICON
            elseif( !empty($instance[$prefix.'list_faicon']) ) {$icon = '<i class="fa '.$faIconsArr[$instance[$prefix.'list_faicon']].'" style="'.$icon_padding.' '.$icon_margin.'"></i> '; $list_cls .= 'fa-ul '; }	// FA ICON
            else {$liststyle = $instance[$prefix.'list_style']; $icon = '';}

            // list classes
            if( !empty($instance[$prefix.'list_fweight']) ) {$list_cls .= $instance[$prefix.'list_fweight'].' ';}
            if( !empty($instance[$prefix.'listitems_align']) ) {$list_cls .= $instance[$prefix.'listitems_align'].' ';}


            // list css
            if( !empty($instance[$prefix.'list_fweight']) ) {$list_css .= 'font-size: '.$instance[$prefix.'list_fsize'].'px;';}
            if( !empty($instance[$prefix.'list_lheight']) ) {$list_css .= 'line-height: '.$instance[$prefix.'list_lheight'].'px;';}
            if( !empty($instance[$prefix.'list_txtclr']) ) {$list_css .= 'color: '.$instance[$prefix.'list_txtclr'].';';}

            // list margin
            $list_css .= $this->build_margpad_array($instance[$prefix.'list_pt'], $instance[$prefix.'list_pr'], $instance[$prefix.'list_pb'], $instance[$prefix.'list_pl'], 'padding');
            $list_css .= $this->build_margpad_array($instance[$prefix.'list_mt'], $instance[$prefix.'list_mr'], $instance[$prefix.'list_mb'], $instance[$prefix.'list_ml'], 'margin');




            // Run trough list items
            $instance[$prefix.'list_items'] = array_filter($instance[$prefix.'list_items']);

            foreach($instance[$prefix.'list_items'] as $item) {

                if( trim($item['text']) != '') {

                    $itemTxt = trim($item['text']);

                    if( !empty($item['link']) ) { $linkBfr = '<a href="'.$item['link'].'">'; $linkAft = '</a>'; } else {$linkBfr = ''; $linkAft = '';}
                    if( !empty($instance[$prefix.'list_imgicon']) ) { $list_items .= '<li class="">'.$linkBfr.$icon.$itemTxt.$linkAft.'</li>'; }
                    elseif( !empty($instance[$prefix.'list_faicon']) ) { $list_items .= '<li class="fa-li">'.$linkBfr.$icon.$itemTxt.$linkAft.'</li>'; }
                    else { $list_items .= '<li class="">'.$linkBfr.$item['text'].$linkAft.'</li>';; }
                }
            }

        }





        // FIX MARGINS & PADDINGS
        $widgetCss 		= '';
        $widgetCss 		.= $this->build_margpad_array($instance[$prefix.'widget_pt'], $instance[$prefix.'widget_pr'], $instance[$prefix.'widget_pb'], $instance[$prefix.'widget_pl'], 'padding');
        $widgetCss 		.= $this->build_margpad_array($instance[$prefix.'widget_mt'], $instance[$prefix.'widget_mr'], $instance[$prefix.'widget_mb'], $instance[$prefix.'widget_ml'], 'margin');


        $result = '
		<div class="singleWidget sitBuilderWidget sitBuilderRptListWidget '.$instance[$prefix.'widget_classes'].'" style="'.$widgetCss.'">
			'.$widget_title.'
			<ul list-style="'.$instance[$prefix.'list_style'].'" class="'.$list_cls.'" style="'.$list_css.'">'.$list_items.'</ul>
		</div>
		';


        echo $before_widget.$result.$after_widget;

    }

}
// add_action( 'widgets_init', create_function('', 'return register_widget("sgListWidget");') );
add_action( 'widgets_init', function () {
    return register_widget('sgListWidget');
}, 1 );
?>