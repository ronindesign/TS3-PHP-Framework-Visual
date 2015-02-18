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
