<?php
/*************************************************
** EMAIL VALIDATION
*************************************************/
function is_valid_email($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL) && preg_match('/@.+\./', $email);
}


/*************************************************
** VALID PHONE NUMBER
*************************************************/
function is_valid_phone($phone) {
    $phone = preg_replace('/[^0-9]/', '', $phone);
    
    if( strlen($phone) >= 10 ) return true;
}
?>