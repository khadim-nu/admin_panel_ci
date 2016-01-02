<?php
function send_email_direct_direct($email_data) {
    // require_once('libs/PHPMailer/class.phpmailer.php');
    $CI = & get_instance();
    $CI->load->library('My_phpmailer');
    $mail = new PHPMailer(); // defaults to using php "mail()"
    $mail->IsSMTP(); // enable SMTP
    // $mail->SMTPDebug = 2; // debugging: 1 = errors and messages, 2 = messages only
    $mail->SMTPAuth = TRUE; // authentication enabled
    $mail->SMTPSecure = 'ssl';
    $mail->Host = "smtp.gmail.com";
    $mail->Port = 465; // or 587
    $mail->IsHTML(true);
    $mail->Username = ADMIN_EMAIL;
    $mail->From = ADMIN_EMAIL;
    $mail->FromName = ADMIN_NAME;
    $mail->Password = ADMIN_EMAIL_PASSWORD;

    $body = $email_data['body'];
    if (isset($email_data['from'])) {
        $mail->AddReplyTo($email_data['from'], $email_data['from_name']);
    }
    if (isset($email_data['to'])) {
        $mail->AddAddress($email_data['to'], $email_data['to_name']);
    } else {
        $mail->AddAddress(TO_EMAIL, TO_NAME);
    }
    $mail->Subject = $email_data['subject'];

    $mail->AltBody = "To view the message, please use an HTML compatible email viewer!"; // optional, comment out and test

    $mail->MsgHTML($body);

    if (!$mail->Send()) {
//            echo "Mailer Error: " . $mail->ErrorInfo;
        return FALSE;
    } else {
        return true;
    }
}

?>