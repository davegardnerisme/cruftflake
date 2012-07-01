<?php
/**
 * ZeroMQ interface for cruftflake
 * 
 * @author @davegardnerisme
 */

namespace Davegardnerisme\CruftFlake;

class ZeroMq
{
    /**
     * Cruft flake generator
     * 
     * @var Generator
     */
    private $generator;
    
    /**
     * Port
     * 
     * @var integer
     */
    private $port;
    
    /**
     * Constructor
     * 
     * @param @inject Generator $generator
     * @param string $port Which TCP port to list on, default 80085
     */
    public function __construct(Generator $generator, $port = 80085)
    {
        $this->generator = $generator;
        $this->port = $port;
    }
    
    /**
     * Run ZMQ interface for generator
     * 
     * Req-rep pattern; msgs are commands:
     * 
     * GEN    = Generate ID
     * STATUS = Get status string
     */
    public function run()
    {
        $context = new \ZMQContext();
        $receiver = new \ZMQSocket($context, \ZMQ::SOCKET_REP);
        $bindTo = 'tcp://*:' . $this->port;
        $receiver->bind($bindTo);
        while (TRUE) {
            $msg = $receiver->recv();
            switch ($msg) {
                case 'GEN':
                    $response = $this->generator->generate();
                    break;
                case 'STATUS':
                    $response = '@TODO';
                    break;
                default:
                    $response = 'UNKNOWN COMMAND';
                    break;
            }
            $receiver->send($response);
        }
    }
}