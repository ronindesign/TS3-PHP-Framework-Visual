<?php
/**
 * Created by Tim Turner | Ronin Design
 * Created on: 3:54 AM 2/18/2015
 * Contact: info@ronin-design.com
 */

require __DIR__ . '/vendor/autoload.php';

$loop   = React\EventLoop\Factory::create();
$pusher = new App\Pusher;

/* load config */
require_once("config.php");

// Listen for the web server to make a ZMQ push after an ajax request
$context = new React\ZMQ\Context($loop);
$pull = $context->getSocket(ZMQ::SOCKET_PULL);
$pull->bind("tcp://" . $cfg["host"] . ":" . $cfg["pub"]);
$pull->on('message', array($pusher, 'on_stat'));

// Set up our WebSocket server for clients wanting real-time updates
$webSock = new React\Socket\Server($loop);
$webSock->listen($cfg["sub"], $cfg["host"]);
$webServer = new Ratchet\Server\IoServer(
    new Ratchet\Http\HttpServer(
        new Ratchet\WebSocket\WsServer(
            new Ratchet\Wamp\WampServer(
                $pusher
            )
        )
    ),
    $webSock
);

$loop->run();