<?php
/*** DEFINE GLOBAL VARS ***/
@define('THEME_NAME', 'vari');
@define('THEME_PREF', THEME_NAME.'_');
@define('THEME_PATH', get_template_directory());
@define('BASE_URL', get_template_directory_uri());
@define('REL_URL', dirname( __FILE__ ));
@define('IMG_URL', get_template_directory_uri().'/images');



/*********************************************
 ** 	LOAD FILES IN FOLDER
 *********************************************/
function sagive_load_directory($folder_name){
    $theme_root 	= THEME_PATH;
    $files_array = glob("$theme_root/$folder_name/*.php");

    foreach ($files_array as $filename) {
        if ( file_exists( $filename ) ) {
            include($filename);
        }
    }
}


/*********************************************
 ** 	INCLUDE STUFF
 *********************************************/
sagive_load_directory( 'functions' );
sagive_load_directory( 'widgets' );
sagive_load_directory( 'ajax' );




/*********************************************
 ** 	INCLUDE IN ADMIN
 *********************************************/
if(is_admin()) {
    sagive_load_directory( 'functions/acf-ext' );
    sagive_load_directory( 'functions/in-admin' );
}

//function som_page_rewrite($query){
//	$tax = get_queried_object();
//	$tax_id = $tax->term_id;
//	if($tax_id == 43){
//		$query->set( 'post_type', 'page' );
//		$query->set( 'ID', '1418' );
//	}
//}
//add_filter('pre_get_posts', 'som_page_rewrite');

//add_filter('request', function(array $query_vars) {
//	// do nothing in wp-admin
//	if(is_admin()) {
//		return $query_vars;
//	}
//	// if the query is for a category
//	if(isset($query_vars['products']) && $query_vars['products'] == 'system-on-module-som') {
//		// save the slug
//		$pagename = $query_vars['products'];
//		// completely replace the query with a page query
//		$query_vars = array('pagename' => "$pagename");
//	}
//	return $query_vars;
//});

//function change_404_slug($link, $post) {
//	if (is_admin())
//		return $link;
//
//	if (is_404()) {
//		$link = str_replace( '404-2', '404', false);
//		// $link 	= $link['scheme'].'://www.'.$link['host'].$path;
//	}
//	return $link;
//}
//add_filter('post_type_link', 'change_404_slug', 10, 2);

// Disable JSON-LD Yoast SEO schema
function remove_yoast_json_schema($data) {
    $data = array();
    return $data;
}
add_filter('wpseo_json_ld_output', 'remove_yoast_json_schema', 10, 1);

if (isset($_GET['noadminbar'])) {
    add_filter('show_admin_bar', '__return_false');
}

// Add www to all REST API requests to resolve the CORS error returned from Chrome
function variscite_update_rest_api_url($url, $path, $blog_id, $scheme) {
    $url_parts = parse_url($url);

    if(strpos($url_parts['host'], 'www.') == false) {
        $url = $url_parts['scheme'] . '://www.' . $url_parts['host'] . $url_parts['path'];
    }

    return $url;
}
//add_filter('rest_url', 'variscite_update_rest_api_url', 10, 4);

wp_enqueue_script( 'jquery' );

// Change Leads default sorting (Admin bar)
function wd_admin_menu_change_leads_url() {
    global $menu;

    $leads_url = $menu[66][2];
    if ( $leads_url === 'edit.php?post_type=leads') {
        $menu[66][2] =  $leads_url . '&orderby=date&order=desc';
    }

}
add_action( 'admin_menu', 'wd_admin_menu_change_leads_url' );







/*
* Creating a function to create our CPT
*/

function custom_post_type() {

    // Set UI labels for Custom Post Type
    $labels = array(
        'name'                => _x( 'Testimonial', 'Post Type General Name', 'variscite' ),
        'singular_name'       => _x( 'Testimonial', 'Post Type Singular Name', 'variscite' ),
        'menu_name'           => __( 'Testimonials', 'variscite' ),

        'all_items'           => __( 'All Testimonial', 'variscite' ),
        'view_item'           => __( 'View Testimonial', 'variscite' ),
        'add_new_item'        => __( 'Add New Testimonial', 'variscite' ),
        'add_new'             => __( 'Add New', 'variscite' ),
        'edit_item'           => __( 'Edit Testimonial', 'variscite' ),
        'update_item'         => __( 'Update Testimonial', 'variscite' ),
        'search_items'        => __( 'Search Testimonial', 'variscite' ),
        'not_found'           => __( 'Not Found', 'variscite' ),
        'not_found_in_trash'  => __( 'Not found in Trash', 'variscite' ),
    );

// Set other options for Custom Post Type

    $args = array(
        'label'               => __( 'testimonial', 'variscite' ),
        'labels'              => $labels,
        'supports'            => array( 'title', 'editor', 'thumbnail' ),
        'hierarchical'        => false,
        'public'              => false,
        'show_ui'             => true,
        'show_in_menu'        => true,
        'show_in_nav_menus'   => false,
        'show_in_admin_bar'   => false,
        'menu_position'       => 5,
        'menu_icon'   => 'dashicons-editor-quote',
        'can_export'          => true,
        'has_archive'         => true,
        'exclude_from_search' => true,
        'publicly_queryable'  => false,
        'capability_type'     => 'post',
        'show_in_rest'        => true,
        'query_var'           => false,
    );

    // Registering your Custom Post Type
    register_post_type( 'testimonial', $args );

}



add_action( 'init', 'custom_post_type', 0 );

add_theme_support('post-thumbnails');

//function get_highlight_tab_content($tabsArr, $postid) {
//
//    $status 		= 'active';
//    $tabsName 		= '';
//    $tabsContent 	= '';
//
//    $kit_check		= get_field('vrs_specs_evaluation_kit', $postid);
//    $isit_kit		= ( !empty($kit_check[0]) && $kit_check[0] == 'evkit' ? true : false );
//
//    foreach($tabsArr as $tab) {
//
//        if( isset($tab['vrs_specs_tbltab_name']) && $tab['vrs_specs_tbltab_name'] != 'Highlights' ) {
//            continue;
//        }
//
//        $tabId				= str2id($tab['vrs_specs_tbltab_name']);
//        $tableArr 			= $tab['vrs_specs_info_table'];
//
//        $tableCount			= 0;
//        $table 				= '';
//        $output = '';
//        // BUILD TAB TABLE
//        if( !empty($tableArr) ) {
//            foreach($tableArr as $singleTable) {
//
//                $tableData	= $singleTable['table'];
//                $tableBody	= '';
//
//                // TABLE BODY
//                $body_trs 	= '';
//                $tr_counter = 0;
//
//                foreach ( $tableData['body'] as $tr ) {
//
//                    $body_tds 	= '';
//                    $tdcounter	= 0;
//
//                    foreach ( $tr as $td ) {
//                        $body_tds .= '<td class="'.($tdcounter == 0 ? 'tr-label '.strip_tags(str2id($td['c'])) : '').'">'.apply_filters('the_content', do_shortcode($td['c'])).'</td>';
//
//                        $tdcounter++;
//                    }
//
//                    $body_trs .= '<tr class="'.strip_tags(str2id($tr[0]['c'])).'">'.$body_tds.'</tr>';
//
//                    $tr_counter++;
//                }
//                $tbody = '<tobdy>'.$body_trs.'</tobdy>';
//
//                // RETURN TABLE
//                $table .= '<table class="table table-responsive table-striped">'.$tbody.'</table>';
//                $tableCount++;
//                $tabsContent .= $table;
//                $status = '';
//            }
//        } else {$table = '';}
//
//
//    }
//
//    $vrs_specs_product_middesc = get_field('vrs_specs_product_middesc');
//    $col = 12;
//
//    if((isset($tableArr) && !empty($tableArr)) && !empty($vrs_specs_product_middesc)) {
//        $col = 6;
//    }
//
//    if((isset($tableArr) && !empty($tableArr)) || !empty($vrs_specs_product_middesc)) {
//        $output .= '
//        <div class="highlight diagonal-cut">
//            <div class="container">
//                <h2>Highlights</h2>
//                <div class="highlight-wrap">
//                    <div class="row">';
//        if(isset($tableArr) && !empty($tableArr)) {
//            $output .=
//                '<div class="col-md-'.$col.'">
//                                <div class="data-tables-box">'.$tabsContent.'</div>
//                            </div>';
//        }
//
//        if(!empty($vrs_specs_product_middesc)) {
//            $output .='<div class="col-md-'.$col.'">'.$vrs_specs_product_middesc.'</div>';
//        }
//        $output .='</div>';
//        $output .= '<div class="text-center">
//                        <h3>Order now and enjoy full support with your first installation (for free)</h3>
//                        <button class="btn btn-warning btn-lg quote-scroll scroll " data-to="prodQuoteForm"><span class="text">Get a Quote</span> <img src="'.get_template_directory_uri().'/images/button-arrow.png" alt="arrow"></button>
//                    </div>
//                </div>
//            </div>
//        </div>';
//    }
//
//    return $output;
//}

// OMER

function modify_product_cat_query( $query ) {
    if (!is_admin() && $query->is_tax("category")){
        $query->set('posts_per_page', 2);
    }
}
add_action( 'pre_get_posts', 'modify_product_cat_query' );

function pre_get_posts_include_post_author( $query ) {
    if (!is_admin() && $query->is_author()){
        $meta_query = array(
                'relation' => 'OR',
                array(
                    'key' => 'include_post_author',
                    'value' => 1,
                    'compare' => '='
                )
        );
        $query->set("meta_query", $meta_query);
    }
}
add_action( 'pre_get_posts', 'pre_get_posts_include_post_author' );
//function clear_from_fields() {
//
//    wp_register_script( "clear_form", get_stylesheet_directory_uri() . "/js/clear-form.js", array( "jquery" ) );
//    wp_enqueue_script( "clear_form" );
//
//}
//add_action( "wp_footer", "clear_from_fields" );



// Create a custom API endpoint for the SFDC lead syncing mechanism
function register_sfdc_sync_api_endpoint() {

    register_rest_route('variscite-sfdc/v1', '/sync', array(
        'methods'  => 'GET',
        'callback' => 'sfdc_sync_api_endpoint_callback',
    ));

    register_rest_route('variscite-sfdc/v1', '/sync', array(
        'methods'  => 'POST',
        'callback' => 'sfdc_sync_api_endpoint_callback',
    ));
}
//add_action('rest_api_init', 'register_sfdc_sync_api_endpoint');

function sfdc_sync_api_endpoint_callback() {

    // Collect all of the leads with the 'pending' status
    $leads = new WP_Query(array(
        'post_type' => 'leads',
        'posts_per_page' => -1,
        'meta_query' => array(
            'relation' => 'OR',
            array(
                'key' => 'lead_record_sf',
                'value' => 'on',
                'compare' => '!='
            ),
            array(
                'key' => 'lead_record_sf',
                'value' => '1',
                'compare' => 'NOT EXISTS'
            )
        ),
        'date_query' => array(
            'after' => '2022-04-10',
        ),
    ));

    if ($leads->posts && !empty($leads->posts)) {

        // Connect to the SFDC SOAP API to pass the lead data
        if(! class_exists('SforceSoapClient')) {
            require_once __DIR__ . '/inc/soapclient/SforcePartnerClient.php';
        }

        $sfdc_creds = array(
            'user' => 'hadas.s@variscite.com',
            'password' => 'Sh102030',
            'token' => 'FZH6Hm8zOGtOVYa2UADxLF73t'
        );

        $sfdc_wsdl = __DIR__ . '/inc/soapclient/partner.wsdl.xml';

        $SFDC = new SforcePartnerClient();
        $SFDC->createConnection($sfdc_wsdl);
        $SFDC->login($sfdc_creds['user'], $sfdc_creds['password'] . $sfdc_creds['token']);

        foreach($leads->posts as $lead) {
            $lid = $lead->ID;

            if ( get_field("curl_errors_documentation", $lid) ) {
                continue;
            }

            $sfdc_data = json_decode(get_field('sfdc_object_to_be_sent', $lid), true);

            // Init the connection to the API and pass the lead to SFDC
            try {
                $records = array();

                $records[0] = new SObject();
                $records[0]->type = 'Lead';
                $records[0]->fields = $sfdc_data;

                update_field('request_log', json_encode($records), $lid);

                $response = $SFDC->create($records);

                if($response[0]->success == true){
                    update_field('lead_record_sf', 'on', $lid);
                } else {
                    sfalert_email($lid);
                    update_field('curl_errors_documentation', 'Request failed: HTTP status code: ' . json_encode($response), $lid);
                }

            } catch (SoapFault $e) {

                # Catch and send out email to support if there is an error
                $errmessage =  "Exception ".$e->faultstring."<br/><br/>\n";
                $errmessage .= "Last Request:<br/><br/>\n";
                $errmessage .= $SFDC->getLastRequestHeaders();
                $errmessage .= "<br/><br/>\n";
                $errmessage .= $SFDC->getLastRequest();
                $errmessage .= "<br/><br/>\n";
                $errmessage .= "Last Response:<br/><br/>\n";
                $errmessage .= $SFDC->getLastResponseHeaders();
                $errmessage .= "<br/><br/>\n";
                $errmessage .= $SFDC->getLastResponse();

                update_field('curl_errors_documentation', json_encode($errmessage), $lid);
                sfalert_email($lid);

            } catch (Exception $e) {

                # Catch and send out email to support if there is an error
                $errmessage =  "Exception ".$e->faultstring."<br/><br/>\n";
                $errmessage .= "Last Request:<br/><br/>\n";
                $errmessage .= $SFDC->getLastRequestHeaders();
                $errmessage .= "<br/><br/>\n";
                $errmessage .= $SFDC->getLastRequest();
                $errmessage .= "<br/><br/>\n";
                $errmessage .= "Last Response:<br/><br/>\n";
                $errmessage .= $SFDC->getLastResponseHeaders();
                $errmessage .= "<br/><br/>\n";
                $errmessage .= $SFDC->getLastResponse();

                update_field('curl_errors_documentation', json_encode($errmessage), $lid);
                sfalert_email($lid);
            }

            // Send an email to the owners about the lead
            $settings = get_field('quote_settings', 'option');
            $message  = get_field('email_message_to_be_sent', $lid);
            $subject  = get_field('email_subject_to_be_sent', $lid);

            $sendResult	= wp_mail($settings['email_to'], $subject, $message);
            if($sendResult) { update_field('lead_record_email', 'on', $lid);  }
        }
    }

    wp_send_json_success();

    add_filter( 'auto_update_plugin', '__return_false' );
}

// Redirect posts with a translated blog category in the URL to 404
function variscite_404_translated_tax_posts() {

    // Run on single posts only
    if(is_singular('post') && !is_preview()) {

        // Extract the category base slug
        $cat_url_exploded = explode('/', trim($_SERVER['REQUEST_URI'], '/'));

        // Go through all of the language codes and unset them from the array
        $langs = apply_filters('wpml_active_languages', NULL, 'orderby=id&order=desc');

        foreach($langs as $lang) {

            if(in_array($lang['language_code'], $cat_url_exploded)) {
                unset($cat_url_exploded[array_search($lang['language_code'], $cat_url_exploded)]);
            }
        }

        // Reset the array's keys
        $cat_url_exploded = array_values($cat_url_exploded);

        // Get the final slug
        if(isset($cat_url_exploded[0]) && ! empty($cat_url_exploded[0])) {
            $cat_slug = $cat_url_exploded[0];

            // Try to get the tax object behind it in the current language.
            // If the result is false (the term doesn't exist), redirect to 404.
            if(get_category_by_slug($cat_slug) === false || get_category_by_slug($cat_slug)->slug !== $cat_slug) {
                wp_redirect('/404');
                die();
            }
        }
    }
}
add_action('template_redirect', 'variscite_404_translated_tax_posts');



function create_custom_table() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'input_tests';

    if ($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {
        $sql = "CREATE TABLE $table_name (
            id INT(11) NOT NULL AUTO_INCREMENT,
            input_data TEXT NOT NULL,
            PRIMARY KEY (id)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;";

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);
    }
}
add_action('after_setup_theme', 'create_custom_table');


function o_sctipts(){
    wp_enqueue_script('scripts.js' , get_stylesheet_directory_uri() . '/scripts.js', array('jquery') );
    wp_localize_script( 'scripts.js', 'myAjax', array( 'ajaxurl' => admin_url( 'admin-ajax.php' )));
    if (is_singular('landing_page')) {
        wp_enqueue_style('landing-page-style', get_template_directory_uri() . '/css/landing-page-43.css', array(), '1.0.1');
    }
}
add_action( 'wp_enqueue_scripts', 'o_sctipts' );
function o_sendPhoneCode(){
    // Check for nonce security


    // Sanitize the input data and insert it into the database.
    $inputData = sanitize_text_field( $_POST['input_data'] );

    // Insert the $inputData into the database.
    global $wpdb;
    $table_name = 'wtj_input_tests'; // Change it with your table name
    $user_ip = $_SERVER['REMOTE_ADDR'];
    $date = date("Y/m/d");
    $wpdb->insert( $table_name, array(
        'input_data' => $inputData,
        'ip' => $user_ip,
        'date' => $date,
    ) );

//  $wpdb->query( "INSERT INTO {$table_name} ('input_data') VALUES ({$inputData});" );

    $response = array( 'message' => 'Data saved' );

    wp_send_json_success( $response );
    die();
}

add_action("wp_ajax_o_sendPhoneCode", "o_sendPhoneCode");
add_action("wp_ajax_nopriv_o_sendPhoneCode", "o_sendPhoneCode");



/** Remove  URL of https://wordpress-689526-3794037.cloudwaysapps.com/products/system-on-module-som/ from YoastSEO sitemap **/
function filter_sitemap_entries($url, $type, $post) {
    if ($url['loc'] === "https://wordpress-689526-3794037.cloudwaysapps.com/products/system-on-module-som/") {
        return array();
    }
    return $url;
}
add_filter('wpseo_sitemap_entry', 'filter_sitemap_entries', 10, 3);

// Set the robots meta tag to noindex nofollow for ?noajax pages
function yoast_seo_change_robots_for_noajax($robots) {

    if(isset($_GET['noajax'])) {
        return 'noindex, nofollow';
    }

    return $robots;
}
add_filter('wpseo_robots', 'yoast_seo_change_robots_for_noajax', PHP_INT_MAX);



 // Set the robots meta tag to noindex for filters SOM + ACC //
function noindex_filter_pages($robots) {
    $url = $_SERVER['REQUEST_URI'];
    if(strpos($url,'accessories/?') !== false) {
        return 'noindex, follow';
    }
    if(isset($_GET['cpu_cat'])){
        return 'noindex, follow';
    }

    return $robots;
}
add_filter('wpseo_robots', 'noindex_filter_pages', PHP_INT_MAX);

add_filter('wp_head', 'dc_preload_image');
function dc_preload_image() {
    if(is_front_page()) {
//        echo '<link rel="preload" fetchpriority="high" as="image" href="https://office-dev1.variscite.co.uk/wp-content/uploads/2021/10/hp-slider-01-1536x584.jpg.webp" type="image/webp"> ';
//        echo '<link rel="preload" fetchpriority="high" as="image" href="https://office-dev1.variscite.co.uk/wp-content/uploads/2018/06/home-header-mobile-v3.jpg.webp" type="image/webp"> ';
//
//        if(ICL_LANGUAGE_CODE == 'en'){
//        ?><!--<img class="lazy hideInDesktop" width="768" height="515" src="/wp-content/uploads/2018/06/home-header-mobile-v3.jpg.webp" alt="home-header-mobile-v3"  style="max-width: 100%; height: auto;">--><?php
//        }
    }

    if(is_singular('specs')){

        echo '<link rel="preload" fetchpriority="high" as="image" href="https://office-dev1.variscite.co.uk/wp-content/uploads/2018/01/bg-specs-header-2560.jpg" type="image/webp">';
        echo '<link rel="preload" fetchpriority="high" as="image" href="https://office-dev1.variscite.co.uk/wp-content/uploads/2023/12/bg-specs-header-mobile.jpg" type="image/webp"> ';

    }

}

// Remove LPs from sitemap 19/02/2024 //
add_filter( 'wpseo_exclude_from_sitemap_by_post_ids', function () {
    return array( 1277111331, 8787, 1277153277, 8655, 6671, 1277160657 );
} );






function custom_display_posts_output( $output, $original_atts, $image, $title, $date, $excerpt, $inner_wrapper, $content, $class ) {
    global $post;

    // Get the post thumbnail
    $image = get_the_post_thumbnail( $post->ID, 'large' );

    // Get the custom meta field for description
    $description = get_post_meta( $post->ID, 'vrs_specs_product_middesc', true );
    $price = "Starting from $" . get_post_meta( $post->ID, 'vrs_specs_price', true );

    // Get the post title
    $post_title = get_the_title();

    // Get the post permalink
    $permalink = get_permalink( $post->ID );

    // Initialize the specifications array
    $specifications = [];

    // Check if the repeater field has rows of data
    if( have_rows('specs_category_values', $post->ID) ) {
        // Loop through the rows of data
        while ( have_rows('specs_category_values', $post->ID) ) {
            the_row();
            // Get sub-field values
            $field_name = get_sub_field('fld_name');
            $field_value = get_sub_field('fld_value');
            $specifications[] = ['name' => $field_name, 'value' => $field_value];
        }
    }

    // Build the item output using the provided HTML structure
    $output = '
    <div class="product-card">
      <div class="product-row">
        <div class="product-image">
          ' . $image . '
        </div>
        <div class="product-details">
          <div class="product-button">
            <a class="product-info-button" href="' . $permalink . '">Product Info <i class="fas fa-chevron-right"></i></a>
          </div>
          <div class="product-text">
            ' . $price . '
          </div>
        </div>
      </div>
      <div class="product-specifications">
        <div class="product-title"><a href="' . $permalink . '">
          ' . $post_title . '
        </a></div>
        <div class="product-specifications-inner">';

    // Add each specification
    foreach ($specifications as $spec) {
        $output .= '
          <div class="specification">
            <div class="specification-name">' . esc_html($spec['name']) . ':</div>
            <div class="specification-value">' . esc_html($spec['value']) . '</div>
          </div>';
    }

    $output .= '
        </div>
      </div>
    </div>';

    return $output;
}
add_filter( 'display_posts_shortcode_output', 'custom_display_posts_output', 10, 9 );



function wrap_display_posts_with_container( $output, $atts, $query ) {
    static $wrap_start = false;

    if (!$wrap_start) {
        $output = '<div class="product-container">' . $output;
        $wrap_start = true;
    }

    if ($query->current_post + 1 === $query->post_count) {
        $output .= '</div>';
        $wrap_start = false;
    }

    return $output;
}
add_filter( 'display_posts_shortcode_output', 'wrap_display_posts_with_container', 10, 3 );


// add_action('add_meta_boxes', 'display_all_acf_meta_boxes', 20);

// function display_all_acf_meta_boxes() {
//     // Get the current post type
//     global $typenow;

//     // Get all field groups for the current post type
//     $field_groups = acf_get_field_groups(array('post_type' => $typenow));

//     if ($field_groups) {
//         echo '<div class="acf-meta-boxes-list">';
//         echo '<h2>ACF Meta Boxes for Post Type: ' . esc_html($typenow) . '</h2>';
//         echo '<ul>';

//         foreach ($field_groups as $field_group) {
//             echo '<li>';
//             echo '<strong>' . esc_html($field_group['title']) . '</strong>';
//             echo '<ul>';

//             // Get the fields for the field group
//             $fields = acf_get_fields($field_group['key']);
//             if ($fields) {
//                 foreach ($fields as $field) {
//                     echo '<li>' . esc_html($field['label']) . ' (' . esc_html($field['type']) . ')</li>';
//                 }
//             } else {
//                 echo '<li>No fields found</li>';
//             }

//             echo '</ul>';
//             echo '</li>';
//         }

//         echo '</ul>';
//         echo '</div>';
//     }
// }




add_action('acf/init', 'add_custom_tab_with_plugin_content');

function add_custom_tab_with_plugin_content() {
    if (function_exists('acf_add_local_field_group')) {
        $field_group_key = 'group_5a1b05f517f0b'; // Replace with your actual field group key

        // Get the existing field group
        $field_group = acf_get_field_group($field_group_key);
        if ($field_group) {
            // Get existing fields
            $existing_fields = acf_get_fields($field_group_key);

            // Define new fields
            $new_fields = array(
                array(
                    'key' => 'field_lp_products',
                    'label' => 'Landing Page Products',
                    'type' => 'tab',
                ),
                array(
                    'key' => 'field_lp_products_content',
                    'label' => 'Select products',
                    'name' => 'custom_plugin_content',
                    'type' => 'custom_plugin_content', // Custom field type
                ),
            );

            // Merge new fields with existing fields
            $fields = array_merge($existing_fields, $new_fields);

            // Update the field group with new fields
            acf_add_local_field_group(array(
                'key' => $field_group_key,
                'title' => $field_group['title'],
                'fields' => $fields,
                'location' => $field_group['location'],
                'menu_order' => $field_group['menu_order'],
                'position' => $field_group['position'],
                'style' => $field_group['style'],
                'label_placement' => $field_group['label_placement'],
                'instruction_placement' => $field_group['instruction_placement'],
                'hide_on_screen' => $field_group['hide_on_screen'],
                'active' => $field_group['active'],
                'description' => $field_group['description'],
            ));
        }
    }
}


// add_action('acf/init', 'add_custom_tab_with_plugin_content');


// function add_custom_tab_with_plugin_content() {
//     if (function_exists('acf_add_local_field')) {
//         $field_group_key = 'group_5a1b05f517f0b'; // Replace with your actual field group key

//         // Get the existing field group
//         $field_group = acf_get_field_group($field_group_key);
//         if ($field_group) {
//             // Get existing fields
//             $fields = acf_get_fields($field_group_key);

//             // Define new fields
//             $new_fields = array(
//                 array(
//                     'key' => 'field_lp_products',
//                     'label' => 'Landing Page Products',
//                     'type' => 'tab',
//                     'parent' => $field_group_key,
//                 ),
//                 array(
//                     'key' => 'field_lp_products_content',
//                     'label' => 'Select products',
//                     'name' => 'custom_plugin_content',
//                     'type' => 'message',
//                     'message' => '', // The content will be populated dynamically
//                     'parent' => $field_group_key,
//                 ),
//             );

//             // Merge new fields with existing fields
//             $fields = array_merge($fields, $new_fields);

//             // Update the field group with new fields
//             acf_add_local_field_group(array(
//                 'key' => $field_group_key,
//                 'title' => $field_group['title'],
//                 'fields' => $fields,
//                 'location' => $field_group['location'],
//                 'menu_order' => $field_group['menu_order'],
//                 'position' => $field_group['position'],
//                 'style' => $field_group['style'],
//                 'label_placement' => $field_group['label_placement'],
//                 'instruction_placement' => $field_group['instruction_placement'],
//                 'hide_on_screen' => $field_group['hide_on_screen'],
//                 'active' => $field_group['active'],
//                 'description' => $field_group['description'],
//             ));
//         }
//     }
// }


// add_filter('acf/load_field/key=field_lp_products_content', 'populate_lp_products_content');

// function populate_lp_products_content($field) {
//     // Capture the plugin content
//     ob_start();

//     // Example of including plugin content by function
//     if (function_exists('srp_render_plugin_content')) {
//         echo srp_render_plugin_content(); // Call the plugin function to display content
//     } else {
//         echo 'Plugin content goes here.'; // Fallback content if the function does not exist
//     }

//     $content = ob_get_clean();

//     // Set the field's message to the plugin content
//     $field['message'] = $content;

//     return $field;
// }

add_action('acf/include_field_types', 'register_custom_plugin_content_field');



function register_custom_plugin_content_field() {
    class acf_field_custom_plugin_content extends acf_field {
        function __construct($settings) {
            $this->name = 'custom_plugin_content';
            $this->label = __('Custom Plugin Content', 'acf-custom-plugin-content');
            $this->category = 'basic';
            $this->defaults = array();
            $this->settings = $settings;
            parent::__construct();
        }

        function render_field($field) {
            // Capture the plugin content
            ob_start();
            if (function_exists('srp_render_plugin_content')) {
                echo srp_render_plugin_content();
            } else {
                echo 'Plugin content goes here.'; // Fallback content if the function does not exist
            }
            $content = ob_get_clean();

            // Display the content
            echo '<div>' . $content . '</div>';
        }
    }

    // Initialize the custom field
    new acf_field_custom_plugin_content(array(
        'version' => '1.0.0',
    ));
}



// function create_custom_post_type() {
//     $labels = array(
//         'name' => 'Custom Pages',
//         'singular_name' => 'Custom Page',
//         'menu_name' => 'Custom Pages',
//         'name_admin_bar' => 'Custom Page',
//         'add_new' => 'Add New',
//         'add_new_item' => 'Add New Custom Page',
//         'new_item' => 'New Custom Page',
//         'edit_item' => 'Edit Custom Page',
//         'view_item' => 'View Custom Page',
//         'all_items' => 'All Custom Pages',
//         'search_items' => 'Search Custom Pages',
//         'not_found' => 'No Custom Pages found.',
//         'not_found_in_trash' => 'No Custom Pages found in Trash.',
//     );

//     $args = array(
//         'labels' => $labels,
//         'public' => true,
//         'has_archive' => true,
//         'show_in_rest' => true, // Enable Gutenberg editor
//         'supports' => array( 'title', 'editor', 'thumbnail' ), // Add more as needed
//         'rewrite' => array( 'slug' => 'custom-page' ),
//     );

//     register_post_type( 'custom_page', $args );
// }
// add_action( 'init', 'create_custom_post_type' );

function create_landing_page_post_type() {
    register_post_type('landing_page',
        array(
            'labels' => array(
                'name' => __('Landing Pages'),
                'singular_name' => __('Landing Page')
            ),
            'public' => true,
            'has_archive' => true,
            'rewrite' => array('slug' => 'landing-pages'),
            'supports' => array('title', 'editor', 'thumbnail'),
            'show_in_rest' => true,
        )
    );
}
add_action('init', 'create_landing_page_post_type');



add_action('acf/init', 'register_landing_page_fields');

function register_landing_page_fields() {
    if (function_exists('acf_add_local_field_group')) {
        acf_add_local_field_group(array(
            'key' => 'group_landing_page',
            'title' => 'Landing Page Settings',
            'fields' => array(
                array(
                    'key' => 'field_landing_header_image',
                    'label' => 'Header Image',
                    'name' => 'landing_header_image',
                    'type' => 'image',
                    'instructions' => 'Upload the header image.',
                    'return_format' => 'url',
                    'preview_size' => 'medium',
                    'library' => 'all',
                ),
                array(
                    'key' => 'field_landing_images_repeater',
                    'label' => 'Compliance Icons',
                    'name' => 'landing_images_repeater',
                    'type' => 'repeater',
                    'instructions' => 'Upload / Pick relevant compliance logos',
                    'sub_fields' => array(
                        array(
                            'key' => 'field_compliance_icon',
                            'label' => 'Compliance Icon',
                            'name' => 'compliance_icon',
                            'type' => 'image',
                            'instructions' => 'Upload the Compliance Icon.',
                            'return_format' => 'url',
                            'preview_size' => 'medium',
                            'library' => 'all',
                        ),
                    ),
                    'min' => 1,
                    'max' => 10,
                    'layout' => 'table',
                    'button_label' => 'Add Icon',
                ),
                array(
                    'key' => 'field_landing_header_text',
                    'label' => 'Header Text',
                    'name' => 'landing_header_text',
                    'type' => 'wysiwyg',
                    'instructions' => 'Enter the header text.',
                ),
                array(
                    'key' => 'field_landing_selected_specs',
                    'label' => 'Selected Specs Pages',
                    'name' => 'landing_selected_specs',
                    'type' => 'relationship',
                    'instructions' => 'Select the specs pages to display.',
                    'post_type' => array('specs'), // Change 'product' to 'specs'
                    'filters' => array('search'),
                    'return_format' => 'object',
                ),
                array(
                    'key' => 'field_landing_featured_products',
                    'label' => 'Featured Products',
                    'name' => 'landing_featured_products',
                    'type' => 'relationship',
                    'instructions' => 'Select the featured products to display.',
                    'post_type' => array('specs'),
                    'filters' => array('search'),
                    'return_format' => 'object',
                ),
                array(
                    'key' => 'field_landing_footer_text',
                    'label' => 'Footer Text',
                    'name' => 'landing_footer_text',
                    'type' => 'wysiwyg',
                    'instructions' => 'Enter the footer text.',
                ),
            ),
            'location' => array(
                array(
                    array(
                        'param' => 'post_type',
                        'operator' => '==',
                        'value' => 'landing_page',
                    ),
                ),
            ),
        ));
    }
    
}

add_filter('acf/load_value/key=field_landing_footer_text', 'insert_html_into_footer_editor', 10, 3);
function insert_html_into_footer_editor($value, $post_id, $field) {
    if (is_admin()) {
        $screen = get_current_screen();
        if ($screen && $screen->post_type == 'landing_page') {
            // Check if we are creating a new post or if the field is empty
            if (get_post_status($post_id) == 'auto-draft' || empty($value)) {
                // Define the path to the HTML file
                $file_path = get_template_directory() . '/landing-page-footer-template.html';
                
                // Check if the file exists
                if (file_exists($file_path)) {
                    // Get the content of the file
                    $value = trim(file_get_contents($file_path));
                } 
            }
        }
    }
    return $value;
}



add_filter('acf/load_value/key=field_landing_header_text', 'insert_html_into_header_editor', 10, 3);
function insert_html_into_header_editor($value, $post_id, $field) {
    if (is_admin()) {
        $screen = get_current_screen();
        if ($screen && $screen->post_type == 'landing_page') {
            // Check if we are creating a new post or if the field is empty
            if (get_post_status($post_id) == 'auto-draft' || empty($value)) {
                // Define the path to the HTML file
                $file_path = get_template_directory() . '/landing-page-header-template.html';
                
                // Check if the file exists
                if (file_exists($file_path)) {
                    // Get the content of the file
                    $value = trim(file_get_contents($file_path));
                } 
            }
        }
    }
    return $value;
}



add_filter('wp_mail', 'custom_error_alert_email_recipients');

function custom_error_alert_email_recipients($args) {
    // Check if the email subject contains the error notification subject
    if (strpos($args['subject'], '[Variscite] Your Site is Experiencing a Technical Issue') !== false) {
        // Define the new recipients
        $new_recipients = array('gregory.m@variscite.com','ayelet.o@variscite.com', 'lena.g@variscite.com'); // Replace with the desired email addresses
        
        // Update the 'to' field with new recipients
        $args['to'] = implode(', ', $new_recipients);
        
        // Optional: Remove the 'Cc' and 'Bcc' fields if necessary
        // $args['headers'] = array();
    }

    return $args;
}







































