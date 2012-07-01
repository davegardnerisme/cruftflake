#!/usr/bin/php
<?php
/**
 * Generate N ids, default N is 1
 */

$opts = getopt('n:');
$n = isset($opts['n']) ? (int)$opts['n'] : 1;
$n = $n < 0 ? 1 : $n;

$context = new \ZMQContext();
$socket = new \ZMQSocket($context, \ZMQ::SOCKET_REQ);
//$socket->connect('tcp://localhost:80085');
$socket->connect('tcp://localhost:5555');
$socket->setSockOpt(\ZMQ::SOCKOPT_LINGER, 0);

for ($i=0; $i<$n; $i++) {
    $socket->send('GEN');
    $id = $socket->recv();
    echo $id . "\n";
}