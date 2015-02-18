<?php
/**
 * Created by Tim Turner | Ronin Design
 * Created on: 3:51 AM 2/18/2015
 * Contact: info@ronin-design.com
 */

namespace App;
use Ratchet\ConnectionInterface;
use Ratchet\Wamp\WampServerInterface;

class Pusher implements WampServerInterface {
    /**
     * A lookup of all the topics clients have subscribed to
     */
    protected $subscribed_properties = array();

    public function onSubscribe(ConnectionInterface $conn, $property) {
        $this->subscribed_properties[$property->getId()] = $property;
    }

    /**
     * @param string JSON object of our data (Note: RTC spec requires only string sent in WAMP pub-sub comm)
     */
    public function on_stat($entry) {
        $entryData = json_decode($entry, true);

        // If the lookup topic object isn't set there is no one to publish to
        if (!array_key_exists($entryData['property'], $this->subscribed_properties)) {
            return;
        }

        $property = $this->subscribed_properties[$entryData['property']];

        // re-send the data to all the clients subscribed to that category
        $property->broadcast($entryData);
    }
    public function onUnSubscribe(ConnectionInterface $conn, $property) {
    }
    public function onOpen(ConnectionInterface $conn) {
    }
    public function onClose(ConnectionInterface $conn) {
    }
    public function onCall(ConnectionInterface $conn, $id, $property, array $params) {
        // In this application if clients send data it's because the user hacked around in console
        $conn->callError($id, $property, 'You are not allowed to make calls')->close();
    }
    public function onPublish(ConnectionInterface $conn, $property, $event, array $exclude, array $eligible) {
        // In this application if clients send data it's because the user hacked around in console
        $conn->close();
    }
    public function onError(ConnectionInterface $conn, \Exception $e)
    {
    }
}