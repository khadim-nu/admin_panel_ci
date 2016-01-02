<?php
use PhpAmqpLib\Connection\AMQPConnection;
use PhpAmqpLib\Message\AMQPMessage;

function send_email_direct($email_data) {
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
            echo "Mailer Error: " . $mail->ErrorInfo;
        return FALSE;
    } else {
        return true;
    }
}


function mail_me_enqueue($email_data) {
//    define('HOST', 'localhost');
//    define('PORT', 5672);
//    define('USER', 'guest');
//    define('PASS', 'guest');
//    define('VHOST', '/');

//If this is enabled you can see AMQP output on the CLI
//    define('AMQP_DEBUG', true);

    $exchange = 'emailer';
    $queue = 'simple_emails';

    $conn = new AMQPConnection(RMQHOST, RMQPORT, RMQUSER, RMQPASS, RMQVHOST);
    $ch = $conn->channel();

    /*
      The following code is the same both in the consumer and the producer.
      In this way we are sure we always have a queue to consume from and an
      exchange where to publish messages.
     */

    /*
      name: $queue
      passive: false
      durable: true // the queue will survive server restarts
      exclusive: false // the queue can be accessed in other channels
      auto_delete: false //the queue won't be deleted once the channel is closed.
     */
    $ch->queue_declare($queue, false, true, false, false);

    /*
      name: $exchange
      type: direct
      passive: false
      durable: true // the exchange will survive server restarts
      auto_delete: false //the exchange won't be deleted once the channel is closed.
     */

    $ch->exchange_declare($exchange, 'direct', false, true, false);

    $ch->queue_bind($queue, $exchange);

   // $msg_body = array("to" => $to, "from" => $from,"from_pass"=> $from_password, "message" => $message);
    $msg_body = json_encode($email_data);
    $msg = new AMQPMessage($msg_body, array('content_type' => 'text/plain', 'delivery_mode' => 2));
    $ch->basic_publish($msg, $exchange);

    $ch->close();
    $conn->close();
}





