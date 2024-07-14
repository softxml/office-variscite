<?php
get_header();

function get_minimal_image_html($post_id) {
    // Get the image ID (assuming the image is set as the featured image)
    $image_id = get_post_thumbnail_id($post_id);

    // Get the full-size image URL
    $image_src = wp_get_attachment_image_src($image_id, 'full')[0];

    // Get the alt text
    $alt_text = get_post_meta($image_id, '_wp_attachment_image_alt', true);

    // Construct the minimal HTML
    $image_html = '<img class="responsive" src="' . esc_url($image_src) . '" alt="' . esc_attr($alt_text) . '">';

    return $image_html;
}

if ( have_posts() ) :
    while ( have_posts() ) : the_post();
        ?>
        <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
            <header class="entry-header">
            <div class="landing_page_header">
                <div class="landing_page_header-left">
                    <?php if( get_field('landing_header_text') ): ?>
                      <?php the_field('landing_header_text'); ?>
                    <?php endif; ?>
                    <div class="landing_page_image-row">
                        <?php if (have_rows('field_landing_images_repeater')): ?>
                            <?php while (have_rows('field_landing_images_repeater')): the_row(); 
                                $image = get_sub_field('compliance_icon'); // Replace 'compliance_icon' with your sub field name if different
                            ?>
                                <?php if ($image): ?>
                                    <div class="landing_page_image">
                                        <img src="<?php echo esc_url($image); ?>" alt="Compliance Icon">
                                    </div>
                                <?php endif; ?>
                            <?php endwhile; ?>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="landing_page_header-right">
                    <?php 
                    $header_image = get_field('field_landing_header_image'); // Get the header image from ACF
                    if ($header_image): ?>
                        <img src="<?php echo esc_url($header_image); ?>" alt="Header Right Image">
                    <?php endif; ?>
                </div>
            </div>

              
            </header>

            <div class="entry-content">
                <?php
                // Selected Products
                $selected_products = get_field('landing_selected_products');
                if( $selected_products ): ?>
                    <div class="selected-products">
                        <h2>Selected Products</h2>
                        <?php foreach( $selected_products as $product ): ?>
                            <div class="product">
                                <a href="<?php echo get_permalink($product->ID); ?>"><?php echo get_the_title($product->ID); ?></a>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

                <?php
                // Featured Products
                $featured_products = get_field('landing_featured_products');
                if( $featured_products ): ?>
                        <div class="container">
                            <div class="display-posts-listing">
                            <div class="product-container">
                                <?php foreach( $featured_products as $product ): 
                                    $permalink = get_permalink($product->ID);
                                    $post_title = get_the_title($product->ID);
                                    $post_price = get_field('vrs_specs_price', $product->ID);
                                    // $image = get_the_post_thumbnail($product->ID, 'large', array('class' => 'attachment-large size-large wp-post-image'));
                                    $image = get_minimal_image_html($product->ID);

                                    // Get the product specifications (assuming you have a custom field for specifications)
                                    $specifications = [];
                                    if( have_rows('specifications', $product->ID) ) {
                                        // Loop through the rows of data
                                        while ( have_rows('specifications', $product->ID) ) {
                                            the_row();
                                            // Get sub-field values
                                            $field_name = get_sub_field('specification_name');
                                            $field_value = get_sub_field('specification_value');
                                            $specifications[] = ['name' => $field_name, 'value' => $field_value];
                                        }
                                    }
                                    else{
                                        if( have_rows('specs_category_values', $product->ID) ) {
                                        // Loop through the rows of data
                                        while ( have_rows('specs_category_values', $product->ID) ) {
                                            the_row();
                                            // Get sub-field values
                                            $field_name = get_sub_field('fld_name');
                                            $field_value = get_sub_field('fld_value');
                                            $specifications[] = ['name' => $field_name, 'value' => $field_value];
                                        }
                                    }
                                    }
                                ?>
                                    
                                        <div class="product-card">
                                            <div class="product-row">
                                                <div class="product-image">
                                                    <?php echo $image; ?>
                                                </div>
                                                <div class="product-details">
                                                    <div class="product-button">
                                                        <a class="product-info-button" href="<?php echo $permalink; ?>">Product Info <i class="fas fa-chevron-right"></i></a>
                                                    </div>
                                                    <div class="product-text">
                                                        Starting from $<?php echo $post_price; // Replace with the actual field key for price ?>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="product-specifications">
                                                <div class="product-title"><a href="<?php echo $permalink; ?>">
                                                    <?php echo $post_title; ?>
                                                </a></div>
                                                <div class="product-specifications-inner">
                                                    <?php if ($specifications): ?>
                                                        <?php foreach ($specifications as $spec): ?>
                                                            <div class="specification">
                                                                <div class="specification-name"><?php echo esc_html($spec['name']); ?>:</div>
                                                                <div class="specification-value"><?php echo esc_html($spec['value']); ?></div>
                                                            </div>
                                                        <?php endforeach; ?>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        </div>
                                    
                                <?php endforeach; ?>
                                                        </div>
                            </div>
                        </div>
                   
                <?php endif; ?>

                <?php the_content(); ?>
            </div>

            <!-- Display the landing footer text content without a footer element -->
            <?php if( get_field('landing_footer_text') ): ?>
                <div class="footer-text"><?php the_field('landing_footer_text'); ?></div>
            <?php endif; ?>
        </article>
        <?php
    endwhile;
else :
    echo '<p>No landing page found.</p>';
endif;

get_footer(); // This will include the general website footer
?>
