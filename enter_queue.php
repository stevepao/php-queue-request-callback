<?php

require_once './vendor/autoload.php';
use SignalWire\LaML;

// Load environment variables from .env
$dotenv = Dotenv\Dotenv::createImmutable('.');
$dotenv->load();

$response = new LaML();
$self="./" . basename(__FILE__);


// If 1 pressed in queue, then play prompt and send text message
if (array_key_exists('Digits', $_REQUEST)) {
  $digit = $_REQUEST['Digits'];
  if ($digit == 1) {
    $response->say('OK. Your call has been queued for call back, We will call you back shortly.  Thank you.');
    $response->redirect('./text_request_call.php');
    echo $response;
    exit(0);
  }
}

// If in queue and 1 not pressed.  Play prompt and gather.
if (array_key_exists('QueueSid', $_REQUEST)) {
  $response->say('Please hold for the next available representative.');
  $response->play('https://sinergyds.blob.core.windows.net/signalwire/snoopclose.wav');
  $gather = $response->gather(array('numDigits' => 1));
  $gather->say('Your call is very important to us, a representative will be with you shortly. To request a call back, press 1 and you will not lose your place in line.');
  $response->redirect($self);
  echo $response;
} else {

// Otherwise play opening prompt

  $response->say("Thank you for calling the queue callback request demo.");
  $response->enqueue('DemoQueue1', ['waitUrl' => $self]);
  echo $response;

}
?>
