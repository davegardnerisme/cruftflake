#!/usr/bin/php
<?php
/**
 * Generate N ids, default N is 1
 * 
 * Usage:
 * 
 *  -n      How many to generate
 *  -p      ZeroMQ port to connect to, default 5599
 */

$opts = getopt('n:p:');
$n = isset($opts['n']) ? (int)$opts['n'] : 1;
$n = $n < 0 ? 1 : $n;
$port = isset($opts['p']) ? $opts['p'] : 5599;

$context = new \ZMQContext();
$socket = new \ZMQSocket($context, \ZMQ::SOCKET_REQ);
$socket->connect("tcp://localhost:{$port}");
$socket->setSockOpt(\ZMQ::SOCKOPT_LINGER, 0);

for ($i=0; $i<$n; $i++) {
    $socket->send('GEN');
    $id = $socket->recv();
    echo $id . "\n";
}