<?php
  // Sends the text message and then queues up the call asynchronously

  // Setup
  require_once './vendor/autoload.php';
  use SignalWire\Rest\Client;
  use SignalWire\LaML;

  // Load environment variables from .env
  $dotenv = Dotenv\Dotenv::createImmutable('.');
  $dotenv->load();

  $from=$_ENV['SIGNALWIRE_NUMBER'];

  // text back to the original caller ID
  $to = $_REQUEST['From'];

  // Customize this text string
  $body = "Thank you for requesting a call back.  We will call you in about 3 minutes.";

  // If a text message then just respond
  if (array_key_exists('MessageSid', $_REQUEST)) {
     $response = new LaML();
     $response->message($body);
     echo $response;

  // Otherwise, if a call, send a text
  } elseif (array_key_exists('CallSid', $_REQUEST)) {
     $project=$_ENV['SIGNALWIRE_PROJECT'];
     $token=$_ENV['SIGNALWIRE_TOKEN'];
     $client = new Client($project, $token);

     // Hangup current call in progress
     $CallSid=$_REQUEST['CallSid'];
     $client->calls($CallSid)
            ->update(array("status" => "completed"));

     // Send text message
     $client->messages->create(
       $to,
       array(
         'from' => $from,
         'body' => $body
         )
       );

  } else {
     echo "This script can only be called from a SignalWire Webhook.\n";
  }

  if ($to != "") {

    // Put together the Base URL
    // base url directory
    $request_uri = $_SERVER['REQUEST_URI'];
    $self=basename(__FILE__);
    $pos = strpos($request_uri, $self);
    $base_url = substr($request_uri, 0, $pos-1);
    // protocol
    $protocol = empty($_SERVER['HTTPS']) ? 'http' : 'https';
    // domain name
    $domain = $_SERVER['SERVER_NAME'];
    // server port
    $port = $_SERVER['SERVER_PORT'];
    $disp_port = ($protocol == 'http' && $port == 80 || $protocol == 'https' && $port == 443) ? '' : ":$port";
    // put em all together to get the complete base URL
    $url = "${protocol}://${domain}${disp_port}${base_url}";

    // Place the call with two arguments - phone number and base URL
    // This is done with through the shell to happen asynchronously
    $php_cli=$_ENV['PHP_CLI'];
    $cmd = $php_cli . " ./do_call.php " . $to . " " . $url;
    exec($cmd . ">/dev/null &");
  }
