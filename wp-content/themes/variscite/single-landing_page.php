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
        // Get the group field
        $header_group = get_field('header_group');
        $header_image = $header_group['landing_header_image'];
        $header_text = $header_group['landing_header_text'];
        $compliance_icons = $header_group['landing_images_repeater'];


        // Check if each field is empty and get default values from settings if they are
        if (empty($header_image)) {
            $default_header_group = get_field('header_group_settings', 'option');
            $header_image = $default_header_group['landing_header_image_settings'];
        }

        if (empty($header_text)) {
            if (!isset($default_header_group)) {
                $default_header_group = get_field('header_group_settings', 'option');
            }
            $header_text = $default_header_group['landing_header_text_settings'];
        }

        if (empty($compliance_icons)) {
            if (!isset($default_header_group)) {
                $default_header_group = get_field('header_group_settings', 'option');
            }
            $compliance_icons = $default_header_group['landing_images_repeater_settings'];
        }


        




        ?>
       
        <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
            <header class="entry-header">
                <div class="landing_page_header">
                    <div class="landing_page_header-left">
                        <?php if ($header_text): ?>
                            <?php echo $header_text; ?>
                        <?php endif; ?>
                        <div class="landing_page_image-row">
                            <?php if ($compliance_icons): ?>
                                <?php foreach ($compliance_icons as $icon): ?>
                                    <div class="landing_page_image">
                                        <img src="<?php echo esc_url($icon['compliance_icon']); ?>" alt="Compliance Icon">
                                    </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="landing_page_header-right">
                        <?php if ($header_image): ?>
                            <img src="<?php echo esc_url($header_image); ?>" alt="Header Right Image">
                        <?php endif; ?>
                    </div>
                </div>
            </header>

            <div class="entry-content">
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
                                        $post_price = get_field('field_specs_page_product_price', $product->ID);
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
                                        } else {
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
                <div class="container container_som_categories">
                    <div class="main-table">
                        <div>
                            <div class="nested-table">
                                <div class="nested-row">
                                    <div class="nested-cell">
                                        <h2>Popular System on Module Categories</h2>
                                    </div>
                                </div>
                                <div class="nested-row">
                                    <div class="nested-cell">
                                        <div class="nested-table">
                                            <div class="nested-row">
                                                <div class="nested-cell">
                                                    <div class="box">
                                                        <h3>NXP i.MX8</h3>
                                                    </div>
                                                </div>
                                                <div class="nested-cell">
                                                    <div class="box">
                                                        <h3>Box 2 Title</h3>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="nested-row">
                                                <div class="nested-cell">
                                                    <div class="box">
                                                        <h3>Box 3 Title</h3>
                                                    </div>
                                                </div>
                                                <div class="nested-cell">
                                                    <div class="box">
                                                        <h3>Box 4 Title</h3>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="image-container">
                            <img src="https://office.variscite.co.uk/wp-content/uploads/2024/07/VAR-SOM-AM62-dual.png" alt="Image">
                        </div>
                </div>
                <?php echo get_field('landing_contact_text_settings', 'option');?>
                
            </div>  
            <div id="pg-17-1" class="panel-grid panel-has-style showInAll"><div class="form-row skewed-section panel-row-style panel-row-style-for-17-1"><div class="innerWrap  cols-2 ">
                <div id="pgc-17-1-0" class="panel-grid-cell">
		
		</div><div id="pgc-17-1-1" class="panel-grid-cell"><div id="panel-17-1-1-0" class="so-panel widget widget_text panel-first-child panel-last-child" data-index="3">			<div class="textwidget">
    <div id="quoteFormWidget" class="quote-form contactUsForm" style="border-color: #000000;  ">
    <input type="hidden" id="curl" name="curl" value="https://www.variscite.com/contact-us/">
    <input type="hidden" id="action_type" name="action_type" value="send_widget_quote">
    <input type="hidden" id="email_to" name="email_to" value="sales@variscite.com">
    <input type="hidden" id="email_subject" name="email_subject" value="New lead from contact us page">
    <input type="hidden" id="thanks" name="thanks" value="/thank-you-for-contacting-us-contact-form/">
    <input type="hidden" id="required" name="required" value="first_name,last_name,company,email,country,phone">
    <input type="hidden" id="lead_source" name="lead_source" value="Contact us Web">
    <input type="hidden" id="event_name" name="event_name" value="form-mainSite-contactUs-success">

    <!--=== ADWORDS FIELDS ===-->
    <input type="hidden" id="Campaign_medium__c" name="Campaign_medium__c" value="N/A">
    <input type="hidden" id="Campaign_source__c" name="Campaign_source__c" value="direct">
    <input type="hidden" id="Campaign_content__c" name="Campaign_content__c" value="N/A">
    <input type="hidden" id="Campaign_term__c" name="Campaign_term__c" value="N/A">
    <input type="hidden" id="Page_url__c" name="Page_url__c" value="https://www.variscite.com/contact-us/">
    <input type="hidden" id="Paid_Campaign_Name__c" name="Paid_Campaign_Name__c" value="N/A">


    <!--=== ADWORDS FIELDS ===--><div class="form-inner">
        <div class="row">
            <div class="col-md-6 field-box form-group field-first_name col-md-6">
                <div class="field-wrap">
                    <div class="row">

                        <div class="col-md-7"><input type="text" name="first_name" id="first_name" class="form-control" placeholder="First Name" value=""></div>
                    </div>
                </div>
            </div>

            <div class="col-md-6 field-box form-group field-last_name col-md-6">
                <div class="field-wrap">
                    <div class="row">

                        <div class="col-md-7"><input type="text" name="last_name" id="last_name" class="form-control" placeholder="Last Name" value=""></div>
                    </div>
                </div>
            </div>

            <div class="col-md-6 field-box form-group field-email col-md-6">
                <div class="field-wrap">
                    <div class="row">

                        <div class="col-md-7"><input type="text" name="email" id="email" class="form-control" placeholder="Email" value=""></div>
                    </div>
                </div>
            </div>

            <div class="col-md-6 field-box form-group field-company col-md-6">
                <div class="field-wrap">
                    <div class="row">

                        <div class="col-md-7"><input type="text" name="company" id="company" class="form-control" placeholder="Company" value=""></div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 field-box form-group field-country col-md-6">
                <div class="field-wrap">
                    <div class="row">

                        <div class="col-md-7">
                            <select name="country" id="country" class="form-control">
                                <option value="">Country</option>
                                <option value="afghanistan">Afghanistan</option><option value="aland-islands">Aland Islands</option><option value="albania">Albania</option><option value="algeria">Algeria</option><option value="andorra">Andorra</option><option value="angola">Angola</option><option value="anguilla">Anguilla</option><option value="antarctica">Antarctica</option><option value="antigua-and-barbuda">Antigua and Barbuda</option><option value="argentina">Argentina</option><option value="armenia">Armenia</option><option value="aruba">Aruba</option><option value="australia">Australia</option><option value="austria">Austria</option><option value="azerbaijan">Azerbaijan</option><option value="bahamas">Bahamas</option><option value="bahrain">Bahrain</option><option value="bangladesh">Bangladesh</option><option value="barbados">Barbados</option><option value="belarus">Belarus</option><option value="belgium">Belgium</option><option value="belize">Belize</option><option value="benin">Benin</option><option value="bermuda">Bermuda</option><option value="bhutan">Bhutan</option><option value="bolivia,-plurinational-state-of">Bolivia, Plurinational State of</option><option value="bonaire,-sint-eustatius-and-saba">Bonaire, Sint Eustatius and Saba</option><option value="bosnia-and-herzegovina">Bosnia and Herzegovina</option><option value="botswana">Botswana</option><option value="bouvet-island">Bouvet Island</option><option value="brazil">Brazil</option><option value="british-indian-ocean-territory">British Indian Ocean Territory</option><option value="brunei-darussalam">Brunei Darussalam</option><option value="bulgaria">Bulgaria</option><option value="burkina-faso">Burkina Faso</option><option value="burundi">Burundi</option><option value="cambodia">Cambodia</option><option value="cameroon">Cameroon</option><option value="canada">Canada</option><option value="cape-verde">Cape Verde</option><option value="cayman-islands">Cayman Islands</option><option value="central-african-republic">Central African Republic</option><option value="chad">Chad</option><option value="chile">Chile</option><option value="china">China</option><option value="christmas-island">Christmas Island</option><option value="cocos-(keeling)-islands">Cocos (Keeling) Islands</option><option value="colombia">Colombia</option><option value="comoros">Comoros</option><option value="congo">Congo</option><option value="congo,-the-democratic-republic-of-the">Congo, the Democratic Republic of the</option><option value="cook-islands">Cook Islands</option><option value="costa-rica">Costa Rica</option><option value="cote-d'ivoire">Cote d'Ivoire</option><option value="croatia">Croatia</option><option value="cuba">Cuba</option><option value="curaçao">Curaçao</option><option value="cyprus">Cyprus</option><option value="czech-republic">Czech Republic</option><option value="denmark">Denmark</option><option value="djibouti">Djibouti</option><option value="dominica">Dominica</option><option value="dominican-republic">Dominican Republic</option><option value="ecuador">Ecuador</option><option value="egypt">Egypt</option><option value="el-salvador">El Salvador</option><option value="equatorial-guinea">Equatorial Guinea</option><option value="eritrea">Eritrea</option><option value="estonia">Estonia</option><option value="ethiopia">Ethiopia</option><option value="falkland-islands-(malvinas)">Falkland Islands (Malvinas)</option><option value="faroe-islands">Faroe Islands</option><option value="fiji">Fiji</option><option value="finland">Finland</option><option value="france">France</option><option value="french-guiana">French Guiana</option><option value="french-polynesia">French Polynesia</option><option value="french-southern-territories">French Southern Territories</option><option value="gabon">Gabon</option><option value="gambia">Gambia</option><option value="georgia">Georgia</option><option value="germany">Germany</option><option value="ghana">Ghana</option><option value="gibraltar">Gibraltar</option><option value="greece">Greece</option><option value="greenland">Greenland</option><option value="grenada">Grenada</option><option value="guadeloupe">Guadeloupe</option><option value="guatemala">Guatemala</option><option value="guernsey">Guernsey</option><option value="guinea">Guinea</option><option value="guinea-bissau">Guinea-Bissau</option><option value="guyana">Guyana</option><option value="haiti">Haiti</option><option value="heard-island-and-mcdonald-islands">Heard Island and McDonald Islands</option><option value="holy-see-(vatican-city-state)">Holy See (Vatican City State)</option><option value="honduras">Honduras</option><option value="hungary">Hungary</option><option value="iceland">Iceland</option><option value="india">India</option><option value="indonesia">Indonesia</option><option value="iran,-islamic-republic-of">Iran, Islamic Republic of</option><option value="iraq">Iraq</option><option value="ireland">Ireland</option><option value="isle-of-man">Isle of Man</option><option value="israel">Israel</option><option value="italy">Italy</option><option value="jamaica">Jamaica</option><option value="japan">Japan</option><option value="jersey">Jersey</option><option value="jordan">Jordan</option><option value="kazakhstan">Kazakhstan</option><option value="kenya">Kenya</option><option value="kiribati">Kiribati</option><option value="korea,-republic-of">Korea, Republic of</option><option value="kuwait">Kuwait</option><option value="kyrgyzstan">Kyrgyzstan</option><option value="lao-people's-democratic-republic">Lao People's Democratic Republic</option><option value="latvia">Latvia</option><option value="lebanon">Lebanon</option><option value="lesotho">Lesotho</option><option value="liberia">Liberia</option><option value="libya">Libya</option><option value="liechtenstein">Liechtenstein</option><option value="lithuania">Lithuania</option><option value="luxembourg">Luxembourg</option><option value="macao">Macao</option><option value="macedonia,-the-former-yugoslav-republic-of">Macedonia, the former Yugoslav Republic of</option><option value="madagascar">Madagascar</option><option value="malawi">Malawi</option><option value="malaysia">Malaysia</option><option value="maldives">Maldives</option><option value="mali">Mali</option><option value="malta">Malta</option><option value="martinique">Martinique</option><option value="mauritania">Mauritania</option><option value="mauritius">Mauritius</option><option value="mayotte">Mayotte</option><option value="mexico">Mexico</option><option value="moldova,-republic-of">Moldova, Republic of</option><option value="monaco">Monaco</option><option value="mongolia">Mongolia</option><option value="montenegro">Montenegro</option><option value="montserrat">Montserrat</option><option value="morocco">Morocco</option><option value="mozambique">Mozambique</option><option value="myanmar">Myanmar</option><option value="namibia">Namibia</option><option value="nauru">Nauru</option><option value="nepal">Nepal</option><option value="netherlands">Netherlands</option><option value="new-caledonia">New Caledonia</option><option value="new-zealand">New Zealand</option><option value="nicaragua">Nicaragua</option><option value="niger">Niger</option><option value="nigeria">Nigeria</option><option value="niue">Niue</option><option value="norfolk-island">Norfolk Island</option><option value="norway">Norway</option><option value="oman">Oman</option><option value="pakistan">Pakistan</option><option value="panama">Panama</option><option value="papua-new-guinea">Papua New Guinea</option><option value="paraguay">Paraguay</option><option value="peru">Peru</option><option value="philippines">Philippines</option><option value="pitcairn">Pitcairn</option><option value="poland">Poland</option><option value="portugal">Portugal</option><option value="qatar">Qatar</option><option value="reunion">Reunion</option><option value="romania">Romania</option><option value="russian-federation">Russian Federation</option><option value="rwanda">Rwanda</option><option value="saint-barthélemy">Saint Barthélemy</option><option value="saint-helena,-ascension-and-tristan-da-cunha">Saint Helena, Ascension and Tristan da Cunha</option><option value="saint-kitts-and-nevis">Saint Kitts and Nevis</option><option value="saint-lucia">Saint Lucia</option><option value="saint-martin-(french-part)">Saint Martin (French part)</option><option value="saint-pierre-and-miquelon">Saint Pierre and Miquelon</option><option value="saint-vincent-and-the-grenadines">Saint Vincent and the Grenadines</option><option value="samoa">Samoa</option><option value="san-marino">San Marino</option><option value="sao-tome-and-principe">Sao Tome and Principe</option><option value="saudi-arabia">Saudi Arabia</option><option value="senegal">Senegal</option><option value="serbia">Serbia</option><option value="seychelles">Seychelles</option><option value="sierra-leone">Sierra Leone</option><option value="singapore">Singapore</option><option value="sint-maarten-(dutch-part)">Sint Maarten (Dutch part)</option><option value="slovakia">Slovakia</option><option value="slovenia">Slovenia</option><option value="solomon-islands">Solomon Islands</option><option value="somalia">Somalia</option><option value="south-africa">South Africa</option><option value="south-georgia-and-the-south-sandwich-islands">South Georgia and the South Sandwich Islands</option><option value="south-sudan">South Sudan</option><option value="spain">Spain</option><option value="sri-lanka">Sri Lanka</option><option value="sudan">Sudan</option><option value="suriname">Suriname</option><option value="svalbard-and-jan-mayen">Svalbard and Jan Mayen</option><option value="swaziland">Swaziland</option><option value="sweden">Sweden</option><option value="switzerland">Switzerland</option><option value="syrian-arab-republic">Syrian Arab Republic</option><option value="taiwan">Taiwan</option><option value="tajikistan">Tajikistan</option><option value="tanzania,-united-republic-of">Tanzania, United Republic of</option><option value="thailand">Thailand</option><option value="timor-leste">Timor-Leste</option><option value="togo">Togo</option><option value="tokelau">Tokelau</option><option value="tonga">Tonga</option><option value="trinidad-and-tobago">Trinidad and Tobago</option><option value="tunisia">Tunisia</option><option value="turkey">Turkey</option><option value="turkmenistan">Turkmenistan</option><option value="turks-and-caicos-islands">Turks and Caicos Islands</option><option value="tuvalu">Tuvalu</option><option value="uganda">Uganda</option><option value="ukraine">Ukraine</option><option value="united-arab-emirates">United Arab Emirates</option><option value="united-kingdom">United Kingdom</option><option value="united-states">United States</option><option value="uruguay">Uruguay</option><option value="uzbekistan">Uzbekistan</option><option value="vanuatu">Vanuatu</option><option value="venezuela,-bolivarian-republic-of">Venezuela, Bolivarian Republic of</option><option value="viet-nam">Viet Nam</option><option value="virgin-islands,-british">Virgin Islands, British</option><option value="wallis-and-futuna">Wallis and Futuna</option><option value="western-sahara">Western Sahara</option><option value="yemen">Yemen</option><option value="zambia">Zambia</option><option value="zimbabwe">Zimbabwe</option>                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 field-box form-group field-phone col-md-6">
                <div class="field-wrap">
                    <div class="row">

                        <div class="col-md-7"><input type="text" id="phone" name="phone" class="form-control" placeholder="Phone" value=""></div>
                    </div>
                </div>
            </div>
         
              <div class="field-box form-group field-note col-md-12">
                <div class="field-wrap">
                    <textarea maxlength="2000" id="note" name="note" cols="30" rows="10" class="form-control" placeholder="Note..." value=""></textarea>
                </div>
            </div>

            <div class="field-box form-group col-md-12 field-agreement_checkbox">
                <div class="field-wrap-transparent">
                    <input type="checkbox" id="agreement_checkbox" name="agreement" value="2024-07-23T13:00:58Z">
                    <label for="agreement_checkbox">I agree to the Variscite                        <a href="https://www.variscite.com/privacy-policy/" target="_blank" rel="noopener">Privacy Policy</a>
                    </label>
                </div>
            </div>

        </div>
    </div>
    <div class="submit-box col-md-12">
        <div class="notice">
                    </div>

        <div class="text-right">
            <input type="button" name="submit" class="btn btn-warning btn-lg btn-arrow-01 submitQuoteWidgetRequest" value="Submit"></div>
    </div>

    </div>
    
</div>
		</div></div></div></div></div>                                             
            
            </div>

            <!-- Display the landing footer text content without a footer element -->
            <?php if( get_field('landing_footer_text') ): ?>
                <div class="footer-text container"><?php the_field('landing_footer_text'); ?></div>
            <?php endif; ?>
        </article>
        <?php
    endwhile;
else :
    echo '<p>No landing page found.</p>';
endif;

get_footer(); // This will include the general website footer
?>
