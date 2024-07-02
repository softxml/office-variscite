<?php

class ProductXElement extends OxyEl {

    function init() {
        // Do some initial things here.
    }

    function afterInit() {
        $this->removeApplyParamsButton();
    }

    function name() {
        return __('ProductX Templates', 'product-blocks');
    }
    
    function slug() {
        return "productx-templates";
    }

    function icon() {
        return WOPB_URL.'addons/oxygen/icon.svg';
    }

    function button_place() {
        // return "interactive";
    }

    function button_priority() {
        return 9;
    }

    
    function render($options, $defaults, $content) {
		$body_class = get_body_class();
		$templates = $options['templates'];
		
		if ( $templates ) {
			wopb_function()->register_scripts_common();
			echo wopb_function()->set_css_style($templates, true); //phpcs:ignore
			$args = array( 'p' => $templates, 'post_type' => 'wopb_templates' );
			$the_query = new \WP_Query($args);
			if ( $the_query->have_posts() ) {
				while ($the_query->have_posts()) {
					$the_query->the_post();
					the_content();
				}
				wp_reset_postdata();
			}
		} else {
			if ( isset($_GET['action']) && strpos( sanitize_text_field($_GET['action']), 'oxy_render_oxy') !== false ) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
                /* translators: %s: is no of template */
				echo '<p style="text-align:center;">'.sprintf( esc_html__( 'Pick a Template from your saved ones. Or create a template from: %s.' , 'product-blocks' ) . ' ', '<strong><i>' . esc_html( 'Dashboard > ProductX > Saved Templates', 'product-blocks' ) . '</i></strong>' ).'</p>';
			}
		}
    }

    function controls() {
		$this->addOptionControl(
            array(
                'type' => 'dropdown',
                'name' => esc_html__( 'Select Your Template', 'product-blocks' ),
                'slug' => 'templates',
                'default' => ''
            )
        )->setValue(wopb_function()->get_all_lists('wopb_templates'))->rebuildElementOnChange();
    }

    function defaultCSS() {
		wopb_function()->register_scripts_common();
    }
    
}

new ProductXElement();
