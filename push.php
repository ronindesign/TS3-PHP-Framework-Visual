<?php
/**
 * Created by Tim Turner | Ronin Design
 * Created on: 4:13 AM 2/18/2015
 * Contact: info@ronin-design.com
 */

    $entryData = array(
        'property' => "connection_bandwidth_received_last_second_total"
    , 'value'    => "no_data"
    , 'when'     => time()
    );

require '../libraries/TeamSpeak3/TeamSpeak3.php';

/* load config */
require_once("config.php");

// connect to local server, authenticate and spawn an object for the server instance
$ts3_s = TeamSpeak3::factory("serverquery://" . $cfg["user"] . ":" . $cfg["pass"] . "@" . $cfg["host"] . ":" . $cfg["query"] . "/");

$context = new ZMQContext();
$socket = $context->getSocket(ZMQ::SOCKET_PUSH, 'pusher');
$socket->connect("tcp://" . $cfg["host"] . ":" . $cfg["pub"]);

while(1) {

    // getProperty:connection_bandwidth_received_last_second_total
    $ts3_v = $ts3_s->serverGetById(1);
    try {
        $entryData['value'] = $ts3_v->getProperty($entryData['property']);
    }
    catch (Exception $e)
    {
        var_dump($e);
        die("EXIT");
    }
    $socket->send(json_encode($entryData));
    sleep(1);
}