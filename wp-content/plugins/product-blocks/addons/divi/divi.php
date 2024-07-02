<?php
add_action( 'et_builder_ready', 'wopb_productx_template_divi_modules' );

function wopb_productx_template_divi_modules() {
	
	if ( ! class_exists( 'ET_Builder_Module' ) ) { return; }

	class ProductX_Template_Module extends ET_Builder_Module {

		public $slug       = 'wopb_productx_template';
		public $vb_support = 'partial';
		
		function init() {
			$this->name			= esc_html__( 'ProductX Template', 'product-blocks' );
			$this->icon_path	= plugin_dir_path( __FILE__ ) . 'icon.svg';
		}
	
		function get_fields() {
			return array(
				'templates' => array(
					'label'			=> esc_html__( 'Select Your Template', 'product-blocks' ),
					'type'			=> 'select',
					'options'		=> wopb_function()->get_all_lists('wopb_templates', 'none'),
					'default'		=> 'none',
					'description'	=> esc_html__( 'Pick a Template from your saved ones. Or create a template from: <strong><i>Dashboard > ProductX > Saved Templates</i></strong>', 'product-blocks' ),
				)
			);
		}
	
		function render( $attrs, $render_slug, $content = null ) {
			$templates = $this->props['templates'];
			
			$output = '';
			$content = '';
			$body_class = get_body_class();
			if ( $templates && $templates != 'none' ) {
				$args = array( 'p' => $templates, 'post_type' => 'wopb_templates');
				$the_query = new \WP_Query($args);
				if ($the_query->have_posts()) {
					ob_start();
					if (in_array('et-fb', $body_class)) {
						echo wopb_function()->set_css_style($templates, true); //phpcs:disable WordPress.Security.EscapeOutput.OutputNotEscaped
					} else {
						wopb_function()->register_scripts_common();
						wopb_function()->set_css_style($templates);
					}
				    while ($the_query->have_posts()) {
				        $the_query->the_post();
				        the_content();
				    }
				    wp_reset_postdata();
					$content = ob_get_clean();
				}
			} else {
				if (in_array('et-fb', $body_class)) {
					$content = '<p style="text-align:center;">'.
                        /* translators: %s: is no of template */
                        sprintf( esc_html__( 'Pick a Template from your saved ones. Or create a template from: %s.' , 'product-blocks' ) . ' ',
                            '<strong><i>' . esc_html( 'Dashboard > ProductX > Saved Templates', 'product-blocks' ) . '</i></strong>' ).'</p>';
				}
			}

			// Render module content
			$output = sprintf(
				'<div class="wopb-shortcode" data-postid="%1$s">%2$s</div>',
				esc_html($templates),
				et_sanitized_previously($content)
			);
			
			return $this->_render_module_wrapper( $output, $render_slug );
		}
	}

	new ProductX_Template_Module;
}
