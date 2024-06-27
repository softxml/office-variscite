<?php
class sgSfQuoteFormWidget extends WP_Widget {


	// DEFINE THE WIDGET
	function __construct() {
		$widget_ops = array(
			'classname' 	=> 'sg_sform_widg',
			'description'	=> __('A custom widget for services page By SITEIT', 'siteitsob')
		);

		parent::__construct('sgSfQuoteFormWidget', __('SF Product Quote Form Widget (SiteIT)', 'siteitsob'), $widget_ops);
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
		elseif($type == 'widths') {
			$type = array( 'col-md-12' => __('Full Width', THEME_NAME), 'col-md-6' 	=> __('1/2 Width', THEME_NAME), 'col-md-4' 	=> __('1/3 Width', THEME_NAME), 'col-md-3' 	=> __('1/4 Width', THEME_NAME), );
		}

		return $type;
	}



	
	function multi_select($value, $label, $type) {
		$data = '';
		$typeArr = $this->sgDataArr($type);
		if($label) { $data .= '<option value="">'.$label.'</option>'; }
		foreach($typeArr as $key => $opt) { if($key == $value) {$selected = 'selected';} else {$selected = '';} $data .= '<option value="'.$key.'" '.$selected.'>'.$opt.'</option>'; }
		return $data;
	}


	function select2_select($value) {

		$values = array();
		
		if(!is_array($value)) { $values[] = $value; }
		else { $values = $value; }

		$items = '';
		foreach($values as $postid) {
			$items .= '<option value="'.$postid.'" selected="selected">'.get_the_title($postid).'</option>';
		}

		return $items;
	}

	function showHideFieldsArr($valPref) {
		return array($valPref.'first_name', $valPref.'last_name', $valPref.'company', $valPref.'email', $valPref.'country', $valPref.'phone', $valPref.'System__c', $valPref.'Operating_System__c', $valPref.'Projected_Quantities__c', $valPref.'agreement_checkbox', $valPref.'note', $valPref.'captachResponse');
	}


	function show_hide_fields($instance, $prefix) {
		
		$fieldsArr  = $this->showHideFieldsArr($prefix);
		$showFields = array();

		foreach($fieldsArr as $subfield) {

			$showhide 	= 'show_'.$subfield;
			$required 	= 'required_'.$subfield;
			$widthPref 	= 'width_'.$subfield;

			$labelStr = str_replace( array('__c'), '', $subfield);
			$labelStr = ucfirst(str_replace( array('_'), ' ', $labelStr));


			$showFields[] = '
			<div class="col-md-12 mb0">
				<div class="admin-row">
					<div class="col-md-4"><label for="'.$this->get_field_id($prefix.$subfield).'"><span class="label-wrap">'.$labelStr.':</span></label></div>
					<div class="col-md-2">
						<select class="widefat" id="'.$this->get_field_id($prefix.$showhide).'" name="'.$this->get_field_name($prefix.$showhide).'">
							'.$this->multi_select($instance[$prefix.$showhide], '', 'yesno').'
						</select>
					</div>
					<div class="col-md-3">
						<label for="'.$this->get_field_id($prefix.$required).'">
							<input type="checkbox" name="'.$this->get_field_name($prefix.$required).'" id="'.$this->get_field_id($prefix.$required).'" '.checked( $instance[ $prefix.$required ], 'on', false ).'> '.__('Required?', THEME_NAME).'
						</label>
					</div>
					<div class="col-md-3">
						<select class="widefat" id="'.$this->get_field_id($prefix.$widthPref).'" name="'.$this->get_field_name($prefix.$widthPref).'">
							'.$this->multi_select($instance[$prefix.$widthPref], '', 'widths').'
						</select>
					</div>
				</div>
			</div>
			';
		}

		return implode(' ', $showFields);
	}



	function siteit_widget_fields() {

		$showFieldsArr 		= $this->showHideFieldsArr('show_');
		$requiredFieldsArr 	= $this->showHideFieldsArr('required_');
		$widthFieldsArr 	= $this->showHideFieldsArr('width_');

		$widget_fields = array(
			'widget_title',
			
			'border_width',
			'border_color',
			'background_color',
			'floating_labels',

			'event_name',

			'widget_id',
			'widget_classes',
			'widget_pt',
			'widget_pr',
			'widget_pb',
			'widget_pl',
			'widget_mt',
			'widget_mr',
			'widget_mb',
			'widget_ml',

			'os',
			'som_list',
			'quantities',

			'email_to',
			'email_subject',
			'thanks_page',
			'recaptcha_key',

			'lead_source',

			'use_mobile',
		);

		$fullValsArr = array_unique( array_merge($showFieldsArr, $widget_fields) );
		$fullValsArr = array_unique( array_merge($requiredFieldsArr, $fullValsArr) );
		$fullValsArr = array_unique( array_merge($widthFieldsArr, $fullValsArr) );

		return $fullValsArr;
	}


	function form_fileds_looper($instance) {

		// rtl fixes
		if(is_rtl()) {$floatDir = 'left';} else {$floatDir = 'right';}
        $prefix 		= '';


		$quantitiesPlaceholder = '
		For Example:
		100-1000
		1000-3000
		';
		
		
		$osPlaceholder = '
		For Example:
		Windows
		linux
		Other
		';
		
		$leadsourcelang = 'en';
		if(ICL_LANGUAGE_CODE == 'de') {
			$leadsourcelang = 'de ';
		}
		else if(ICL_LANGUAGE_CODE == 'it') {
            $leadsourcelang = 'it ';
        }
		if((strpos($instance[$prefix.'lead_source'],$leadsourcelang))!==FALSE ){
			$leadsource = $instance[$prefix.'lead_source'];
		} elseif($leadsourcelang !== 'en') {
			$leadsource = $leadsourcelang . $instance[$prefix.'lead_source'];
		} else {
			$leadsource = $instance[$prefix.'lead_source'];
		}
			$formFields = '
		<div class="admin-row">
			<div class="col-md-3">

				<h4 class="row-title">'.__('General Widget Settings', 'siteitsob').'  <span class="sitb-icon icon-image icon-big"></span></h4>
				<div class="row-wrap">
					<div class="admin-row">
						<div class="col-md-12">
							<label for="'.$this->get_field_id($prefix.'widget_title').'"><span class="label-wrap">'.__('Widget Title (Not Public)', 'siteitsob').':</span>
							<input class="widefat" id="'.$this->get_field_id($prefix.'widget_title').'" name="'.$this->get_field_name($prefix.'widget_title').'" type="text" value="'.esc_attr($instance[$prefix.'widget_title']).'" />
							</label>
                        </div>
						<div class="col-md-4">
							<label for="'.$this->get_field_id($prefix.'border_width').'"><span class="label-wrap p0i">'.__('Border Width', 'siteitsob').':</span>
							<input class="widefat" id="'.$this->get_field_id($prefix.'border_width').'" name="'.$this->get_field_name($prefix.'border_width').'" type="number" value="'.esc_attr($instance[$prefix.'border_width']).'" />
							</label>
						</div>
						<div class="col-md-4">
							<label for="'.$this->get_field_id($prefix.'border_color').'"><span class="label-wrap">'.__('Border Color', 'siteitsob').':</span> <br /></label>
							<input class="widefat sgColorPicker" id="'.$this->get_field_id($prefix.'border_color').'" name="'.$this->get_field_name($prefix.'border_color').'" type="color" value="'.esc_attr($instance[$prefix.'border_color']).'" />
						</div>
						<div class="col-md-4">
							<label for="'.$this->get_field_id($prefix.'background_color').'"><span class="label-wrap">'.__('Background', 'siteitsob').':</span> <br /></label>
							<input class="widefat sgColorPicker" id="'.$this->get_field_id($prefix.'background_color').'" name="'.$this->get_field_name($prefix.'background_color').'" type="color" value="'.$instance[$prefix.'background_color'].'" />
						</div>

						<div class="col-md-6">
							<label for="'.$this->get_field_id($prefix.'widget_id').'"><span class="label-wrap">'.__('Widget ID', 'siteitsob').'</span>
							<input class="widefat" id="'.$this->get_field_id($prefix.'widget_id').'" name="'.$this->get_field_name($prefix.'widget_id').'" type="text" value="'.esc_attr($instance[$prefix.'widget_id']).'" />
							</label>
						</div>
						<div class="col-md-6">
							<label for="'.$this->get_field_id($prefix.'widget_classes').'"><span class="label-wrap">'.__('Widget Class', 'siteitsob').'</span>
							<input class="widefat" id="'.$this->get_field_id($prefix.'widget_classes').'" name="'.$this->get_field_name($prefix.'widget_classes').'" type="text" value="'.esc_attr($instance[$prefix.'widget_classes']).'" />
							</label>
						</div>
						<div class="col-md-12 mb0">
							<label for="'.$this->get_field_id($prefix.'widget_pt').'"><span class="label-wrap">'.__('Padding', 'siteitsob').':</span></label>
							<div class="admin-row small-padding mb0">
								<div class="col-md-3 pmbox"> <span>top</span> <input class="widefat" id="'.$this->get_field_id($prefix.'widget_pt').'" name="'.$this->get_field_name($prefix.'widget_pt').'" type="number" value="'.esc_attr($instance[$prefix.'widget_pt']).'" placeholder="0" /> </div>
								<div class="col-md-3 pmbox"> <span>right</span><input class="widefat" id="'.$this->get_field_id($prefix.'widget_pr').'" name="'.$this->get_field_name($prefix.'widget_pr').'" type="number" value="'.esc_attr($instance[$prefix.'widget_pr']).'" placeholder="0" /></div>
								<div class="col-md-3 pmbox"> <span>bottom</span><input class="widefat" id="'.$this->get_field_id($prefix.'widget_pb').'" name="'.$this->get_field_name($prefix.'widget_pb').'" type="number" value="'.esc_attr($instance[$prefix.'widget_pb']).'" placeholder="0" /></div>
								<div class="col-md-3 pmbox"> <span>left</span><input class="widefat" id="'.$this->get_field_id($prefix.'widget_pl').'" name="'.$this->get_field_name($prefix.'widget_pl').'" type="number" value="'.esc_attr($instance[$prefix.'widget_pl']).'" placeholder="0" /></div>
							</div>
						</div>
						<div class="col-md-12">
							<label for="'.$this->get_field_id($prefix.'widget_mt').'"><span class="label-wrap">'.__('Margin', 'siteitsob').':</span></label>
							<div class="admin-row small-padding mb0">
								<div class="col-md-3 pmbox"> <span>top</span> <input class="widefat" id="'.$this->get_field_id($prefix.'widget_mt').'" name="'.$this->get_field_name($prefix.'widget_mt').'" type="number" value="'.esc_attr($instance[$prefix.'widget_mt']).'" placeholder="0" /></div>
								<div class="col-md-3 pmbox"> <span>right</span> <input class="widefat" id="'.$this->get_field_id($prefix.'widget_mr').'" name="'.$this->get_field_name($prefix.'widget_mr').'" type="number" value="'.esc_attr($instance[$prefix.'widget_mr']).'" placeholder="0" /></div>
								<div class="col-md-3 pmbox"> <span>bottom</span> <input class="widefat" id="'.$this->get_field_id($prefix.'widget_mb').'" name="'.$this->get_field_name($prefix.'widget_mb').'" type="number" value="'.esc_attr($instance[$prefix.'widget_mb']).'" placeholder="0" /></div>
								<div class="col-md-3 pmbox"> <span>left</span> <input class="widefat" id="'.$this->get_field_id($prefix.'widget_ml').'" name="'.$this->get_field_name($prefix.'widget_ml').'" type="number" value="'.esc_attr($instance[$prefix.'widget_ml']).'" placeholder="0" /></div>
							</div>
						</div>

					</div>
				</div>


				<h4 class="row-title">'.__('Google Push Event Name', 'siteitsob').'  <span class="sitb-icon icon-cog icon-big"></span></h4>
				<div class="row-wrap">
					<div class="admin-row">
						<div class="col-md-12">
							<label for="'.$this->get_field_id($prefix.'event_name').'"><span class="label-wrap">'.__('Event Name', 'siteitsob').'</span>
							<input class="widefat" id="'.$this->get_field_id($prefix.'event_name').'" name="'.$this->get_field_name($prefix.'event_name').'" type="text" value="'.esc_attr($instance[$prefix.'event_name']).'" />
							</label>
						</div>
					</div>
				</div>

			</div>
			<div class="col-md-4">

				<h4 class="row-title">'.__('Show / Hide Form Fields', 'siteitsob').'  <span class="sitb-icon icon-cog icon-big"></span></h4>
				<div class="row-wrap">
					<div class="admin-row">
						'.$this->show_hide_fields($instance, $prefix).'
					</div>
				</div>

            </div>
            <div class="col-md-3">

                <h4 class="row-title">'.__('System On Module Checkboxes', 'siteitsob').'  <span class="sitb-icon icon-cog icon-big"></span></h4>
                <div class="row-wrap">
                    <div class="admin-row">
                        <div class="col-md-12">
                            <label for="'.$this->get_field_id($prefix.'som_list').'"><span class="label-wrap">'.__('Search for products', 'siteitsob').':</span>
                                <select class="widefat select2posts" multiple="multiple" id="'.$this->get_field_id($prefix.'som_list').'" name="'.$this->get_field_name($prefix.'som_list').'">
                                    '.$this->select2_select($instance[$prefix.'som_list']).'
                                </select>
                            </label>
                        </div>
                    </div>
                </div>

                <h4 class="row-title">'.__('Projected Quantities Radio Buttons', 'siteitsob').'  <span class="sitb-icon icon-cog icon-big"></span></h4>
                <div class="row-wrap">
                    <div class="admin-row">
                        <div class="col-md-12">
                            <label for="'.$this->get_field_id($prefix.'quantities').'"><span class="label-wrap">'.__('Each Range in a seperate line', 'siteitsob').':</span>
								<textarea maxlength="2000" name="'.$this->get_field_name($prefix.'quantities').'" id="'.$this->get_field_id($prefix.'quantities').'" class="widefat" rows="5" placeholder="'.$quantitiesPlaceholder.'">'.esc_attr($instance[$prefix.'quantities']).'</textarea>
                            </label>
                        </div>
                    </div>
				</div>

                <h4 class="row-title">'.__('Operating Systems', 'siteitsob').'  <span class="sitb-icon icon-cog icon-big"></span></h4>
                <div class="row-wrap">
                    <div class="admin-row">
                        <div class="col-md-12">
                            <label for="'.$this->get_field_id($prefix.'os').'"><span class="label-wrap">'.__('Each Range in a seperate line', 'siteitsob').':</span>
								<textarea maxlength="2000" name="'.$this->get_field_name($prefix.'os').'" id="'.$this->get_field_id($prefix.'os').'" class="widefat" rows="5" placeholder="'.$osPlaceholder.'">'.esc_attr($instance[$prefix.'os']).'</textarea>
                            </label>
                        </div>
                    </div>
				</div>

			</div>
			<div class="col-md-2">
				<div class="admin-row">
				
					<h4 class="row-title">'.__('Settings', 'siteitsob').'  <span class="sitb-icon icon-cog icon-big"></span></h4>
					<div class="row-wrap">
						<div class="admin-row">
							<div class="col-md-12">
								<label for="'.$this->get_field_id($prefix.'email_subject').'"><span class="label-wrap">'.__('Email Subject', 'siteitsob').':</span>
									<input class="widefat" id="'.$this->get_field_id($prefix.'email_subject').'" name="'.$this->get_field_name($prefix.'email_subject').'" type="text" value="'.esc_attr($instance[$prefix.'email_subject']).'" />
								</label>
							</div>
							<div class="col-md-12">
								<label for="'.$this->get_field_id($prefix.'email_to').'"><span class="label-wrap">'.__('Email Seperated by commans', 'siteitsob').':</span>
									<textarea maxlength="2000" name="'.$this->get_field_name($prefix.'email_to').'" id="'.$this->get_field_id($prefix.'email_to').'" class="widefat" rows="3" placeholder="For Example: alon@gmail.com, dani@siteit.co.il">'.esc_attr($instance[$prefix.'email_to']).'</textarea>
								</label>
							</div>
							<div class="col-md-12">
								<label for="'.$this->get_field_id($prefix.'thanks_page').'"><span class="label-wrap">'.__('Pick thanks page', 'siteitsob').':</span>
									<select class="widefat select2posts" id="'.$this->get_field_id($prefix.'thanks_page').'" name="'.$this->get_field_name($prefix.'thanks_page').'">
										'.$this->select2_select($instance[$prefix.'thanks_page']).'
									</select>
								</label>
							</div>
						</div>
					</div>
				
					<h4 class="row-title">'.__('Label Settings', 'siteitsob').'  <span class="sitb-icon icon-cog icon-big"></span></h4>
					<div class="row-wrap">
						<div class="admin-row">

							<div class="col-md-12">
								<label for="'.$this->get_field_id($prefix.'floating_labels').'">
									<input type="checkbox" name="'.$this->get_field_name($prefix.'floating_labels').'" id="'.$this->get_field_id($prefix.'floating_labels').'" '.checked( $instance[ $prefix.'floating_labels' ], 'on', false ).'> '.__('Use Floating Labels', THEME_NAME).'
								</label>
							</div>
						</div>
					</div>
				
					<h4 class="row-title">'.__('Lead Source', 'siteitsob').'  <span class="sitb-icon icon-cog icon-big"></span></h4>
					<div class="row-wrap">
						<div class="admin-row">

							<div class="col-md-12">
								<label for="'.$this->get_field_id($prefix.'lead_source').'"><span class="label-wrap">'.__('Lead Source', 'siteitsob').':</span>
									<input class="widefat" id="'.$this->get_field_id($prefix.'lead_source').'" name="'.$this->get_field_name($prefix.'lead_source').'" type="text" value="'. $leadsource .'" />
								</label>
							</div>
						</div>
					</div>
				
					<h4 class="row-title">'.__('ReCaptcha API Key', 'siteitsob').'  <span class="sitb-icon icon-cog icon-big"></span></h4>
					<div class="row-wrap">
						<div class="admin-row">

							<div class="col-md-12">
								<label for="'.$this->get_field_id($prefix.'recaptcha_key').'"><span class="label-wrap">'.__('ReCaptcha Key', 'siteitsob').':</span>
									<input class="widefat" id="'.$this->get_field_id($prefix.'recaptcha_key').'" name="'.$this->get_field_name($prefix.'recaptcha_key').'" type="text" value="'.esc_attr($instance[$prefix.'recaptcha_key']).'" />
								</label>
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
		jQuery(document).ready(function($) {
			$.fn.extend({
				select2_sortable: function(){
					var select = $(this);
					$(select).select2({
						width: "100%",
						createTag: function(params) {
							return undefined;
						},
						ajax: {
								url: ajaxurl, // AJAX URL is predefined in WordPress admin
								dataType: "json",
								delay: 250, // delay in ms while typing when to perform a AJAX search
								data: function (params) {
									return {
										q: params.term, // search query
										action: "sgetposts" // AJAX action for admin-ajax.php
									};
								},
								processResults: function( data ) {
								var options = [];
								if ( data ) {
				
									// data is the array of arrays, and each of them contains ID and the Label of the option
									$.each( data, function( index, text ) { // do not forget that "index" is just auto incremented value
										options.push( { id: text[0], text: text[1]  } );
									});
				
								}
								return {
									results: options
								};
							},
							cache: true
						},
						minimumInputLength: 3 // the minimum of symbols to input before perform a search
					});
					var ul = $(select).next(".select2-container").first("ul.select2-selection__rendered");
					ul.sortable({
						placeholder : "ui-state-highlight",
						forcePlaceholderSize: true,
						items       : "li:not(.select2-search__field)",
						tolerance   : "pointer",
						stop 		: function() {
							$( $(ul).find(".select2-selection__choice").get().reverse() ).each(function() {
								console.log(this);
								var title = $(this).attr("title");
								var option = $(select).find( "option:contains(" + title + ")" );
								console.log(option);
								$(select).prepend(option);
							});
						}
					});
				}
			});


			$(".select2posts").each(function(){
				$(this).select2_sortable();
			})


			$(".sgColorPicker").wpColorPicker({defaultColor: false,});
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
		$result 			= '';
		$before_widget 		= '';
		$after_widget  		= '';
		$fullForm 			= '';
		$picked_fields		= array();
		$form_css  			= array();
		$form_fields  		= array();
		$form_end_fields  	= array();
        $prefix = '';


		// FLOATING LABELS?
		if( $instance[$prefix.'floating_labels'] == 'on' ) {$fltLbls = 'floating-labels';} else {$fltLbls = '';}


		// GET REQUIRES VALUES
		$requiredFields 	= array();
		$reqKeys 			= $this->showHideFieldsArr('required_');

		foreach($reqKeys as $rkey) {
			if( $instance[$prefix.$rkey] == 'on' ) {
				$requiredFields[ str_replace('required_', '', $rkey) ] = $instance[$prefix.$rkey];
			}
		}
		$requiredFields = implode(",", array_keys($requiredFields));


		// COUNTRY LIST HANDLER FOR SELECT FIELD
		$cnSelect  = '<option value="">'.__('Country', THEME_NAME).'</option>';
		$countries = get_field('quote_product_country_select', 'option');
		$countries = preg_split("/\\r\\n|\\r|\\n/", $countries);

		foreach($countries as $country) {
			$cnSelect .= '<option value="'.str_replace( ' ', '-', strtolower($country) ).'">'.$country.'</option>';
		}



		// OPERATING SYSTEMS CHECKBOXES
		if($instance[$prefix.'os']) {
			$os 	= array();
			$osArr 	= preg_split('/\r\n|\r|\n/', $instance[$prefix.'os']);
			foreach($osArr as $osys) {
				$os[] = '<li><label for="'.$osys.'"><input type="checkbox" name="Operating_System__c" id="'.$osys.'" value="'.$osys.'"> '.$osys.'</label></li>';
			}
		}
 

		if( $instance[$prefix.'show_first_name'] != 'no' ) { $form_fields[__('first_name', THEME_NAME)]  = '<input type="text" name="first_name" id="first_name" class="form-control" placeholder="'.( $fltLbls ? '' : __('First Name', THEME_NAME) ).'" value="">'; }
        if( $instance[$prefix.'show_last_name'] != 'no' ) { $form_fields[__('last_name', THEME_NAME)] = '<input type="text" name="last_name" id="last_name" class="form-control" placeholder="'.( $fltLbls ? '' : __('Last Name', THEME_NAME) ).'" value="">'; }
		if( $instance[$prefix.'show_email'] != 'no' ) { $form_fields[__('email', THEME_NAME)] = '<input type="text" name="email" id="email" class="form-control" placeholder="'.( $fltLbls ? '' : __('Email', THEME_NAME) ).'" value="">'; }
		if( $instance[$prefix.'show_company'] != 'no' ) { $form_fields[__('company', THEME_NAME)] = '<input type="text" name="company" id="company" class="form-control" placeholder="'.( $fltLbls ? '' : __('Company', THEME_NAME) ).'" value="">'; }
		if( $instance[$prefix.'show_country'] != 'no' ) { $form_fields[__('country', THEME_NAME)] = '<select name="country" id="country" class="form-control">'.$cnSelect.'</select>'; }
		if( $instance[$prefix.'show_phone'] != 'no' ) { $form_fields[__('phone', THEME_NAME)] = '<input type="text" id="phone" class="form-control" placeholder="'.( $fltLbls ? '' : __('Phone', THEME_NAME) ).'" value="">'; }
 
		if( $instance[$prefix.'show_Operating_System__c'] != 'no') {$form_fields['operating_systems'] = '<ul class="list-inline p0 form-group">'.implode(' ', $os).'</ul>';}
		if( $instance[$prefix.'show_note'] != 'no' ) { $form_end_fields['note'] = '<textarea maxlength="2000" id="note" cols="30" rows="10" class="form-control" placeholder="'.( $fltLbls ? '' : __('Note', THEME_NAME) ).'"></textarea>'; }
		if( $instance[$prefix.'show_agreement_checkbox'] != 'no' ) { $form_end_fields['agreement_checkbox'] = '<input type="checkbox" id="agreement_checkbox" name="agreement" value="' . date('Y-m-d\TH:i:s\Z') . '"> <label for="agreement_checkbox">' . esc_attr__('I agree to the Variscite ', THEME_NAME) .' <a href="'. get_site_url() .'/privacy-policy/" target="_blank">' . __('Privacy Policy</a>', THEME_NAME) .'</label></div>'; }
		if( !empty($instance[$prefix.'show_captachResponse']) && $instance[$prefix.'show_captachResponse'] != 'no' ) { $form_end_fields['captcha'] = '<div id="widgetFormCaptcha"></div> <label for="captachResponse"><input type="hidden" id="captachResponse" value=""> <span class="dnone">Captach</span></label>'; }
		if( $instance[$prefix.'show_System__c'] != 'no') {$picked_fields[] = 'xxx';}


		// BUILD THE BEGGINING FOR THE FORM
		foreach($form_fields as $key => $fld) {

			if( $key == 'operating_systems' ) { $fieldWidth = $instance[$prefix.'width_Operating_System__c']; }
			else { $fieldWidth = $instance[$prefix.'width_'.$key]; }

			$label = '';

			$fullForm .=  '
			<div class="col-md-6 field-box form-group field-'.$key.' '.$fltLbls.' '.$fieldWidth.'">
				<div class="field-wrap">
					<div class="row">
						<div class="col-md-5"><label for="'.__($key, THEME_NAME).'">'.ucfirst(str_replace('_', ' ', $key)).'</label></div>
						<div class="col-md-7">'.$fld.'</div>
					</div>
				</div>
			</div>
			';
		}

		
		// // BUILD SOM LIST
		if( !empty($instance[$prefix.'som_list']) ) {
			$som_list = array();
			foreach($instance[$prefix.'som_list'] as $item) {
				$sname = get_field('vrs_specs_short_pname', $item); if(!$sname) {$sname = get_the_title($item);}
				$som_list[] = '<div class="form-group"> <label for="product-'.$item.'"> <input type="checkbox" name="System__c" id="product-'.$item.'" value="'.get_the_title($item).'"> '.$sname.'</label></div>';
			}

			$fullForm .= '
			<div class="som-list form-group '.($instance[$prefix.'width_System__c'] ? $instance[$prefix.'width_System__c'] : '').'">
				<div class="row">
					<div class="col-md-5"><label for="som-list-box">'.__('System on Module', THEME_NAME).'</label></div>
					<div class="col-md-7" id="som-list-box">'.implode(' ', $som_list).'</div>
				</div>
			</div>
			';
		}


		// BUILD QUANTITIES SELECT
		if( !empty($instance[$prefix.'quantities']) ) {
			$quanSelect 	= array();
			$quantities 	= preg_split('/\r\n|\r|\n/', $instance[$prefix.'quantities']);
			$quanSelect[] 	= '<option value="">Projected Quantities</option>';

			foreach($quantities as $quantity) {
				$quanSelect[] = '<option value="'.$quantity.'">'.$quantity.'</option>';
			}

			$fullForm .= '
			<div class="quantities-select form-group '.($instance[$prefix.'width_Operating_System__c'] ? $instance[$prefix.'width_Operating_System__c'] : '').'">
				<div class="row">
					<div class="col-md-5"><label for="Projected_Quantities__c">'.__('Projected Quantities', THEME_NAME).'</label></div>
					<div class="col-md-7"><select name="Projected_Quantities__c" id="Projected_Quantities__c" class="form-control">'.implode(' ', $quanSelect).'</select></div>
				</div>
			</div>
			';
		}


		// BUILD THE END FOR THE FORM
		foreach($form_end_fields as $key => $fld) {
//			if($key == 'captcha') {$key = 'captachResponse';}
			if($key !== 'agreement_checkbox') {
				$fullForm .=  '
				<div class="field-box form-group field-'.$key.' '.$fltLbls.' '.($instance[$prefix.'width_'.$key] ? $instance[$prefix.'width_'.$key] : '').'">
					<div class="field-wrap">
					'.($key == 'note' ? '<div class="col-md-5"><label for="note">'.__('Note', THEME_NAME).'</label></div>' : '').'					
						'.$fld.'
					</div>
				</div>
				';
			}
			else {
				$fullForm .=  '
				<div class="field-box form-group col-md-12 field-'.$key.'">
					<div class="field-wrap-transparent">
						'.$fld.'
					</div>
				</div>
				';
			}
		}


		// COMBINE CSS VALUES
		if($instance[$prefix.'border_width']) {$form_css[] = 'border-width: '.$instance[$prefix.'border_width'].'px;';}
		if($instance[$prefix.'border_color']) {$form_css[] = 'border-color: '.$instance[$prefix.'border_color'].';';}
		if($instance[$prefix.'border_width'] && $instance[$prefix.'border_color']) {$form_css[] = 'border-style: solid;';}
		if($instance[$prefix.'background_color'] && $instance[$prefix.'background_color'] != '#000000') {$form_css[] = 'background: '.$instance[$prefix.'background_color'].';';}


		// PADDING & MARGIN
		$form_css[] = $this->build_margpad_array($instance[$prefix.'widget_pt'], $instance[$prefix.'widget_pr'], $instance[$prefix.'widget_pb'], $instance[$prefix.'widget_pl'], 'padding');
		$form_css[] = $this->build_margpad_array($instance[$prefix.'widget_mt'], $instance[$prefix.'widget_mr'], $instance[$prefix.'widget_mb'], $instance[$prefix.'widget_ml'], 'margin');
		
		$leadsourcelang = 'en';
		if(ICL_LANGUAGE_CODE == 'de') {
			$leadsourcelang = 'de ';
		}
		else if(ICL_LANGUAGE_CODE == 'it') {
            $leadsourcelang = 'it ';
        }
		if((strpos($instance[$prefix.'lead_source'],$leadsourcelang))!==FALSE ){
			$leadsource = $instance[$prefix.'lead_source'];
		} elseif($leadsourcelang !== 'en') {
			$leadsource = $leadsourcelang . $instance[$prefix.'lead_source'];
		} else {
			$leadsource = $instance[$prefix.'lead_source'];
		}

        $result = '
        <div id="quoteFormWidget" class="quote-form '.$instance[$prefix.'widget_classes'].'"  style="'.( !empty($form_css) ? implode(' ', $form_css) : '' ).'">
			<input type="hidden" id="curl" value="'.get_permalink(get_the_ID()).'">
			
			<input type="hidden" id="email_to" value="'.$instance[$prefix.'email_to'].'">
			<input type="hidden" id="email_subject" value="'.$instance[$prefix.'email_subject'].'">
			<input type="hidden" id="thanks" value="'.get_permalink($instance[$prefix.'thanks_page']).'">
			<input type="hidden" id="required" value="'.$requiredFields.'">
			<input type="hidden" id="lead_source" value="'.  $leadsource .'">
			<input type="hidden" id="event_name" value="'.$instance[$prefix.'event_name'].'">

			<!--=== ADWORDS FIELDS ===-->
			<input type="hidden" id="Campaign_medium__c" value="">
			<input type="hidden" id="Campaign_source__c" value="">
			<input type="hidden" id="Campaign_content__c" value="">
			<input type="hidden" id="Campaign_term__c" value="">
            <input type="hidden" id="Page_url__c" value="">
			<input type="hidden" id="Paid_Campaign_Name__c" value="">
			<input type="hidden" id="GA_id__c" value="">
			<!--=== ADWORDS FIELDS ===-->

			<div class="form-inner">
				<div class="row">
					'.$fullForm.'
				</div>

				<div class="submit-box">
					<div class="row">
						<div class="col-sm-6"><div class="notice"></div></div>
						<div class="col-sm-6 text-right"><input type="submit" name="submit" class="btn btn-warning btn-lg btn-arrow-01 submitQuoteWidgetRequest" value="'.__('Send', THEME_NAME).'"></div>
					</div>
				</div>
				
			</div>
		</div>
		
		<script type="text/javascript">
		jQuery(document).ready(function($) {
			$(".floating-labels input, .floating-labels textarea").focus(function(){
				$(this).parents(".floating-labels").addClass("active");
			}).blur(function(){
				var ival = $(this).val();
				if(ival.length < 1) {
					$(this).parents(".floating-labels").removeClass("active");
				}
			})
		});
		</script>';

		echo $before_widget.$result.$after_widget;

	}
 
}
//add_action( 'widgets_init', create_function('', 'return register_widget("sgSfQuoteFormWidget");') );
add_action( 'widgets_init', function () {
    return register_widget('sgSfQuoteFormWidget');
}, 1 );
?>