<?php
  // Waits for 2 minutes and then places call
  // To be called from command line to not hold up Web server
  // Takes two arguments:
  // $argv[1] expects $to phone number in e.164 format as first argument
  // $argv[2] expects $basedir to be HTML path (no trailing slash)

  // Setup

  require_once __DIR__ .'/vendor/autoload.php';
  use SignalWire\Rest\Client;

  // Load environment variables from .env
  $dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
  $dotenv->load();

  $project=$_ENV["SIGNALWIRE_PROJECT"];
  $token=$_ENV["SIGNALWIRE_TOKEN"];
  $from=$_ENV["SIGNALWIRE_NUMBER"];

  // Get the phone number to call to from the commmand line argument
  $to = sanitizePhone($argv[1]);
  $basedir = $argv[2];

  if ($to != "" && $basedir != "") {
    // sleep for two minutes
    sleep(120);

    // Place the call
    $client = new Client($project, $token);
    $call = $client->calls
                   ->create($to,
                            $from,
                            ["url" => $basedir . "/connect_agent.php"]
                      );
  } else {
    echo "Error - This script must be called from text_request_call.php\n";
  }

  // Sanitize phone number and convert it to e.164 format
  function sanitizePhone ($phoneNumber) {
     $phoneNumber = preg_replace('/[^0-9]/','',$phoneNumber);
     $phoneNumberLen = strlen($phoneNumber);
       if ($phoneNumberLen > 15) {
           $phoneNumber = "";
       } elseif ($phoneNumberLen > 10) {
           $phoneNumber = "+" . $phoneNumber;
       } elseif ($phoneNumberLen == 10) {
           $phoneNumber = "+1" . $phoneNumber;
       } else {
           $phoneNumber = "";
       }
       return $phoneNumber;
  }

?>
