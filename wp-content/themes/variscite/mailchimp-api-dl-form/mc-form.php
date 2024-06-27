<?php

include './MailChimp.php';
require_once("../../../../wp-load.php");

use \DrewM\MailChimp\MailChimp;

$api_key = '3b4729fb3d5cf2fe4d56f007647818fc-us15';
$list_id = 'a65e6c887f';

$MailChimp = new MailChimp($api_key);

if($_POST) {
    $fname = $_POST['FNAME'];
    $lname = $_POST['LNAME'];
    $email = $_POST['EMAIL'];
    $country = $_POST['COUNTRY'];
    $company = $_POST['MMERGE6'];
    $privacy = (isset($_POST['PRIVACY']) && $_POST['PRIVACY']=='Yes') ? "Yes": "No";


    function emailExistsMc($subscriberMail, $list_id){
        global $MailChimp;
        $subscriber_hash = $MailChimp->subscriberHash($subscriberMail);
        $result = $MailChimp->get("lists/$list_id/members/$subscriber_hash");
        if($result['status'] == '404') return false;
        return true;
    }

    $subscriber_hash = MailChimp::subscriberHash($email);

    if(emailExistsMc($email, $list_id)){
        $contact = $MailChimp->get("lists/$list_id/members/$subscriber_hash");
        $contact['marketing_permissions'][0]['enabled']=($privacy=='Yes');
        $enabledMarketingPermissions[] = $contact['marketing_permissions'][0];
        $result = $MailChimp->patch("lists/$list_id/members/$subscriber_hash", [
            'email_address' => $email,
            'merge_fields' => ['FNAME'=>$fname, 'LNAME'=>$lname, 'COUNTRY'=>$country, 'MMERGE6'=>$company, 'PRIVACY'=>$privacy],
            'marketing_permissions' => $enabledMarketingPermissions,
            'tags'  => array('Guest post OpenAMP'),
            'status'        => 'subscribed',
        ]);
    } else {
        $gpdr = array(
            "marketing_permission_id" => "f9eb1fef27",
            "text" => "I agree to the Variscite privacy policy",
            "enabled" => ($privacy=='Yes')
        );
        $enabledMarketingPermissions[] = $gpdr;
        $result = $MailChimp->post("lists/$list_id/members", [
            'email_address' => $email,
            'merge_fields' => ['FNAME'=>$fname, 'LNAME'=>$lname, 'COUNTRY'=>$country, 'MMERGE6'=>$company, 'PRIVACY'=>$privacy],
            "marketing_permissions" => $enabledMarketingPermissions,
            'tags'  => array('Guest post OpenAMP'),
            'status'        => 'subscribed',
        ]);
    }

    if ($MailChimp->success()) {
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
//        error_log("devtest: tried to email, result=".print_r($result,true));
        wp_mail($to,$subject,$body);
    }
    else {
        error_log('mc-form.php: Form failed to send - '. $MailChimp->getLastError());
        http_response_code(501);
    }
}




