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
     * Url
     * 
     * @var string
     */
    private $url;
    
    /**
     * Constructor
     * 
     * @param @inject Generator $generator
     * @param string $url to listen on, default 'tcp://*:5599'
     */
    public function __construct(Generator $generator, $url = 'tcp://*:5599')
    {
        $this->generator = $generator;
        $this->url = $url;
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
        echo "Binding to {$this->url}\n";
        $receiver->bind($this->url);
        while (TRUE) {
            $msg = $receiver->recv();
            switch ($msg) {
                case 'GEN':
                    try {
                        $response = $this->generator->generate();
                    } catch (\Exception $e) {
                        $response = "ERROR";
                    }
                    break;
                case 'STATUS':
                    $response = json_encode($this->generator->status());
                    break;
                default:
                    $response = 'UNKNOWN COMMAND';
                    break;
            }
            $receiver->send($response);
        }
    }
}