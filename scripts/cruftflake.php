#!/usr/bin/php
<?php
/**
 * Cruft flake - simple ZMQ req/rep loop
 * 
 * Usage:
 * 
 *  -p      ZeroMQ port to bind to, default 5599
 *  -z      ZooKeeper hostname:port to connect to, eg: localhost:2181
 */

$opts = getopt('p:');
$port = isset($opts['p']) ? $opts['p'] : 5599;
$zks = isset($opts['z']) ? $opts['z'] : 'localhost:2181';

// include the pure-php class loader, if not already exists (eg: via binary)
if (!class_exists('\SplClassLoader')) {
    include dirname(__FILE__)
            . '/../dependencies/spl-class-loader/SplClassLoader.php';
}
// autoload
$classLoader = new \SplClassLoader(
        'Davegardnerisme\CruftFlake',
        dirname(__FILE__) . '/../src/'
        );
$classLoader->register();

$timer = new \Davegardnerisme\CruftFlake\Timer;
$config = new \Davegardnerisme\CruftFlake\ZkConfig($zks);
$generator = new \Davegardnerisme\CruftFlake\Generator($config, $timer);
$zmqRunner = new \Davegardnerisme\CruftFlake\ZeroMq($generator, $port);

$zmqRunner->run();
