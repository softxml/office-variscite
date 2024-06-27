<?php
class sgCleanJsWidget extends WP_Widget {


	
	// DEFINE THE WIDGET
	function __construct() {
		$widget_ops = array(
			'classname' 	=> 'sg_jscode_widg', 
			'description'	=> __('A simple widget that will allow you to add JavaScript directly to the desired location', 'siteitsob')
		);

		parent::__construct('sgCleanJsWidget', __('Easy Javascript Code Widget (SiteIT)', 'siteitsob'), $widget_ops);
	}


	// MULTI SELECT
	function sgDataArr($type) {
		if($type == 'yesno') { $type = array('yes'=>__('Yes'), 'no'=>__('No')); }
		return $type;
	}


	function multi_select($value, $label, $type) {
		$data = '';
		$typeArr = $this->sgDataArr($type);
		if($label) { $data .= '<option value="">'.$label.'</option>'; }
		foreach($typeArr as $key => $opt) { if($key == $value) {$selected = 'selected';} else {$selected = '';} $data .= '<option value="'.$key.'" '.$selected.'>'.$opt.'</option>'; }
		return $data;
	}


	function siteit_widget_fields() {
		return array(
			'widget_title', 
			'code_box', 
			'use_mobile', 
		);
	}


	function form_fileds_looper($instance) {


		// rtl fixes
		if(is_rtl()) {$floatDir = 'left';} else {$floatDir = 'right';}
        $prefix 		= '';


		$formFields = '
		<div class="admin-row">
			<div class="col-md-12">
				<h4 class="row-title">'.__('Code Box', 'siteitsob').' <span class="sitb-icon icon-title icon-big"></span></h4>
				<div class="row-wrap">
					<div class="admin-row">
						
						<div class="col-md-12">
							<label for="'.$this->get_field_id($prefix.'spacer_height').'"><span class="label-wrap">'.__('Title (Not Public)', 'siteitsob').':</span>
							<input class="widefat" id="'.$this->get_field_id($prefix.'widget_title').'" name="'.$this->get_field_name($prefix.'widget_title').'" type="text" value="'.__('Spacer Widget', 'siteitsob').'" disabled />
							</label>	
						</div>
						<div class="col-md-12">
                            <label for="'.$this->get_field_id($prefix.'code_box').'"><span class="label-wrap">'.__('Code Box', 'siteitsob').':</span><br />
                            <textarea class="widefat" name="'.$this->get_field_name($prefix.'code_box').'" id="'.$this->get_field_id($prefix.'code_box').'" rows="10">'.$instance[$prefix.'code_box'].'</textarea>
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

		// BASIC SETTINGS
		$result 		= '';
		$before_widget 	= '';
		$after_widget  	= '';
        $prefix = '';

		/*
		'code_box',
		'use_mobile', 
		*/


		$result = $instance[$prefix.'code_box'];
		
	
		echo $before_widget.$result.$after_widget;

	}
 
}
// add_action( 'widgets_init', create_function('', 'return register_widget("sgCleanJsWidget");') );
add_action( 'widgets_init', function () {
    return register_widget('sgCleanJsWidget');
}, 1 );
?>