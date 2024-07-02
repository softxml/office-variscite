<?php
defined( 'ABSPATH' ) || exit;

class ProductX_Widget extends \Elementor\Widget_Base {

    public function get_name() {
        return 'productx-blocks';
    }

    public function get_title() {
        return __( 'ProductX Template', 'product-blocks' );
    }

    public function get_icon() {
        return 'eicon-posts-grid';
    }

    public function get_categories() {
        return [ 'general' ];
    }

    protected function register_controls() {
        $this->start_controls_section(
            'content_section',
            [
                'label' => __( 'Settings', 'product-blocks' ),
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            ]
        );
        $this->add_control(
			'saved_template',
			[
				'label' => __( 'Saved Template', 'product-blocks' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'options' => wopb_function()->get_all_lists('wopb_templates'),
			]
		);
        $this->add_control(
			'edit_template',
			[
				'type' => \Elementor\Controls_Manager::RAW_HTML,
				'raw' => '<a href="'.admin_url('edit.php?post_type=wopb_templates').'" style="color:#fff; background-color:#0c0d0e; padding:10px 20px; border-radius:4px; display:inline-block;" target="_blank"><span style="color:#fff; font-size:12px; width:12px; height:12px;" class="dashicons dashicons-edit"></span> '.__('Edit This Template', 'product-blocks').'</a>',
			]
		);
        $this->add_control(
			'add_new_template',
			[
				'type' => \Elementor\Controls_Manager::RAW_HTML,
				'raw' => '<a href="'.admin_url('post-new.php?post_type=wopb_templates').'" style="color:#fff; background-color:#0c0d0e; padding:10px 20px; border-radius:4px; display:inline-block;" target="_blank"><span style="color:#fff; font-size:12px; width:12px; height:12px;" class="dashicons dashicons-plus-alt2"></span> '.__('Add New Template', 'product-blocks').'</a>',
			]
		);
        $this->end_controls_section();
    }


    protected function render() {
        $settings = $this->get_settings_for_display();
        $body_class = get_body_class();
        $id = $settings['saved_template'];

        if ($id) {
            $this->set_inline_css($id);
            echo '<div class="wopb-shortcode" data-postid="'.esc_attr($id).'">';
                $args = array( 'p' => $id, 'post_type' => 'wopb_templates');
                $the_query = new \WP_Query($args);
                if ($the_query->have_posts()) {
                    while ($the_query->have_posts()) {
                        $the_query->the_post();
                        the_content();
                    }
                    wp_reset_postdata();
                }
            echo '</div>';
        } else {
            if (isset($_GET['action']) && $_GET['action'] == 'elementor') { // phpcs:ignore WordPress.Security.NonceVerification.Recommended
                /* translators: %s: is no of template */
                echo '<p style="text-align:center;">'.sprintf( esc_html__( 'Pick a Template from your saved ones. Or create a template from: %s.' , 'product-blocks' ) . ' ', '<strong><i>' . esc_html( 'Dashboard > ProductX > Saved Templates', 'product-blocks' ) . '</i></strong>' ).'</p>';
            }
        }
    }

    public function set_inline_css($id) {
        if (isset($_GET['action']) || isset($_POST['action'])) { // phpcs:ignore WordPress.Security.NonceVerification.Recommended,WordPress.Security.NonceVerification.Missing
            echo wopb_function()->set_css_style($id, true); //phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
        } else {
            wopb_function()->set_css_style($id);
        }
    }
}