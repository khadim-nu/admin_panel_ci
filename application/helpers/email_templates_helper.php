<?php

function change_status_email($email_data) {
    $CI = & get_instance();
    $CI->load->helper('email_sender');

    $body = '<h3>Account Status Changed successfully</h3>';
    $body = $body . 'Your account status is changed by Admin. <br>';
    $body = $body . 'Current status is  <strong>' . $email_data['value1'] . '.</strong> <br> <br>';
    $body = $body . 'For more information clicke here  ' . base_url();
    $body = $body . '<hr>';
    $body = $body . 'You have received this notification because you have either subscribed to it, or are involved in it.
            To change your notification preferences, please click here: http://hostname/my/account';
    // setting parameters in array
    $data = array();
    $data['to'] = $email_data['email'];
    $data['to_name'] = $email_data['name'];
    $data['from'] = ADMIN_EMAIL;
    $data['from_name'] = ADMIN_NAME;
    $data['from_pass'] = ADMIN_EMAIL_PASSWORD;
    $data['subject'] = 'Account Status Changed';
    $data['body'] = $body;
    // calling function in email_sender_helper
    send_email_direct($data);
    return TRUE;
}

function seed_email($email_data) {
    $CI = & get_instance();
    $CI->load->helper('email_sender');
    $body = '<h3>Registered in ' . APP_NAME . ' successfully</h3>';
    $body = $body . 'Your account is created as <strong>' . $email_data['type'] . '</strong>  by Super Admin. <br>';
    $body = $body . '<h4>Login Information</h4>';
    $body = $body . 'Login ID : <strong>' . $email_data['email'] . '</strong> <br>';
    $body = $body . 'Password :<strong>' . $email_data['password'] . '</strong> <br>';
    $body = $body . 'Current status is  <strong> Active </strong> <br> <br>';
    $body = $body . 'For more information clicke here  ' . base_url();
    $body = $body . '<hr>';
    $body = $body . 'You have received this notification because you have either subscribed to it, or are involved in it.
            To change your notification preferences, please click here: http://hostname/my/account';
    // setting parameters in array
    $data = array();
    $data['to'] = $email_data['email'];
    $data['to_name'] = $email_data['name'];
    $data['from'] = ADMIN_EMAIL;
    $data['from_name'] = ADMIN_NAME;
    $data['from_pass'] = ADMIN_EMAIL_PASSWORD;
    $data['subject'] = 'Registratation';
    $data['body'] = $body;
    // calling function in email_sender_helper
    send_email_direct($data);
    return TRUE;
}

function register_email($email_data) {
    $CI = & get_instance();
    $CI->load->helper('email_sender');
    if ($email_data['type'] != 'Player') {
        $body = '<h3>Registered in ' . APP_NAME . ' successfully </h3>';
        $body = $body . 'Your account is created as <strong>' . $email_data['type'] . ' </strong>. <br>';
        $body = $body . '<strong>Login Details:</strong> <br>';
        $body = $body . '   Login ID : <strong>' . $email_data['email'] . '</strong> <br>';
        $body = $body . '   Password : <strong>' . $email_data['password'] . '</strong> <br>';


        if (isset($email_data['token'])) {
            $body = $body . 'Current status is  <strong> Not Verified </strong> ';
            $body = $body . 'To verify Click on <h3>' . $email_data['url'] . ' </h3>';
        } else {
            $body = $body . 'Current status is  <strong> Not Verified </strong>. verification request is sent to Admin. <br>';
            $body = $body . 'On verification you will be notified. <br><br>';
        }
        $body = $body . 'For more information clicke here  ' . base_url();
        $body = $body . '<hr>';
        $body = $body . 'You have received this notification because you have either subscribed to it, or are involved in it.
            To change your notification preferences, please click here: http://hostname/my/account';
    } else {
        $body = $CI->load->view('email_notifications/player_register', $email_data, TRUE);
    }
    // setting parameters in array
    $data = array();
    $data['to'] = $email_data['email'];
    $data['to_name'] = $email_data['name'];
    $data['from'] = ADMIN_EMAIL;
    $data['from_name'] = ADMIN_NAME;
    $data['from_pass'] = ADMIN_EMAIL_PASSWORD;
    $data['subject'] = 'User Registration and Account Verification';
    $data['body'] = $body;
    // calling function in email_sender_helper
    send_email_direct($data);
    return TRUE;
}

function admin_registration_verification_email($email_data) {
    $CI = & get_instance();
    $CI->load->helper('email_sender');
    $body = '<h3>Verify Registration for ' . APP_NAME . ' </h3>';
    $body = $body . 'User is registered as Admin in ' . APP_NAME . '. <br>';
    $body = $body . 'Email id is : <strong>' . $email_data['email'] . '</strong> <br>';
    $body = $body . 'Current status is  <strong> Not Verified </strong> ';
    $body = $body . 'To verify Click on <h3>' . $email_data['url'] . ' </h3>';

    $body = $body . '<br>For more information clicke here  ' . base_url();
    $body = $body . '<hr>';
    $body = $body . 'You have received this notification because you have either subscribed to it, or are involved in it.
            To change your notification preferences, please click here: http://hostname/my/account';

    // setting parameters in array
    $data = array();
    $data['to'] = ADMIN_EMAIL;
    $data['to_name'] = ADMIN_NAME;
    $data['from'] = ADMIN_EMAIL;
    $data['from_name'] = ADMIN_NAME;
    $data['from_pass'] = ADMIN_EMAIL_PASSWORD;
    $data['subject'] = 'Registratation Verification Request';
    $data['body'] = $body;
    // calling function in email_sender_helper
    send_email_direct($data);
    return TRUE;
}

function verify_registration_email($email_data) {
    $CI = & get_instance();
    $CI->load->helper('email_sender');

    $body = '<h3>Account Registration is Verified successfully</h3>';
    $body = $body . 'Your account registration request is verified as <strong> ' . $email_data['type'] . ' </strong>. <br>';
    $body = $body . 'Current status is  <strong> Active </strong>. <br>';
    $body = $body . 'You can Login as <strong> ' . $email_data['type'] . ' </strong>. <br> <br>';
    $body = $body . 'Your Password is <strong> ' . $email_data['password'] . ' </strong>. <br> <br>';
    $body = $body . 'For more information click here  ' . base_url() . $email_data['url'];
    $body = $body . '<hr>';
    $body = $body . 'You have received this notification because you have either subscribed to it, or are involved in it.
            To change your notification preferences, please click here: http://hostname/my/account';
    // setting parameters in array
    $data = array();
    $data['to'] = $email_data['email'];
    $data['to_name'] = $email_data['name'];
    $data['from'] = ADMIN_EMAIL;
    $data['from_name'] = ADMIN_NAME;
    $data['from_pass'] = ADMIN_EMAIL_PASSWORD;
    $data['subject'] = 'Registration Verified';
    $data['body'] = $body;
    // calling function in email_sender_helper
    send_email_direct($data);
    return TRUE;
}

function update_password_email($email_data) {
    $CI = & get_instance();
    $CI->load->helper('email_sender');

    $body = '<h3>Password Changed successfully</h3>';
    $body = $body . 'Your password is changed. <br>';
    $body = $body . 'New password is  <strong>' . $email_data['password'] . ' </strong> <br> <br>';
    $body = $body . 'For more information clicke here  ' . base_url();
    $body = $body . '<hr>';
    $body = $body . 'You have received this notification because you have either subscribed to it, or are involved in it.
            To change your notification preferences, please click here: http://hostname/my/account';
    // setting parameters in array
    $data = array();
    $data['to'] = $email_data['email'];
    $data['to_name'] = $email_data['name'];
    $data['from'] = ADMIN_EMAIL;
    $data['from_name'] = ADMIN_NAME;
    $data['from_pass'] = ADMIN_EMAIL_PASSWORD;
    $data['subject'] = 'Password Changed';
    $data['body'] = $body;
    // calling function in email_sender_helper
    send_email_direct($data);
    return TRUE;
}

function forgot_password_email($email_data) {
    $CI = & get_instance();
    $CI->load->helper('email_sender');
    $body = $CI->load->view('email_templates/forgot_password', $email_data, TRUE);

    // setting parameters in array
    $data = array();
    $data['to'] = $email_data['email'];
    $data['to_name'] = $email_data['name'];
    $data['from'] = ADMIN_EMAIL;
    $data['from_name'] = ADMIN_NAME;
    $data['from_pass'] = ADMIN_EMAIL_PASSWORD;
    $data['subject'] = 'Forgot Password';
    $data['body'] = $body;
    // calling function in email_sender_helper
    send_email_direct($data);
    return TRUE;
}

function email_edit_basic_info($email_data) {
    $CI = & get_instance();
    $CI->load->helper('email_sender');

    $body = '<h3>Account Basic Information Updated Successfully</h3>';
    $body = $body . 'Your have Updated Account Basic Information. <br>';
    $body = $body . 'Your New <strong>Information Details are:</strong> <br>';

    if (isset($email_data['lname'])) {
        $body = $body . '   First Name: ' . $email_data['fname'] . ' <br>';
        $body = $body . '   Last Name: ' . $email_data['lname'] . '<br>';
    } else {
        $body = $body . 'Name: ' . $email_data['fname'] . '<br>';
    }
    $body = $body . '<br>For more information clicke here  ' . base_url();
    $body = $body . '<hr>';
    $body = $body . 'You have received this notification because you have either subscribed to it, or are involved in it.
            To change your notification preferences, please click here: http://hostname/my/account';
    // setting parameters in array
    $data = array();
    $data['to'] = $email_data['email'];
    $data['to_name'] = $email_data['name'];
    $data['from'] = ADMIN_EMAIL;
    $data['from_name'] = ADMIN_NAME;
    $data['from_pass'] = ADMIN_EMAIL_PASSWORD;
    $data['subject'] = 'Basic Info Updated';
    $data['body'] = $body;
    // calling function in email_sender_helper
    send_email_direct($data);
    return TRUE;
}
