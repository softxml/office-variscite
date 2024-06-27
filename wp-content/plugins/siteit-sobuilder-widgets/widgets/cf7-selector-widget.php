<?php
class wbCF7formWidget extends WP_Widget {


	
	// DEFINE THE WIDGET
	function __construct() {
		$widget_ops = array(
			'classname' 	=> 'wb_cf7selectwidget_widg', 
			'description'	=> __('An easy to use contact form 7 form selector', THEME_NAME)
		);

		parent::__construct('wbCF7formWidget', __('Contact form 7 Selector (SiteIT)', THEME_NAME), $widget_ops);
	}



    // RETURN CF7 SELECT
	function cforms_datarry() {

		$formslist = array();
		
		$args = array('post_type' => 'wpcf7_contact_form', 'posts_per_page' => -1);
		if( $cf7Forms = get_posts( $args ) ){
			foreach($cf7Forms as $cf7Form){ $formslist[$cf7Form->ID] = $cf7Form->post_title; }
		}

        return $formslist;
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
 
 
	// MULTI SELECT
	function sgDataArr($type) {
		
		if($type == 'yesno') {
			$type = array('yes'=>__('Yes'), 'no'=>__('No'));
		}
		elseif($type == 'imgstyle') {
			$type = array('parallax'=>__('Parallax', THEME_NAME), 'fixed'=>__('Fixed', THEME_NAME));
		}
		elseif($type == 'ttype') {
			$type = array('h3'=>__('h3'),'h1'=>__('h1'),'h2'=>__('h2'), 'h4'=>__('h4'), 'h5'=>__('h5'));
		}
		elseif($type == 'talign') {
			$type = array('text-left' => __('Left', THEME_NAME), 'text-center' => __('Center', THEME_NAME), 'text-right' => __('Right', THEME_NAME));
		}
		elseif($type == 'valigns') {
			$type = array('top' => __('Top', THEME_NAME), 'middle' => __('Middle', THEME_NAME), 'bottom' => __('Bottom', THEME_NAME));
		}
		elseif($type == 'topmarg') {
			$type = array( 'mt0' => __('0 (no margin)', THEME_NAME), 'mt1' => __('1% from top', THEME_NAME),'mt2' => __('2% from top', THEME_NAME),'mt3' => __('3% from top', THEME_NAME),'mt4' => __('4% from top', THEME_NAME),'mt5' => __('5% from top', THEME_NAME),'mt10' => __('10% from top', THEME_NAME),'mt20' => __('20% from top', THEME_NAME),'mt30' => __('30% from top', THEME_NAME),'mt20x' => __('20px from top', THEME_NAME),'mt30x' => __('30px from top', THEME_NAME),'mt40x' => __('40px from top', THEME_NAME),'mt50x' => __('50px from top', THEME_NAME),'mt60x' => __('60px from top', THEME_NAME),'mt70x' => __('70px from top', THEME_NAME),'mt80x' => __('80px from top', THEME_NAME),'mt90x' => __('90px from top', THEME_NAME),'mt100x' => __('100px from top', THEME_NAME),'mt120x' => __('120px from top', THEME_NAME),'mt150x' => __('150px from top', THEME_NAME),'mt170x' => __('170px from top', THEME_NAME),'mt200x' => __('200px from top', THEME_NAME), );
		}
		elseif($type == 'botmarg') {
			$type = array( 'mb0' => __('0 (no margin)', THEME_NAME), 'mb1' => __('1% from bottom', THEME_NAME),'mb2' => __('2% from bottom', THEME_NAME),'mb3' => __('3% from bottom', THEME_NAME),'mb4' => __('4% from bottom', THEME_NAME),'mb5' => __('5% from bottom', THEME_NAME),'mb10' => __('10% from bottom', THEME_NAME),'mb20' => __('20% from bottom', THEME_NAME),'mb30' => __('30% from bottom', THEME_NAME),'mb20x' => __('20px from bottom', THEME_NAME),'mb30x' => __('30px from bottom', THEME_NAME),'mb40x' => __('40px from bottom', THEME_NAME),'mb50x' => __('50px from bottom', THEME_NAME),'mb60x' => __('60px from bottom', THEME_NAME),'mb70x' => __('70px from bottom', THEME_NAME),'mb80x' => __('80px from bottom', THEME_NAME),'mb90x' => __('90px from bottom', THEME_NAME),'mb100x' => __('100px from bottom', THEME_NAME),'mb120x' => __('120px from bottom', THEME_NAME),'mb150x' => __('150px from bottom', THEME_NAME),'mb170x' => __('170px from bottom', THEME_NAME),'mb200x' => __('200px from bottom', THEME_NAME), );
		}
		elseif($type == 'borderColors') {
			$type = array('fullbDark'=>__('Black', THEME_NAME), 'fullbCC'=>__('Grey', THEME_NAME), 'fullbED'=>__('Light Grey', THEME_NAME), 'fullbRed'=>__('Red', THEME_NAME), 'fullbBlue'=>__('Blue', THEME_NAME), 'fullbGreen'=>__('Green', THEME_NAME));
		}
		elseif($type == 'imagePositions') {
			$type = array('above'=>__('Above Title', THEME_NAME), 'under'=>__('Under Title', THEME_NAME));
		}
		elseif($type == 'cf7forms') {
			$type = $this->cforms_datarry();
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
			'title_mt',
			'title_mr',
			'title_mb',
			'title_ml',
			'title_pt',
			'title_pr',
			'title_pb',
			'title_pl',  
            'pick_cform',  
			'form_mt',
			'form_mr',
			'form_mb',
			'form_ml',
			'form_pt',
			'form_pr',
			'form_pb',
			'form_pl',  
			'title_classes', 
			'widget_classes',
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
                            <label for="'.$this->get_field_id($prefix.'title_pt').'"><span class="label-wrap">'.__('Padding', 'siteitsob').':</span></label>
                            <div class="admin-row mini-padding mb0">
                                <div class="col-md-3"><input class="widefat" id="'.$this->get_field_id($prefix.'title_pt').'" name="'.$this->get_field_name($prefix.'title_pt').'" type="number" value="'.esc_attr($instance[$prefix.'title_pt']).'" placeholder="0" /> <span class="icon-small arrow-mt"></span></div>
                                <div class="col-md-3"><input class="widefat" id="'.$this->get_field_id($prefix.'title_pr').'" name="'.$this->get_field_name($prefix.'title_pr').'" type="number" value="'.esc_attr($instance[$prefix.'title_pr']).'" placeholder="0" /></div>
                                <div class="col-md-3"><input class="widefat" id="'.$this->get_field_id($prefix.'title_pb').'" name="'.$this->get_field_name($prefix.'title_pb').'" type="number" value="'.esc_attr($instance[$prefix.'title_pb']).'" placeholder="0" /></div>
                                <div class="col-md-3"><input class="widefat" id="'.$this->get_field_id($prefix.'title_pl').'" name="'.$this->get_field_name($prefix.'title_pl').'" type="number" value="'.esc_attr($instance[$prefix.'title_pl']).'" placeholder="0" /></div>
                            </div>
                        </div>
                        <div class="col-md-6 mb0">
                            <label for="'.$this->get_field_id($prefix.'title_mt').'"><span class="label-wrap">'.__('Margin', 'siteitsob').':</span></label>
                            <div class="admin-row mini-padding mb0">
                                <div class="col-md-3"><input class="widefat" id="'.$this->get_field_id($prefix.'title_mt').'" name="'.$this->get_field_name($prefix.'title_mt').'" type="number" value="'.esc_attr($instance[$prefix.'title_mt']).'" placeholder="0" /></div>
                                <div class="col-md-3"><input class="widefat" id="'.$this->get_field_id($prefix.'title_mr').'" name="'.$this->get_field_name($prefix.'title_mr').'" type="number" value="'.esc_attr($instance[$prefix.'title_mr']).'" placeholder="0" /></div>
                                <div class="col-md-3"><input class="widefat" id="'.$this->get_field_id($prefix.'title_mb').'" name="'.$this->get_field_name($prefix.'title_mb').'" type="number" value="'.esc_attr($instance[$prefix.'title_mb']).'" placeholder="0" /></div>
                                <div class="col-md-3"><input class="widefat" id="'.$this->get_field_id($prefix.'title_ml').'" name="'.$this->get_field_name($prefix.'title_ml').'" type="number" value="'.esc_attr($instance[$prefix.'title_ml']).'" placeholder="0" /></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <h4 class="row-title">'.__('Contact Form Settings', 'siteitsob').' <span class="sitb-icon icon-settings icon-big"></span></h4>
                <div class="row-wrap">
                    <div class="admin-row">
                        <div class="col-md-12">
                            <label for="'.$this->get_field_id($prefix.'pick_cform').'"><span class="label-wrap">'.__('Pick Contact Form', 'siteitsob').':</span>
                                <select class="widefat" id="'.$this->get_field_id($prefix.'pick_cform').'" name="'.$this->get_field_name($prefix.'pick_cform').'">
                                    '.$this->multi_select($instance[$prefix.'pick_cform'], __('Pick Form', THEME_NAME), 'cf7forms').'
                                </select>
                            </label>
                        </div>
                        <div class="col-md-6 mb0">
                            <label for="'.$this->get_field_id($prefix.'form_pt').'"><span class="label-wrap">'.__('Form Padding', 'siteitsob').':</span></label>
                            <div class="admin-row mini-padding mb0">
                                <div class="col-md-3"><input class="widefat" id="'.$this->get_field_id($prefix.'form_pt').'" name="'.$this->get_field_name($prefix.'form_pt').'" type="number" value="'.esc_attr($instance[$prefix.'form_pt']).'" placeholder="0" /> <span class="icon-small arrow-mt"></span></div>
                                <div class="col-md-3"><input class="widefat" id="'.$this->get_field_id($prefix.'form_pr').'" name="'.$this->get_field_name($prefix.'form_pr').'" type="number" value="'.esc_attr($instance[$prefix.'form_pr']).'" placeholder="0" /></div>
                                <div class="col-md-3"><input class="widefat" id="'.$this->get_field_id($prefix.'form_pb').'" name="'.$this->get_field_name($prefix.'form_pb').'" type="number" value="'.esc_attr($instance[$prefix.'form_pb']).'" placeholder="0" /></div>
                                <div class="col-md-3"><input class="widefat" id="'.$this->get_field_id($prefix.'form_pl').'" name="'.$this->get_field_name($prefix.'form_pl').'" type="number" value="'.esc_attr($instance[$prefix.'form_pl']).'" placeholder="0" /></div>
                            </div>
                        </div>
                        <div class="col-md-6 mb0">
                            <label for="'.$this->get_field_id($prefix.'form_mt').'"><span class="label-wrap">'.__('Form Margin', 'siteitsob').':</span></label>
                            <div class="admin-row mini-padding mb0">
                                <div class="col-md-3"><input class="widefat" id="'.$this->get_field_id($prefix.'form_mt').'" name="'.$this->get_field_name($prefix.'form_mt').'" type="number" value="'.esc_attr($instance[$prefix.'form_mt']).'" placeholder="0" /></div>
                                <div class="col-md-3"><input class="widefat" id="'.$this->get_field_id($prefix.'form_mr').'" name="'.$this->get_field_name($prefix.'form_mr').'" type="number" value="'.esc_attr($instance[$prefix.'form_mr']).'" placeholder="0" /></div>
                                <div class="col-md-3"><input class="widefat" id="'.$this->get_field_id($prefix.'form_mb').'" name="'.$this->get_field_name($prefix.'form_mb').'" type="number" value="'.esc_attr($instance[$prefix.'form_mb']).'" placeholder="0" /></div>
                                <div class="col-md-3"><input class="widefat" id="'.$this->get_field_id($prefix.'form_ml').'" name="'.$this->get_field_name($prefix.'form_ml').'" type="number" value="'.esc_attr($instance[$prefix.'form_ml']).'" placeholder="0" /></div>
                            </div>
                        </div>                        
                    </div>
                </div>

                <h4 class="row-title">'.__('Advance Settings', 'siteitsob').' <span class="sitb-icon icon-settings2 icon-big"></span></h4>
                <div class="row-wrap">
                    <div class="admin-row">
                        <div class="col-md-6">
                            <label for="'.$this->get_field_id($prefix.'title_classes').'"><span class="label-wrap">'.__('Title Classes', 'siteitsob').':</span>
                                <input class="widefat" id="'.$this->get_field_id($prefix.'title_classes').'" name="'.$this->get_field_name($prefix.'title_classes').'" type="text" value="'.esc_attr($instance[$prefix.'title_classes']).'" />
                            </label>
                        </div>                    
                        <div class="col-md-6">
                            <label for="'.$this->get_field_id($prefix.'widget_classes').'"><span class="label-wrap">'.__('Widget Classes', 'siteitsob').':</span>
                                <input class="widefat" id="'.$this->get_field_id($prefix.'widget_classes').'" name="'.$this->get_field_name($prefix.'widget_classes').'" type="text" value="'.esc_attr($instance[$prefix.'widget_classes']).'" />
                            </label>
                        </div>                    
                    </div>
                </div>
            </div>

            <div class="col-md-4 preview">
                <h4 class="row-title">'.__('Form Preview', 'siteitsob').' <span class="sitb-icon icon-styling icon-big"></span></h4>
                <div class="row-wrap">
					<div class="admin-row">
						<div class="col-md-12">
							'.do_shortcode('[contact-form-7 id="'.$instance[$prefix.'pick_cform'].'"]').'
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
		echo '<div>'.$this->form_fileds_looper($instance).'</div>';
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

		// BASIC SETTINGS
		$result					= '';
        $title_css 				= '';
        
		$before_widget 			= '<div class="singleWidget cf7SelectorWidget '.$instance['widget_classes'].'">';
		$after_widget 			= '</div>';


		// FIX TITLE 
		if(!empty($instance[$prefix.'widget_title'])) {
            
            $titleCss 		= '';
            $titleCss 		.= $this->build_margpad_array($instance[$prefix.'title_pt'], $instance[$prefix.'title_pr'], $instance[$prefix.'title_pb'], $instance[$prefix.'title_pl'], 'padding');
            $titleCss 		.= $this->build_margpad_array($instance[$prefix.'title_mt'], $instance[$prefix.'title_mr'], $instance[$prefix.'title_mb'], $instance[$prefix.'title_ml'], 'margin');

            if(!$instance[$prefix.'title_type']) {$instance[$prefix.'title_type'] = 'h3';}
            if(!$instance[$prefix.'title_size']) {$instance[$prefix.'title_size'] = '30'; }
            if($instance[$prefix.'title_color']) {$titleCss .= 'color:'.$instance[$prefix.'title_color'].';';}
            if($instance[$prefix.'title_width']) {$titleCss .= 'width:'.$instance[$prefix.'title_width'].';'; }


            $widget_title = '<div class="title-box '.$instance[$prefix.'title_classes'].' '.$instance[$prefix.'title_align'].' "><'.$instance[$prefix.'title_type'].' class="widgettitle '.$instance[$prefix.'title_fweight'].' '.$instance[$prefix.'title_classes'].' " style="'.$titleCss.'; font-size: '.$instance[$prefix.'title_size'].'px; display: inline-block;">'.$instance[$prefix.'widget_title'].'</'.$instance[$prefix.'title_type'].'></div>';
        }
        else {$widget_title = '';}


        if($instance[$prefix.'pick_cform']) {

            $formCss 		    = '';
            $formCss 		    .= $this->build_margpad_array($instance[$prefix.'form_pt'], $instance[$prefix.'form_pr'], $instance[$prefix.'form_pb'], $instance[$prefix.'form_pl'], 'padding');
            $formCss 		    .= $this->build_margpad_array($instance[$prefix.'form_mt'], $instance[$prefix.'form_mr'], $instance[$prefix.'form_mb'], $instance[$prefix.'form_ml'], 'margin');

            // FIX FORM DATA
            $cforms_datarry     = $this->cforms_datarry();
            $form_title         = $cforms_datarry[ $instance[$prefix.'pick_cform'] ];


            $widget_form        = '<div class="form-box" style="'.$formCss.'">'.do_shortcode('[contact-form-7 id="'.$instance[$prefix.'pick_cform'].'" title="'.$form_title.'"]').'</div>';
        }



        // BUILD RESULT
        $result .= $widget_title.$widget_form;
	


		echo $before_widget.$result.$after_widget;

	}
 
}
// add_action( 'widgets_init', create_function('', 'return register_widget("wbCF7formWidget");') );
add_action( 'widgets_init', function () {
    return register_widget('wbCF7formWidget');
}, 1 );
?>