# ts3-php-framework-visual
Visualizations for possible statistical data produced from ts3-php-framework

## Overview

Complete code for real-time visualization of TeamSpeak3 server query property retrieved using the ts3-php-framework.
Features:
- Real-time: Live, per second, stat updates from TS3 Server through ts3-php-framework and TS3 server query
- Full async: Any number of persistent WebSocket connections (browser, individual applet, other client)
- Pub-Sub: Clients can "subscribe" to the stats they want (e.g. seperate stats/sockets per client)
- Customizable: Pause graph, change update interval and stat history size
- Extendable: Only minimal complete code provided to show one stat working, many more possible.

## What's inside

- Core:
  - config.php - IP/Port bind selections, user credentials, etc
  - example.css - Basic CSS for styling graph
  - index.php - Contains graph html / javascript, including WebSocket creation and "event subscription"
  - statsrv.php - Main server application handling new "publish" events and transmitting data to all "subscribers"
  - src/App/Pusher.php - Pusher class for handling pub-sub events and data.
  - push.php - Stats server, connects to TS3 server query, request stats, sends "publish events" to WebSocket
  - composer.json - Configuration file for autoloading and composer install of react, ratchet, zeromq, etc
 
- TeamSpeak3 PHP Framework:
The TS3 PHP Framework is a modern use-at-will framework that provides individual components to communicate with the TeamSpeak 3 Server. Initially released in January 2010, the TS3 PHP Framework is a powerful, open source, object-oriented framework implemented in PHP 5 and licensed under the GNU General Public License. It's based on simplicity and a rigorously tested agile codebase. Extend the functionality of your servers with scripts or create powerful web applications to manage all features of your TeamSpeak 3 Server instances.

- Other:
  - Composer - Package manager and autoloader for required supporting libraries
  - cboden/rachet - WebSocket libraby built on React PHP framework
  - react/zmq - ZeroMQ react module, uses PHP binding through PECL extension and requires ZeroMQ install
  - ZeroMQ (ZMQ) - High-performance async messaging library for scalable distributed / concurrent aps.
  - JQuery - Delicious.
  - Flot - JavaScript library for working with graphs
  - Autobahn - JavaScript library implementing WAMP on top of WebSocket providing async remote calls / pub-sub

## How-to use

The idea is a complete packages to get the application up and running. From here you can add / extend the application to provide more functionality. With this in mind, everything except the composer packages are installed, which are a single step away since composer.json is ready to go. To get the application up and running:

Pre-Requirements:
- ZeroMQ needs to be installed. This includes source install and PHP binding through PECL extension.
- Composer needs to be installed.

Step 1: Upload the package to a web server.
Step 2: Change to directory, then:
```
php ~/composer.phar install
```
You should now have a 'vendor' folder with 6 directories and 'autoload.php'

Step 3: Create basic 'config.php' file in main directory:

```
<?php
/**
 * Basic config file for IP, port binding, user credentials
 */

/**
 * The IPv4 address or FQDN of the TeamSpeak 3 server. Use the local loopback
 * address (127.0.0.1 or localhost) if the server is running on the same host
 * and is not bound to a specific address.
 *
 * Example: 123.45.67.89 or teamspeak.example.com
 */
$cfg["host"] = "123.123.123.123";

/**
 * The ServerQuery port used by the TeamSpeak 3 server. Do NOT change this
 * setting unless you know what you're doing.
 *
 * Default: 10011
 */
$cfg["query"] = 10011;

/**
 * The UDP voice port used by the TeamSpeak 3 server. This is the same port
 * you entered in your TeamSpeak 3 client application.
 *
 * Default: 9987
 */
$cfg["voice"] = 9987;

/**
 * The login credentials used to authenticate with the TeamSpeak 3 server query.
 */
// TS3 Server Query Credentials 
$cfg["user"] = "serveradmin";
$cfg["pass"] = "PASSWORD";


/**
 * WebSocket/WAMP Config
 */
// WebSocket Publish Port - TS3 stats sent here from server/monitor
$cfg["pub"] = 5522;
// WebSocket Listen Port - TS3 stats can be subscribed from here
$cfg["sub"] = 8080;
```

Step 4: You're ready to start-up the server and stat pusher (you may need two terminals):

First terminal
```
php statsrv.php
```
Second terminal
```
php push.php
```

Now you're ready to go. Simply open up the main directory in a web browser (i.e. index.php) and you will see the Flot graph being updated according to the default parameters.
