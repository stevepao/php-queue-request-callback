# PHP Version of Queue Request Callback
This is a PHP port of the Queue Request Callback app featured on the SignalWire blog at
[https://signalwire.com/blogs/developers/sms-decrease-high-call-volume].
## About Queue Request Callback
This project just shows how call centers can offer callers a callback option either by pressing a digit while on call or simply sending a text message (SMS) to receive a callback when the next agent is available.
## Getting Started
This script is designed to be used on any standard shared hosting infrastructure using PHP, such as 1&1 IONOS.----
## Running Queue Request Callback - How It Works
## Methods and Endpoints

```
Endpoint: /enter_queue.php
Methods: GET OR POST
Endpoint to place callers into queue, handle the people in queue, and take callback requests
```

```
Endpoint: /text_request_call.php
Methods: GET OR POST
Endpoint to accept incoming SMS messages, to send SMS confirmations to both voice and SMS users, and to call out to do_call.php asynchronously.
```

```
Endpoint: /do_call.php
Methods: command line invocation
This script gets called by text_request_call.php through a shell command so that it does not hold up the Web server.  It waits 2 minutes prior to placing an outbound call.
```

```
Endpoint: /connect_agent.php
Methods: GET OR POST
Endpoint to connect agent to caller.
```

## Setup Your Environment File

1. Copy from example.env and fill in your values
2. Save new file called .env

Your file should look something like this
```
## This is the full name of your SignalWire Space. e.g.: example.signalwire.com
SIGNALWIRE_SPACE_URL=
# Your Project ID - you can find it on the `API` page in your SignalWire Space.
SIGNALWIRE_PROJECT=
#Your API token - you can generate one on the `API` page in your SignalWire Space.
SIGNALWIRE_TOKEN=
# The phone number you'll be using for this application in e.164 format. Must include the `+1` , e.g.: +15551234567
SIGNALWIRE_NUMBER=
# The PHP command line interface to used (e.g., /usr/bin/php or /usr/bin/php-7.3-cli)
PHP_CLI=
```

## Dependencies
The composer.json file is included with this project.  It requires both signalwire/signalwire and vlucas/dotenv.  Copy all the files to your Web host and do a composer update.

Also, note that because PHP on shared hosting providers does not support threading, the project calls out to the shell to execute a PHP script asynchronously.  You should make sure to configure the PHP command line interface appropriately.  For example, on 1&1 IONOS, /usr/bin/php does not utilize PHP 7 by default.

----

# More Documentation
You can find more documentation on LaML, Relay, and all Signalwire APIs at:
- [SignalWire PHP SDK](https://github.com/signalwire/signalwire-php)
- [SignalWire API Docs](https://docs.signalwire.com)
- [SignalWire Github](https://gituhb.com/signalwire)
