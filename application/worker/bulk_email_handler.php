<?php

require_once('PHPMailer/class.phpmailer.php');
require_once __DIR__.'/../libraries/vendor/autoload.php';
use PhpAmqpLib\Connection\AMQPConnection;
use PhpAmqpLib\Message\AMQPMessage;


define('HOST', 'localhost');
define('PORT', 5672);
define('USER', 'guest');
define('PASS', 'guest');
define('VHOST', '/');

//If this is enabled you can see AMQP output on the CLI
define('AMQP_DEBUG', true);

$exchange = 'bulker';
$queue = 'bulk_emails';
$consumer_tag = 'consumer';

$conn = new AMQPConnection(HOST, PORT, USER, PASS, VHOST);
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


function send_mail($to,$from,$from_password,$message) {
	$mail  = new PHPMailer(); // defaults to using php "mail()"
	$mail->IsSMTP(); // enable SMTP
	$mail->SMTPDebug = 2; // debugging: 1 = errors and messages, 2 = messages only
	$mail->SMTPAuth = true; // authentication enabled
	$mail->SMTPSecure = 'ssl'; // secure transfer enabled REQUIRED for GMail
	$mail->Host = "smtp.gmail.com";
	$mail->Port = 465; // or 587
	$mail->IsHTML(true);
	$mail->Username = $from;
	$mail->Password = $from_password;
	$body=$message;

	$mail->AddReplyTo($from,"OZONE-PLAY");

	$mail->AddAddress($to,"OZONE-PLAY user");

	$mail->Subject    = "OZONE-PLAY";

	$mail->AltBody    = "To view the message, please use an HTML compatible email viewer!"; // optional, comment out and test

	$mail->MsgHTML($body);

	if(!$mail->Send()) {
	  echo "Mailer Error: " . $mail->ErrorInfo;
	} else {
	  echo "Message sent!";
	}
}


set_error_handler('myErrorHandler');
register_shutdown_function('fatalErrorShutdownHandler');
function fatalErrorShutdownHandler()
{
  $last_error = error_get_last();
  if ($last_error['type'] === E_ERROR) {
    // fatal error
    myErrorHandler(E_ERROR, $last_error['message'], $last_error['file'], $last_error['line']);
  }
}
function myErrorHandler() {
    echo 'yaen';
}

function process_message($msg)
{
        require 'lull.php';
        echo "\n----ANYTHINGGGGGGGGGGGGGGGGGG----\n";
    //    echo $msg->body;
    //    $arr = json_decode($msg->body);
    //    send_mail($arr->to,$arr->from,$arr->from_pass,$arr->message);
        echo "\n--------\n";

        $msg->delivery_info['channel']->
            basic_ack($msg->delivery_info['delivery_tag']);

    // // Send a message with the string "quit" to cancel the consumer.
    // if ($msg->body === 'quit') {
    //     $msg->delivery_info['channel']->
    //         basic_cancel($msg->delivery_info['consumer_tag']);
    // }
}

/*
    queue: Queue from where to get the messages
    consumer_tag: Consumer identifier
    no_local: Don't receive messages published by this consumer.
    no_ack: Tells the server if the consumer will acknowledge the messages.
    exclusive: Request exclusive consumer access, meaning only this consumer can access the queue
    nowait:
    callback: A PHP Callback
*/

$ch->basic_consume($queue, $consumer_tag, false, false, false, false, 'process_message');

function shutdown($ch, $conn)
{
    $ch->close();
    $conn->close();
}
register_shutdown_function('shutdown', $ch, $conn);

// Loop as long as the channel has callbacks registered
while (count($ch->callbacks)) {
    $ch->wait();
}