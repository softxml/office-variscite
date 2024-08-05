<?php
/*********************************************
 ** CURRENT YEAR
 *********************************************/
add_shortcode('cyear', 'sc_cyear');
function sc_cyear($atts, $content = null) {

    // return result
    return date('Y');
}




/*************************************************
 ** DYNMIC LIST
 *************************************************/
add_shortcode('list', 'sc_dynamic_list');
function sc_dynamic_list($atts, $content = null) {

    extract(shortcode_atts(array(
        "design"    => '',
        "classes"   => '',
        "id"        => ''
    ), $atts));


    // wrap inner LI with span
    $content = str_replace('<li>', '<li><span>', $content);
    $content = str_replace('</li>', '</span></li>', $content);


    // return result
    return '
    <div id="'.$id.'" class="clist list-'.$design.' '.$classes.'">
        '.$content.'
    </div>
    ';
}



/*************************************************
 ** EASY ICONS
 *************************************************/
add_shortcode('icon', 'sc_easy_icon');
function sc_easy_icon($atts, $content = null) {

    extract(shortcode_atts(array(
        "name"    	=> '',
        "classes"   => ''
    ), $atts));


    // return result
    return '<img src="'.IMG_URL.'/icons/'.$name.'.png" alt="'.$name.'" '.($classes ? 'class="'.$classes.'"' : '').' >';
}



/*************************************************
 ** EASY TOOLTIP
 *************************************************/
add_shortcode('tooltip', 'sc_easy_tooltip');
function sc_easy_tooltip($atts, $content = null) {

    extract(shortcode_atts(array(
        "tip"    	=> '',
        "classes"   => ''
    ), $atts));


    // return result
    return '<span class="slow-tip" data-toggle="tooltip" title="'.$content.'">'.content_to_excerpt($content, 25).'</span>';
}



/*************************************************
 ** EASY TOOLTIP
 *************************************************/
add_shortcode('diagram', 'sc_diagram');
function sc_diagram($atts, $content = null) {

    extract(shortcode_atts(array(
        "tip"     => '',
        "classes"   => ''
    ), $atts));

    $diagram = get_field('vrs_specs_block_diagram', get_the_ID());
    // return result

    if( have_rows('vrs_specs_block_multiple_diagrams', get_the_ID()) ): $i = 0;
        $diagram_tabs = '';
        $diagram_tabs_content = '';
        while( have_rows('vrs_specs_block_multiple_diagrams', get_the_ID()) ): the_row(); $i++;
            $image = get_sub_field('vrs_specs_block_multiple_diagrams');
            $caption = get_sub_field('vrs_specs_block_multiple_diagrams_caption');
            $diagram_tabs .= '<div class="vrs-diagram-tab-caption" data-tab="diagram-tab-'.$i.'">'.$caption.'</div><div class="vrs-diagram-image-wrap-mobile"><div data-name="diagram-tab-'.$i.'" class="vrs-diagram-tab-image"><img src="'.$image['url'].'" alt="'.$image['alt'].' '.__('Diagram', THEME_NAME).'"></div></div>';
            $diagram_tabs_content .= '<div id="diagram-tab-'.$i.'" class="vrs-diagram-tab-image"><img src="'.$image['url'].'" alt="'.$image['alt'].' '.__('Diagram', THEME_NAME).'"></div>';
        endwhile;

        return '<div class="vrs-diagrams-wrap"><div class="vrs-diagram-tabs">'.$diagram_tabs.'</div><div class="vrs-diagram-image-wrap-desktop">'.$diagram_tabs_content.'</div></div>';

    elseif($diagram) :
        return '<img src="'.$diagram['url'].'" alt="'.$diagram['alt'].' '.__('Diagram', THEME_NAME).'">';
    endif;
}


/*************************************************
 ** EASY BUTTON
 *************************************************/
add_shortcode('button', 'sc_button');
function sc_button($atts, $content = null) {

    extract(shortcode_atts(array(
        "type"    	=> '',
        "url"    	=> '',
        "size"    	=> '',
        "classes"   => ''
    ), $atts));

    return '<a href="'.$url.'" class="btn btn-'.$type.' '.$size.' '.$classes.'">'.$content.'</a>';
}




/*********************************************
 ** EASY TAB LINK
 *********************************************/
add_shortcode('tab', 'ez_tablink');
function ez_tablink($atts, $content = null) {

    extract(shortcode_atts(array(
        "name"    	=> '',
        "classes"   => ''
    ), $atts));

    return '<a href="#'.str2id($name).'" class="tab-link '.$classes.'">'.$content.'</a>';
}

/*********   New contact form   ********/
add_shortcode( 'new_contact_form', 'contact_form' );
function contact_form($atts) {
    $is_valid = true;
    $message  = '';

    if(isset($_GET['noajax'])) {
    ?>

        <script type="text/javascript">
            console.log('noajax page');
        </script>

    <?php
    }

    if(isset($_POST['submit']) && esc_html($_POST['submit']) === 'Submit') {
        $email = $_POST['form_data'];

        $reqs  = array(
            'first_name' => 'First Name',
            'last_name'  => 'Last Name',
            'company'    => 'Company Name',
            'email'      => 'A Valid Email',
            'phone'      => 'Phone',
            'country'    => 'Country'
        );

        foreach(explode(',', esc_html($email['required'])) as $req) {

            if(! $is_valid) {
                continue;
            }

            if(! isset($email[$req]) || empty($email[$req])) {
                $is_valid = false;
                $message  = "{$reqs[$req]} is Required";
            }
        }

        if($is_valid && ! is_valid_email($email['email'])) {
            $is_valid = false;
            $message  = "{$reqs['email']} is Required";
        }

        if($is_valid && (! isset($email['System__c']) || empty($email['System__c']))) {
            $is_valid = false;
            $message  = 'Please select SoM Platform';
        }

        if($is_valid && (! isset($email['Projected_Quantities__c']) || empty($email['Projected_Quantities__c']))) {
            $is_valid = false;
            $message  = 'Please select Estimated Project Quantities';
        }

        if($is_valid) {
            require_once(WP_CONTENT_DIR . '/plugins/variscite-salesforce/inc/global-ajax.php');
            $resp = global_ajaxfunc();

            if(isset($resp['thanks']) && ! empty($resp['thanks'])) {
                wp_redirect($resp['thanks']);
                exit;
            }
        } else {
            ?>

            <script>
                document.addEventListener('DOMContentLoaded', function(e) {
                    document.getElementById('pg-17-1').scrollIntoView({
                        behavior: 'instant'
                    });
                });
            </script>

            <?php
        }
    }

    $current_date = Date("Y-m-d\TH:i:s\Z");
    $tag          = isset($_GET['noajax']) ? 'form' : 'div';
    $actual_link  = "https://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
    $form_atts    = isset($_GET['noajax']) ? "method=\"post\" action=\"$actual_link\"" : '';
    $name_prefix  = isset($_GET['noajax']) ? 'form_data[' : '';
    $name_suffix  = isset($_GET['noajax']) ? ']' : '';

    ob_start();
    ?>

    <<?php echo "$tag $form_atts"; ?> id="quoteFormWidget" class="quote-form contactUsForm" style="border-color: #000000;  ">
    <input type="hidden" id="curl" name="<?php printf('%s%s%s', $name_prefix, 'curl', $name_suffix); ?>" value="/contact-us/">
    <input type="hidden" id="action_type" name="action_type" value="send_widget_quote" />
    <input type="hidden" id="email_to" name="<?php printf('%s%s%s', $name_prefix, 'email_to', $name_suffix); ?>" value="sales@variscite.com">
    <input type="hidden" id="email_subject" name="<?php printf('%s%s%s', $name_prefix, 'email_subject', $name_suffix); ?>" value="New lead from contact us page">
    <input type="hidden" id="thanks" name="<?php printf('%s%s%s', $name_prefix, 'thanks', $name_suffix); ?>" value="<?php echo $atts["thanks"]; ?>">
    <input type="hidden" id="required" name="<?php printf('%s%s%s', $name_prefix, 'required', $name_suffix); ?>" value="first_name,last_name,company,email,country,phone">
    <input type="hidden" id="lead_source" name="<?php printf('%s%s%s', $name_prefix, 'lead_source', $name_suffix); ?>" value="<?php echo $atts["lead-src"]; ?>">
    <input type="hidden" id="event_name" name="<?php printf('%s%s%s', $name_prefix, 'event_name', $name_suffix); ?>" value="form-mainSite-contactUs-success">

    <!--=== ADWORDS FIELDS ===-->
    <input type="hidden" id="Campaign_medium__c" name="<?php printf('%s%s%s', $name_prefix, 'Campaign_medium__c', $name_suffix); ?>" value="<?php echo (isset($_GET['noajax']) ? esc_html(isset($_POST['form_data']['Campaign_medium__c'])) : 'N/A'); ?>">
    <input type="hidden" id="Campaign_source__c" name="<?php printf('%s%s%s', $name_prefix, 'Campaign_source__c', $name_suffix); ?>" value="<?php echo (isset($_GET['noajax']) ? esc_html(isset($_POST['form_data']['Campaign_source__c'])) : 'direct'); ?>">
    <input type="hidden" id="Campaign_content__c" name="<?php printf('%s%s%s', $name_prefix, 'Campaign_content__c', $name_suffix); ?>" value="<?php echo (isset($_GET['noajax']) ? esc_html(isset($_POST['form_data']['Campaign_content__c'])) : 'N/A'); ?>">
    <input type="hidden" id="Campaign_term__c" name="<?php printf('%s%s%s', $name_prefix, 'Campaign_term__c', $name_suffix); ?>" value="<?php echo (isset($_GET['noajax']) ? esc_html(isset($_POST['form_data']['Campaign_term__c'])) : 'N/A'); ?>">
    <input type="hidden" id="Page_url__c" name="<?php printf('%s%s%s', $name_prefix, 'Page_url__c', $name_suffix); ?>" value="/contact-us/">
    <input type="hidden" id="Paid_Campaign_Name__c" name="<?php printf('%s%s%s', $name_prefix, 'Paid_Campaign_Name__c', $name_suffix); ?>" value="<?php echo (isset($_GET['noajax']) ? esc_html(isset($_POST['form_data']['Paid_Campaign_Name__c'])) : 'N/A'); ?>">


    <!--=== ADWORDS FIELDS ===--><div class="form-inner">
        <div class="row">
            <div class="col-md-6 field-box form-group field-first_name col-md-6">
                <div class="field-wrap">
                    <div class="row">

                        <div class="col-md-7"><input type="text" name="<?php printf('%s%s%s', $name_prefix, 'first_name', $name_suffix); ?>" id="first_name" class="form-control"
                                                     placeholder="<?php echo $atts["f-name"]; ?>" value="<?php echo (isset($_GET['noajax']) && isset($_POST['form_data']['first_name']) ? esc_html($_POST['form_data']['first_name']) : ''); ?>"></div>
                    </div>
                </div>
            </div>

            <div class="col-md-6 field-box form-group field-last_name col-md-6">
                <div class="field-wrap">
                    <div class="row">

                        <div class="col-md-7"><input type="text" name="<?php printf('%s%s%s', $name_prefix, 'last_name', $name_suffix); ?>" id="last_name" class="form-control"
                                                     placeholder="<?php echo $atts['l-name']; ?>" value="<?php echo (isset($_GET['noajax']) && isset($_POST['form_data']['last_name']) ? esc_html($_POST['form_data']['last_name']) : ''); ?>"></div>
                    </div>
                </div>
            </div>

            <div class="col-md-6 field-box form-group field-email col-md-6">
                <div class="field-wrap">
                    <div class="row">

                        <div class="col-md-7"><input type="text" name="<?php printf('%s%s%s', $name_prefix, 'email', $name_suffix); ?>" id="email" class="form-control" placeholder="<?php echo $atts["email"]; ?>" value="<?php echo (isset($_GET['noajax']) && isset($_POST['form_data']['email']) ? esc_html($_POST['form_data']['email']) : ''); ?>"></div>
                    </div>
                </div>
            </div>

            <div class="col-md-6 field-box form-group field-company col-md-6">
                <div class="field-wrap">
                    <div class="row">

                        <div class="col-md-7"><input type="text" name="<?php printf('%s%s%s', $name_prefix, 'company', $name_suffix); ?>" id="company" class="form-control" placeholder="<?php echo $atts["company"]; ?>" value="<?php echo (isset($_GET['noajax']) && isset($_POST['form_data']['company']) ? esc_html($_POST['form_data']['company']) : ''); ?>"></div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 field-box form-group field-country col-md-6">
                <div class="field-wrap">
                    <div class="row">

                        <div class="col-md-7">
                            <select name="<?php printf('%s%s%s', $name_prefix, 'country', $name_suffix); ?>" id="country" class="form-control">
                                <option value=""><?php echo $atts["country"]; ?></option>
                                <?php
                                $countries_list = '<option value="afghanistan">Afghanistan</option><option value="aland-islands">Aland Islands</option><option value="albania">Albania</option><option value="algeria">Algeria</option><option value="andorra">Andorra</option><option value="angola">Angola</option><option value="anguilla">Anguilla</option><option value="antarctica">Antarctica</option><option value="antigua-and-barbuda">Antigua and Barbuda</option><option value="argentina">Argentina</option><option value="armenia">Armenia</option><option value="aruba">Aruba</option><option value="australia">Australia</option><option value="austria">Austria</option><option value="azerbaijan">Azerbaijan</option><option value="bahamas">Bahamas</option><option value="bahrain">Bahrain</option><option value="bangladesh">Bangladesh</option><option value="barbados">Barbados</option><option value="belarus">Belarus</option><option value="belgium">Belgium</option><option value="belize">Belize</option><option value="benin">Benin</option><option value="bermuda">Bermuda</option><option value="bhutan">Bhutan</option><option value="bolivia,-plurinational-state-of">Bolivia, Plurinational State of</option><option value="bonaire,-sint-eustatius-and-saba">Bonaire, Sint Eustatius and Saba</option><option value="bosnia-and-herzegovina">Bosnia and Herzegovina</option><option value="botswana">Botswana</option><option value="bouvet-island">Bouvet Island</option><option value="brazil">Brazil</option><option value="british-indian-ocean-territory">British Indian Ocean Territory</option><option value="brunei-darussalam">Brunei Darussalam</option><option value="bulgaria">Bulgaria</option><option value="burkina-faso">Burkina Faso</option><option value="burundi">Burundi</option><option value="cambodia">Cambodia</option><option value="cameroon">Cameroon</option><option value="canada">Canada</option><option value="cape-verde">Cape Verde</option><option value="cayman-islands">Cayman Islands</option><option value="central-african-republic">Central African Republic</option><option value="chad">Chad</option><option value="chile">Chile</option><option value="china">China</option><option value="christmas-island">Christmas Island</option><option value="cocos-(keeling)-islands">Cocos (Keeling) Islands</option><option value="colombia">Colombia</option><option value="comoros">Comoros</option><option value="congo">Congo</option><option value="congo,-the-democratic-republic-of-the">Congo, the Democratic Republic of the</option><option value="cook-islands">Cook Islands</option><option value="costa-rica">Costa Rica</option><option value="cote-d\'ivoire">Cote d\'Ivoire</option><option value="croatia">Croatia</option><option value="cuba">Cuba</option><option value="curaçao">Curaçao</option><option value="cyprus">Cyprus</option><option value="czech-republic">Czech Republic</option><option value="denmark">Denmark</option><option value="djibouti">Djibouti</option><option value="dominica">Dominica</option><option value="dominican-republic">Dominican Republic</option><option value="ecuador">Ecuador</option><option value="egypt">Egypt</option><option value="el-salvador">El Salvador</option><option value="equatorial-guinea">Equatorial Guinea</option><option value="eritrea">Eritrea</option><option value="estonia">Estonia</option><option value="ethiopia">Ethiopia</option><option value="falkland-islands-(malvinas)">Falkland Islands (Malvinas)</option><option value="faroe-islands">Faroe Islands</option><option value="fiji">Fiji</option><option value="finland">Finland</option><option value="france">France</option><option value="french-guiana">French Guiana</option><option value="french-polynesia">French Polynesia</option><option value="french-southern-territories">French Southern Territories</option><option value="gabon">Gabon</option><option value="gambia">Gambia</option><option value="georgia">Georgia</option><option value="germany">Germany</option><option value="ghana">Ghana</option><option value="gibraltar">Gibraltar</option><option value="greece">Greece</option><option value="greenland">Greenland</option><option value="grenada">Grenada</option><option value="guadeloupe">Guadeloupe</option><option value="guatemala">Guatemala</option><option value="guernsey">Guernsey</option><option value="guinea">Guinea</option><option value="guinea-bissau">Guinea-Bissau</option><option value="guyana">Guyana</option><option value="haiti">Haiti</option><option value="heard-island-and-mcdonald-islands">Heard Island and McDonald Islands</option><option value="holy-see-(vatican-city-state)">Holy See (Vatican City State)</option><option value="honduras">Honduras</option><option value="hungary">Hungary</option><option value="iceland">Iceland</option><option value="india">India</option><option value="indonesia">Indonesia</option><option value="iran,-islamic-republic-of">Iran, Islamic Republic of</option><option value="iraq">Iraq</option><option value="ireland">Ireland</option><option value="isle-of-man">Isle of Man</option><option value="israel">Israel</option><option value="italy">Italy</option><option value="jamaica">Jamaica</option><option value="japan">Japan</option><option value="jersey">Jersey</option><option value="jordan">Jordan</option><option value="kazakhstan">Kazakhstan</option><option value="kenya">Kenya</option><option value="kiribati">Kiribati</option><option value="korea,-republic-of">Korea, Republic of</option><option value="kuwait">Kuwait</option><option value="kyrgyzstan">Kyrgyzstan</option><option value="lao-people\'s-democratic-republic">Lao People\'s Democratic Republic</option><option value="latvia">Latvia</option><option value="lebanon">Lebanon</option><option value="lesotho">Lesotho</option><option value="liberia">Liberia</option><option value="libya">Libya</option><option value="liechtenstein">Liechtenstein</option><option value="lithuania">Lithuania</option><option value="luxembourg">Luxembourg</option><option value="macao">Macao</option><option value="macedonia,-the-former-yugoslav-republic-of">Macedonia, the former Yugoslav Republic of</option><option value="madagascar">Madagascar</option><option value="malawi">Malawi</option><option value="malaysia">Malaysia</option><option value="maldives">Maldives</option><option value="mali">Mali</option><option value="malta">Malta</option><option value="martinique">Martinique</option><option value="mauritania">Mauritania</option><option value="mauritius">Mauritius</option><option value="mayotte">Mayotte</option><option value="mexico">Mexico</option><option value="moldova,-republic-of">Moldova, Republic of</option><option value="monaco">Monaco</option><option value="mongolia">Mongolia</option><option value="montenegro">Montenegro</option><option value="montserrat">Montserrat</option><option value="morocco">Morocco</option><option value="mozambique">Mozambique</option><option value="myanmar">Myanmar</option><option value="namibia">Namibia</option><option value="nauru">Nauru</option><option value="nepal">Nepal</option><option value="netherlands">Netherlands</option><option value="new-caledonia">New Caledonia</option><option value="new-zealand">New Zealand</option><option value="nicaragua">Nicaragua</option><option value="niger">Niger</option><option value="nigeria">Nigeria</option><option value="niue">Niue</option><option value="norfolk-island">Norfolk Island</option><option value="norway">Norway</option><option value="oman">Oman</option><option value="pakistan">Pakistan</option><option value="panama">Panama</option><option value="papua-new-guinea">Papua New Guinea</option><option value="paraguay">Paraguay</option><option value="peru">Peru</option><option value="philippines">Philippines</option><option value="pitcairn">Pitcairn</option><option value="poland">Poland</option><option value="portugal">Portugal</option><option value="qatar">Qatar</option><option value="reunion">Reunion</option><option value="romania">Romania</option><option value="russian-federation">Russian Federation</option><option value="rwanda">Rwanda</option><option value="saint-barthélemy">Saint Barthélemy</option><option value="saint-helena,-ascension-and-tristan-da-cunha">Saint Helena, Ascension and Tristan da Cunha</option><option value="saint-kitts-and-nevis">Saint Kitts and Nevis</option><option value="saint-lucia">Saint Lucia</option><option value="saint-martin-(french-part)">Saint Martin (French part)</option><option value="saint-pierre-and-miquelon">Saint Pierre and Miquelon</option><option value="saint-vincent-and-the-grenadines">Saint Vincent and the Grenadines</option><option value="samoa">Samoa</option><option value="san-marino">San Marino</option><option value="sao-tome-and-principe">Sao Tome and Principe</option><option value="saudi-arabia">Saudi Arabia</option><option value="senegal">Senegal</option><option value="serbia">Serbia</option><option value="seychelles">Seychelles</option><option value="sierra-leone">Sierra Leone</option><option value="singapore">Singapore</option><option value="sint-maarten-(dutch-part)">Sint Maarten (Dutch part)</option><option value="slovakia">Slovakia</option><option value="slovenia">Slovenia</option><option value="solomon-islands">Solomon Islands</option><option value="somalia">Somalia</option><option value="south-africa">South Africa</option><option value="south-georgia-and-the-south-sandwich-islands">South Georgia and the South Sandwich Islands</option><option value="south-sudan">South Sudan</option><option value="spain">Spain</option><option value="sri-lanka">Sri Lanka</option><option value="sudan">Sudan</option><option value="suriname">Suriname</option><option value="svalbard-and-jan-mayen">Svalbard and Jan Mayen</option><option value="swaziland">Swaziland</option><option value="sweden">Sweden</option><option value="switzerland">Switzerland</option><option value="syrian-arab-republic">Syrian Arab Republic</option><option value="taiwan">Taiwan</option><option value="tajikistan">Tajikistan</option><option value="tanzania,-united-republic-of">Tanzania, United Republic of</option><option value="thailand">Thailand</option><option value="timor-leste">Timor-Leste</option><option value="togo">Togo</option><option value="tokelau">Tokelau</option><option value="tonga">Tonga</option><option value="trinidad-and-tobago">Trinidad and Tobago</option><option value="tunisia">Tunisia</option><option value="turkey">Turkey</option><option value="turkmenistan">Turkmenistan</option><option value="turks-and-caicos-islands">Turks and Caicos Islands</option><option value="tuvalu">Tuvalu</option><option value="uganda">Uganda</option><option value="ukraine">Ukraine</option><option value="united-arab-emirates">United Arab Emirates</option><option value="united-kingdom">United Kingdom</option><option value="united-states">United States</option><option value="uruguay">Uruguay</option><option value="uzbekistan">Uzbekistan</option><option value="vanuatu">Vanuatu</option><option value="venezuela,-bolivarian-republic-of">Venezuela, Bolivarian Republic of</option><option value="viet-nam">Viet Nam</option><option value="virgin-islands,-british">Virgin Islands, British</option><option value="wallis-and-futuna">Wallis and Futuna</option><option value="western-sahara">Western Sahara</option><option value="yemen">Yemen</option><option value="zambia">Zambia</option><option value="zimbabwe">Zimbabwe</option>';

                                echo (isset($_GET['noajax']) && isset($_POST['form_data']['country']) && ! empty($_POST['form_data']['country']) ? str_replace(
                                    "value=\"{$_POST['form_data']['country']}\"",
                                    "value=\"{$_POST['form_data']['country']}\" selected",
                                    $countries_list
                                ) : $countries_list); ?>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 field-box form-group field-phone col-md-6">
                <div class="field-wrap">
                    <div class="row">

                        <div class="col-md-7"><input type="text" id="phone" name="<?php printf('%s%s%s', $name_prefix, 'phone', $name_suffix); ?>" class="form-control" placeholder="<?php echo $atts["phone"]; ?>" value="<?php echo (isset($_GET['noajax']) && isset($_POST['form_data']['phone']) ? esc_html($_POST['form_data']['phone']) : ''); ?>"></div>
                    </div>
                </div>
            </div>
            <div class="col-md-12 field-box form-group som-select">
                <div class="field-wrap">
                    <div class="row">
                        <div class="col-md-7 som-multiselect">
                            <div class="selectBox" onclick="showCheckboxes()">
                                <select name="<?php printf('%s%s%s', $name_prefix, 'som-platforms', $name_suffix); ?>" id="som-platforms" class="form-control">
                                    <option value=""><?php echo $atts["select-plat"]; ?></option>
                                </select>
                                <div class="overSelect"></div>
                            </div>

                            <div id="som-checkboxes">
                                <div class="form-group">
                                    <input type="checkbox" name="<?php printf('%s%s%s%s', $name_prefix, 'System__c', $name_suffix, (isset($_GET['noajax']) ? '[]' : '')); ?>" id="product-1277160392" value="i.MX 95" <?php echo (isset($_GET['noajax']) && isset($_POST['form_data']['System__c']) && in_array('i.MX 93', $_POST['form_data']['System__c']) ? 'checked' : ''); ?>>
                                    <label for="product-1277160392">i.MX 95</label>
                                </div>
                                <div class="form-group">
                                    <input type="checkbox" name="<?php printf('%s%s%s%s', $name_prefix, 'System__c', $name_suffix, (isset($_GET['noajax']) ? '[]' : '')); ?>" id="product-1277150480" value="i.MX 93" <?php echo (isset($_GET['noajax']) && isset($_POST['form_data']['System__c']) && in_array('i.MX 93', $_POST['form_data']['System__c']) ? 'checked' : ''); ?>>
                                    <label for="product-1277150480">i.MX 93</label>
                                </div>
                                <div class="form-group">
                                    <input type="checkbox" name="<?php printf('%s%s%s%s', $name_prefix, 'System__c', $name_suffix, (isset($_GET['noajax']) ? '[]' : '')); ?>" id="product-1277150515" value="AM625x" <?php echo (isset($_GET['noajax']) && isset($_POST['form_data']['System__c']) && in_array('AM625x', $_POST['form_data']['System__c']) ? 'checked' : ''); ?>>
                                    <label for="product-1277150515">AM625x</label>
                                </div>
                                <div class="form-group">
                                    <input type="checkbox" name="<?php printf('%s%s%s%s', $name_prefix, 'System__c', $name_suffix, (isset($_GET['noajax']) ? '[]' : '')); ?>" id="product-1277125310" value="i.MX 8M Plus" <?php echo (isset($_GET['noajax']) && isset($_POST['form_data']['System__c']) && in_array('i.MX 8M Plus', $_POST['form_data']['System__c']) ? 'checked' : ''); ?>>
                                    <label for="product-1277125310">i.MX 8M Plus</label>
                                </div>
                                <div class="form-group">
                                    <input type="checkbox" name="<?php printf('%s%s%s%s', $name_prefix, 'System__c', $name_suffix, (isset($_GET['noajax']) ? '[]' : '')); ?>" id="product-1277117426" value="i.MX 8M Mini" <?php echo (isset($_GET['noajax']) && isset($_POST['form_data']['System__c']) && in_array('i.MX 8M Mini', $_POST['form_data']['System__c']) ? 'checked' : ''); ?>>
                                    <label for="product-1277117426">i.MX 8M Mini</label>
                                </div>
                                <div class="form-group">
                                    <input type="checkbox" name="<?php printf('%s%s%s%s', $name_prefix, 'System__c', $name_suffix, (isset($_GET['noajax']) ? '[]' : '')); ?>" id="product-1277118379" value="i.MX 8M Nano" <?php echo (isset($_GET['noajax']) && isset($_POST['form_data']['System__c']) && in_array('i.MX 8M Nano', $_POST['form_data']['System__c']) ? 'checked' : ''); ?>>
                                    <label for="product-1277118379">i.MX 8M Nano</label>
                                </div>
                                <div class="form-group">
                                    <input type="checkbox" name="<?php printf('%s%s%s%s', $name_prefix, 'System__c', $name_suffix, (isset($_GET['noajax']) ? '[]' : '')); ?>" id="product-1277111620" value="i.MX 6UL 6ULL" <?php echo (isset($_GET['noajax']) && isset($_POST['form_data']['System__c']) && in_array('i.MX 6UL 6ULL', $_POST['form_data']['System__c']) ? 'checked' : ''); ?>>
                                    <label for="product-1277111620">i.MX 6UL / 6ULL</label>
                                </div>
                                <div class="form-group">
                                    <input type="checkbox" name="<?php printf('%s%s%s%s', $name_prefix, 'System__c', $name_suffix, (isset($_GET['noajax']) ? '[]' : '')); ?>" id="product-2810" value="i.MX 7" <?php echo (isset($_GET['noajax']) && isset($_POST['form_data']['System__c']) && in_array('i.MX 7', $_POST['form_data']['System__c']) ? 'checked' : ''); ?>>
                                    <label for="product-2810">i.MX 7</label>
                                </div>
                                <div class="form-group">
                                    <input type="checkbox" name="<?php printf('%s%s%s%s', $name_prefix, 'System__c', $name_suffix, (isset($_GET['noajax']) ? '[]' : '')); ?>" id="product-10997" value="i.MX 8X" <?php echo (isset($_GET['noajax']) && isset($_POST['form_data']['System__c']) && in_array('i.MX 8X', $_POST['form_data']['System__c']) ? 'checked' : ''); ?>>
                                    <label for="product-10997">i.MX 8X</label>
                                </div>
                                <div class="form-group">
                                    <input type="checkbox" name="<?php printf('%s%s%s%s', $name_prefix, 'System__c', $name_suffix, (isset($_GET['noajax']) ? '[]' : '')); ?>" id="product-7330" value="i.MX 8 QuadMax" <?php echo (isset($_GET['noajax']) && isset($_POST['form_data']['System__c']) && in_array('i.MX 8 QuadMax', $_POST['form_data']['System__c']) ? 'checked' : ''); ?>>
                                    <label for="product-7330">i.MX 8 QuadMax</label>
                                </div>
                                <div class="form-group">
                                    <input type="checkbox" name="<?php printf('%s%s%s%s', $name_prefix, 'System__c', $name_suffix, (isset($_GET['noajax']) ? '[]' : '')); ?>" id="product-4114" value="i.MX 8M" <?php echo (isset($_GET['noajax']) && isset($_POST['form_data']['System__c']) && in_array('i.MX 8M', $_POST['form_data']['System__c']) ? 'checked' : ''); ?>>
                                    <label for="product-4114">i.MX 8M</label>
                                </div>
                                <div class="form-group">
                                    <input type="checkbox" name="<?php printf('%s%s%s%s', $name_prefix, 'System__c', $name_suffix, (isset($_GET['noajax']) ? '[]' : '')); ?>" id="product-1087" value="i.MX 6" <?php echo (isset($_GET['noajax']) && isset($_POST['form_data']['System__c']) && in_array('i.MX 6', $_POST['form_data']['System__c']) ? 'checked' : ''); ?>>
                                    <label for="product-1087">i.MX 6</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-12 quantities-select form-group">
                <div class="field-wrap">
                    <div class="row">
                        <div class="col-md-7 quantity-wrapper">
                            <select name="<?php printf('%s%s%s', $name_prefix, 'Projected_Quantities__c', $name_suffix); ?>" id="Projected_Quantities__c" class="form-control">
                                <option value=""><?php echo $atts["quantity"]; ?></option>
                                <option <?php echo (isset($_GET['noajax']) && $_POST['form_data']['Projected_Quantities__c'] == '1-100' ? 'selected' : ''); ?> value="1-100">1-100</option>
                                <option <?php echo (isset($_GET['noajax']) && $_POST['form_data']['Projected_Quantities__c'] == '100-500' ? 'selected' : ''); ?> value="100-500">100-500</option>
                                <option <?php echo (isset($_GET['noajax']) && $_POST['form_data']['Projected_Quantities__c'] == '500-1000' ? 'selected' : ''); ?> value="500-1000">500-1000</option>
                                <option <?php echo (isset($_GET['noajax']) && $_POST['form_data']['Projected_Quantities__c'] == '1000-3000' ? 'selected' : ''); ?> value="1000-3000">1000-3000</option>
                                <option <?php echo (isset($_GET['noajax']) && $_POST['form_data']['Projected_Quantities__c'] == '3000-5000' ? 'selected' : ''); ?> value="3000-5000">3000-5000</option>
                                <option <?php echo (isset($_GET['noajax']) && $_POST['form_data']['Projected_Quantities__c'] == '>5000' ? 'selected' : ''); ?> value=">5000">&gt;5000</option>
                            </select></div>
                    </div>
                </div>
            </div>

            <div class="field-box form-group field-note col-md-12">
                <div class="field-wrap">
                    <textarea maxlength="2000" id="note" name="<?php printf('%s%s%s', $name_prefix, 'note', $name_suffix); ?>" cols="30" rows="10" class="form-control" placeholder="<?php echo $atts["note"]; ?>" value="<?php echo (isset($_GET['noajax']) && isset($_POST['form_data']['note']) ? esc_html($_POST['form_data']['note']) : ''); ?>"></textarea>
                </div>
            </div>

            <div class="field-box form-group col-md-12 field-agreement_checkbox">
                <div class="field-wrap-transparent">
                    <input type="checkbox" id="agreement_checkbox" name="<?php printf('%s%s%s', $name_prefix, 'agreement', $name_suffix); ?>" value="<?php echo $current_date; ?>" <?php echo (isset($_GET['noajax']) && isset($_POST['form_data']['agreement']) && ! empty($_POST['form_data']['agreement']) ? 'checked' : ''); ?>>
                    <label for="agreement_checkbox"><?php echo $atts["privacy"]; ?>
                        <a href="/privacy-policy/" target="_blank"><?php echo $atts["privacy-link"]; ?></a>
                    </label>
                </div>
            </div>

        </div>
    </div>
    <div class="submit-box col-md-12">
        <div class="notice">
            <?php if(! $is_valid && isset($_GET['noajax'])): ?>
                <i class="fa fa-exclamation-triangle c6"></i> <?php echo $message; ?>
            <?php endif; ?>
        </div>

        <div class="text-right">
            <input type="<?php echo ($tag === 'form' ? 'submit' : 'button'); ?>" name="submit" class="btn btn-warning btn-lg btn-arrow-01 submitQuoteWidgetRequest" value="<?php echo $atts["submit-btn"]; ?>"></div>
    </div>

    </<?php echo $tag; ?>>
    <?php
    $content = ob_get_contents();
    ob_end_clean();
    return $content;
}
/*********   END of New contact form ********/

/*********   New Exit popup  form   ********/

add_shortcode( 'exit_contact_form_popup', 'exit_contact_form_callback' );
function exit_contact_form_callback($atts){
    $current_date = Date("Y-m-d\TH:i:s\Z");

    ob_start();
    ?>
    <div id="conFormExitPopup" class="quote-form contactUsForm" style="border-color: #000000;  ">
        <input type="hidden" id="curl" value="/contact-us/">

        <input type="hidden" id="email_to" value="sales@variscite.com">
        <input type="hidden" id="email_subject" value="New lead from Web-exit popup">
        <input type="hidden" id="thanks" value="/thank-you-exit-pop-up/">
        <input type="hidden" id="required" value="first_name,last_name,company,email,country,phone">
        <input type="hidden" id="lead_source" value="Web - exit popup">
        <input type="hidden" id="event_name" value="form-mainSite-contactUs-success">

        <!--=== ADWORDS FIELDS ===-->
        <input type="hidden" id="Campaign_medium__c" value="N/A">
        <input type="hidden" id="Campaign_source__c" value="direct">
        <input type="hidden" id="Campaign_content__c" value="N/A">
        <input type="hidden" id="Campaign_term__c" value="N/A">
        <input type="hidden" id="Page_url__c" value="/contact-us/">
        <input type="hidden" id="Paid_Campaign_Name__c" value="N/A">


        <!--=== ADWORDS FIELDS ===--><div class="form-inner">
            <div class="row">
                <div class="col-md-6 col-sm-6 col-xs-12 field-box form-group field-first_name">
                    <div class="field-wrap">
                        <input type="text" name="first_name" id="first_name" class="form-control"
                               placeholder="<?php echo $atts["f-name"]; ?>" value="">
                    </div>
                </div>

                <div class="col-md-6 col-sm-6 col-xs-12 field-box form-group field-last_name">
                    <div class="field-wrap">
                        <input type="text" name="last_name" id="last_name" class="form-control"
                               placeholder="<?php echo $atts['l-name']; ?>" value="">
                    </div>
                </div>

                <div class="col-md-12 col-xs-12 field-box form-group field-email">

                    <div class="field-wrap">
                        <input type="text" name="email" id="email" class="form-control" placeholder="<?php echo $atts["email"]; ?>" value="">
                    </div>
                </div>

                <div class="col-md-12 col-xs-12 field-box form-group field-company">
                    <div class="field-wrap">
                        <input type="text" name="company" id="company" class="form-control" placeholder="<?php echo $atts["company"]; ?>" value="">
                    </div>
                </div>
                <div class="col-md-6 col-sm-6 col-xs-12 field-box form-group field-country">
                    <div class="field-wrap">
                        <select name="country_exit" id="country_exit" class="form-control"><option value=""><?php echo $atts["country"]; ?></option><option value="afghanistan">Afghanistan</option><option value="aland-islands">Aland Islands</option><option value="albania">Albania</option><option value="algeria">Algeria</option><option value="andorra">Andorra</option><option value="angola">Angola</option><option value="anguilla">Anguilla</option><option value="antarctica">Antarctica</option><option value="antigua-and-barbuda">Antigua and Barbuda</option><option value="argentina">Argentina</option><option value="armenia">Armenia</option><option value="aruba">Aruba</option><option value="australia">Australia</option><option value="austria">Austria</option><option value="azerbaijan">Azerbaijan</option><option value="bahamas">Bahamas</option><option value="bahrain">Bahrain</option><option value="bangladesh">Bangladesh</option><option value="barbados">Barbados</option><option value="belarus">Belarus</option><option value="belgium">Belgium</option><option value="belize">Belize</option><option value="benin">Benin</option><option value="bermuda">Bermuda</option><option value="bhutan">Bhutan</option><option value="bolivia,-plurinational-state-of">Bolivia, Plurinational State of</option><option value="bonaire,-sint-eustatius-and-saba">Bonaire, Sint Eustatius and Saba</option><option value="bosnia-and-herzegovina">Bosnia and Herzegovina</option><option value="botswana">Botswana</option><option value="bouvet-island">Bouvet Island</option><option value="brazil">Brazil</option><option value="british-indian-ocean-territory">British Indian Ocean Territory</option><option value="brunei-darussalam">Brunei Darussalam</option><option value="bulgaria">Bulgaria</option><option value="burkina-faso">Burkina Faso</option><option value="burundi">Burundi</option><option value="cambodia">Cambodia</option><option value="cameroon">Cameroon</option><option value="canada">Canada</option><option value="cape-verde">Cape Verde</option><option value="cayman-islands">Cayman Islands</option><option value="central-african-republic">Central African Republic</option><option value="chad">Chad</option><option value="chile">Chile</option><option value="china">China</option><option value="christmas-island">Christmas Island</option><option value="cocos-(keeling)-islands">Cocos (Keeling) Islands</option><option value="colombia">Colombia</option><option value="comoros">Comoros</option><option value="congo">Congo</option><option value="congo,-the-democratic-republic-of-the">Congo, the Democratic Republic of the</option><option value="cook-islands">Cook Islands</option><option value="costa-rica">Costa Rica</option><option value="cote-d'ivoire">Cote d'Ivoire</option><option value="croatia">Croatia</option><option value="cuba">Cuba</option><option value="curaçao">Curaçao</option><option value="cyprus">Cyprus</option><option value="czech-republic">Czech Republic</option><option value="denmark">Denmark</option><option value="djibouti">Djibouti</option><option value="dominica">Dominica</option><option value="dominican-republic">Dominican Republic</option><option value="ecuador">Ecuador</option><option value="egypt">Egypt</option><option value="el-salvador">El Salvador</option><option value="equatorial-guinea">Equatorial Guinea</option><option value="eritrea">Eritrea</option><option value="estonia">Estonia</option><option value="ethiopia">Ethiopia</option><option value="falkland-islands-(malvinas)">Falkland Islands (Malvinas)</option><option value="faroe-islands">Faroe Islands</option><option value="fiji">Fiji</option><option value="finland">Finland</option><option value="france">France</option><option value="french-guiana">French Guiana</option><option value="french-polynesia">French Polynesia</option><option value="french-southern-territories">French Southern Territories</option><option value="gabon">Gabon</option><option value="gambia">Gambia</option><option value="georgia">Georgia</option><option value="germany">Germany</option><option value="ghana">Ghana</option><option value="gibraltar">Gibraltar</option><option value="greece">Greece</option><option value="greenland">Greenland</option><option value="grenada">Grenada</option><option value="guadeloupe">Guadeloupe</option><option value="guatemala">Guatemala</option><option value="guernsey">Guernsey</option><option value="guinea">Guinea</option><option value="guinea-bissau">Guinea-Bissau</option><option value="guyana">Guyana</option><option value="haiti">Haiti</option><option value="heard-island-and-mcdonald-islands">Heard Island and McDonald Islands</option><option value="holy-see-(vatican-city-state)">Holy See (Vatican City State)</option><option value="honduras">Honduras</option><option value="hungary">Hungary</option><option value="iceland">Iceland</option><option value="india">India</option><option value="indonesia">Indonesia</option><option value="iran,-islamic-republic-of">Iran, Islamic Republic of</option><option value="iraq">Iraq</option><option value="ireland">Ireland</option><option value="isle-of-man">Isle of Man</option><option value="israel">Israel</option><option value="italy">Italy</option><option value="jamaica">Jamaica</option><option value="japan">Japan</option><option value="jersey">Jersey</option><option value="jordan">Jordan</option><option value="kazakhstan">Kazakhstan</option><option value="kenya">Kenya</option><option value="kiribati">Kiribati</option><option value="korea,-republic-of">Korea, Republic of</option><option value="kuwait">Kuwait</option><option value="kyrgyzstan">Kyrgyzstan</option><option value="lao-people's-democratic-republic">Lao People's Democratic Republic</option><option value="latvia">Latvia</option><option value="lebanon">Lebanon</option><option value="lesotho">Lesotho</option><option value="liberia">Liberia</option><option value="libya">Libya</option><option value="liechtenstein">Liechtenstein</option><option value="lithuania">Lithuania</option><option value="luxembourg">Luxembourg</option><option value="macao">Macao</option><option value="macedonia,-the-former-yugoslav-republic-of">Macedonia, the former Yugoslav Republic of</option><option value="madagascar">Madagascar</option><option value="malawi">Malawi</option><option value="malaysia">Malaysia</option><option value="maldives">Maldives</option><option value="mali">Mali</option><option value="malta">Malta</option><option value="martinique">Martinique</option><option value="mauritania">Mauritania</option><option value="mauritius">Mauritius</option><option value="mayotte">Mayotte</option><option value="mexico">Mexico</option><option value="moldova,-republic-of">Moldova, Republic of</option><option value="monaco">Monaco</option><option value="mongolia">Mongolia</option><option value="montenegro">Montenegro</option><option value="montserrat">Montserrat</option><option value="morocco">Morocco</option><option value="mozambique">Mozambique</option><option value="myanmar">Myanmar</option><option value="namibia">Namibia</option><option value="nauru">Nauru</option><option value="nepal">Nepal</option><option value="netherlands">Netherlands</option><option value="new-caledonia">New Caledonia</option><option value="new-zealand">New Zealand</option><option value="nicaragua">Nicaragua</option><option value="niger">Niger</option><option value="nigeria">Nigeria</option><option value="niue">Niue</option><option value="norfolk-island">Norfolk Island</option><option value="norway">Norway</option><option value="oman">Oman</option><option value="pakistan">Pakistan</option><option value="panama">Panama</option><option value="papua-new-guinea">Papua New Guinea</option><option value="paraguay">Paraguay</option><option value="peru">Peru</option><option value="philippines">Philippines</option><option value="pitcairn">Pitcairn</option><option value="poland">Poland</option><option value="portugal">Portugal</option><option value="qatar">Qatar</option><option value="reunion">Reunion</option><option value="romania">Romania</option><option value="russian-federation">Russian Federation</option><option value="rwanda">Rwanda</option><option value="saint-barthélemy">Saint Barthélemy</option><option value="saint-helena,-ascension-and-tristan-da-cunha">Saint Helena, Ascension and Tristan da Cunha</option><option value="saint-kitts-and-nevis">Saint Kitts and Nevis</option><option value="saint-lucia">Saint Lucia</option><option value="saint-martin-(french-part)">Saint Martin (French part)</option><option value="saint-pierre-and-miquelon">Saint Pierre and Miquelon</option><option value="saint-vincent-and-the-grenadines">Saint Vincent and the Grenadines</option><option value="samoa">Samoa</option><option value="san-marino">San Marino</option><option value="sao-tome-and-principe">Sao Tome and Principe</option><option value="saudi-arabia">Saudi Arabia</option><option value="senegal">Senegal</option><option value="serbia">Serbia</option><option value="seychelles">Seychelles</option><option value="sierra-leone">Sierra Leone</option><option value="singapore">Singapore</option><option value="sint-maarten-(dutch-part)">Sint Maarten (Dutch part)</option><option value="slovakia">Slovakia</option><option value="slovenia">Slovenia</option><option value="solomon-islands">Solomon Islands</option><option value="somalia">Somalia</option><option value="south-africa">South Africa</option><option value="south-georgia-and-the-south-sandwich-islands">South Georgia and the South Sandwich Islands</option><option value="south-sudan">South Sudan</option><option value="spain">Spain</option><option value="sri-lanka">Sri Lanka</option><option value="sudan">Sudan</option><option value="suriname">Suriname</option><option value="svalbard-and-jan-mayen">Svalbard and Jan Mayen</option><option value="swaziland">Swaziland</option><option value="sweden">Sweden</option><option value="switzerland">Switzerland</option><option value="syrian-arab-republic">Syrian Arab Republic</option><option value="taiwan">Taiwan</option><option value="tajikistan">Tajikistan</option><option value="tanzania,-united-republic-of">Tanzania, United Republic of</option><option value="thailand">Thailand</option><option value="timor-leste">Timor-Leste</option><option value="togo">Togo</option><option value="tokelau">Tokelau</option><option value="tonga">Tonga</option><option value="trinidad-and-tobago">Trinidad and Tobago</option><option value="tunisia">Tunisia</option><option value="turkey">Turkey</option><option value="turkmenistan">Turkmenistan</option><option value="turks-and-caicos-islands">Turks and Caicos Islands</option><option value="tuvalu">Tuvalu</option><option value="uganda">Uganda</option><option value="ukraine">Ukraine</option><option value="united-arab-emirates">United Arab Emirates</option><option value="united-kingdom">United Kingdom</option><option value="united-states">United States</option><option value="uruguay">Uruguay</option><option value="uzbekistan">Uzbekistan</option><option value="vanuatu">Vanuatu</option><option value="venezuela,-bolivarian-republic-of">Venezuela, Bolivarian Republic of</option><option value="viet-nam">Viet Nam</option><option value="virgin-islands,-british">Virgin Islands, British</option><option value="wallis-and-futuna">Wallis and Futuna</option><option value="western-sahara">Western Sahara</option><option value="yemen">Yemen</option><option value="zambia">Zambia</option><option value="zimbabwe">Zimbabwe</option></select>
                    </div>
                </div>
                <div class="col-md-6 col-sm-6 col-xs-12 field-box form-group field-phone">
                    <div class="field-wrap">
                        <input type="text" id="phone" class="form-control" placeholder="<?php echo $atts["phone"]; ?>" value="">
                    </div>
                </div>

                <div class="field-box form-group field-note col-md-12">
                    <div class="field-wrap">
                        <textarea maxlength="2000" id="note" cols="30" rows="10" class="form-control" placeholder="<?php echo $atts["note"]; ?>"></textarea>
                    </div>
                </div>

                <div class="field-box form-group col-md-12 field-agreement_checkbox">
                    <div class="field-wrap-transparent">
                        <input type="checkbox" id="exit_agreement_checkbox" name="agreement" value="<?php echo $current_date; ?>">
                        <label for="exit_agreement_checkbox"><?php echo $atts["privacy"]; ?>
                            <a href="/privacy-policy/" target="_blank"><?php echo $atts["privacy-link"]; ?></a>
                        </label>
                    </div>
                </div>

            </div>
        </div>
        <div class="submit-box col-md-12">
            <div class="notice"></div>
            <div class="text-right">
                <input type="submit" name="submit" class="btn btn-warning btn-lg btn-arrow-01 submitExitPopupForm" value="<?php echo $atts["submit-btn"]; ?>"></div>
        </div>
    </div>
    <?php
    $content = ob_get_contents();
    ob_end_clean();
    return $content;
}
/*********   END of Exit popup form ********/

/*********   Mixed Mode article form   ********/

add_shortcode( 'mixed-mode-article-form', 'article_form' );
function article_form($atts){
    $title = get_field('article_download_title');
    $text = get_field('article_download_text');
    $link = get_field('article_download_link');


    ob_start();
    ?>
    <div class="page-container">
        <div class="form-box">
            <h1><?php echo $title; ?></h1>
            <h2><?php echo $text; ?></h2>
            <!-- Begin Mailchimp Download Form -->
            <div id="mc_embed_signup" class="article-form-wrapper">
                <form id="dl-form" class="article-form" method="POST" action="/wp-content/themes/variscite/mailchimp-api-dl-form/mc-form.php">
                    <div class="form-row">
                        <div class="mc-field-group">
                            <input type="text" value="" name="FNAME" class="required" id="FNAME" placeholder="<?php echo $atts["f-name"]; ?>">
                        </div>
                        <div class="mc-field-group">
                            <input type="text" value="" name="LNAME" class="required" id="LNAME" placeholder="<?php echo $atts['l-name']; ?>">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="mc-field-group">
                            <select name="COUNTRY" class="required form-select" id="COUNTRY">
                                <option value=""><?php echo $atts["country"]; ?></option><option value="afghanistan">Afghanistan</option><option value="aland-islands">Aland Islands</option><option value="albania">Albania</option><option value="algeria">Algeria</option><option value="andorra">Andorra</option><option value="angola">Angola</option><option value="anguilla">Anguilla</option><option value="antarctica">Antarctica</option><option value="antigua-and-barbuda">Antigua and Barbuda</option><option value="argentina">Argentina</option><option value="armenia">Armenia</option><option value="aruba">Aruba</option><option value="australia">Australia</option><option value="austria">Austria</option><option value="azerbaijan">Azerbaijan</option><option value="bahamas">Bahamas</option><option value="bahrain">Bahrain</option><option value="bangladesh">Bangladesh</option><option value="barbados">Barbados</option><option value="belarus">Belarus</option><option value="belgium">Belgium</option><option value="belize">Belize</option><option value="benin">Benin</option><option value="bermuda">Bermuda</option><option value="bhutan">Bhutan</option><option value="bolivia,-plurinational-state-of">Bolivia, Plurinational State of</option><option value="bonaire,-sint-eustatius-and-saba">Bonaire, Sint Eustatius and Saba</option><option value="bosnia-and-herzegovina">Bosnia and Herzegovina</option><option value="botswana">Botswana</option><option value="bouvet-island">Bouvet Island</option><option value="brazil">Brazil</option><option value="british-indian-ocean-territory">British Indian Ocean Territory</option><option value="brunei-darussalam">Brunei Darussalam</option><option value="bulgaria">Bulgaria</option><option value="burkina-faso">Burkina Faso</option><option value="burundi">Burundi</option><option value="cambodia">Cambodia</option><option value="cameroon">Cameroon</option><option value="canada">Canada</option><option value="cape-verde">Cape Verde</option><option value="cayman-islands">Cayman Islands</option><option value="central-african-republic">Central African Republic</option><option value="chad">Chad</option><option value="chile">Chile</option><option value="china">China</option><option value="christmas-island">Christmas Island</option><option value="cocos-(keeling)-islands">Cocos (Keeling) Islands</option><option value="colombia">Colombia</option><option value="comoros">Comoros</option><option value="congo">Congo</option><option value="congo,-the-democratic-republic-of-the">Congo, the Democratic Republic of the</option><option value="cook-islands">Cook Islands</option><option value="costa-rica">Costa Rica</option><option value="cote-d'ivoire">Cote d'Ivoire</option><option value="croatia">Croatia</option><option value="cuba">Cuba</option><option value="curaçao">Curaçao</option><option value="cyprus">Cyprus</option><option value="czech-republic">Czech Republic</option><option value="denmark">Denmark</option><option value="djibouti">Djibouti</option><option value="dominica">Dominica</option><option value="dominican-republic">Dominican Republic</option><option value="ecuador">Ecuador</option><option value="egypt">Egypt</option><option value="el-salvador">El Salvador</option><option value="equatorial-guinea">Equatorial Guinea</option><option value="eritrea">Eritrea</option><option value="estonia">Estonia</option><option value="ethiopia">Ethiopia</option><option value="falkland-islands-(malvinas)">Falkland Islands (Malvinas)</option><option value="faroe-islands">Faroe Islands</option><option value="fiji">Fiji</option><option value="finland">Finland</option><option value="france">France</option><option value="french-guiana">French Guiana</option><option value="french-polynesia">French Polynesia</option><option value="french-southern-territories">French Southern Territories</option><option value="gabon">Gabon</option><option value="gambia">Gambia</option><option value="georgia">Georgia</option><option value="germany">Germany</option><option value="ghana">Ghana</option><option value="gibraltar">Gibraltar</option><option value="greece">Greece</option><option value="greenland">Greenland</option><option value="grenada">Grenada</option><option value="guadeloupe">Guadeloupe</option><option value="guatemala">Guatemala</option><option value="guernsey">Guernsey</option><option value="guinea">Guinea</option><option value="guinea-bissau">Guinea-Bissau</option><option value="guyana">Guyana</option><option value="haiti">Haiti</option><option value="heard-island-and-mcdonald-islands">Heard Island and McDonald Islands</option><option value="holy-see-(vatican-city-state)">Holy See (Vatican City State)</option><option value="honduras">Honduras</option><option value="hungary">Hungary</option><option value="iceland">Iceland</option><option value="india">India</option><option value="indonesia">Indonesia</option><option value="iran,-islamic-republic-of">Iran, Islamic Republic of</option><option value="iraq">Iraq</option><option value="ireland">Ireland</option><option value="isle-of-man">Isle of Man</option><option value="israel">Israel</option><option value="italy">Italy</option><option value="jamaica">Jamaica</option><option value="japan">Japan</option><option value="jersey">Jersey</option><option value="jordan">Jordan</option><option value="kazakhstan">Kazakhstan</option><option value="kenya">Kenya</option><option value="kiribati">Kiribati</option><option value="korea,-republic-of">Korea, Republic of</option><option value="kuwait">Kuwait</option><option value="kyrgyzstan">Kyrgyzstan</option><option value="lao-people's-democratic-republic">Lao People's Democratic Republic</option><option value="latvia">Latvia</option><option value="lebanon">Lebanon</option><option value="lesotho">Lesotho</option><option value="liberia">Liberia</option><option value="libya">Libya</option><option value="liechtenstein">Liechtenstein</option><option value="lithuania">Lithuania</option><option value="luxembourg">Luxembourg</option><option value="macao">Macao</option><option value="macedonia,-the-former-yugoslav-republic-of">Macedonia, the former Yugoslav Republic of</option><option value="madagascar">Madagascar</option><option value="malawi">Malawi</option><option value="malaysia">Malaysia</option><option value="maldives">Maldives</option><option value="mali">Mali</option><option value="malta">Malta</option><option value="martinique">Martinique</option><option value="mauritania">Mauritania</option><option value="mauritius">Mauritius</option><option value="mayotte">Mayotte</option><option value="mexico">Mexico</option><option value="moldova,-republic-of">Moldova, Republic of</option><option value="monaco">Monaco</option><option value="mongolia">Mongolia</option><option value="montenegro">Montenegro</option><option value="montserrat">Montserrat</option><option value="morocco">Morocco</option><option value="mozambique">Mozambique</option><option value="myanmar">Myanmar</option><option value="namibia">Namibia</option><option value="nauru">Nauru</option><option value="nepal">Nepal</option><option value="netherlands">Netherlands</option><option value="new-caledonia">New Caledonia</option><option value="new-zealand">New Zealand</option><option value="nicaragua">Nicaragua</option><option value="niger">Niger</option><option value="nigeria">Nigeria</option><option value="niue">Niue</option><option value="norfolk-island">Norfolk Island</option><option value="norway">Norway</option><option value="oman">Oman</option><option value="pakistan">Pakistan</option><option value="panama">Panama</option><option value="papua-new-guinea">Papua New Guinea</option><option value="paraguay">Paraguay</option><option value="peru">Peru</option><option value="philippines">Philippines</option><option value="pitcairn">Pitcairn</option><option value="poland">Poland</option><option value="portugal">Portugal</option><option value="qatar">Qatar</option><option value="reunion">Reunion</option><option value="romania">Romania</option><option value="russian-federation">Russian Federation</option><option value="rwanda">Rwanda</option><option value="saint-barthélemy">Saint Barthélemy</option><option value="saint-helena,-ascension-and-tristan-da-cunha">Saint Helena, Ascension and Tristan da Cunha</option><option value="saint-kitts-and-nevis">Saint Kitts and Nevis</option><option value="saint-lucia">Saint Lucia</option><option value="saint-martin-(french-part)">Saint Martin (French part)</option><option value="saint-pierre-and-miquelon">Saint Pierre and Miquelon</option><option value="saint-vincent-and-the-grenadines">Saint Vincent and the Grenadines</option><option value="samoa">Samoa</option><option value="san-marino">San Marino</option><option value="sao-tome-and-principe">Sao Tome and Principe</option><option value="saudi-arabia">Saudi Arabia</option><option value="senegal">Senegal</option><option value="serbia">Serbia</option><option value="seychelles">Seychelles</option><option value="sierra-leone">Sierra Leone</option><option value="singapore">Singapore</option><option value="sint-maarten-(dutch-part)">Sint Maarten (Dutch part)</option><option value="slovakia">Slovakia</option><option value="slovenia">Slovenia</option><option value="solomon-islands">Solomon Islands</option><option value="somalia">Somalia</option><option value="south-africa">South Africa</option><option value="south-georgia-and-the-south-sandwich-islands">South Georgia and the South Sandwich Islands</option><option value="south-sudan">South Sudan</option><option value="spain">Spain</option><option value="sri-lanka">Sri Lanka</option><option value="sudan">Sudan</option><option value="suriname">Suriname</option><option value="svalbard-and-jan-mayen">Svalbard and Jan Mayen</option><option value="swaziland">Swaziland</option><option value="sweden">Sweden</option><option value="switzerland">Switzerland</option><option value="syrian-arab-republic">Syrian Arab Republic</option><option value="taiwan">Taiwan</option><option value="tajikistan">Tajikistan</option><option value="tanzania,-united-republic-of">Tanzania, United Republic of</option><option value="thailand">Thailand</option><option value="timor-leste">Timor-Leste</option><option value="togo">Togo</option><option value="tokelau">Tokelau</option><option value="tonga">Tonga</option><option value="trinidad-and-tobago">Trinidad and Tobago</option><option value="tunisia">Tunisia</option><option value="turkey">Turkey</option><option value="turkmenistan">Turkmenistan</option><option value="turks-and-caicos-islands">Turks and Caicos Islands</option><option value="tuvalu">Tuvalu</option><option value="uganda">Uganda</option><option value="ukraine">Ukraine</option><option value="united-arab-emirates">United Arab Emirates</option><option value="united-kingdom">United Kingdom</option><option value="united-states">United States</option><option value="uruguay">Uruguay</option><option value="uzbekistan">Uzbekistan</option><option value="vanuatu">Vanuatu</option><option value="venezuela,-bolivarian-republic-of">Venezuela, Bolivarian Republic of</option><option value="viet-nam">Viet Nam</option><option value="virgin-islands,-british">Virgin Islands, British</option><option value="wallis-and-futuna">Wallis and Futuna</option><option value="western-sahara">Western Sahara</option><option value="yemen">Yemen</option><option value="zambia">Zambia</option><option value="zimbabwe">Zimbabwe</option>
                            </select>
                        </div>
                        <div class="mc-field-group">
                            <input type="text" value="" name="MMERGE6" class="required" id="MMERGE6" placeholder="<?php echo $atts["company"]; ?>">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="mc-field-group e-mail">
                            <input type="email" value="" name="EMAIL" class="required email" id="EMAIL" placeholder="<?php echo $atts["email"]; ?>">
                        </div>
                    </div>
                    <!-- real people should not fill this in and expect good things - do not remove this or risk form bot signups-->
                    <div style="position: absolute; left: -5000px;" aria-hidden="true"><input type="text" name="b_f7f1ec26a1a4d314e5595e1a3_a65e6c887f" tabindex="-1" value=""></div>
                    <div class="form-row">

                        <div class="privacy-wrapper">
                            <label>
                                <input type="checkbox" id="PRIVACY" name="PRIVACY" value="Yes"><span><?php echo $atts["privacy-text"]; ?> <a href="/privacy-policy/" target="_blank"><?php echo $atts["privacy-link-text"]; ?></a></span>
                            </label>
                        </div>
                        <div class="clear submit-wrapper">
                            <input type="submit" value="<?php echo $atts["submit-btn"]; ?>" name="subscribe" id="mc-embedded-subscribe" class="button">
                        </div>
                    </div>
                    <div class="form-row">
                        <div id="form-message"></div>
                    </div>
                    <a href="<?php echo $link; ?>" download target="_blank" id="dl-btn" style="display: none;"></a>
                </form>


                <div id="submit-message"></div>
            </div>

            <script type="text/javascript" src="/wp-content/themes/variscite/js/mc-signup.js"></script>
            <!--End ailchimp Download Form-->

        </div>
    </div>


    <?php
    $content = ob_get_contents();
    ob_end_clean();
    return $content;
}
/*********   END of Mixed Mode article form   ********/

/************* New CPU filter on Accessories page **************/
add_shortcode( 'add_cpu_to_acc_filters', 'add_cpu_to_acc_filters' );
function add_cpu_to_acc_filters(){

    echo filter_tab_builder_one_field(get_field( 'som_filter_tab_group',1418 ),"CPU Name")
    ?>
    <?php
}


/*********************************************
 ** EASY LIGHT TEXT WEIGHT WRAP
 *********************************************/
add_shortcode('light', 'ez_lightxt');
function ez_lightxt($atts, $content = null) {
    return '<span class="light">'.$content.'</span>';
}


add_shortcode( 'step_quote_form', 'wpdocs_footag_func' );
function wpdocs_footag_func($atts) {

    if(empty($atts['addon'])) {
        return;
    }

    if(isset($atts['addon']) && !empty($atts['addon'])) {
        $addon = $atts['addon'];
    }
    $cpid = get_the_ID();

    if($addon == 'kits') {
        $fieldkey = 'optage_specs_quote_kits_addons';
    } else {
        $fieldkey = 'optage_specs_quote_som_addons';
    }

    if(empty($fieldkey)) {
        return;
    }

    // QUOTE PRODUCT ADDONS (SELECTED BY USER)
    $quoteSettings 		= get_field('quote_settings', 'option');
    $quoteProducts		= get_field('quote_product_addons_group', 'option');
    $selectedProducts 	= $quoteProducts[$fieldkey];
    $productCheckboxes  = '';
    $shortName          = get_field('vrs_specs_short_pname', $cpid);

    // CUSTOM (PAGE SPECIFIC) DATA
    $cSomProducts 		= get_field('vrs_specs_custom_som_products', $cpid);   if(!empty($cSomProducts)) { $selectedProducts = $cSomProducts; }
    $cThanksPage 		= get_field('vrs_specs_cthanks_page', $cpid);
    $cpuName            = get_field('vrs_specs_processor_pname', $cpid);

    $vrs_specs_quote_title = get_field('vrs_specs_quote_title', $cpid);
    $vrs_specs_quote_desc = get_field('vrs_specs_quote_desc', $cpid);
    $vrs_specs_quote_product_addons = get_field('vrs_specs_quote_product_addons', $cpid);

    $output = '<div class="multi-step">
    <div class="stepwizard">
        <ul class="steps-item">
            <li><a href="#step-form-1" class="step-active"><span>1</span> Personal Info</a></li>
            <li><a href="#step-form-2"><span>2</span> Product Specifications</a></li>
        </ul>    
    </div>
    <div class="lp-contact-form-col panel-cell-style panel-cell-style-for-6671-6-1">
        <div class="step-form">
            <div id="quoteFormStep" class="quote-form lpForm som-form" style="border-color: #000000;  ">
                <input type="hidden" id="curl" value="/variscite-products/">
                
                <input type="hidden" id="email_to" value="sales@variscite.com">
                <input type="hidden" id="email_subject" value="New Lead from landing page">
                <input type="hidden" id="thanks" value="/thanks-you-lp/">
                <input type="hidden" id="required" value="first_name,last_name,email,phone,quote-quantity,company">
                <input type="hidden" id="required-step-1" value="first_name,last_name,email,phone,company,country">
                <input type="hidden" id="lead_source" value="Web">
                <input type="hidden" id="event_name" value="form-lp-success">
                <input type="hidden" id="product_id" value="'.$cpid.'">

                <!--=== ADWORDS FIELDS ===-->
                <input type="hidden" id="Campaign_medium__c" value="">
                <input type="hidden" id="Campaign_source__c" value="">
                <input type="hidden" id="Campaign_content__c" value="">
                <input type="hidden" id="Campaign_term__c" value="">
                <input type="hidden" id="Page_url__c" value="/variscite-products/">
                <input type="hidden" id="Paid_Campaign_Name__c" value="">
                <input type="hidden" id="GA_id__c" value="">

                <!--=== ADWORDS FIELDS ===-->
                <div class="form-inner">
                    <div class="setup-content-block" id="step-form-1">';
    if(!empty($vrs_specs_quote_title)) {
        $output .= '<div class="quote-title">'.$vrs_specs_quote_title.'</div>';
    } else {
        $output .= '<div class="quote-title">Get A Quote</div>';
    }

    if(!empty($vrs_specs_quote_title)) {
        $output .= '<div class="quote-sub-title">'.$vrs_specs_quote_desc.'</div>';
    } else {
        $output .= '<div class="quote-title">Fill out the details below and one of our representatives will contact you shortly</div>';
    }
    $output .= '<div class="step-fields">
                        <div class="row">';
    $output .= '<div class="field-box form-group col-12">
                                <div class="field-name-wrap">
                                    <div class="field-wrap floating-labels">
                                        <div class="row">
                                            <div class="col-md-5"><label for="first_name">First name</label></div>
                                            <div class="col-md-7"><input type="text" name="first_name" id="first_name" class="form-control" placeholder="" value="" required></div>
                                        </div>
                                    </div>
                                    <div class="field-wrap floating-labels">
                                        <div class="row">
                                            <div class="col-md-5"><label for="last_name">Last name</label></div>
                                            <div class="col-md-7"><input type="text" name="last_name" id="last_name" class="form-control" placeholder="" value="" required></div>
                                        </div>
                                    </div>
                                </div>
                            </div>';

    $output .=  '<div class="field-box form-group field-email floating-labels col-12">
                    <div class="field-wrap">
                        <div class="row">
                            <div class="col-md-5"><label for="email">Email</label></div>
                            <div class="col-md-7"><input type="email" name="email" id="email" class="form-control" placeholder="" value="" required ></div>
                        </div>
                    </div>
                </div>';

    $output .=  '<div class="field-box form-group field-company floating-labels col-12">
                    <div class="field-wrap">
                        <div class="row">
                            <div class="col-md-5"><label for="email">Company</label></div>
                            <div class="col-md-7"><input type="text" name="company" id="company" class="form-control" placeholder="" value="" required ></div>
                        </div>
                    </div>
                </div>';
    $output .=  '<div class="field-box form-group field-country floating-labels col-12">
                <div class="field-wrap">
                    <div class="row">
                        <div class="col-md-7"><select name="country" id="country" class="form-control" required><option value="">Country</option><option value="afghanistan">Afghanistan</option><option value="aland-islands">Aland Islands</option><option value="albania">Albania</option><option value="algeria">Algeria</option><option value="andorra">Andorra</option><option value="angola">Angola</option><option value="anguilla">Anguilla</option><option value="antarctica">Antarctica</option><option value="antigua-and-barbuda">Antigua and Barbuda</option><option value="argentina">Argentina</option><option value="armenia">Armenia</option><option value="aruba">Aruba</option><option value="australia">Australia</option><option value="austria">Austria</option><option value="azerbaijan">Azerbaijan</option><option value="bahamas">Bahamas</option><option value="bahrain">Bahrain</option><option value="bangladesh">Bangladesh</option><option value="barbados">Barbados</option><option value="belarus">Belarus</option><option value="belgium">Belgium</option><option value="belize">Belize</option><option value="benin">Benin</option><option value="bermuda">Bermuda</option><option value="bhutan">Bhutan</option><option value="bolivia,-plurinational-state-of">Bolivia, Plurinational State of</option><option value="bonaire,-sint-eustatius-and-saba">Bonaire, Sint Eustatius and Saba</option><option value="bosnia-and-herzegovina">Bosnia and Herzegovina</option><option value="botswana">Botswana</option><option value="bouvet-island">Bouvet Island</option><option value="brazil">Brazil</option><option value="british-indian-ocean-territory">British Indian Ocean Territory</option><option value="brunei-darussalam">Brunei Darussalam</option><option value="bulgaria">Bulgaria</option><option value="burkina-faso">Burkina Faso</option><option value="burundi">Burundi</option><option value="cambodia">Cambodia</option><option value="cameroon">Cameroon</option><option value="canada">Canada</option><option value="cape-verde">Cape Verde</option><option value="cayman-islands">Cayman Islands</option><option value="central-african-republic">Central African Republic</option><option value="chad">Chad</option><option value="chile">Chile</option><option value="china">China</option><option value="christmas-island">Christmas Island</option><option value="cocos-(keeling)-islands">Cocos (Keeling) Islands</option><option value="colombia">Colombia</option><option value="comoros">Comoros</option><option value="congo">Congo</option><option value="congo,-the-democratic-republic-of-the">Congo, the Democratic Republic of the</option><option value="cook-islands">Cook Islands</option><option value="costa-rica">Costa Rica</option><option value="cote-d\'ivoire">Cote d\'Ivoire</option><option value="croatia">Croatia</option><option value="cuba">Cuba</option><option value="curaçao">Curaçao</option><option value="cyprus">Cyprus</option><option value="czech-republic">Czech Republic</option><option value="denmark">Denmark</option><option value="djibouti">Djibouti</option><option value="dominica">Dominica</option><option value="dominican-republic">Dominican Republic</option><option value="ecuador">Ecuador</option><option value="egypt">Egypt</option><option value="el-salvador">El Salvador</option><option value="equatorial-guinea">Equatorial Guinea</option><option value="eritrea">Eritrea</option><option value="estonia">Estonia</option><option value="ethiopia">Ethiopia</option><option value="falkland-islands-(malvinas)">Falkland Islands (Malvinas)</option><option value="faroe-islands">Faroe Islands</option><option value="fiji">Fiji</option><option value="finland">Finland</option><option value="france">France</option><option value="french-guiana">French Guiana</option><option value="french-polynesia">French Polynesia</option><option value="french-southern-territories">French Southern Territories</option><option value="gabon">Gabon</option><option value="gambia">Gambia</option><option value="georgia">Georgia</option><option value="germany">Germany</option><option value="ghana">Ghana</option><option value="gibraltar">Gibraltar</option><option value="greece">Greece</option><option value="greenland">Greenland</option><option value="grenada">Grenada</option><option value="guadeloupe">Guadeloupe</option><option value="guatemala">Guatemala</option><option value="guernsey">Guernsey</option><option value="guinea">Guinea</option><option value="guinea-bissau">Guinea-Bissau</option><option value="guyana">Guyana</option><option value="haiti">Haiti</option><option value="heard-island-and-mcdonald-islands">Heard Island and McDonald Islands</option><option value="holy-see-(vatican-city-state)">Holy See (Vatican City State)</option><option value="honduras">Honduras</option><option value="hungary">Hungary</option><option value="iceland">Iceland</option><option value="india">India</option><option value="indonesia">Indonesia</option><option value="iran,-islamic-republic-of">Iran, Islamic Republic of</option><option value="iraq">Iraq</option><option value="ireland">Ireland</option><option value="isle-of-man">Isle of Man</option><option value="israel">Israel</option><option value="italy">Italy</option><option value="jamaica">Jamaica</option><option value="japan">Japan</option><option value="jersey">Jersey</option><option value="jordan">Jordan</option><option value="kazakhstan">Kazakhstan</option><option value="kenya">Kenya</option><option value="kiribati">Kiribati</option><option value="korea,-republic-of">Korea, Republic of</option><option value="kuwait">Kuwait</option><option value="kyrgyzstan">Kyrgyzstan</option><option value="lao-people\'s-democratic-republic">Lao People\'s Democratic Republic</option><option value="latvia">Latvia</option><option value="lebanon">Lebanon</option><option value="lesotho">Lesotho</option><option value="liberia">Liberia</option><option value="libya">Libya</option><option value="liechtenstein">Liechtenstein</option><option value="lithuania">Lithuania</option><option value="luxembourg">Luxembourg</option><option value="macao">Macao</option><option value="macedonia,-the-former-yugoslav-republic-of">Macedonia, the former Yugoslav Republic of</option><option value="madagascar">Madagascar</option><option value="malawi">Malawi</option><option value="malaysia">Malaysia</option><option value="maldives">Maldives</option><option value="mali">Mali</option><option value="malta">Malta</option><option value="martinique">Martinique</option><option value="mauritania">Mauritania</option><option value="mauritius">Mauritius</option><option value="mayotte">Mayotte</option><option value="mexico">Mexico</option><option value="moldova,-republic-of">Moldova, Republic of</option><option value="monaco">Monaco</option><option value="mongolia">Mongolia</option><option value="montenegro">Montenegro</option><option value="montserrat">Montserrat</option><option value="morocco">Morocco</option><option value="mozambique">Mozambique</option><option value="myanmar">Myanmar</option><option value="namibia">Namibia</option><option value="nauru">Nauru</option><option value="nepal">Nepal</option><option value="netherlands">Netherlands</option><option value="new-caledonia">New Caledonia</option><option value="new-zealand">New Zealand</option><option value="nicaragua">Nicaragua</option><option value="niger">Niger</option><option value="nigeria">Nigeria</option><option value="niue">Niue</option><option value="norfolk-island">Norfolk Island</option><option value="norway">Norway</option><option value="oman">Oman</option><option value="pakistan">Pakistan</option><option value="panama">Panama</option><option value="papua-new-guinea">Papua New Guinea</option><option value="paraguay">Paraguay</option><option value="peru">Peru</option><option value="philippines">Philippines</option><option value="pitcairn">Pitcairn</option><option value="poland">Poland</option><option value="portugal">Portugal</option><option value="qatar">Qatar</option><option value="reunion">Reunion</option><option value="romania">Romania</option><option value="russian-federation">Russian Federation</option><option value="rwanda">Rwanda</option><option value="saint-barthélemy">Saint Barthélemy</option><option value="saint-helena,-ascension-and-tristan-da-cunha">Saint Helena, Ascension and Tristan da Cunha</option><option value="saint-kitts-and-nevis">Saint Kitts and Nevis</option><option value="saint-lucia">Saint Lucia</option><option value="saint-martin-(french-part)">Saint Martin (French part)</option><option value="saint-pierre-and-miquelon">Saint Pierre and Miquelon</option><option value="saint-vincent-and-the-grenadines">Saint Vincent and the Grenadines</option><option value="samoa">Samoa</option><option value="san-marino">San Marino</option><option value="sao-tome-and-principe">Sao Tome and Principe</option><option value="saudi-arabia">Saudi Arabia</option><option value="senegal">Senegal</option><option value="serbia">Serbia</option><option value="seychelles">Seychelles</option><option value="sierra-leone">Sierra Leone</option><option value="singapore">Singapore</option><option value="sint-maarten-(dutch-part)">Sint Maarten (Dutch part)</option><option value="slovakia">Slovakia</option><option value="slovenia">Slovenia</option><option value="solomon-islands">Solomon Islands</option><option value="somalia">Somalia</option><option value="south-africa">South Africa</option><option value="south-georgia-and-the-south-sandwich-islands">South Georgia and the South Sandwich Islands</option><option value="south-sudan">South Sudan</option><option value="spain">Spain</option><option value="sri-lanka">Sri Lanka</option><option value="sudan">Sudan</option><option value="suriname">Suriname</option><option value="svalbard-and-jan-mayen">Svalbard and Jan Mayen</option><option value="swaziland">Swaziland</option><option value="sweden">Sweden</option><option value="switzerland">Switzerland</option><option value="syrian-arab-republic">Syrian Arab Republic</option><option value="taiwan">Taiwan</option><option value="tajikistan">Tajikistan</option><option value="tanzania,-united-republic-of">Tanzania, United Republic of</option><option value="thailand">Thailand</option><option value="timor-leste">Timor-Leste</option><option value="togo">Togo</option><option value="tokelau">Tokelau</option><option value="tonga">Tonga</option><option value="trinidad-and-tobago">Trinidad and Tobago</option><option value="tunisia">Tunisia</option><option value="turkey">Turkey</option><option value="turkmenistan">Turkmenistan</option><option value="turks-and-caicos-islands">Turks and Caicos Islands</option><option value="tuvalu">Tuvalu</option><option value="uganda">Uganda</option><option value="ukraine">Ukraine</option><option value="united-arab-emirates">United Arab Emirates</option><option value="united-kingdom">United Kingdom</option><option value="united-states">United States</option><option value="uruguay">Uruguay</option><option value="uzbekistan">Uzbekistan</option><option value="vanuatu">Vanuatu</option><option value="venezuela,-bolivarian-republic-of">Venezuela, Bolivarian Republic of</option><option value="viet-nam">Viet Nam</option><option value="virgin-islands,-british">Virgin Islands, British</option><option value="wallis-and-futuna">Wallis and Futuna</option><option value="western-sahara">Western Sahara</option><option value="yemen">Yemen</option><option value="zambia">Zambia</option><option value="zimbabwe">Zimbabwe</option></select></div>
                        </div>
                    </div>
                </div>';
    $output .= '<div class="field-box form-group field-phone floating-labels col-12">
                    <div class="field-wrap">
                        <div class="row">
                            <div class="col-md-5"><label for="phone">Phone</label></div>
                            <div class="col-md-7"><input type="text" id="phone" name="phone" class="form-control" placeholder="" value="" required></div>
                        </div>
                    </div>
                </div>';

    $output .=  '<div class="field-box form-group col-md-12 field-agreement_checkbox">
                                <div class="field-wrap-transparent">
                                    <input type="checkbox" id="agreement_checkbox" name="agreement" value="' . date('Y-m-d\TH:i:s\Z') . '" class="form-control"> <label for="agreement_checkbox">I agree to the Variscite  <a href="/privacy-policy/" target="_blank">Privacy Policy</a></label></div>
                                </div>
                </div>';
    $output .='<div class="col-12 submit-notices"><div class="notice"></div></div>';
    $output .=  '<div class="btn-wrap text-center"><button class="button btn btn-warning btn-lg nextBtn" type="button">Continue</button>
                </div>

                </div></div>';
    $output .=  '<div class="setup-content-block" id="step-form-2">';
    if(!empty($vrs_specs_quote_title)) {
        $output .= '<div class="quote-title">'.$vrs_specs_quote_title.'</div>';
    } else {
        $output .= '<div class="quote-title">Get A Quote</div>';
    }

    if(!empty($vrs_specs_quote_title)) {
        $output .= '<div class="quote-sub-title">'.$vrs_specs_quote_desc.'</div>';
    } else {
        $output .= '<div class="quote-title">Fill out the details below and one of our representatives will contact you shortly</div>';
    }
    $output .=

        '<div class="step-fields">
                                <div class="row">';
    $output .=  '<div class="field-box form-group field-phone floating-labels col-12">
                                <div class="field-wrap">
                                    <label class="som-select-label">SoM Platforms I\'m Interested In</label>
                                    <select class="js-quote-product quote-product" multiple="multiple" name="quote-product" id="quote-product">';
    foreach($selectedProducts as $product) {
        $product 	= ( !empty($product['caddon_product']) ? $product['caddon_product'] : $product['product_addons'] );
        $moduleName = get_field('vrs_specs_processor_pname', $product->ID);
        $selected = ($cpuName == $moduleName) ? 'selected' : '';

        $output .= '<option value="'.$moduleName.'" '.$selected.'>'.$moduleName.'</option>';
    }
    $output .= '</select>
                                </div>
                            </div>';

    $quatitiesArr	= $quoteSettings['quantities_values'];
    $quatitiesArr	= preg_split('/\r\n|\r|\n/', $quatitiesArr);

    $output .=  '<div class="field-box form-group field-phone floating-labels col-12">
                                <div class="field-wrap">
                                    <label class="som-select-label">Estimated Project Quantity</label>  
                                    <select class="js-quantity-product" name="quote-quantity" id="quote-quantity" required>
                                        <option value="">Choose Quantity</option>';
    foreach($quatitiesArr as $quan) {
        $quanVal = str_replace('>', 'more-than-', $quan);
        $output .= '<option value="'.$quan.'">'.$quan.'</option>';
    }
    $output .=  '</select>
                                </div>
                            </div>';

    $output .='<div class="field-box form-group field-note floating-labels col-12">
                    <div class="field-wrap">
                        <textarea maxlength="2000" id="note" cols="30" rows="10" class="form-control" placeholder="Note"></textarea>
                    </div>
                </div>
                </div>';

    $output .=  '<div class="col-12 submit-notices"><div class="notice"></div></div><div class="step2-buttons">
                    <div class="back-box">
                        <div class="row">
                            <div class="col-6 text-center"><button class="btn back-btn" id="back-btn">Back</div>
                        
                        </div>
                    </div>
                    <div class="submit-box">
                            <div class="row">
                                <div class="col-6 text-center"><input type="submit" name="submit" class="btn btn-warning btn-lg btn-arrow-01 submitMultiStepRequest" value="Submit"></div>
                                
                            </div>
                    </div>
                    </div>
                    </div>
                    </div>';

    $output .=  '</div>
                    </div>
                    </div>
                </div>
            </div> 
        </div>
    </div>';

    return $output;
}

/* contact form shortcode */
add_shortcode( 'contact_form', 'contact_form_func' );
function contact_form_func($atts) {

    if(empty($atts['link_label'])) {
        return;
    }

    if(isset($atts['link_label']) && !empty($atts['link_label'])) {
        $link_label = $atts['link_label'];
    }

    $popup_form_heading = get_field('popup_form_heading','option');
    $popup_form_sub_heading = get_field('popup_form_sub_heading','option');

    $popup_form_email_to = get_field('popup_form_email_to','option');
    $popup_form_subject = get_field('popup_form_subject','option');
    $popup_form_lead_source = get_field('popup_form_lead_source','option');
    $popup_thank_you_page = get_field('popup_thank_you_page','option');

    $output = '<div class="contact-popup">';
    $output .='<a href="#" class="js-close-popup"><img src="'.get_template_directory_uri().'/images/specs/close.png"> </a>';
    $output .='<div id="contactFormPopup" class="lpForm popup-form">';


    if(!empty($popup_form_heading)) {
        $output .= '<div class="popup-form-title">'.$popup_form_heading.'</div>';
    }

    if(!empty($popup_form_sub_heading)) {
        $output .= '<p>'.$popup_form_sub_heading.'</p>';
    }

    $output .='<input type="hidden" id="curl" value="'.get_permalink(get_the_ID()).'">
            
            <input type="hidden" id="popup_email_to" value="'.$popup_form_email_to.'">
            <input type="hidden" id="popup_email_subject" value="'.$popup_form_subject.'">
            <input type="hidden" id="popup_thanks" value="'.get_permalink($popup_thank_you_page).'">
            <input type="hidden" id="popup_required" value="popup_first_name,popup_last_name,popup_company,popup_email,popup_country,popup_telephone">
            <input type="hidden" id="popup_lead_source" value="Web – pop up">
            <input type="hidden" id="popup_event_name" value="form-lp-success">

            <!--=== ADWORDS FIELDS ===-->
            <input type="hidden" id="popup_Campaign_medium__c" value="N/A">
            <input type="hidden" id="popup_Campaign_source__c" value="direct">
            <input type="hidden" id="popup_Campaign_content__c" value="N/A">
            <input type="hidden" id="popup_Campaign_term__c" value="N/A">
            <input type="hidden" id="popup_Page_url__c" value="/variscite-products/">
            <input type="hidden" id="popup_Paid_Campaign_Name__c" value="N/A">
            <input type="hidden" id="popup_GA_id__c" value="">

            <!--=== ADWORDS FIELDS ===-->

            <div class="form-inner">
                <div class="row">';
    $output .= '<div class="field-box form-group field-first_name col-12">
                            <div class="field-wrap">
                                <div class="row">
                                    <div class="col-md-6 floating-labels">
                                        <div class="row">
                                            <div class="col-md-5"><label for="popup_first_name">First name</label></div>
                                            <div class="col-md-7"><input type="text" name="popup_first_name" id="popup_first_name" class="form-control" placeholder="" value="" required></div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 floating-labels">
                                        <div class="row">
                                            <div class="col-md-5"><label for="popup_last_name">Last name</label></div>
                                            <div class="col-md-7"><input type="text" name="popup_last_name" id="popup_last_name" class="form-control" placeholder="" value="" required></div>
                                        </div>
                                    </div>    
                                </div>
                            </div>
                        </div>';

    $output .=  '<div class="field-box form-group field-company floating-labels col-12">
                                <div class="field-wrap">
                                    <div class="row">
                                        <div class="col-md-5"><label for="popup_company">Company</label></div>
                                        <div class="col-md-7"><input type="text" name="popup_company" id="popup_company" class="form-control" placeholder="" value="" required ></div>
                                    </div>
                                </div>
                            </div>';

    $output .=  '<div class="col-12 field-box form-group field-country floating-labels col-12">
				<div class="field-wrap">
					<div class="row">
						<div class="col-md-5"><label for="country">Country</label></div>
						<div class="col-md-7"><select name="popup_country" id="popup_country" class="form-control"><option value="">Country</option><option value="afghanistan">Afghanistan</option><option value="aland-islands">Aland Islands</option><option value="albania">Albania</option><option value="algeria">Algeria</option><option value="andorra">Andorra</option><option value="angola">Angola</option><option value="anguilla">Anguilla</option><option value="antarctica">Antarctica</option><option value="antigua-and-barbuda">Antigua and Barbuda</option><option value="argentina">Argentina</option><option value="armenia">Armenia</option><option value="aruba">Aruba</option><option value="australia">Australia</option><option value="austria">Austria</option><option value="azerbaijan">Azerbaijan</option><option value="bahamas">Bahamas</option><option value="bahrain">Bahrain</option><option value="bangladesh">Bangladesh</option><option value="barbados">Barbados</option><option value="belarus">Belarus</option><option value="belgium">Belgium</option><option value="belize">Belize</option><option value="benin">Benin</option><option value="bermuda">Bermuda</option><option value="bhutan">Bhutan</option><option value="bolivia,-plurinational-state-of">Bolivia, Plurinational State of</option><option value="bonaire,-sint-eustatius-and-saba">Bonaire, Sint Eustatius and Saba</option><option value="bosnia-and-herzegovina">Bosnia and Herzegovina</option><option value="botswana">Botswana</option><option value="bouvet-island">Bouvet Island</option><option value="brazil">Brazil</option><option value="british-indian-ocean-territory">British Indian Ocean Territory</option><option value="brunei-darussalam">Brunei Darussalam</option><option value="bulgaria">Bulgaria</option><option value="burkina-faso">Burkina Faso</option><option value="burundi">Burundi</option><option value="cambodia">Cambodia</option><option value="cameroon">Cameroon</option><option value="canada">Canada</option><option value="cape-verde">Cape Verde</option><option value="cayman-islands">Cayman Islands</option><option value="central-african-republic">Central African Republic</option><option value="chad">Chad</option><option value="chile">Chile</option><option value="china">China</option><option value="christmas-island">Christmas Island</option><option value="cocos-(keeling)-islands">Cocos (Keeling) Islands</option><option value="colombia">Colombia</option><option value="comoros">Comoros</option><option value="congo">Congo</option><option value="congo,-the-democratic-republic-of-the">Congo, the Democratic Republic of the</option><option value="cook-islands">Cook Islands</option><option value="costa-rica">Costa Rica</option><option value="cote-d\'ivoire">Cote d\'Ivoire</option><option value="croatia">Croatia</option><option value="cuba">Cuba</option><option value="curaçao">Curaçao</option><option value="cyprus">Cyprus</option><option value="czech-republic">Czech Republic</option><option value="denmark">Denmark</option><option value="djibouti">Djibouti</option><option value="dominica">Dominica</option><option value="dominican-republic">Dominican Republic</option><option value="ecuador">Ecuador</option><option value="egypt">Egypt</option><option value="el-salvador">El Salvador</option><option value="equatorial-guinea">Equatorial Guinea</option><option value="eritrea">Eritrea</option><option value="estonia">Estonia</option><option value="ethiopia">Ethiopia</option><option value="falkland-islands-(malvinas)">Falkland Islands (Malvinas)</option><option value="faroe-islands">Faroe Islands</option><option value="fiji">Fiji</option><option value="finland">Finland</option><option value="france">France</option><option value="french-guiana">French Guiana</option><option value="french-polynesia">French Polynesia</option><option value="french-southern-territories">French Southern Territories</option><option value="gabon">Gabon</option><option value="gambia">Gambia</option><option value="georgia">Georgia</option><option value="germany">Germany</option><option value="ghana">Ghana</option><option value="gibraltar">Gibraltar</option><option value="greece">Greece</option><option value="greenland">Greenland</option><option value="grenada">Grenada</option><option value="guadeloupe">Guadeloupe</option><option value="guatemala">Guatemala</option><option value="guernsey">Guernsey</option><option value="guinea">Guinea</option><option value="guinea-bissau">Guinea-Bissau</option><option value="guyana">Guyana</option><option value="haiti">Haiti</option><option value="heard-island-and-mcdonald-islands">Heard Island and McDonald Islands</option><option value="holy-see-(vatican-city-state)">Holy See (Vatican City State)</option><option value="honduras">Honduras</option><option value="hungary">Hungary</option><option value="iceland">Iceland</option><option value="india">India</option><option value="indonesia">Indonesia</option><option value="iran,-islamic-republic-of">Iran, Islamic Republic of</option><option value="iraq">Iraq</option><option value="ireland">Ireland</option><option value="isle-of-man">Isle of Man</option><option value="israel">Israel</option><option value="italy">Italy</option><option value="jamaica">Jamaica</option><option value="japan">Japan</option><option value="jersey">Jersey</option><option value="jordan">Jordan</option><option value="kazakhstan">Kazakhstan</option><option value="kenya">Kenya</option><option value="kiribati">Kiribati</option><option value="korea,-republic-of">Korea, Republic of</option><option value="kuwait">Kuwait</option><option value="kyrgyzstan">Kyrgyzstan</option><option value="lao-people\'s-democratic-republic">Lao People\'s Democratic Republic</option><option value="latvia">Latvia</option><option value="lebanon">Lebanon</option><option value="lesotho">Lesotho</option><option value="liberia">Liberia</option><option value="libya">Libya</option><option value="liechtenstein">Liechtenstein</option><option value="lithuania">Lithuania</option><option value="luxembourg">Luxembourg</option><option value="macao">Macao</option><option value="macedonia,-the-former-yugoslav-republic-of">Macedonia, the former Yugoslav Republic of</option><option value="madagascar">Madagascar</option><option value="malawi">Malawi</option><option value="malaysia">Malaysia</option><option value="maldives">Maldives</option><option value="mali">Mali</option><option value="malta">Malta</option><option value="martinique">Martinique</option><option value="mauritania">Mauritania</option><option value="mauritius">Mauritius</option><option value="mayotte">Mayotte</option><option value="mexico">Mexico</option><option value="moldova,-republic-of">Moldova, Republic of</option><option value="monaco">Monaco</option><option value="mongolia">Mongolia</option><option value="montenegro">Montenegro</option><option value="montserrat">Montserrat</option><option value="morocco">Morocco</option><option value="mozambique">Mozambique</option><option value="myanmar">Myanmar</option><option value="namibia">Namibia</option><option value="nauru">Nauru</option><option value="nepal">Nepal</option><option value="netherlands">Netherlands</option><option value="new-caledonia">New Caledonia</option><option value="new-zealand">New Zealand</option><option value="nicaragua">Nicaragua</option><option value="niger">Niger</option><option value="nigeria">Nigeria</option><option value="niue">Niue</option><option value="norfolk-island">Norfolk Island</option><option value="norway">Norway</option><option value="oman">Oman</option><option value="pakistan">Pakistan</option><option value="panama">Panama</option><option value="papua-new-guinea">Papua New Guinea</option><option value="paraguay">Paraguay</option><option value="peru">Peru</option><option value="philippines">Philippines</option><option value="pitcairn">Pitcairn</option><option value="poland">Poland</option><option value="portugal">Portugal</option><option value="qatar">Qatar</option><option value="reunion">Reunion</option><option value="romania">Romania</option><option value="russian-federation">Russian Federation</option><option value="rwanda">Rwanda</option><option value="saint-barthélemy">Saint Barthélemy</option><option value="saint-helena,-ascension-and-tristan-da-cunha">Saint Helena, Ascension and Tristan da Cunha</option><option value="saint-kitts-and-nevis">Saint Kitts and Nevis</option><option value="saint-lucia">Saint Lucia</option><option value="saint-martin-(french-part)">Saint Martin (French part)</option><option value="saint-pierre-and-miquelon">Saint Pierre and Miquelon</option><option value="saint-vincent-and-the-grenadines">Saint Vincent and the Grenadines</option><option value="samoa">Samoa</option><option value="san-marino">San Marino</option><option value="sao-tome-and-principe">Sao Tome and Principe</option><option value="saudi-arabia">Saudi Arabia</option><option value="senegal">Senegal</option><option value="serbia">Serbia</option><option value="seychelles">Seychelles</option><option value="sierra-leone">Sierra Leone</option><option value="singapore">Singapore</option><option value="sint-maarten-(dutch-part)">Sint Maarten (Dutch part)</option><option value="slovakia">Slovakia</option><option value="slovenia">Slovenia</option><option value="solomon-islands">Solomon Islands</option><option value="somalia">Somalia</option><option value="south-africa">South Africa</option><option value="south-georgia-and-the-south-sandwich-islands">South Georgia and the South Sandwich Islands</option><option value="south-sudan">South Sudan</option><option value="spain">Spain</option><option value="sri-lanka">Sri Lanka</option><option value="sudan">Sudan</option><option value="suriname">Suriname</option><option value="svalbard-and-jan-mayen">Svalbard and Jan Mayen</option><option value="swaziland">Swaziland</option><option value="sweden">Sweden</option><option value="switzerland">Switzerland</option><option value="syrian-arab-republic">Syrian Arab Republic</option><option value="taiwan">Taiwan</option><option value="tajikistan">Tajikistan</option><option value="tanzania,-united-republic-of">Tanzania, United Republic of</option><option value="thailand">Thailand</option><option value="timor-leste">Timor-Leste</option><option value="togo">Togo</option><option value="tokelau">Tokelau</option><option value="tonga">Tonga</option><option value="trinidad-and-tobago">Trinidad and Tobago</option><option value="tunisia">Tunisia</option><option value="turkey">Turkey</option><option value="turkmenistan">Turkmenistan</option><option value="turks-and-caicos-islands">Turks and Caicos Islands</option><option value="tuvalu">Tuvalu</option><option value="uganda">Uganda</option><option value="ukraine">Ukraine</option><option value="united-arab-emirates">United Arab Emirates</option><option value="united-kingdom">United Kingdom</option><option value="united-states">United States</option><option value="uruguay">Uruguay</option><option value="uzbekistan">Uzbekistan</option><option value="vanuatu">Vanuatu</option><option value="venezuela,-bolivarian-republic-of">Venezuela, Bolivarian Republic of</option><option value="viet-nam">Viet Nam</option><option value="virgin-islands,-british">Virgin Islands, British</option><option value="wallis-and-futuna">Wallis and Futuna</option><option value="western-sahara">Western Sahara</option><option value="yemen">Yemen</option><option value="zambia">Zambia</option><option value="zimbabwe">Zimbabwe</option></select></div>
                        </div>
                    </div>
                </div><!-- col-md-6 -->';

    $output .=  '<div class="field-box form-group field-email floating-labels col-12">
                                <div class="field-wrap">
                                    <div class="row">
                                        <div class="col-md-5"><label for="popup_email">Email</label></div>
                                        <div class="col-md-7"><input type="text" name="popup_email" id="popup_email" class="form-control" placeholder="" value="" required ></div>
                                    </div>
                                </div>
                            </div>';

    $output .=  '<div class="field-box form-group field-phone floating-labels col-12">
                                <div class="field-wrap">
                                    <div class="row">
                                        <div class="col-md-5"><label for="phone">Phone</label></div>
                                        <div class="col-md-7"><input type="text" id="popup_telephone" name="popup_telephone" class="form-control" placeholder="" value="" required></div>
                                    </div>
                                </div>
                            </div>';

    $output .= '<div class="field-box form-group field-note  col-md-12">
                                <div class="field-wrap">
                                     <div class="col-md-7"><textarea maxlength="2000" id="popup_note" cols="30" rows="10" class="form-control" placeholder="Your message"></textarea></div>
                                    </div>
                                </div>    
                            </div>';

    $output .=  '<div class="field-box form-group col-md-12 field-agreement_checkbox">
                    <div class="field-wrap-transparent">
                        <input type="checkbox" id="agreement_checkbox_popup" name="popup_agreement" value="' . date('Y-m-d\TH:i:s\Z') . '" class="form-control"> <label for="agreement_checkbox_popup">I agree to the Variscite  <a href="/privacy-policy/" target="_blank">Privacy Policy</a></label>
                    </div>
                </div>';
    $output .=  '<div class="submit-box">
                    <div class="row">
                        <div class="col-12 text-center"><input type="submit" name="submit" class="btn btn-warning btn-lg btn-arrow-01 submitPopupFomrRequest" value="Submit"></div>
                        <div class="col-12"><div class="notice"></div></div>
                    </div>
                </div>';
    $output .=  '</div></div></div></div>';
    $output .= '</div>
            </div>
        </div>
    </div>';

    return $output;
}


/* contact form shortcode */
add_shortcode( 'contact_form_inline', 'contact_form_inline_func' );
function contact_form_inline_func($atts) {
    $islp=false;
    $output = '';

    $output .='<div id="quoteFormWidget" class="quote-form lpForm som-form contact-form-inline" style="border-color: #000000;  ">';
    $output .='<input type="hidden" id="curl" value="'.get_permalink(get_the_ID()).'">
            
            <input type="hidden" id="email_to" value="sales@variscite.com">
            <input type="hidden" id="email_subject" value="New lead from popup form">
            <input type="hidden" id="thanks" value="/thank-you-for-contacting-us-contact-form/">
            <input type="hidden" id="required" value="first_name,last_name,company,email,country,phone">
            <input type="hidden" id="lead_source" value="web">
            <input type="hidden" id="event_name" value="form-lp-success">
 
            <!--=== ADWORDS FIELDS ===-->
            <input type="hidden" id="Campaign_medium__c" value="N/A">
            <input type="hidden" id="Campaign_source__c" value="direct">
            <input type="hidden" id="Campaign_content__c" value="N/A">
            <input type="hidden" id="Campaign_term__c" value="N/A">
            <input type="hidden" id="Page_url__c" value="/variscite-products/">
            <input type="hidden" id="Paid_Campaign_Name__c" value="N/A">
            <input type="hidden" id="GA_id__c" value="">

            <!--=== ADWORDS FIELDS ===-->';

    $output .='<div class="form-inner">';
    if (isset($atts['fromlp']) && $atts['fromlp']) {
        $islp=true;
        if (defined('ICL_LANGUAGE_CODE')) {
            $current_language = ICL_LANGUAGE_CODE;
        } else {
            // Fallback method to get the current language
            $current_language = apply_filters('wpml_current_language', NULL);
        }
        
        // Initialize the variable to hold the "Contact Us" sentence
        $contact_us_sentence = "";
        
        // Set the "Contact Us" sentence based on the current language
        switch ($current_language) {
            case 'en':
                $contact_us_sentence = "Contact Us";
                break;
            case 'de':
                $contact_us_sentence = "Kontakt";
                break;
            case 'it':
                $contact_us_sentence = "Contatti";
                break;
            default:
                // Default to English if the language is not one of the specified ones
                $contact_us_sentence = "Contact Us";
                break;
        }
        
        // Output the contact us sentence (for demonstration purposes)
        

        $output .= '<div class="row_fromlp">' . $contact_us_sentence  . '</div>';
    }
    $output .='<div class="row">';
    $output .= '
                    <div class="col-md-6 field-box form-group field-first_name floating-labels col-md-6">
                        <div class="field-wrap">
                            <div class="row">
                                <div class="col-md-5"><label for="first_name">'.__('First name', 'variscite_lp').'</label></div>
                                <div class="col-md-7"><input type="text" name="first_name" id="first_name" class="form-control" placeholder="" value=""></div>
                            </div><!-- row-->
                        </div><!-- field-wrap -->
                    </div><!-- col-md-6 -->
			
                    <div class="col-md-6 field-box form-group field-last_name floating-labels col-md-6">
                        <div class="field-wrap">
                            <div class="row">
                                <div class="col-md-5"><label for="last_name">'.__('Last name', 'variscite_lp').'</label></div>
                                <div class="col-md-7"><input type="text" name="last_name" id="last_name" class="form-control" placeholder="" value=""></div>
                            </div>
                        </div>
                    </div><!-- col-md-6 -->
                    
                    <div class="col-md-6 field-box form-group field-email floating-labels col-md-6">
                        <div class="field-wrap">
                            <div class="row">
                                <div class="col-md-5"><label for="email">'.__('Email', 'variscite_lp').'</label></div>
                                <div class="col-md-7"><input type="text" name="email" id="email" class="form-control" placeholder="" value=""></div>
                            </div>
                        </div>
                    </div><!-- col-md-6 -->
                    
                    <div class="col-md-6 field-box form-group field-company floating-labels col-md-6">
                        <div class="field-wrap">
                            <div class="row">
                                <div class="col-md-5"><label for="company">'.__('Company', 'variscite_lp').'</label></div>
                                <div class="col-md-7"><input type="text" name="company" id="company" class="form-control" placeholder="" value=""></div>
                            </div>
                        </div>
                    </div><!-- col-md-6 -->';

    $output .=  '<div class="col-md-6 field-box form-group field-country floating-labels col-md-6">
				<div class="field-wrap">
					<div class="row">
						<div class="col-md-5"><label for="country">'.__('Country', 'variscite_lp').'</label></div>
						<div class="col-md-7"><select name="country" id="country" class="form-control"><option value="">Country</option><option value="afghanistan">Afghanistan</option><option value="aland-islands">Aland Islands</option><option value="albania">Albania</option><option value="algeria">Algeria</option><option value="andorra">Andorra</option><option value="angola">Angola</option><option value="anguilla">Anguilla</option><option value="antarctica">Antarctica</option><option value="antigua-and-barbuda">Antigua and Barbuda</option><option value="argentina">Argentina</option><option value="armenia">Armenia</option><option value="aruba">Aruba</option><option value="australia">Australia</option><option value="austria">Austria</option><option value="azerbaijan">Azerbaijan</option><option value="bahamas">Bahamas</option><option value="bahrain">Bahrain</option><option value="bangladesh">Bangladesh</option><option value="barbados">Barbados</option><option value="belarus">Belarus</option><option value="belgium">Belgium</option><option value="belize">Belize</option><option value="benin">Benin</option><option value="bermuda">Bermuda</option><option value="bhutan">Bhutan</option><option value="bolivia,-plurinational-state-of">Bolivia, Plurinational State of</option><option value="bonaire,-sint-eustatius-and-saba">Bonaire, Sint Eustatius and Saba</option><option value="bosnia-and-herzegovina">Bosnia and Herzegovina</option><option value="botswana">Botswana</option><option value="bouvet-island">Bouvet Island</option><option value="brazil">Brazil</option><option value="british-indian-ocean-territory">British Indian Ocean Territory</option><option value="brunei-darussalam">Brunei Darussalam</option><option value="bulgaria">Bulgaria</option><option value="burkina-faso">Burkina Faso</option><option value="burundi">Burundi</option><option value="cambodia">Cambodia</option><option value="cameroon">Cameroon</option><option value="canada">Canada</option><option value="cape-verde">Cape Verde</option><option value="cayman-islands">Cayman Islands</option><option value="central-african-republic">Central African Republic</option><option value="chad">Chad</option><option value="chile">Chile</option><option value="china">China</option><option value="christmas-island">Christmas Island</option><option value="cocos-(keeling)-islands">Cocos (Keeling) Islands</option><option value="colombia">Colombia</option><option value="comoros">Comoros</option><option value="congo">Congo</option><option value="congo,-the-democratic-republic-of-the">Congo, the Democratic Republic of the</option><option value="cook-islands">Cook Islands</option><option value="costa-rica">Costa Rica</option><option value="cote-d\'ivoire">Cote d\'Ivoire</option><option value="croatia">Croatia</option><option value="cuba">Cuba</option><option value="curaçao">Curaçao</option><option value="cyprus">Cyprus</option><option value="czech-republic">Czech Republic</option><option value="denmark">Denmark</option><option value="djibouti">Djibouti</option><option value="dominica">Dominica</option><option value="dominican-republic">Dominican Republic</option><option value="ecuador">Ecuador</option><option value="egypt">Egypt</option><option value="el-salvador">El Salvador</option><option value="equatorial-guinea">Equatorial Guinea</option><option value="eritrea">Eritrea</option><option value="estonia">Estonia</option><option value="ethiopia">Ethiopia</option><option value="falkland-islands-(malvinas)">Falkland Islands (Malvinas)</option><option value="faroe-islands">Faroe Islands</option><option value="fiji">Fiji</option><option value="finland">Finland</option><option value="france">France</option><option value="french-guiana">French Guiana</option><option value="french-polynesia">French Polynesia</option><option value="french-southern-territories">French Southern Territories</option><option value="gabon">Gabon</option><option value="gambia">Gambia</option><option value="georgia">Georgia</option><option value="germany">Germany</option><option value="ghana">Ghana</option><option value="gibraltar">Gibraltar</option><option value="greece">Greece</option><option value="greenland">Greenland</option><option value="grenada">Grenada</option><option value="guadeloupe">Guadeloupe</option><option value="guatemala">Guatemala</option><option value="guernsey">Guernsey</option><option value="guinea">Guinea</option><option value="guinea-bissau">Guinea-Bissau</option><option value="guyana">Guyana</option><option value="haiti">Haiti</option><option value="heard-island-and-mcdonald-islands">Heard Island and McDonald Islands</option><option value="holy-see-(vatican-city-state)">Holy See (Vatican City State)</option><option value="honduras">Honduras</option><option value="hungary">Hungary</option><option value="iceland">Iceland</option><option value="india">India</option><option value="indonesia">Indonesia</option><option value="iran,-islamic-republic-of">Iran, Islamic Republic of</option><option value="iraq">Iraq</option><option value="ireland">Ireland</option><option value="isle-of-man">Isle of Man</option><option value="israel">Israel</option><option value="italy">Italy</option><option value="jamaica">Jamaica</option><option value="japan">Japan</option><option value="jersey">Jersey</option><option value="jordan">Jordan</option><option value="kazakhstan">Kazakhstan</option><option value="kenya">Kenya</option><option value="kiribati">Kiribati</option><option value="korea,-republic-of">Korea, Republic of</option><option value="kuwait">Kuwait</option><option value="kyrgyzstan">Kyrgyzstan</option><option value="lao-people\'s-democratic-republic">Lao People\'s Democratic Republic</option><option value="latvia">Latvia</option><option value="lebanon">Lebanon</option><option value="lesotho">Lesotho</option><option value="liberia">Liberia</option><option value="libya">Libya</option><option value="liechtenstein">Liechtenstein</option><option value="lithuania">Lithuania</option><option value="luxembourg">Luxembourg</option><option value="macao">Macao</option><option value="macedonia,-the-former-yugoslav-republic-of">Macedonia, the former Yugoslav Republic of</option><option value="madagascar">Madagascar</option><option value="malawi">Malawi</option><option value="malaysia">Malaysia</option><option value="maldives">Maldives</option><option value="mali">Mali</option><option value="malta">Malta</option><option value="martinique">Martinique</option><option value="mauritania">Mauritania</option><option value="mauritius">Mauritius</option><option value="mayotte">Mayotte</option><option value="mexico">Mexico</option><option value="moldova,-republic-of">Moldova, Republic of</option><option value="monaco">Monaco</option><option value="mongolia">Mongolia</option><option value="montenegro">Montenegro</option><option value="montserrat">Montserrat</option><option value="morocco">Morocco</option><option value="mozambique">Mozambique</option><option value="myanmar">Myanmar</option><option value="namibia">Namibia</option><option value="nauru">Nauru</option><option value="nepal">Nepal</option><option value="netherlands">Netherlands</option><option value="new-caledonia">New Caledonia</option><option value="new-zealand">New Zealand</option><option value="nicaragua">Nicaragua</option><option value="niger">Niger</option><option value="nigeria">Nigeria</option><option value="niue">Niue</option><option value="norfolk-island">Norfolk Island</option><option value="norway">Norway</option><option value="oman">Oman</option><option value="pakistan">Pakistan</option><option value="panama">Panama</option><option value="papua-new-guinea">Papua New Guinea</option><option value="paraguay">Paraguay</option><option value="peru">Peru</option><option value="philippines">Philippines</option><option value="pitcairn">Pitcairn</option><option value="poland">Poland</option><option value="portugal">Portugal</option><option value="qatar">Qatar</option><option value="reunion">Reunion</option><option value="romania">Romania</option><option value="russian-federation">Russian Federation</option><option value="rwanda">Rwanda</option><option value="saint-barthélemy">Saint Barthélemy</option><option value="saint-helena,-ascension-and-tristan-da-cunha">Saint Helena, Ascension and Tristan da Cunha</option><option value="saint-kitts-and-nevis">Saint Kitts and Nevis</option><option value="saint-lucia">Saint Lucia</option><option value="saint-martin-(french-part)">Saint Martin (French part)</option><option value="saint-pierre-and-miquelon">Saint Pierre and Miquelon</option><option value="saint-vincent-and-the-grenadines">Saint Vincent and the Grenadines</option><option value="samoa">Samoa</option><option value="san-marino">San Marino</option><option value="sao-tome-and-principe">Sao Tome and Principe</option><option value="saudi-arabia">Saudi Arabia</option><option value="senegal">Senegal</option><option value="serbia">Serbia</option><option value="seychelles">Seychelles</option><option value="sierra-leone">Sierra Leone</option><option value="singapore">Singapore</option><option value="sint-maarten-(dutch-part)">Sint Maarten (Dutch part)</option><option value="slovakia">Slovakia</option><option value="slovenia">Slovenia</option><option value="solomon-islands">Solomon Islands</option><option value="somalia">Somalia</option><option value="south-africa">South Africa</option><option value="south-georgia-and-the-south-sandwich-islands">South Georgia and the South Sandwich Islands</option><option value="south-sudan">South Sudan</option><option value="spain">Spain</option><option value="sri-lanka">Sri Lanka</option><option value="sudan">Sudan</option><option value="suriname">Suriname</option><option value="svalbard-and-jan-mayen">Svalbard and Jan Mayen</option><option value="swaziland">Swaziland</option><option value="sweden">Sweden</option><option value="switzerland">Switzerland</option><option value="syrian-arab-republic">Syrian Arab Republic</option><option value="taiwan">Taiwan</option><option value="tajikistan">Tajikistan</option><option value="tanzania,-united-republic-of">Tanzania, United Republic of</option><option value="thailand">Thailand</option><option value="timor-leste">Timor-Leste</option><option value="togo">Togo</option><option value="tokelau">Tokelau</option><option value="tonga">Tonga</option><option value="trinidad-and-tobago">Trinidad and Tobago</option><option value="tunisia">Tunisia</option><option value="turkey">Turkey</option><option value="turkmenistan">Turkmenistan</option><option value="turks-and-caicos-islands">Turks and Caicos Islands</option><option value="tuvalu">Tuvalu</option><option value="uganda">Uganda</option><option value="ukraine">Ukraine</option><option value="united-arab-emirates">United Arab Emirates</option><option value="united-kingdom">United Kingdom</option><option value="united-states">United States</option><option value="uruguay">Uruguay</option><option value="uzbekistan">Uzbekistan</option><option value="vanuatu">Vanuatu</option><option value="venezuela,-bolivarian-republic-of">Venezuela, Bolivarian Republic of</option><option value="viet-nam">Viet Nam</option><option value="virgin-islands,-british">Virgin Islands, British</option><option value="wallis-and-futuna">Wallis and Futuna</option><option value="western-sahara">Western Sahara</option><option value="yemen">Yemen</option><option value="zambia">Zambia</option><option value="zimbabwe">Zimbabwe</option></select></div>
                        </div>
                    </div>
                </div><!-- col-md-6 -->';

    $output .=  '<div class="col-md-6 field-box form-group field-phone floating-labels col-md-6">
                    <div class="field-wrap">
                        <div class="row">
                            <div class="col-md-5"><label for="phone">'.__('Phone', 'variscite_lp').'</label></div>
                            <div class="col-md-7"><input type="text" id="phone" class="form-control" placeholder="" value=""></div>
                        </div>
                    </div>
                    </div><!-- col-md-6 -->
                
                    
                    <div class="field-box form-group field-note floating-labels col-md-12">
                        <div class="field-wrap">
						
                            <!-- <div class="col-md-5"><label for="note">Note</label></div> -->					
                            <textarea maxlength="2000" id="note" cols="30" rows="10" class="form-control" placeholder="'.__('Note', 'variscite_lp').'"></textarea>
							
                        </div><!-- field-wrap -->
                    </div><!-- col-md-12-->
                    
                    <div class="field-box form-group col-md-12 field-agreement_checkbox">
                        <div class="field-wrap-transparent">
                            <input type="checkbox" id="agreement_checkbox" name="agreement" value="2021-12-21T06:23:45Z"> <label for="agreement_checkbox">'.__('I agree to the Variscite ', 'variscite_lp').' <a href="/privacy-policy/" target="_blank">'.__('Privacy Policy', 'variscite_lp').'</a></label>
                        </div>
                    </div><!-- col-md-12-->
                    
                    

                    <div class="submit-box col-md-12">
                        <div class="notice"></div>
                        <div class="text-right"><input type="submit" name="submit" class="btn btn-warning ';
                        if($islp) {
                            $output .= 'short ';
                        }
                        $output .= 'btn-lg btn-arrow-01 submitQuoteWidgetRequest" value="'.__('Send', 'variscite_lp').'"></div>
                    </div><!-- submit-box-->
                
    ';

    $output .=  '</div><!-- row -->';
    $output .='</div><!-- form-inner -->';
    $output .=  '</div><!-- quoteFormWidget -->';
    return $output;
}


add_shortcode( 'testimonial_slider', 'testimonial_slider_func' );

function testimonial_slider_func($atts) {
    $args = [
        'post_type' => 'testimonial',
        'posts_per_page' => -1
    ];

    $the_query = new WP_Query( $args );
    $output_html = '';

    if ( $the_query->have_posts() )  {
        $testimonial_title = get_field('testimonial_title','option');

        if(!empty($testimonial_title) && $atts['title'] != 'false') {
            $output_html .= '<h2>'.$testimonial_title.'</h2>';
        }

        $output_html .= '<div class="customer-say-inner">';
        $output_html .= '<div class="swiper js-customer-say">';
        $output_html .= '<div class="swiper-wrapper">';

        while ( $the_query->have_posts() ) {
            $the_query->the_post();
            $output_html .='<div class="swiper-slide">';
            $output_html .= get_the_content();
            $output_html .= '<div class="client-info"><h4>'.get_the_title().'</h4><figure>'.get_the_post_thumbnail().'</figure></div>';
            $output_html .='</div>';
        }

        $output_html .= '</div>
        </div>
        <div class="swiper-button-next"></div>
        <div class="swiper-button-prev"></div></div>';
    }
    wp_reset_postdata();

    return $output_html;
}

