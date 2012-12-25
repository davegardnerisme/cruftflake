#!/usr/bin/php
<?php
/**
 * Get generator status
 * 
 * Usage:
 * 
 *  -u      ZeroMQ url to connect to, default 'tcp://localhost:5599'
 *  -t      Send / Receive timeout, default 100ms
 */

$opts = getopt('p:');
$url = isset($opts['u']) ? $opts['u'] : 'tcp://localhost:5599';
$timeout = isset($opts['t']) ? (int)$opts['t'] : 100;

$context = new \ZMQContext();
$socket = new \ZMQSocket($context, \ZMQ::SOCKET_REQ);
$socket->connect($url);
$socket->setSockOpt(\ZMQ::SOCKOPT_LINGER, 0);
self::$socket->setSockOpt(ZMQ::SOCKOPT_SNDTIMEO, $timeout);
self::$socket->setSockOpt(ZMQ::SOCKOPT_RCVTIMEO, $timeout);

$socket->send('STATUS');
$status = $socket->recv();
$status = json_decode($status, TRUE);

echo "STATUS\n\n";
foreach ($status as $k => $v) {
    echo "$k\t$v\n";
}
echo "\n";
