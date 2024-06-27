<?php

// Load the WP environment
define('WP_USE_THEMES', false);

$staging_path = dirname(dirname(dirname(__DIR__)));
require_once $staging_path . '/wp-blog-header.php';
require_once $staging_path . '/wp-config.php';

global $wpdb;

$leads = new WP_Query(array(
    'post_type' => 'leads',
    'posts_per_page' => -1,
    'meta_query' => array(
        'relation' => 'OR',
        array(
            'key'   => 'lead_record_sf',
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
        'after' => '2022-04-10'
    ),
));

if($leads->posts && ! empty($leads->posts)) {

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

        $message = '
            <h4>'.__('Sender Information', THEME_NAME).'</h4>
            <ul>
                <li> <strong>From:</strong> '.$sfdc_data['first_name'].' '.$sfdc_data['last_name'].'</li>
                <li> <strong>Phone:</strong> '.$sfdc_data['phone'].'</li>
                <li> <strong>Email:</strong> '.$sfdc_data['email'].'</li>
                <li> <strong>Company:</strong> '.$sfdc_data['company'].'</li>
                <li> <strong>Country:</strong> '.$sfdc_data['country'].'</li>
                <li> <strong>Note:</strong><br> '.$sfdc_data['note'].'</li>
            </ul>
            <br>
    
            <h4>'.__('Product Information', THEME_NAME).'</h4>
            <ul>
                <li> <strong>System on Module:</strong> '.$sfdc_data['System__c'].'</li>
                <li> <strong>Operating Systems:</strong> '.$sfdc_data['Operating_System__c'].'</li>
                <li> <strong>Estimated Quantities:</strong> '.$sfdc_data['Projected_Quantities__c'].'</li>
            </ul>
            <br>
    
            <h4>'.__('Additional Information', THEME_NAME).'</h4>
            <ul>
                <li> <strong>Origin Product:</strong> '.$sfdc_data['url'].'</li>
                <li> <strong>User Device:</strong> '.$sfdc_data['device'].'</li>
            </ul>
    
            <h4>'.__('Campagin Information (optional)', THEME_NAME).'</h4>
            <ul>
                <li> <strong>Campagin Medium:</strong> '.$sfdc_data['Campaign_medium__c'].'</li>
                <li> <strong>Campagin Source:</strong> '.$sfdc_data['Campaign_source__c'].'</li>
                <li> <strong>Campagin Content:</strong> '.$sfdc_data['Campaign_content__c'].'</li>
                <li> <strong>Campagin Term:</strong> '.$sfdc_data['Campaign_term__c'].'</li>
                <li> <strong>Page_url__c:</strong> '.$sfdc_data['curl'].'</li>
                <li> <strong>Paid_Campaign_Name__c:</strong> '.$sfdc_data['Paid_Campaign_Name__c'].'</li>
                <li> <strong>GA ID:</strong> '.$sfdc_data['GA_id__c'].'</li>
            </ul>
            ';

        if(strpos(get_field('email_subject', $lid), 'landing') != false){
            $subjectString = '[New Lead from landing page] ';
        } else{
            $subjectString = '[New Lead from contact us page] ';
        }

        $lang = get_field('email_lang', $lid);
        $sendResult	= wp_mail($settings['email_to'], $subjectString . $sfdc_data['company'] . ' [' . strtoupper($lang).']', $message);
        if($sendResult) { update_field('lead_record_email', 'on', $lid);  }
    }
}

wp_send_json_success();
