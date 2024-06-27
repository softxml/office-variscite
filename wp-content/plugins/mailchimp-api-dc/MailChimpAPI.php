<?php

require_once(plugin_dir_path(__FILE__).'vendor/autoload.php');

/**
 *
 * A class created to simplify the use of MailChimp's official API.
 *
 * @link https://mailchimp.com/developer/marketing/guides/quick-start/
 *
 */
class MailChimpAPI
{

    private $api_key;
    private $server;
    private $client;

    /**
     * Settings for the API
     */
    public function __construct()
    {
        $this->api_key = '0c04cbfeef6c6a72c6b2c322af9e338e-us15';
        $this->server = 'us15';
        $this->client = new MailchimpMarketing\ApiClient();
        $this->client->setConfig([
            'apiKey' => $this->api_key,
            'server' => $this->server,
        ]);

        // Privacy policy consent page actions
        add_action('wp_ajax_update_mailchimp_user', array($this, 'pp_consent_form'));
        add_action('wp_ajax_nopriv_update_mailchimp_user', array($this, 'pp_consent_form'));

        // Asymmetric multiprocessing on heterogeneous multiprocessor systems actions
        add_action('wp_ajax_asym_mc', array($this, 'asym_form'));
        add_action('wp_ajax_nopriv_asym_mc', array($this, 'asym_form'));
    }

    /**
     *
     * Endpoints that reads or updates existing users requires the user's email hash.
     * The hash is the md5 version of the lowercase email
     *
     * @param string $contact_email The contact's email
     * @return string
     */
    public function email_hash($contact_email)
    {
        return md5(strtolower($contact_email));
    }


    /**
     *
     * Search for contact on MailChimp
     *
     * @param string $contact_email The contact's email
     * @param string $list_id The list id on MailChimp
     * @return false|mixed Contact object if found. False if nothing found
     *
     * @link https://mailchimp.com/help/find-audience-id/
     */
    public function get_list_member($contact_email, $list_id = "a65e6c887f")
    {
        try {
            // Check if there is a user with given email
            return $this->client->lists->getListMember($list_id, $this->email_hash($contact_email));
        } catch (Exception $e) {
            return false;
        }
    }

    /**
     *
     * Create a new contact in MailChimp list
     *
     * @param string $contact_email The contact's email
     * @param array $args A key-value of details about the contact according to MailChimp's API
     * @param array $merge_fields A key-value of merge fields according to MailChimp's API
     * @param string $list_id The id of the list to add the contact to
     * @return array|bool[] Response array whether the user has been created/updated or not
     *
     * @link https://mailchimp.com/developer/marketing/api/list-members/add-or-update-list-member/
     * @link https://mailchimp.com/developer/marketing/docs/merge-fields/#add-merge-data-to-contacts
     */
    public function add_list_member($contact_email, $args, $merge_fields = null, $list_id = "a65e6c887f")
    {
        try {
            $args['email_address'] = $contact_email;
            $args['merge_fields'] = $merge_fields;
            if (isset($merge_fields)) {
                $args[] = array("skip_merge_validation" => true);
            }
            $this->client->lists->addListMember($list_id, $args);
            return array(
                'result' => true
            );
        } catch (Exception $e) {
            return array(
                'result' => false,
                'notes' => 'Please provide a valid email',
                'error' => $e->getMessage()
            );
        }
    }

    /**
     * @param string $contact_email The contact's email
     * @param array $args A key-value of details about the contact according to MailChimp's API
     * @param array $merge_fields A key-value of merge fields according to MailChimp's API
     * @param string $list_id The id of the list to add the contact to
     * @return array|bool[] Response array whether the user has been created/updated or not
     *
     * @link https://mailchimp.com/developer/marketing/api/list-members/add-or-update-list-member/
     * @link https://mailchimp.com/developer/marketing/docs/merge-fields/#add-merge-data-to-contacts
     */
    public function update_list_member($contact_email, $args = array(), $merge_fields = null, $list_id = "a65e6c887f")
    {
        try {
            $args['email_address'] = $contact_email;
            $args['status_if_new'] = 'subscribed';
            if (isset($merge_fields)) {
                $args['merge_fields'] = $merge_fields;
                $args[] = array("skip_merge_validation" => true);
            }
            $this->client->lists->setListMember($list_id, $this->email_hash($contact_email), $args);

            return array(
                'result' => true
            );

        } catch (Exception $e) {
            return array(
                'result' => false,
                'error' => $e->getMessage()
            );
        }
    }

    /**
     *
     * The function for the Privacy Policy Consent form
     *
     * @return void
     */
    public function pp_consent_form()
    {
        $data = array();

        foreach ($_POST['form_data'] as $form_data) {
            $data[$form_data['key']] = $form_data['val'];
        }

        $email = $data['email'];

        $args = array(
            'tags' => array(
                'Privacy policy consent page',
            ),
        );
        $merge_fields = array(
            "FNAME"     => $data["firstname"],
            "LNAME"     => $data["lastname"],
            "PPDATE"    => date("Y-m-d H:i:s")
        );

        $response = $this->update_list_member($email, $args, $merge_fields);

        if ($response['result']) {
            echo json_encode(array(
                'result' => true
            ));
            exit;
        } else {
            echo json_encode(array(
                'result' => false,
                'error' => $response['error'],
                'notes' => 'Please provide a valid email',
            ));
            exit;
        }
    }

    /**
     *
     * The function for the Asymmetric Multiprocessing on Heterogeneous Multiprocessor Systems form
     *
     * @return void
     */
    public function asym_form() {
        if (!$_POST) {
            error_log('mc-form.php: Form failed to send');
            http_response_code(501);
        }
        $fname = $_POST['FNAME'];
        $lname = $_POST['LNAME'];
        $email = $_POST['EMAIL'];
        $country = $_POST['COUNTRY'];
        $company = $_POST['MMERGE6'];
        $privacy = (isset($_POST['PRIVACY']) && $_POST['PRIVACY']=='Yes') ? "Yes": "No";

        $merge_fields = array(
            "FNAME"     => $fname,
            "LNAME"     => $lname,
            "COUNTRY"   => $country,
            "MMERGE6"   => $company,
            "PRIVACY"   => $privacy
        );

        $response = $this->update_list_member($email, array(), $merge_fields);

        if ($response['result']) {
            $to = array( 'ayelet.o@variscite.com', 'lena.g@variscite.com','sales@variscite.com');
            $subject = "New Article Download  - Implementation of asymmetric multiprocessing using a practical example on the i.MX8X with OpenAMP - $company";
            $body = "A new article download  – Implementation of asymmetric multiprocessing using a practical example on the i.MX8X with OpenAMP <br><br>

            First Name: $fname<br>
            Last Name: $lname<br>
            Company: $company<br>
            Email: $email<br>
            Country: $country<br>";
            $headers = array(
                'Content-Type: text/html; charset=UTF-8',
            );
            wp_mail($to,$subject,$body);
            wp_send_json_success();
        } else {
            wp_send_json_error();
        }

    }
}

$mc_api = new MailChimpAPI();
