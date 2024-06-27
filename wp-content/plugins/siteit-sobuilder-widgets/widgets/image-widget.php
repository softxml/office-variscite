<?php
class sgImageWidget extends WP_Widget {


	
	// DEFINE THE WIDGET
	function __construct() {
		$widget_ops = array(
			'classname' 	=> 'sg_image_widg', 
			'description'	=> __('Custom Image widget by SITEIT', 'siteitsob')
		);

		parent::__construct('sgImageWidget', __('Image Widget', 'siteitsob'), $widget_ops);

        add_action('admin_enqueue_scripts', array($this, 'upload_scripts'));	
	}
	
	
    /* Upload the Javascripts for the media uploader */
    public function upload_scripts() {
		wp_enqueue_script('jquery');
		wp_enqueue_script('media-upload');
		wp_enqueue_script('thickbox');
		wp_register_script('upload_media_widget', plugin_dir_url( __FILE__ ) . '../lib/backend/upload-media.js', array('jquery','media-upload','thickbox'));
		wp_enqueue_script('upload_media_widget');
		wp_enqueue_style('thickbox');
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
		
		include(plugin_dir_path( __FILE__ ).'../functions/cssAnimationArray.php');

		if($type == 'yesno') {
			$type = array('yes'=>__('Yes'), 'no'=>__('No'));
		}
		elseif($type == 'borderColors') {
			$type = array('fullbDark'=>__('Black', 'siteitsob'), 'fullbCC'=>__('Grey', 'siteitsob'), 'fullbED'=>__('Light Grey', 'siteitsob'), 'fullbRed'=>__('Red', 'siteitsob'), 'fullbBlue'=>__('Blue', 'siteitsob'), 'fullbGreen'=>__('Green', 'siteitsob'));
		}
		elseif($type == 'linkTargets') {
			$type = array('_blank'=>__('New Window'), '_self'=>__('Same Window'));
		}
		elseif($type == 'borderadius') {
			$type = array('brad3'=>__('3px', 'siteitsob'), 'brad5'=>__('5px', 'siteitsob'), 'brad7' => __('7px', 'siteitsob'), 'brad10' => __('10px', 'siteitsob'), 'brad25' => __('25px', 'siteitsob'), 'brad50' => __('50px', 'siteitsob'), 'brad50p' => __('50%', 'siteitsob'));
		}
		elseif($type == 'talign') {
			$type = array('text-left' => __('Left', 'siteitsob'), 'text-center' => __('Center', 'siteitsob'), 'text-right' => __('Right', 'siteitsob'));
		}
		elseif($type == 'valigns') {
			$type = array('top' => __('Top', 'siteitsob'), 'middle' => __('Middle', 'siteitsob'), 'bottom' => __('Bottom', 'siteitsob'));
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
			'image_url',
			'image_alt',
			'image_link',
			'link_target',
			'image_nocls',
			'image_pt',
			'image_pr',
			'image_pb',
			'image_pl',
			'image_mt',
			'image_mr', 
			'image_mb',
			'image_ml',
			'image_border',
			'image_bradius',
			'image_bcolor',
			'image_align',
			'image_valign',
			'image_animation',
			'image_animation_duration',
			'image_animation_delay',
			'image_classes',

			'use_tooltip',
			'tooltip_text',
			'use_popover',
			'popover_text',

			'widget_classes',
			'use_mobile',
		);
	}


	function form_fileds_looper($instance) {
        $prefix = '';

        // if image saved 
        if($instance[$prefix.'image_url']) {$previewImg = $instance[$prefix.'image_url'];} else {$previewImg = 'http://placehold.it/450x300/eeeeee/212121/&text=Placeholder';}

		// rtl fixes
		if(is_rtl()) {$floatDir = 'left';} else {$floatDir = 'right';}


		// IF INNER HEIGHT IS NEEDED
		if($image_valign = 'middle') {$dnoneVA = 'display: none;';} else {$dnoneVA = '';}



		$formFields = '
        <div class="admin-row">
			<div class="col-md-4">

				<h4 class="row-title">'.__('Image Info', 'siteitsob').'  <span class="sitb-icon icon-cog icon-big"></span></h4>
                <div class="row-wrap">
					<div class="admin-row">
						<div class="col-md-8">
							<label for="'.$this->get_field_name($prefix.'image_url').'"><span class="label-wrap">'. __('Upload Your Image:', 'siteitsob').'</span></label>
							<input name="'.$this->get_field_name($prefix.'image_url').'" id="'.$this->get_field_id($prefix.'image_url').'" class="widefat imageUploader" type="text" size="36"  value="'.esc_url($instance[$prefix.'image_url']).'" style="width: 62%;" /> 
							<input data-input="#'.$this->get_field_id($prefix.'image_url').'" class="upload_image_button button button-primary" type="button" value="'.__('Upload Image', 'siteitsob').'" />
						</div>
						<div class="col-md-4">
							<label for="'.$this->get_field_id($prefix.'image_alt').'"><span class="label-wrap">'.__('Image Alt', 'siteitsob').':</span>
							<input class="widefat" id="'.$this->get_field_id($prefix.'image_alt').'" name="'.$this->get_field_name($prefix.'image_alt').'" type="text" value="'.esc_attr($instance[$prefix.'image_alt']).'" placeholder="'.__('Enter Image Alt', 'siteitsob').'" />
							</label>
						</div>
						<div class="col-md-4 mb0">
							<label for="'.$this->get_field_id($prefix.'image_link').'"><span class="label-wrap">'.__('Image Link <small class="diblock">(Optional)</small>', 'siteitsob').':</span>
								<input class="widefat" id="'.$this->get_field_id($prefix.'image_link').'" name="'.$this->get_field_name($prefix.'image_link').'" type="text" value="'.esc_attr($instance[$prefix.'image_link']).'" />
							</label>
						</div>
						<div class="col-md-4">
							<label for="'.$this->get_field_id($prefix.'link_target').'"><span class="label-wrap">'.__('Link Target', 'siteitsob').':</span>
								<select class="widefat" id="'.$this->get_field_id($prefix.'link_target').'" name="'.$this->get_field_name($prefix.'link_target').'">
									'.$this->multi_select($instance[$prefix.'link_target'], __('Default'), 'linkTargets').'
								</select>
							</label>
						</div>						

                        <div class="col-md-4">
							<label for="'.$this->get_field_id($prefix.'image_nocls').'"><span class="label-wrap">'.__('Responsive Image?', 'siteitsob').':</span>
								<select class="widefat" id="'.$this->get_field_id($prefix.'image_nocls').'" name="'.$this->get_field_name($prefix.'image_nocls').'">
									'.$this->multi_select($instance[$prefix.'image_nocls'], '', 'yesno').'
								</select>
							</label>
						</div>
						<div class="col-md-6">
							<label for="'.$this->get_field_id($prefix.'image_pt').'"><span class="label-wrap">'.__('Padding', 'siteitsob').':</span></label>
							<div class="admin-row small-padding mb0">
								<div class="col-md-3"><input class="widefat" id="'.$this->get_field_id($prefix.'image_pt').'" name="'.$this->get_field_name($prefix.'image_pt').'" type="number" value="'.esc_attr($instance[$prefix.'image_pt']).'" placeholder="0" /></div>
								<div class="col-md-3"><input class="widefat" id="'.$this->get_field_id($prefix.'image_pr').'" name="'.$this->get_field_name($prefix.'image_pr').'" type="number" value="'.esc_attr($instance[$prefix.'image_pr']).'" placeholder="0" /></div>
								<div class="col-md-3"><input class="widefat" id="'.$this->get_field_id($prefix.'image_pb').'" name="'.$this->get_field_name($prefix.'image_pb').'" type="number" value="'.esc_attr($instance[$prefix.'image_pb']).'" placeholder="0" /></div>
								<div class="col-md-3"><input class="widefat" id="'.$this->get_field_id($prefix.'image_pl').'" name="'.$this->get_field_name($prefix.'image_pl').'" type="number" value="'.esc_attr($instance[$prefix.'image_pl']).'" placeholder="0" /></div>
							</div>	
						</div>
						<div class="col-md-6">
							<label for="'.$this->get_field_id($prefix.'image_mt').'"><span class="label-wrap">'.__('Margin', 'siteitsob').':</span></label>
							<div class="admin-row small-padding mb0">
								<div class="col-md-3"><input class="widefat" id="'.$this->get_field_id($prefix.'image_mt').'" name="'.$this->get_field_name($prefix.'image_mt').'" type="number" value="'.esc_attr($instance[$prefix.'image_mt']).'" placeholder="0" /></div>
								<div class="col-md-3"><input class="widefat" id="'.$this->get_field_id($prefix.'image_mr').'" name="'.$this->get_field_name($prefix.'image_mr').'" type="number" value="'.esc_attr($instance[$prefix.'image_mr']).'" placeholder="0" /></div>
								<div class="col-md-3"><input class="widefat" id="'.$this->get_field_id($prefix.'image_mb').'" name="'.$this->get_field_name($prefix.'image_mb').'" type="number" value="'.esc_attr($instance[$prefix.'image_mb']).'" placeholder="0" /></div>
								<div class="col-md-3"><input class="widefat" id="'.$this->get_field_id($prefix.'image_ml').'" name="'.$this->get_field_name($prefix.'image_ml').'" type="number" value="'.esc_attr($instance[$prefix.'image_ml']).'" placeholder="0" /></div>
							</div>	
						</div>						

					</div>
				</div>

				<h4 class="row-title">'.__('Image Tooltip / Popover (Optional)', 'siteitsob').'  <span class="sitb-icon icon-pazel icon-big"></span></h4>
				<div class="row-wrap">
					<div class="admin-row">

						<div class="col-md-12">
							<p class="alert-warning">'.__('Please Note: Using a popover would overwrite the tooltip feature.').'</p>
						</div>

						<div class="col-md-4">
							<br>
							<label for="'.$this->get_field_id($prefix.'use_tooltip').'">
								<input type="checkbox" name="'.$this->get_field_name($prefix.'use_tooltip').'" id="'.$this->get_field_id($prefix.'use_tooltip').'" value="yes" '.($instance[$prefix.'use_tooltip'] == 'yes' ? 'checked' : '').' >
								<span class="label-wrap">'.__('Use a tooltip', 'siteitsob').'</span>
							</label>
						</div>
						<div class="col-md-8">
							<label for="'.$this->get_field_id($prefix.'tooltip_text').'"><span class="label-wrap">'.__('Tooltip Text', 'siteitsob').':</span>
								<input class="widefat" id="'.$this->get_field_id($prefix.'tooltip_text').'" name="'.$this->get_field_name($prefix.'tooltip_text').'" type="text" value="'.esc_attr($instance[$prefix.'tooltip_text']).'" />
							</label>
						</div>

						<div class="col-md-4">
							<br>
							<label for="'.$this->get_field_id($prefix.'use_popover').'">
								<input type="checkbox" name="'.$this->get_field_name($prefix.'use_popover').'" id="'.$this->get_field_id($prefix.'use_popover').'" value="yes" '.($instance[$prefix.'use_popover'] == 'yes' ? 'checked' : '').' >
								<span class="label-wrap">'.__('Use a popover', 'siteitsob').'</span>
							</label>
						</div>
						<div class="col-md-8">
							<label for="'.$this->get_field_id($prefix.'popover_text').'"><span class="label-wrap">'.__('Popover Text', 'siteitsob').':</span></label>
							<textarea class="widefat main_text" id="'.$this->get_field_id($prefix.'popover_text').'" name="'.$this->get_field_name($prefix.'popover_text').'" rows="5">'.esc_attr($instance[$prefix.'popover_text']).'</textarea>
						</div>

					</div>
				</div>


            </div>

			<div class="col-md-4">


				<h4 class="row-title">'.__('Image Styling', 'siteitsob').'  <span class="sitb-icon icon-settings2 icon-big"></span></h4>
				<div class="row-wrap">
					<div class="admin-row">
						<div class="col-md-4">
							<label for="'.$this->get_field_id($prefix.'image_border').'"><span class="label-wrap">'.$labelfix.__('Image Border', 'siteitsob').':</span>
								<select class="widefat" id="'.$this->get_field_id($prefix.'image_border').'" name="'.$this->get_field_name($prefix.'image_border').'">
									'.$this->multi_select($instance[$prefix.'image_border'], __('Default', 'siteitsob'), 'yesno').'
								</select>	
							</label>
						</div>
						<div class="col-md-4">
							<label for="'.$this->get_field_id($prefix.'image_bradius').'"><span class="label-wrap">'.$labelfix.__('Border Radius', 'siteitsob').':</span>
								<select class="widefat" id="'.$this->get_field_id($prefix.'image_bradius').'" name="'.$this->get_field_name($prefix.'image_bradius').'">
									'.$this->multi_select($instance[$prefix.'image_bradius'], __('No Radius', 'siteitsob'), 'borderadius').'
								</select>	
							</label>						
						</div>
						<div class="col-md-4">
							<label for="'.$this->get_field_id($prefix.'image_bcolor').'"><span class="label-wrap">'.$labelfix.__('Border Color', 'siteitsob').':</span>
								<input class="widefat colorpicker" id="'.$this->get_field_id($prefix.'image_bcolor').'" type="color" name="'.$this->get_field_name($prefix.'image_bcolor').'" value="'.(esc_attr($instance[$prefix.'image_bcolor']) ? esc_attr($instance[$prefix.'image_bcolor']) : '#212121').'" />
							</label>
						</div>
						<div class="col-md-4">
							<label for="'.$this->get_field_id($prefix.'image_align').'"><span class="label-wrap">'.__('Image Align', 'siteitsob').':</span>
							<select class="widefat" id="'.$this->get_field_id($prefix.'image_align').'" name="'.$this->get_field_name($prefix.'image_align').'">
								'.$this->multi_select($instance[$prefix.'image_align'], __('Default', 'siteitsob'), 'talign').'
							</select>	
							</label>	
						</div>
						<div class="col-md-4">
							<label for="'.$this->get_field_id($prefix.'image_valign').'"><span class="label-wrap">'.__('Vertical Align', 'siteitsob').':</span>
								<select class="widefat imgValign" id="'.$this->get_field_id($prefix.'image_valign').'" name="'.$this->get_field_name($prefix.'image_valign').'">
									'.$this->multi_select($instance[$prefix.'image_valign'], __('Default', 'siteitsob'), 'valigns').'
								</select>
							</label>
						</div>

					</div>
				</div>

				<h4 class="row-title">'.__('Image Animation', 'siteitsob').'  <span class="sitb-icon icon-pazel icon-big"></span></h4>
				<div class="row-wrap">
					<div class="admin-row">

						<div class="col-md-4">
							<label for="'.$this->get_field_id($prefix.'image_animation').'"><span class="label-wrap">'.__('Animation Type', 'siteitsob').'</span>:
								<select class="widefat" id="'.$this->get_field_id($prefix.'image_animation').'" name="'.$this->get_field_name($prefix.'image_animation').'">
									'.$this->multi_select($instance[$prefix.'image_animation'], '', 'animations').'
								</select>
							</label>						
						</div>
						<div class="col-md-4">
							<label for="'.$this->get_field_id($prefix.'image_animation_duration').'"><span class="label-wrap">'.__('Animation Duration', 'siteitsob').':</span>
								<div class="range-counter counter-'.$this->get_field_id($prefix.'image_animation_duration').'" style="margin-top: 0;">'.($instance[$prefix.'image_animation_duration'] ? esc_attr($instance[$prefix.'image_animation_duration']) : 1).'</div>
								<input class="widefat range-input" data-counter="counter-'.$this->get_field_id($prefix.'image_animation_duration').'" id="'.$this->get_field_id($prefix.'image_animation_duration').'" name="'.$this->get_field_name($prefix.'image_animation_duration').'" type="range" value="'.($instance[$prefix.'image_animation_duration'] ? esc_attr($instance[$prefix.'image_animation_duration']) : 1).'" min="0.5" max="10" step="0.5" />
							</label>
						</div>
						<div class="col-md-4">
							<label for="'.$this->get_field_id($prefix.'image_animation_delay').'"><span class="label-wrap">'.__('Animation Delay', 'siteitsob').':</span>
								<div class="range-counter counter-'.$this->get_field_id($prefix.'image_animation_delay').'">'.($instance[$prefix.'image_animation_delay'] ? esc_attr($instance[$prefix.'image_animation_delay']) : 0.25).'</div>
								<input class="widefat range-input" data-counter="counter-'.$this->get_field_id($prefix.'image_animation_delay').'" id="'.$this->get_field_id($prefix.'image_animation_delay').'" name="'.$this->get_field_name($prefix.'image_animation_delay').'" type="range" value="'.($instance[$prefix.'image_animation_delay'] ? esc_attr($instance[$prefix.'image_animation_delay']) : 0.5).'" min="0" max="10" step="0.25" />
							</label>
						</div>
					</div>
				</div>

			</div>

            <div class="col-md-4">
				<h4 class="row-title">'.__('Image Preview', 'siteitsob').'  <span class="sitb-icon icon-image icon-big"></span></h4>
                <div class="row-wrap">
                    <div class="admin-row">
                        <div class="col-md-12">
                            <img src="'.$previewImg.'" alt="" id="previewImage" style="height: auto; max-width: 100%;">
						</div>
						
                        <div class="col-md-6">
                            <label for="'.$this->get_field_id($prefix.'image_classes').'">'.$labelfix.__('Image Classes', 'siteitsob').':
                            <input class="widefat" id="'.$this->get_field_id($prefix.'image_classes').'" name="'.$this->get_field_name($prefix.'image_classes').'" type="text" value="'.esc_attr($instance[$prefix.'image_classes']).'" />
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
        </div>


        <script>
        jQuery(function($){

            
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

			$(".imageUploader").live("change", function() {
				console.log($(this).val());
				 $("#previewImage").attr("src", $(this).val()); 
			}); 
 
			$(".imgValign").change(function() {
				 $(".verticalRequired").toggleClass("diblock", $(this).val() == "middle");
			});
		});
		</script>		';	
		
	}
 
 
	// SAVE FORM VALUES
	function update($new_instance, $old_instance) {

		$instance	= $old_instance;
		$fieldNames = $this->siteit_widget_fields();

		foreach($fieldNames as $field) {
			$instance[$field]			=	$new_instance[$field];
		}

		return $instance;
	}

	
	// DISPLAY THE WIDGET
	function widget($args, $instance) {
		extract($args, EXTR_SKIP);

		// BASIC SETTINGS
		$result					= '';
        $prefix = '';

		$before_widget 		= '<div class="singleWidget sitBuilderWidget sobImageWidget '.$instance[$prefix.'image_align'].' '.$instance[$prefix.'widget_classes'].'">';
		$after_widget 		= '</div>';


		$imgStyles 	= '';
		$imgClasses = array();
		$imgTitle 	= '';
		$imgContent	= '';


		if($instance[$prefix.'image_classes']) {$imgClasses[] .= $instance[$prefix.'image_classes'].' ';}
		if($instance[$prefix.'image_nocls'] == 'yes') {$imgClasses[] .= 'img-responsive';}
		if($instance[$prefix.'image_link']) {$astart = '<a href="'.$instance[$prefix.'image_link'].'" target="'.$instance[$prefix.'link_target'].'">'; $aend = '</a>';} else {$astart = ''; $aend = '';}
		if($instance[$prefix.'image_bradius']) {$imgClasses[] .= $instance[$prefix.'image_bradius'];}
		if($instance[$prefix.'image_border'] == 'yes') { $imgStyles 	.= 'border: 1px solid '.$instance[$prefix.'image_bcolor'].';'; }


		$imgStyles 	.= $this->build_margpad_array($instance[$prefix.'image_pt'], $instance[$prefix.'image_pr'], $instance[$prefix.'image_pb'], $instance[$prefix.'image_pl'], 'padding');
		$imgStyles 	.= $this->build_margpad_array($instance[$prefix.'image_mt'], $instance[$prefix.'image_mr'], $instance[$prefix.'image_mb'], $instance[$prefix.'image_ml'], 'margin');


		// TOOLTIP / POPOVER SETTINGS
		if( !empty($instance[$prefix.'use_popover']) && $instance[$prefix.'use_popover'] == 'yes') {
			$imgClasses[] = 'sitsobPopover';
			$imgTitle = $instance[$prefix.'image_alt'];
			$imgContent = str_replace('"', '\'', preg_replace( "/\r|\n/", "", $instance[$prefix.'popover_text'] ) );
		}
		elseif( !empty($instance[$prefix.'use_tooltip']) && $instance[$prefix.'use_tooltip'] == 'yes') {
			$imgClasses[] = 'sitsobTooltip';
			$imgTitle = $instance[$prefix.'tooltip_text'];
		}



		// BUILD ANIMATION
		if($instance[$prefix.'image_animation']) {
			$imgClasses 	.= ' wow '.$instance[$prefix.'image_animation'].' ';
			$animationData 	= 'data-wow-duration="'.$instance[$prefix.'image_animation_duration'].'s" data-wow-delay="'.$instance[$prefix.'image_animation_delay'].'s" ';
		} else {$animationData = '';}


		$result = $astart.'<img src="'.$instance[$prefix.'image_url'].'" alt="'.$instance[$prefix.'image_alt'].'" title="'.$imgTitle.'" '.($imgContent ? 'data-content="'.$imgContent.'"' : '').' class="'.implode(' ', $imgClasses).'" '.( !empty($imgStyles) ? 'style="'.$imgStyles.'"' : '').' '.$animationData.' /> '.$aend;
	

		echo $before_widget.$result.$after_widget;

	}
 
}
// add_action( 'widgets_init', create_function('', 'return register_widget("sgImageWidget");') );
add_action( 'widgets_init', function () {
    return register_widget('sgImageWidget');
}, 1 );
?>