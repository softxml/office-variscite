<?php
class sgCustomTextProductWidget extends WP_Widget {


    // DEFINE THE WIDGET
    function __construct() {
        $widget_ops = array(
            'classname' 	=> 'sg_customproduct_widget',
            'description'	=> __('Full controlled custom structure product widget', 'siteitsob')
        );

        parent::__construct('sgCustomTextProductWidget', __('Custom Product Widget (SiteIT)', 'siteitsob'), $widget_ops);
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



    function siteit_widget_fields() {

        $widget_fields = array(
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

            'product_image',
            'product_imgalt',
            'product_prepricelbl',
            'product_price',
            'product_size',
            'product_rmlbl',
            'product_id',
            'product_clink',

            'product_title',
            'product_desc',
            'product_icon_wifi',
            'product_icon_bluetooth',
            'product_icon_android',
            'product_icon_linux',
            'prop_tbl_lbl1',
            'prop_tbl_val1',
            'prop_tbl_lbl2',
            'prop_tbl_val2',
            'prop_tbl_lbl3',
            'prop_tbl_val3',

            'use_mobile',
        );


        return $widget_fields;
    }


    function form_fileds_looper($instance) {

        // rtl fixes
        if(is_rtl()) {$floatDir = 'left';} else {$floatDir = 'right';}
        $prefix 		= '';


        $formFields = '
		<div class="admin-row">
			<div class="col-md-4">

				<h4 class="row-title">'.__('General Widget Settings', 'siteitsob').'  <span class="sitb-icon icon-image icon-big"></span></h4>
				<div class="row-wrap">
					<div class="admin-row">
						<div class="col-md-12">
							<label for="'.$this->get_field_id($prefix.'widget_title').'"><span class="label-wrap">'.__('Widget Title (Not Public)', 'siteitsob').':</span>
							<input class="widefat" id="'.$this->get_field_id($prefix.'widget_title').'" name="'.$this->get_field_name($prefix.'widget_title').'" type="text" value="'.esc_attr($instance[$prefix.'widget_title']).'" />
							</label>
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

			</div>
            <div class="col-md-4">

                <h4 class="row-title">'.__('Product Image box settings', 'siteitsob').'  <span class="sitb-icon icon-cog icon-big"></span></h4>
                <div class="row-wrap">
					<div class="admin-row">
						<div class="col-md-8">
							<label for="'.$this->get_field_name($prefix.'product_image').'"><span class="label-wrap">'. __('Upload / Pick Product Image:', 'siteitsob').'</span></label> <br>
							<input name="'.$this->get_field_name($prefix.'product_image').'" id="'.$this->get_field_id($prefix.'product_image').'" class="widefat imageUploader" type="text" size="36"  value="'.esc_url($instance[$prefix.'product_image']).'" style="width: 75%;" /> 
							<input data-input="#'.$this->get_field_id($prefix.'product_image').'" class="upload_image_button button button-primary" type="button" value="'.__('Upload', 'siteitsob').'" />
						</div>
                        <div class="col-md-4">
							<label for="'.$this->get_field_id($prefix.'product_imgalt').'"><span class="label-wrap">'.__('Image Alt (Optional)', 'siteitsob').':</span>
								<input class="widefat" id="'.$this->get_field_id($prefix.'product_imgalt').'" name="'.$this->get_field_name($prefix.'product_imgalt').'" type="text" value="'.esc_attr($instance[$prefix.'product_imgalt']).'" />
							</label>
                        </div>
                        <div class="col-md-4">
							<label for="'.$this->get_field_id($prefix.'product_prepricelbl').'"><span class="label-wrap">'.__('Label Before Price', 'siteitsob').':</span>
								<input class="widefat" id="'.$this->get_field_id($prefix.'product_prepricelbl').'" name="'.$this->get_field_name($prefix.'product_prepricelbl').'" type="text" value="'.esc_attr($instance[$prefix.'product_prepricelbl']).'" />
							</label>
                        </div>
                        <div class="col-md-4">
							<label for="'.$this->get_field_id($prefix.'product_price').'"><span class="label-wrap">'.__('Price', 'siteitsob').':</span>
								<input class="widefat" id="'.$this->get_field_id($prefix.'product_price').'" name="'.$this->get_field_name($prefix.'product_price').'" type="text" value="'.esc_attr($instance[$prefix.'product_price']).'" />
							</label>
                        </div>
                        <div class="col-md-4">
							<label for="'.$this->get_field_id($prefix.'product_size').'"><span class="label-wrap">'.__('Measurements', 'siteitsob').':</span>
								<input class="widefat" id="'.$this->get_field_id($prefix.'product_size').'" name="'.$this->get_field_name($prefix.'product_size').'" type="text" value="'.esc_attr($instance[$prefix.'product_size']).'" />
							</label>
                        </div>
                        <div class="col-md-4">
							<label for="'.$this->get_field_id($prefix.'product_rmlbl').'"><span class="label-wrap">'.__('Button Label', 'siteitsob').':</span>
								<input class="widefat" id="'.$this->get_field_id($prefix.'product_rmlbl').'" name="'.$this->get_field_name($prefix.'product_rmlbl').'" type="text" value="'.( $instance[$prefix.'product_rmlbl'] ? esc_attr($instance[$prefix.'product_rmlbl']) : __('Product Info', THEME_NAME) ).'" />
							</label>
						</div>
                        <div class="col-md-4">
                            <label for="'.$this->get_field_id($prefix.'product_id').'"><span class="label-wrap">'.__('Link This Product', 'siteitsob').':</span>
                                <select class="widefat select2posts" id="'.$this->get_field_id($prefix.'product_id').'" name="'.$this->get_field_name($prefix.'product_id').'">
                                    '.$this->select2_select($instance[$prefix.'product_id']).'
                                </select>	
                            </label>
                        </div>
                        <div class="col-md-4">
                            <label for="'.$this->get_field_id($prefix.'product_clink').'"><span class="label-wrap">'.__('Custom Link', 'siteitsob').':</span>
								<input class="widefat" id="'.$this->get_field_id($prefix.'product_clink').'" name="'.$this->get_field_name($prefix.'product_clink').'" type="text" value="'.esc_attr($instance[$prefix.'product_clink']).'" />
                            </label>
                        </div>
                    </div>
                </div>

			</div>
			<div class="col-md-4">

				<h4 class="row-title">'.__('Product Information', 'siteitsob').'  <span class="sitb-icon icon-cog icon-big"></span></h4>
				<div class="row-wrap">
					<div class="admin-row small-padding">
						<div class="col-md-12">
							<label for="'.$this->get_field_id($prefix.'product_title').'"><span class="label-wrap">'.__('Product Title', 'siteitsob').':</span>
								<input class="widefat" id="'.$this->get_field_id($prefix.'product_title').'" name="'.$this->get_field_name($prefix.'product_title').'" type="text" value="'.esc_attr($instance[$prefix.'product_title']).'" />
							</label>
						</div>
						<div class="col-md-12">
							<label for="'.$this->get_field_id($prefix.'product_desc').'"><span class="label-wrap">'.__('Product Description', 'siteitsob').':</span></label>
							<textarea class="widefat" id="'.$this->get_field_id($prefix.'product_desc').'" name="'.$this->get_field_name($prefix.'product_desc').'" rows="5">'.esc_attr($instance[$prefix.'product_desc']).'</textarea>
						</div>
						<div class="col-md-12">
						    <label for="'.$this->get_field_id($prefix.'product_icon_wifi').'"><input type="checkbox" id="'.$this->get_field_id($prefix.'product_icon_wifi').'" name="'.$this->get_field_name($prefix.'product_icon_wifi').'" '.($instance[$prefix.'product_icon_wifi'] == 'on' ? 'checked' : '').' />'.__('Wi-Fi', 'siteitsob').'</label>
						    <label for="'.$this->get_field_id($prefix.'product_icon_bluetooth').'"><input type="checkbox" id="'.$this->get_field_id($prefix.'product_icon_bluetooth').'" name="'.$this->get_field_name($prefix.'product_icon_bluetooth').'" '.($instance[$prefix.'product_icon_bluetooth'] == 'on' ? 'checked' : '').' />'.__('Bluetooth', 'siteitsob').'</label>
						    <label for="'.$this->get_field_id($prefix.'product_icon_android').'"><input type="checkbox" id="'.$this->get_field_id($prefix.'product_icon_android').'" name="'.$this->get_field_name($prefix.'product_icon_android').'" '.($instance[$prefix.'product_icon_android'] == 'on' ? 'checked' : '').' />'.__('Android', 'siteitsob').'</label>
						    <label for="'.$this->get_field_id($prefix.'product_icon_linux').'"><input type="checkbox" id="'.$this->get_field_id($prefix.'product_icon_linux').'" name="'.$this->get_field_name($prefix.'product_icon_linux').'" '.($instance[$prefix.'product_icon_linux'] == 'on' ? 'checked' : '').' />'.__('Linux', 'siteitsob').'</label>
                        </div>
						<div class="col-md-12">
							<span class="label-wrap">'.__('Properties Table', 'siteitsob').':</span>
						</div>

						<div class="col-md-4"> <input class="widefat" id="'.$this->get_field_id($prefix.'prop_tbl_lbl1').'" name="'.$this->get_field_name($prefix.'prop_tbl_lbl1').'" type="text" value="'.esc_attr($instance[$prefix.'prop_tbl_lbl1']).'" placeholder="'.__('1st Label', THEME_NAME).'" /> </div>
						<div class="col-md-8"> <input class="widefat" id="'.$this->get_field_id($prefix.'prop_tbl_val1').'" name="'.$this->get_field_name($prefix.'prop_tbl_val1').'" type="text" value="'.esc_attr($instance[$prefix.'prop_tbl_val1']).'" placeholder="'.__('1st Value', THEME_NAME).'" /> </div>

						<div class="col-md-4"> <input class="widefat" id="'.$this->get_field_id($prefix.'prop_tbl_lbl2').'" name="'.$this->get_field_name($prefix.'prop_tbl_lbl2').'" type="text" value="'.esc_attr($instance[$prefix.'prop_tbl_lbl2']).'" placeholder="'.__('2nd Label', THEME_NAME).'" /> </div>
						<div class="col-md-8"> <input class="widefat" id="'.$this->get_field_id($prefix.'prop_tbl_val2').'" name="'.$this->get_field_name($prefix.'prop_tbl_val2').'" type="text" value="'.esc_attr($instance[$prefix.'prop_tbl_val2']).'" placeholder="'.__('2nd Value', THEME_NAME).'" /> </div>

						<div class="col-md-4"> <input class="widefat" id="'.$this->get_field_id($prefix.'prop_tbl_lbl3').'" name="'.$this->get_field_name($prefix.'prop_tbl_lbl3').'" type="text" value="'.esc_attr($instance[$prefix.'prop_tbl_lbl3']).'" placeholder="'.__('3rd Label', THEME_NAME).'" /> </div>
						<div class="col-md-8"> <input class="widefat" id="'.$this->get_field_id($prefix.'prop_tbl_val3').'" name="'.$this->get_field_name($prefix.'prop_tbl_val3').'" type="text" value="'.esc_attr($instance[$prefix.'prop_tbl_val3']).'" placeholder="'.__('3rd Value', THEME_NAME).'" /> </div>

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
        $widget_css 		= array();
        $prefix = '';


        // PADDING & MARGIN
        $widget_css[] = $this->build_margpad_array($instance[$prefix.'widget_pt'], $instance[$prefix.'widget_pr'], $instance[$prefix.'widget_pb'], $instance[$prefix.'widget_pl'], 'padding');
        $widget_css[] = $this->build_margpad_array($instance[$prefix.'widget_mt'], $instance[$prefix.'widget_mr'], $instance[$prefix.'widget_mb'], $instance[$prefix.'widget_ml'], 'margin');


        // BUILD PRODUCT LINK
        if($instance[$prefix.'product_clink']) { $plink = $instance[$prefix.'product_clink']; }
        elseif($instance[$prefix.'product_id']) { $plink = get_permalink($instance[$prefix.'product_id']); }

        if( !isset($instance[$prefix.'product_icon_wifi']) ) {$instance[$prefix.'product_icon_wifi'] = '';}
        if( !isset($instance[$prefix.'product_icon_bluetooth']) ) {$instance[$prefix.'product_icon_bluetooth'] = '';}
        if( !isset($instance[$prefix.'product_icon_android']) ) {$instance[$prefix.'product_icon_android'] = '';}
        if( !isset($instance[$prefix.'product_icon_linux']) ) {$instance[$prefix.'product_icon_linux'] = '';}



        // BUILD PROPS TABLE
        $i 		= 1;
        $props 	= '';
        while($i < 4) {
            if( $instance[$prefix.'prop_tbl_lbl'.$i] || $instance[$prefix.'prop_tbl_val'.$i]) {
                $props .= '<tr><td>'.($instance[$prefix.'prop_tbl_lbl'.$i] ? '<strong>'.$instance[$prefix.'prop_tbl_lbl'.$i].' : </strong>' : '').' '.$instance[$prefix.'prop_tbl_val'.$i].'</td></tr>'; }
            $i++;
        }


        // SVG ARROW
        $arrow = '
		<svg xmlns="http://www.w3.org/2000/svg" width="8" height="12" viewBox="0 0 8 12">
			<path fill="none" fill-rule="evenodd" stroke="#0d0d0d" stroke-linejoin="round" stroke-width="2" d="M1 1l6 5-6 5"/>
		</svg>
		';



        $result = '
		<div id="'.$widget_id.'" class="singlewidget customPProoductWidget '.$instance[$prefix.'widget_classes'].'" '.( !empty($widget_css) ? 'style="'.implode(' ', $widget_css).'"' : '' ).' >
			<div class="inner">

				<div class="pimg-box">
                    <a href="'.$plink.'"><img src="'.$instance[$prefix.'product_image'].'" alt="'.($instance[$prefix.'product_imgalt'] ? $instance[$prefix.'product_imgalt'] : '').'" class="img-responsive"></a>
                    <ul class="pmeta-info">
                        '.($instance[$prefix.'product_prepricelbl'] ? '<li class="preprice">'.$instance[$prefix.'product_prepricelbl'].'</li>' : '').'
                        '.($instance[$prefix.'product_price'] ? '<li class="price">'.$instance[$prefix.'product_price'].'</li>' : '').'
                        '.($instance[$prefix.'product_size'] ? '<li class="psize">'.$instance[$prefix.'product_size'].'</li>' : '').'
                    </ul>
				</div>

				<div class="pinfo-box">
					<h3><a href="'.$plink.'">'.$instance[$prefix.'product_title'].'</a></h3>					
					<table class="pprops">'.$props.'</table>
					<div class="pdesc">'.$instance[$prefix.'product_desc'].'</div>
					<ul class="pbadges">
					    '.($instance[$prefix.'product_icon_wifi'] == 'on' ? '<li class="pbadge-wifi"><span class="sr-only">'.$instance[$prefix.'product_icon_wifi'].'</span></li>' : '').'
					    '.($instance[$prefix.'product_icon_bluetooth'] == 'on' ? '<li class="pbadge-bt"><span class="sr-only">'.$instance[$prefix.'product_icon_bluetooth'].'</span></li>' : '').'
					    '.($instance[$prefix.'product_icon_android'] == 'on' ? '<li class="pbadge-android"><span class="sr-only">'.$instance[$prefix.'product_icon_android'].'</span></li>' : '').'
					    '.($instance[$prefix.'product_icon_linux'] == 'on' ? '<li class="pbadge-linux"><span class="sr-only">'.$instance[$prefix.'product_icon_linux'].'</span></li>' : '').'
                    </ul>
				</div>
				
				<div class="readmore-box">
					<a href="'.$plink.'" class="btn btn-default rmbtn"><span>'.__('Product Info', THEME_NAME).'</span> <i class="fa fa-arrow-right" aria-hidden="true"></i></a>
				</div>

			</div>
		</div>
        ';

        echo $before_widget.$result.$after_widget;

    }

}
//add_action( 'widgets_init', create_function('', 'return register_widget("sgCustomTextProductWidget");') );
add_action( 'widgets_init', function () {
    return register_widget('sgCustomTextProductWidget');
}, 1 );
?>