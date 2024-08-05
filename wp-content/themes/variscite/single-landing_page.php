<?php
get_header();

function display_currency_with_icon( $currency_code ) {
    $currency_icons = array(
        'USD' => 'fa-dollar-sign',        // United States Dollar
        'EUR' => 'fa-euro-sign',          // Euro
        'GBP' => 'fa-pound-sign',         // British Pound Sterling
        'JPY' => 'fa-yen-sign',           // Japanese Yen
        'CNY' => 'fa-yen-sign',           // Chinese Yuan
        'AUD' => 'fa-dollar-sign',        // Australian Dollar
        'CAD' => 'fa-dollar-sign',        // Canadian Dollar
        'CHF' => 'fa-franc-sign',         // Swiss Franc ( no standard Font Awesome icon, using dollar sign as placeholder )
        'HKD' => 'fa-dollar-sign',        // Hong Kong Dollar
        'NZD' => 'fa-dollar-sign',        // New Zealand Dollar
        'SEK' => 'fa-kr-sign',            // Swedish Krona
        'KRW' => 'fa-won-sign',           // South Korean Won
        'SGD' => 'fa-dollar-sign',        // Singapore Dollar
        'NOK' => 'fa-kr-sign',            // Norwegian Krone
        'MXN' => 'fa-dollar-sign',        // Mexican Peso
        'INR' => 'fa-rupee-sign',         // Indian Rupee
        'RUB' => 'fa-ruble-sign',         // Russian Ruble
        'ZAR' => 'fa-rand-sign',          // South African Rand ( no standard Font Awesome icon, using dollar sign as placeholder )
        'TRY' => 'fa-lira-sign',          // Turkish Lira
        'BRL' => 'fa-dollar-sign',        // Brazilian Real
        'TWD' => 'fa-dollar-sign',        // Taiwan Dollar
        'DKK' => 'fa-kr-sign',            // Danish Krone
        'PLN' => 'fa-dollar-sign',        // Polish Zloty ( no standard Font Awesome icon, using dollar sign as placeholder )
        'THB' => 'fa-baht-sign',          // Thai Baht
        'IDR' => 'fa-dollar-sign',        // Indonesian Rupiah ( no standard Font Awesome icon, using dollar sign as placeholder )
        'HUF' => 'fa-dollar-sign',        // Hungarian Forint ( no standard Font Awesome icon, using dollar sign as placeholder )
        'CZK' => 'fa-dollar-sign',        // Czech Koruna ( no standard Font Awesome icon, using dollar sign as placeholder )
        'ILS' => 'fa-shekel-sign',        // Israeli Shekel
        'CLP' => 'fa-dollar-sign',        // Chilean Peso
        'PHP' => 'fa-peso-sign',          // Philippine Peso
        'AED' => 'fa-dollar-sign',        // United Arab Emirates Dirham ( no standard Font Awesome icon, using dollar sign as placeholder )
        'COP' => 'fa-dollar-sign',        // Colombian Peso
        'SAR' => 'fa-dollar-sign',        // Saudi Riyal ( no standard Font Awesome icon, using dollar sign as placeholder )
        'MYR' => 'fa-dollar-sign',        // Malaysian Ringgit
        'RON' => 'fa-dollar-sign',        // Romanian Leu ( no standard Font Awesome icon, using dollar sign as placeholder )
        'VND' => 'fa-dong-sign',          // Vietnamese Dong
        // Add more currencies as needed
    );

    if ( !function_exists( 'truncate_string' ) ) {
        function truncate_string( $string, $length = 50 ) {
            if ( strlen( $string ) > $length ) {
                return substr( $string, 0, $length );
            }
            return $string;
        }
    }

    if ( array_key_exists( $currency_code, $currency_icons ) ) {
        $icon = $currency_icons[ $currency_code ];
        echo '<span class="currency-icon"><i class="fas ' . esc_attr( $icon ) . '"></i></span>';
    } else {
        echo esc_html( $currency_code );
    }
}

function get_minimal_image_html( $post_id ) {
    // Get the image ID ( assuming the image is set as the featured image )
    $image_id = get_post_thumbnail_id( $post_id );

    // Get the full-size image URL
    $image_src = wp_get_attachment_image_src( $image_id, 'full' )[ 0 ];

    // Get the alt text
    $alt_text = get_post_meta( $image_id, '_wp_attachment_image_alt', true );

    // Construct the minimal HTML
    $image_html = '<img class="responsive" src="' . esc_url( $image_src ) . '" alt="' . esc_attr( $alt_text ) . '">';

    return $image_html;
}

if ( have_posts() ) :
$post_id = get_the_ID();

// Get the current language
$current_language = apply_filters( 'wpml_current_language', NULL );

// Get the translated post ID for the current language
$translated_post_id = apply_filters( 'wpml_object_id', $post_id, get_post_type( $post_id ), false, $current_language );

while ( have_posts() ) : the_post();
// Get the group field
$header_group = get_field( 'header_group', $translated_post_id );
$header_image = $header_group[ 'landing_header_image' ];
$header_text = $header_group[ 'landing_header_text' ];
$compliance_icons = $header_group[ 'landing_images_repeater' ];

// Check if each field is empty and get default values from settings if they are
if ( empty( $header_image ) ) {
    $default_header_group = get_field( 'header_group_settings', 'option' );
    $header_image = $default_header_group[ 'landing_header_image_settings' ];
}

if ( empty( $header_text ) ) {
    if ( !isset( $default_header_group ) ) {
        $default_header_group = get_field( 'header_group_settings', 'option' );
    }
    $header_text = $default_header_group[ 'landing_header_text_settings' ];
}

if ( empty( $compliance_icons ) ) {
    if ( !isset( $default_header_group ) ) {
        $default_header_group = get_field( 'header_group_settings', 'option' );
    }
    $compliance_icons = $default_header_group[ 'landing_images_repeater_settings' ];
}

?>

<article id = 'post-<?php the_ID(); ?>' <?php post_class();
?>>
<header class = 'entry-header'>
<div class = 'landing_page_header'>
<div class = 'landing_page_header-left'>
<?php if ( $header_text ): ?>
<?php echo $header_text;
?>
<?php endif;
?>
<div class = 'landing_page_image-row'>
<?php if ( $compliance_icons ): ?>
<?php foreach ( $compliance_icons as $icon ): ?>
<div class = 'landing_page_image'>
<img src = "<?php echo esc_url($icon['compliance_icon']); ?>" alt = 'Compliance Icon'>
</div>
<?php endforeach;
?>
<div class = 'landing_page_image'>
<img src = '/wp-content/uploads/2024/07/platinum_new-1-1.png' alt = 'Compliance Icon'>
</div>
<?php endif;
?>
</div>
</div>
<div class = 'landing_page_header-right'>
<?php if ( $header_image ): ?>
<img src = "<?php echo esc_url($header_image); ?>" alt = 'Header Right Image'>
<?php endif;
?>
</div>
</div>
</header>

<div class = 'entry-content'>
<?php
// Featured Products

function get_featured_products() {
    // Try to get the landing page settings
    $featured_products = get_field( 'landing_featured_products' );

    // Fallback to current landing page data if settings are empty
    if ( empty( $featured_products ) ) {
        $featured_products = get_field( 'landing_featured_specs_settings', 'option' );
    }

    return $featured_products;
}

function get_latest_soms_products() {
    // Try to get the landing page settings
    $latest_soms_products = get_field( 'landing_latest_soms_products' );

    // Fallback to current landing page data if settings are empty
    if ( empty( $latest_soms_products ) ) {

        $latest_soms_products = get_field( 'latest_soms_products_settings', 'option' );

    }

    return $latest_soms_products;
}

$featured_products = get_featured_products();
$latest_soms_products = get_latest_soms_products();

$post_latest_som_img = get_field( 'latest_soms_image' );
if ( empty( $post_latest_som_img ) ) {
    $post_latest_som_img = get_field( 'field_landing_latest_soms_image_settings', 'option' );
}

$post_latest_som_title = get_field( 'latest_soms_title' );
if ( empty( $post_latest_som_title ) ) {
    $post_latest_som_title = get_field( 'field_landing_latest_soms_title_settings', 'option' );
}

$post_latest_som_url = get_field( 'latest_soms_url' );
if ( empty( $post_latest_som_url ) ) {
    $post_latest_som_url = get_field( 'field_landing_latest_soms_url_settings', 'option' );
}

$post_latest_soms_product_1 = get_field( 'field_landing_latest_soms_product_1' );
if ( empty( $post_latest_soms_product_1 ) ) {
    $post_latest_soms_product_1 = get_field( 'field_landing_latest_soms_product_1_settings', 'option' );
}

$post_latest_soms_product_2 = get_field( 'field_landing_latest_soms_product_2' );
if ( empty( $post_latest_soms_product_2 ) ) {
    $post_latest_soms_product_2 = get_field( 'field_landing_latest_soms_product_2_settings', 'option' );
}
$post_latest_soms_product_3 = get_field( 'field_landing_latest_soms_product_3' );
if ( empty( $post_latest_soms_product_3 ) ) {
    $post_latest_soms_product_3 = get_field( 'field_landing_latest_soms_product_3_settings', 'option' );
}

$post_latest_soms_more_info = get_field( 'latest_soms_more_info' );
if ( empty( $post_latest_soms_more_info ) ) {
    $post_latest_soms_more_info = get_field( 'latest_soms_more_info_settings', 'option' );
}

$post_product_info = get_field( 'landing_product_info' );
if ( empty( $post_product_info ) ) {
    $post_product_info = get_field( 'landing_product_info_settings', 'option' );
}

if ( $featured_products ): ?>
<div class = 'container'>
<div class = 'display-posts-listing'>
<div class = 'product-container'>
<?php foreach ( $featured_products as $product ):
$permalink = get_permalink( $product->ID );
$post_title = get_the_title( $product->ID );
$post_price_text = get_field( 'vrs_specs_price', $product->ID );


// $image = get_the_post_thumbnail( $product->ID, 'large', array( 'class' => 'attachment-large size-large wp-post-image' ) );
$image = get_minimal_image_html( $product->ID );

// Get the product specifications ( assuming you have a custom field for specifications )
$specifications = [];
if ( have_rows( 'specifications', $product->ID ) ) {
    // Loop through the rows of data
    while ( have_rows( 'specifications', $product->ID ) ) {
        the_row();
        // Get sub-field values
        $field_name = get_sub_field( 'specification_name' );
        $field_value = get_sub_field( 'specification_value' );
        $specifications[] = [ 'name' => $field_name, 'value' => $field_value ];
    }
} else {
    if ( have_rows( 'specs_category_values', $product->ID ) ) {
        // Loop through the rows of data
        while ( have_rows( 'specs_category_values', $product->ID ) ) {
            the_row();
            // Get sub-field values
            $field_name = get_sub_field( 'fld_name' );
            $field_value = get_sub_field( 'fld_value' );
            $specifications[] = [ 'name' => $field_name, 'value' => $field_value ];
        }
    }
}
?>
<div class = 'product-card'>
<div class = 'product-row'>
<div class = 'product-image'>
<a href = "<?php echo $permalink; ?>"><?php echo $image;
?></a>
</div>
<div class = 'product-details'>
<div class = 'product-button'>
<a class = 'product-info-button' href = "<?php echo $permalink; ?>"><?php echo strtoupper( $post_product_info )?> <i class = 'fas fa-chevron-right'></i></a>
</div>
<div class = 'product-text'>
<?php echo $post_price_text;?> 
</div>
</div>
</div>
<div class = 'product-specifications'>
<div class = 'product-title'><a href = "<?php echo $permalink; ?>">
<?php echo $post_title;
?>
</a></div>
<div class = 'product-specifications-inner'>
<?php if ( $specifications ): ?>
<?php foreach ( $specifications as $spec ): ?>
<div class = 'specification'>
<div class = 'specification-name'><?php echo esc_html( $spec[ 'name' ] );
?>:</div>
<div class = 'specification-value'><?php echo esc_html( $spec[ 'value' ] );
?></div>
</div>
<?php endforeach;
?>
<?php endif;
?>
</div>
</div>
</div>
<?php endforeach;
?>
<?php endif;
?>
<div class = 'som-container'>
<div class = 'som-text'>
<h2><?php echo $post_latest_som_title?></h2>

<ul>
<li title = "<?php echo strtoupper($post_latest_soms_product_1); ?>"><?php echo strtoupper( $post_latest_soms_product_1 );
?></li>
<li title = "<?php echo strtoupper($post_latest_soms_product_2); ?>"><?php echo strtoupper( $post_latest_soms_product_2 );
?></li>
<li title = "<?php echo strtoupper($post_latest_soms_product_3); ?>"><?php echo strtoupper( $post_latest_soms_product_3 );
?></li>
</ul>

<a class = 'more-info' href = "<?php echo $post_latest_som_url?>"><?php echo strtoupper( $post_latest_soms_more_info )?> <img class = 'more-info-arrow' src = '/wp-content/uploads/2024/07/Vector.png'/></a>
</div>
<div class = 'som-image'>
<img src = "<?php echo $post_latest_som_img; ?>" alt = 'Latest SOMs'>
</div>
</div>
</div>
</div>
</div>

<div class = 'container container_som_categories'>
<div class = 'main-table'>
<div>
<div class = 'nested-table'>
<div class = 'nested-row'>
<div class = 'nested-cell'>
<h2>
    <?php 
        if (defined('ICL_LANGUAGE_CODE')) {
            $current_language = ICL_LANGUAGE_CODE;
        } else {
            // Fallback method to get the current language
            $current_language = apply_filters('wpml_current_language', NULL);
        }
        
        // Initialize the variable to hold the "Contact Us" sentence
        $popular_sentence = "";
        
        // Set the "Contact Us" sentence based on the current language
        switch ($current_language) {
            case 'en':
                $popular_sentence = "Popular System on Module Categories";
                break;
            case 'de':
                $popular_sentence = "Beliebte System-on-Module-Kategorien";
                break;
            case 'it':
                $popular_sentence = "Categorie popolari di System on Module";
                break;
            default:
                // Default to English if the language is not one of the specified ones
                $popular_sentence = "Popular System on Module Categories";
                break;
        }
        echo $popular_sentence;
    ?>
    
</h2>
</div>
</div>
<div class = 'nested-row'>
<div class = 'nested-cell'>
<div class = 'nested-table'>
<div class = 'nested-row'>
<div class = 'nested-cell'>
<a href = '/products/system-on-module-som/?cpu_name=NXP%20iMX8'>
<div class = 'box'>
<h3>NXP i.MX8</h3>
</div>
</a>
</div>
<div class = 'nested-cell'>
<a href = '/products/system-on-module-som/?cpu_name=TI%20Sitara%20AM62x'>
<div class = 'box'>
<h3>TI AM62x</h3>
</div>
</a>
</div>
</div>
<div class = 'nested-row'>
<div class = 'nested-cell'>
<a href = '/products/system-on-module-som/?cpu_name=NXP%20iMX9'>
<div class = 'box'>
<h3>NXP i.MX9</h3>
</div>
</a>
</div>
<div class = 'nested-cell'>
<a href = '/products/system-on-module-som/?cpu_name=NXP%20iMX6'>
<div class = 'box'>
<h3>NXP i.MX6</h3>
</div>
</a>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
<div class = 'image-container'>
<img src = 'https://office.variscite.co.uk/wp-content/uploads/2024/07/VAR-SOM-AM62-dual.png' alt = 'Image'>
</div>
</div>
<?php echo get_field( 'landing_contact_text_settings', 'option' );
?>

</div>
<div id = 'pg-17-1' class = 'panel-grid panel-has-style showInAll'><div class = 'form-row skewed-section panel-row-style panel-row-style-for-17-1'><div class = 'innerWrap  cols-2 '>
<div id = 'pgc-17-1-0' class = 'panel-grid-cell'>

</div>
<div id = 'pgc-17-1-1' class = 'panel-grid-cell'>
    
    <?php echo do_shortcode( '[contact_form_inline fromlp="yes"]' );?>
    
</div></div></div>

</div>

<!-- Display the landing footer text content without a footer element -->
<?php
$landing_footer_text = get_field( 'landing_footer_text', $translated_post_id );

if ( empty( $landing_footer_text ) ) {
    $landing_footer_text = get_field( 'landing_footer_text_settings', 'option' );
}
?>
<div class = 'footer-text-container'>
<div class = 'footer-text container'><?php echo $landing_footer_text;
?></div>
</div>

</article>
<?php
endwhile;
else :
echo '<p>No landing page found.</p>';
endif;

get_footer();
// This will include the general website footer
?>
