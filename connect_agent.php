<?php

require_once './vendor/autoload.php';
use SignalWire\LaML;

// Load environment variables from .env
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$response = new LaML();

$response->say("This is your callback from your earlier request.");
$response->play("https://sinergyds.blob.core.windows.net/signalwire/snoopclose.wav");
$response->say("For more excellent demos and starting projects, visit the SignalWire blog.");
$response->hangup();
echo $response;

?>
