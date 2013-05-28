#!/usr/bin/php
<?php
/**
 * Generate N ids, default N is 1
 * 
 * Usage:
 * 
 *  -n      How many to generate
 *  -u      ZeroMQ url to connect to, default 'tcp://localhost:5599'
 *  -t      Send / Receive timeout, default 100ms
 */

$opts = getopt('n:u:');
$n = isset($opts['n']) ? (int)$opts['n'] : 1;
$n = $n < 0 ? 1 : $n;
$url = isset($opts['u']) ? $opts['u'] : 'tcp://localhost:5599';
$timeout = isset($opts['t']) ? (int)$opts['t'] : 1000;

$context = new \ZMQContext();
$socket = new \ZMQSocket($context, \ZMQ::SOCKET_REQ);
$socket->connect($url);
$socket->setSockOpt(\ZMQ::SOCKOPT_LINGER, 0);
$socket->setSockOpt(\ZMQ::SOCKOPT_SNDTIMEO, $timeout);
$socket->setSockOpt(\ZMQ::SOCKOPT_RCVTIMEO, $timeout);

for ($i=0; $i<$n; $i++) {
    $socket->send('GEN');
    $id = $socket->recv();
    echo $id . "\n";
}