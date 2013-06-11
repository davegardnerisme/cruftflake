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

$opts = getopt('u:t:');
$url = isset($opts['u']) ? $opts['u'] : 'tcp://localhost:5599';
$timeout = isset($opts['t']) ? (int)$opts['t'] : 1000;

$context = new \ZMQContext();
$socket = new \ZMQSocket($context, \ZMQ::SOCKET_REQ);
$socket->connect($url);
$socket->setSockOpt(\ZMQ::SOCKOPT_LINGER, 0);
$socket->setSockOpt(\ZMQ::SOCKOPT_SNDTIMEO, $timeout);
$socket->setSockOpt(\ZMQ::SOCKOPT_RCVTIMEO, $timeout);

$socket->send('STATUS');
$status = $socket->recv();
$status = json_decode($status, TRUE);

echo "STATUS\n\n";
foreach ($status as $k => $v) {
    echo "$k\t$v\n";
}
echo "\n";
