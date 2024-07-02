<?php
/**
 * REST API Action.
 * 
 * @package WOPB\Dashboard
 * @since 3.0.0
 */
namespace WOPB;


defined('ABSPATH') || exit;

/**
 * Dashboard class.
 */
class Dashboard {
    
    /**
	 * Setup class.
	 */
    public function __construct() {
        add_action( 'rest_api_init', array($this, 'wopb_register_route') );
        if(!get_option('edd_wopb_license_price_id') && wopb_function()->is_lc_active()) {
            $this->license_save_action(['edd_wopb_license_key' => get_option('edd_wopb_license_key')]);
        }
    }

    /**
	 * REST API Action
	 * @return NULL
	 */
    public function wopb_register_route() {
        register_rest_route(
			'wopb/v2', 
			'/dashborad/',
			array(
				array(
					'methods'  => 'POST', 
					'callback' => array( $this, 'get_dashboard_callback'),
					'permission_callback' => function () {
						return current_user_can( 'manage_options' );
					},
					'args' => array()
				)
			)
        );
        register_rest_route(
			'wopb/v2',
			'/get_all_settings/',
			array(
				array(
					'methods'  => 'POST',
					'callback' => array($this, 'get_all_settings'),
					'permission_callback' => function () {
						return current_user_can('manage_options');
					},
					'args' => array()
				)
			)
		);
        register_rest_route(
			'wopb/v2',
			'/addon_block_action/',
			array(
				array(
					'methods'  => 'POST',
					'callback' => array($this, 'addon_block_action'),
					'permission_callback' => function () {
						return current_user_can('manage_options');
					},
					'args' => array()
				)
			)
		);
        register_rest_route(
			'wopb/v2',
			'/save_plugin_settings/',
			array(
				array(
					'methods'  => 'POST',
					'callback' => array($this, 'save_plugin_settings'),
					'permission_callback' => function () {
						return current_user_can('manage_options');
					},
					'args' => array()
				)
			)
		);
        register_rest_route(
			'wopb/v2',
			'/premade_wishlist_save/',
			array(
				array(
					'methods'  => 'POST',
					'callback' => array($this, 'premade_wishlist_save'),
					'permission_callback' => function () {
						return current_user_can('edit_posts');
					},
					'args' => array()
				)
			)
		);
    }

    /**
	 * Save and get premade_wishlist_save
     * 
     * @since 3.0.0
     * @param STRING
	 * @return ARRAY | Inserted Post Url 
	 */
    public function premade_wishlist_save($server) {
        $post = $server->get_params();
        $id = sanitize_text_field($post['id']);
        $action = sanitize_text_field($post['action']);
        $wishListArr = get_option('wopb_premade_wishlist', []);

        if ($id && sanitize_text_field($post['type']) != 'fetchData') {
            if($action == 'remove') {
                $index = array_search($id, $wishListArr);
                if ($index !== false) {
                    unset($wishListArr[$index]);
                }
            } else {
                if (!in_array($id, $wishListArr)) {
                    array_push($wishListArr,  $id );
                }
            }
            update_option('wopb_premade_wishlist', $wishListArr);
        }
        return rest_ensure_response([
            'success' => true, 
            'message' => $action == 'remove' ? __('Item has been removed from wishlist.', 'product-blocks') : __('Item added to wishlist.', 'product-blocks'),
            'wishListArr' => wp_json_encode($wishListArr)]
        );
    }

    /**
	 * Save Settings of Option Panel
     * 
     * @since 3.0.0
	 * @return NULL
	 */
    public function save_plugin_settings($server) {
        $post = $server->get_params();
        $data = wopb_function()->recursive_sanitize_text_field($post['settings']);
        if (count($data) > 0) {
            foreach ($data as $key => $val) {
                wopb_function()->set_setting($key, $val);
            }
        }
        return rest_ensure_response([
            'success' => true, 
            'message' => __('You have successfully saved the settings data.', 'product-blocks') , 
            'wishListArr' => wp_json_encode($data),
            'settings' => wopb_function()->get_setting()]);
    }

    /**
	 * Save addon / blocks on/off data 
     * 
     * @since 3.0.0
     * @param STRING
	 * @return ARRAY | Inserted Post Url 
	 */
    public function addon_block_action($server) {
        $post = $server->get_params();
        $addon_name = sanitize_text_field($post['key']);
        $addon_value = sanitize_text_field($post['value']);
        if ($addon_name) {
            $addon_data = wopb_function()->get_setting();
            $addon_data[$addon_name] = $addon_value;
            $GLOBALS['wopb_settings'][$addon_name] = $addon_value;
            update_option('wopb_options', $addon_data);
        }
        return array( 
            'success' => true, 
            'message' => ($addon_value == 'true' || $addon_value == 'false') ? ($addon_value == 'true' ? __('The addon has been enabled.', 'product-blocks') : __('The addon has been disabled.', 'product-blocks') ) : ($addon_value == 'yes' ? __('The block has been enabled.', 'product-blocks') : __('The block has been disabled.', 'product-blocks')) 
        );
    }

    /**
	 * All Settings
     * 
     * @since 3.0.0
	 * @return ARRAy
	 */
    public function get_all_settings() {
        return rest_ensure_response(['success' => true, 'settings' => wopb_function()->get_setting()]);
    }

    /**
	 * Dashboard & Saved Template & Custom Font Actions 
     * 
     * @since 3.0.0
     * @param STRING
	 * @return ARRAY | Inserted Post Url 
	 */
    public function get_dashboard_callback($server) {
        $post = $server->get_params();
        if (isset($post['type'])) {
            $type = sanitize_text_field( $post['type'] );

            switch ( $type ) {

                case 'saved_templates':
                    $post_per_page = 10;
                    $data = [];
                    $args = array(
                        'post_type' => $post['pType'],
                        'post_status' => array('publish', 'draft'),
                        'posts_per_page' => $post_per_page,
                        'paged' => $post['pages']
                    );

                    if (isset($post['search'])) {
                        $args['paged'] = 1;
                        $args['s'] = $post['search'];
                    }

                    $the_query = new \WP_Query( $args );
                    if ( $the_query->have_posts() ) {
                        while ( $the_query->have_posts() ) {
                            $the_query->the_post();
                            $final = [
                                'id' => get_the_ID(),
                                'title' => get_the_title(),
                                'date' => get_the_modified_date('Y/m/d h:i a'),
                                'status' => get_post_status(),
                                'edit' => get_edit_post_link()
                            ];
        
                            if ($post['pType'] == 'wopb_custom_font') {
                                $final = array_merge($final ,['woff' => false,'woff2' => false,'ttf' => false,'svg' => false,'eot' => false]);
                                $settings = get_post_meta( get_the_ID(), '__font_settings', true );
                                foreach ($settings as $key => $value) {
                                    if ($value['ttf']) { $final['ttf'] = true; }
                                    if ($value['svg']) { $final['svg'] = true; }
                                    if ($value['eot']) { $final['eot'] = true; }
                                    if ($value['woff']) { $final['woff'] = true; }
                                    if ($value['woff2']) { $final['woff2'] = true; }
                                }
                                $final['font_settings'] = $settings;
                            }
                            $data[] = $final;
                        }
                    }
                    wp_reset_postdata();
                    return array(
                        'success' => true, 
                        'data' => $data,
                        'new' => ($post['pType'] == 'wopb_custom_font' ? admin_url('post-new.php?post_type=wopb_custom_font') : admin_url('post-new.php?post_type=wopb_templates')),
                        'found' => $the_query->found_posts,
                        'pages' => $the_query->max_num_pages
                    );
                break;

                
                case 'action_draft':
                case 'action_publish':
                    if (isset($post['ids']) && is_array($post['ids'])) {
                        foreach ($post['ids'] as $id) {
                            wp_update_post(array(
                                'ID' => sanitize_text_field( $id ),
                                'post_status' => str_replace('action_', '',$type)
                            ));
                        }
                        return array(
                            'success' => true, 
                            'message' => __('Status changed for selected items.', 'product-blocks')
                        );
                    }
                break;

                case 'license_action':
                   return $this->license_save_action($post);
                break;

                case 'action_delete':
                    if (isset($post['ids']) && is_array($post['ids'])) {
                        foreach ($post['ids'] as $id) {
                            wp_delete_post( $id, true); 
                        }
                    }
                    return array(
                        'success' => true, 
                        'message' => __('The selected item is deleted.', 'product-blocks')
                    );
                break;
                case 'support_data':
                    $user_info = get_userdata( get_current_user_id() );
                    $name = $user_info->first_name . ($user_info->last_name ? ' ' . $user_info->last_name : '');
                    return array(
                        'success' => true,
                        'data' => array(
                            'name' => $name ? $name : $user_info->user_login,
                            'email' => $user_info->user_email
                        )
                    );

                case 'support_action':
                    $api_params = array(
                        'user_name' => sanitize_text_field($post['name']),
                        'user_email' => sanitize_email($post['email']),
                        'subject' => sanitize_text_field($post['subject']),
                        'desc' => sanitize_textarea_field($post['desc']),
                    );
                    $response = wp_remote_get(
                        'https://wpxpo.com/wp-json/v2/support_mail',
                        array(
                            'method' => 'POST',
                            'timeout' => 120,
                            'body' =>  $api_params
                        )
                    );
                    $response_data = json_decode($response['body']);
                    $success = ( isset($response_data->success) && $response_data->success ) ? true : false;

                    return array(
                        'success' => $success,
                        'message' => $success ? __('New Support Ticket has been Created.', 'ultimate-post') : __('New Support Ticket is not Created Due to Some Issues.', 'ultimate-post')
                    );
                    break;
                
                case 'helloBarAction':
                    set_transient( 'wopb_helloBar', 'hide', 1296000);
                    return array(
                        'success' => true, 
                        'message' => __('Notice is removed.', 'product-blocks')
                    );
                break;

                default:
                    # code...
                    break;
            }
        }
    }

    private function license_save_action ($post) {
        $message = '';
        $requirePlugin= false;

        if ( isset($post['edd_wopb_license_key']) && function_exists('wopb_pro_function') ) {
            $is_success = false;
            $license = trim( sanitize_text_field( $post['edd_wopb_license_key'] ) );

            if ($license && $license != '******************') {
                update_option( 'edd_wopb_license_key', $license);
                $api_params = array(
                    'edd_action' => 'activate_license',
                    'license'    => $license,
                    'item_id'    => 1263,
                    'url'        => home_url()
                );

                $response = wp_remote_post(
                    'https://account.wpxpo.com',
                    array(
                        'timeout' => 15,
                        'sslverify' => false,
                        'body' => $api_params
                    )
                );

                if ( is_wp_error( $response ) || 200 !== wp_remote_retrieve_response_code( $response ) ) {
                    $message =  ( is_wp_error( $response ) && ! empty( $response->get_error_message() ) ) ? $response->get_error_message() : __('An error occurred, please try again.', 'product-blocks');
                } else {
                    $license_data = json_decode( wp_remote_retrieve_body( $response ) );
                    if ( false === $license_data->success ) {
                        update_option( 'edd_wopb_license_key', '');
                        switch( $license_data->error ) {
                            case 'expired' :
                                $message = sprintf(
                                /* translators: %s: is date */
                                    __('Your license key expired on %s.', 'product-blocks'),
                                    date_i18n( get_option( 'date_format' ), strtotime( $license_data->expires, current_time( 'timestamp' ) ) )
                                );
                                break;
                            case 'revoked' :
                                $message = __('Your license key has been disabled.', 'product-blocks');
                                break;
                            case 'missing' :
                                $message = __('Invalid license.', 'product-blocks');
                                break;
                            case 'invalid' :
                            case 'site_inactive':
                                $message = __( 'Your license is not active for this URL.', 'product-blocks' );
                                break;
                            case 'item_name_mismatch':
                                $message = __( 'This appears to be an invalid license key.', 'product-blocks' );
                                break;
                            case 'no_activations_left':
                                $message = __( 'Your license key has reached its activation limit.', 'product-blocks' );
                                break;
                            default :
                                $message = __( 'An error occurred, please try again.', 'product-blocks' );
                                break;
                        }
                    } else {
                        $message = __('Your license key has been updated.', 'product-blocks');
                        $is_success = true;
                    }
                    update_option( 'edd_wopb_license_status', $license_data->license );
                    update_option( 'edd_wopb_license_expire', $license_data->expires );
                    update_option( 'edd_wopb_license_limit', $license_data->license_limit );
                    update_option( 'edd_wopb_license_activations_left', $license_data->activations_left );
                    update_option( 'edd_wopb_license_price_id', $license_data->price_id );
                    update_option( 'edd_wopb_license_data', (array) $license_data);

                }
            } else {
                $message = __( 'Invalid license.', 'product-blocks' );
            }
        } else {
            $message = __( 'Invalid license.', 'product-blocks' );
            if (!function_exists('wopb_pro_function')) {
                $requirePlugin = true;
                $message = __( 'Install & Acivate ProductX Pro plugin.', 'product-blocks' );
            }
        }
        return array('success' => $is_success, 'message' => $message, 'requirePlugin' => $requirePlugin);
    }
}