#!/usr/bin/php
<?php
/**
 * Get generator status
 * 
 * Usage:
 * 
 *  -p      ZeroMQ port to connect to, default 5599
 */

$opts = getopt('p:');
$port = isset($opts['p']) ? $opts['p'] : 5599;

$context = new \ZMQContext();
$socket = new \ZMQSocket($context, \ZMQ::SOCKET_REQ);
$socket->connect("tcp://localhost:{$port}");
$socket->setSockOpt(\ZMQ::SOCKOPT_LINGER, 0);

$socket->send('STATUS');
$status = $socket->recv();
$status = json_decode($status, TRUE);

echo "STATUS\n\n";
foreach ($status as $k => $v) {
    echo "$k\t$v\n";
}
echo "\n";
