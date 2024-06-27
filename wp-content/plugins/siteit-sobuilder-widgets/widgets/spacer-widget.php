<?php
class sgSpacerWidget extends WP_Widget {


	
	// DEFINE THE WIDGET
	function __construct() {
		$widget_ops = array(
			'classname' 	=> 'sg_spacer_widg', 
			'description'	=> __('Easy to use flexible spacer SITEIT', 'siteitsob')
		);

		parent::__construct('sgSpacerWidget', __('Spacer Widget (SiteIT)', 'siteitsob'), $widget_ops);
	}


	// MULTI SELECT
	function sgDataArr($type) {
		
		if($type == 'yesno') {
			$type = array('yes'=>__('Yes'), 'no'=>__('No'));
		}
		elseif($type == 'sizeunits') {
			$type = array('px'=>__('PX', 'siteitsob'), 'vh'=>__('VH', 'siteitsob'), 'em' => __('EM', 'siteitsob'));
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
			'spacer_height', 
			'spacer_unit', 
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
			<div class="col-md-12">
				<h4 class="row-title">'.__('Spacer Settings', 'siteitsob').' <span class="sitb-icon icon-title icon-big"></span></h4>
				<div class="row-wrap">
					<div class="admin-row">
						
						<div class="col-md-4" style="display: none">
							<label for="'.$this->get_field_id($prefix.'spacer_height').'"><span class="label-wrap">'.__('Title', 'siteitsob').':</span>
							<input class="widefat dnone" id="'.$this->get_field_id($prefix.'widget_title').'" name="'.$this->get_field_name($prefix.'widget_title').'" type="text" value="'.__('Spacer Widget', 'siteitsob').'" disabled />
							</label>	
						</div>
						<div class="col-md-6">
							<div class="admin-row">
								<div class="col-md-8">
									<label for="'.$this->get_field_id($prefix.'spacer_height').'"><span class="label-wrap">'.__('Spacer Height', 'siteitsob').':</span>
									<input class="widefat" id="'.$this->get_field_id($prefix.'spacer_height').'" name="'.$this->get_field_name($prefix.'spacer_height').'" type="number" value="'.(esc_attr($instance[$prefix.'spacer_height']) ? esc_attr($instance[$prefix.'spacer_height']) : 50).'" placeholder="'.__('For example: 60', 'siteitsob').'" />
									</label>	
								</div>
								<div class="col-md-4">
									<label for="'.$this->get_field_id($prefix.'spacer_unit').'"><span class="label-wrap">&nbsp;</span>
									<select class="widefat" id="'.$this->get_field_id($prefix.'spacer_unit').'" name="'.$this->get_field_name($prefix.'spacer_unit').'">
										'.$this->multi_select($instance[$prefix.'spacer_unit'], '', 'sizeunits').'
									</select>	
									</label>	
								</div>
							</div>
						</div>
						<div class="col-md-4">
							<label for="'.$this->get_field_id($prefix.'widget_classes').'"><span class="label-wrap">'.__('Widget Classes', 'siteitsob').':</span>
							<input class="widefat" id="'.$this->get_field_id($prefix.'widget_classes').'" name="'.$this->get_field_name($prefix.'widget_classes').'" type="text" value="" />
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


		$result = ' <div class="singleWidget sitBuilderWidget sitBuilderSpacerWidget '.$instance[$prefix.'widget_classes'].'" style="height: '.$instance[$prefix.'spacer_height'].$instance[$prefix.'spacer_unit'].'"></div> ';
		
	
		echo $before_widget.$result.$after_widget;

	}
 
}
// add_action( 'widgets_init', create_function('', 'return register_widget("sgSpacerWidget");') );
add_action( 'widgets_init', function () {
    return register_widget('sgSpacerWidget');
}, 1 );
?>