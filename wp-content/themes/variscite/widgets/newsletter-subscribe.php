<?php
class sgNewletterSubscribe extends WP_Widget {


	// DEFINE THE WIDGET
	function __construct() {
		$widget_ops = array(
			'classname' 	=> 'sg_rsssubscribeblog_widg',
			'description'	=> __('MailChimp subscribe widget SITEIT', 'siteitsob')
		);

		parent::__construct('sgNewletterSubscribe', __('MailChimp NewsLetter Subscribe (SiteIT)', 'siteitsob'), $widget_ops);
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
		return $type;
	}



	function multi_select($value, $label, $type) {
		$data = '';
		
		$typeArr = $this->sgDataArr($type);
		
		if($label) {
			$data .= '<option value="">'.$label.'</option>';
		}
		
		foreach($typeArr as $key => $opt) {
            if(is_array($value) && in_array($key, $value)) {$selected = 'selected';}
			elseif($key == $value) {$selected = 'selected';}
            else {$selected = '';}
			$data .= '<option value="'.$key.'" '.$selected.'>'.$opt.'</option>';
		}
		
		return $data;
	}





	function siteit_widget_fields() {
		return array(
            'widget_title',
            'fname_lbl',
            'lname_lbl',
            'email_lbl',
            'agreement_lbl',
            'btn_lbl',
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
			<div class="col-md-12 col-xs-12">
				<h4 class="row-title">'.__('Widget Settings', 'siteitsob').'  <span class="sitb-icon icon-cog icon-big"></span></h4>
				<div class="row-wrap">
					<div class="admin-row">
						<div class="col-md-12 col-xs-6">
							<label for="'.$this->get_field_id($prefix.'widget_title').'">'.__('Widget Title', 'siteitsob').':
							<input class="widefat" id="'.$this->get_field_id($prefix.'widget_title').'" name="'.$this->get_field_name($prefix.'widget_title').'" type="text" value="'.esc_attr($instance[$prefix.'widget_title']).'" />
							</label>
						</div>
						<div class="col-md-6 col-xs-6">
							<label for="'.$this->get_field_id($prefix.'fname_lbl').'">'.__('First Name Label', 'siteitsob').':
							<input class="widefat" id="'.$this->get_field_id($prefix.'fname_lbl').'" name="'.$this->get_field_name($prefix.'fname_lbl').'" type="text" value="'.esc_attr($instance[$prefix.'fname_lbl']).'" />
							</label>
						</div>
						<div class="col-md-6 col-xs-6">
							<label for="'.$this->get_field_id($prefix.'lname_lbl').'">'.__('Last Name Label', 'siteitsob').':
							<input class="widefat" id="'.$this->get_field_id($prefix.'lname_lbl').'" name="'.$this->get_field_name($prefix.'lname_lbl').'" type="text" value="'.esc_attr($instance[$prefix.'lname_lbl']).'" />
							</label>
						</div>
						<div class="col-md-6 col-xs-6">
							<label for="'.$this->get_field_id($prefix.'email_lbl').'">'.__('Email Label', 'siteitsob').':
							<input class="widefat" id="'.$this->get_field_id($prefix.'email_lbl').'" name="'.$this->get_field_name($prefix.'email_lbl').'" type="text" value="'.esc_attr($instance[$prefix.'email_lbl']).'" />
							</label>
						</div>
						<div class="col-md-6 col-xs-6">
							<label for="'.$this->get_field_id($prefix.'btn_lbl').'">'.__('Button Label', 'siteitsob').':
							<input class="widefat" id="'.$this->get_field_id($prefix.'btn_lbl').'" name="'.$this->get_field_name($prefix.'btn_lbl').'" type="text" value="'.esc_attr($instance[$prefix.'btn_lbl']).'" />
							</label>
						</div>
						<div class="col-md-12 col-xs-6">
							<label for="'.$this->get_field_id($prefix.'widget_classes').'">'.__('Widget Classes', 'siteitsob').':
							<input class="widefat" id="'.$this->get_field_id($prefix.'widget_classes').'" name="'.$this->get_field_name($prefix.'widget_classes').'" type="text" value="'.esc_attr($instance[$prefix.'widget_classes']).'" placeholder="'.__('For example: mt0 mb0', 'siteitsob').'" />
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
        $prefix = '';


		$result = '
		<div id="mc_embed_signup" class="singleWidget sitBuilderWidget sitMailchimpSubscribeWidget newsletterSignUpFooter '.$instance[$prefix.'widget_classes'].'" >
			<form action="https://variscite.us15.list-manage.com/subscribe/post?u=f7f1ec26a1a4d314e5595e1a3&amp;id=a65e6c887f" method="post" id="mc-embedded-subscribe-form" name="mc-embedded-subscribe-form" class="validate" target="_blank" novalidate>
                <div id="mc_embed_signup_scroll">
                    <h3 class="widgettitle">'.$instance[$prefix.'widget_title'].'</h3>
                    <div class="row relative">
                        <div class="mc-field-group col-md-6 col-xs-6">
                            <div class="form-group">
                                <input type="text" value="" name="FNAME" class="form-control input-lg" id="mce-FNAME">
                                <label for="mce-FNAME">'.$instance[$prefix.'fname_lbl'].'</label>
                            </div>
                        </div>
                        <div class="mc-field-group col-md-6 col-xs-6">
						<div class="form-group">
                                <input type="text" value="" name="LNAME" class="form-control input-lg" id="mce-LNAME">
                                <label for="mce-LNAME">'.$instance[$prefix.'lname_lbl'].'</label>
                            </div>
                        </div>
                        <div class="mc-field-group col-md-12 col-xs-12">
						<div class="form-group">
                                <input type="email" value="" name="EMAIL" class="required email form-control input-lg" id="mce-EMAIL">
                                <label for="mce-EMAIL">'.$instance[$prefix.'email_lbl'].'</label>
                            </div>
                        </div>

                        <!-- real people should not fill this in and expect good things - do not remove this or risk form bot signups-->
                        <div style="position: absolute; left: -5000px;" aria-hidden="true"><input type="text" name="b_f7f1ec26a1a4d314e5595e1a3_a65e6c887f" tabindex="-1" value=""></div>

                        <div id="mce-responses" class="col-md-12">
                            <div class="response" id="mce-error-response" style="display:none"></div>
                            <div class="response" id="mce-success-response" style="display:none"></div>
						</div>
												<div class="mc-field-group col-md-6 col-xs-12">
													<div class="custom-agreement-checkbox">
                                <input type="checkbox" value="' . date('Y-m-d H:i:s') . '" name="PRIVACY" id="mce-AGREEMENT">
                                <label for="mce-AGREEMENT">' . esc_attr__('I agree to the Variscite', THEME_NAME) . ' <a href="https://wordpress-689526-3817782.cloudwaysapps.com/privacy-policy/" target="_blank">' . __('Privacy Policy</a>', THEME_NAME) . ' </label>
                            </div>
                        </div>
                        <div class="col-md-6 col-xs-12">
                            <input type="submit" value="'.$instance[$prefix.'btn_lbl'].'" name="subscribe" id="mc-embedded-subscribe" class="btn btn-warning btn-lg w100">
                        </div>
                    </div>

                </div>
            </form>
		</div>
		<script type="text/javascript" src="//s3.amazonaws.com/downloads.mailchimp.com/js/mc-validate.js"></script>
		<script type="text/javascript">
		(function($) {
			window.fnames = new Array();
			window.ftypes = new Array();
			fnames[0]="EMAIL";
			ftypes[0]="email";
			fnames[1]="FNAME";
			ftypes[1]="text";
			fnames[2]="LNAME";
			ftypes[2]="text";
		}(jQuery));
		
		var $mcj = jQuery.noConflict(true);
		</script>
		';
		
	
		echo $before_widget.$result.$after_widget;

	}
 
}
//add_action( 'widgets_init', create_function('', 'return register_widget("sgNewletterSubscribe");') );
add_action( 'widgets_init', function () {
    return register_widget('sgNewletterSubscribe');
}, 1 );
?>