<?php
/**
 * Like the Twitter one
 * 
 * 64 bits:
 * 
 * time - 41 bits (millisecond precision w/ a custom epoch gives us 69 years)
 * configured machine id - 10 bits - gives us up to 1024 machines
 * sequence number - 12 bits - rolls over every 4096 per machine (with protection to avoid rollover in the same ms)
 * 
 * 32 bits + 9 = 41 bits of time
 * 2199023255552 < milliseconds = 2199023255 seconds
 *                                2147483647 < max 31 bit int (signed)
 * @author @davegardnerisme
 */

namespace Davegardnerisme\CruftFlake;

class Generator
{
    /**
     * Hexdec lookup
     * 
     * @staticvar array
     */
    private static $hexdec = array(
        "0" => 0,
        "1" => 1,
        "2" => 2,
        "3" => 3,
        "4" => 4,
        "5" => 5,
        "6" => 6,
        "7" => 7,
        "8" => 8,
        "9" => 9,
        "a" => 10,
        "b" => 11,
        "c" => 12,
        "d" => 13,
        "e" => 14,
        "f" => 15
        );
    
    /**
     * Configured machine ID - 10 bits (dec 0 -> 1023)
     *
     * @var integer
     */
    private $machine;
    
    /**
     * Epoch - in UTC millisecond timestamp
     *
     * @var integer
     */
    private $epoch = 1325376000000;
    
    /**
     * Sequence number - 12 bits, we auto-increment for same-millisecond collisions
     *
     * @var integer
     */
    private $sequence = 1;
    
    /**
     * The most recent millisecond time window encountered
     *
     * @var integer
     */
    private $lastTime = NULL;
    
    /**
     * Constructor
     * 
     * @param @inject ConfigInterface $config
     */
    public function __construct(ConfigInterface $config)
    {
        $this->machine = $config->getMachine();
        if (!is_int($this->machine) || $this->machine < 0 || $this->machine > 1023) {
            throw new \InvalidArgumentException(
                    'Machine identifier invalid -- must be 10 bit integer (0 to 1023)'
                    );
        }
    }
    
    /**
     *
     * @return type 
     */
    public function generate()
    {
        $t = $this->mintTime();
        if ($t !== $this->lastTime) {
            if ($t < $this->lastTime) {
                throw new \UnexpectedValueException(
                        'Time moved backwards. We cannot generate IDs for '
                        . ($this->lastTime - $t) . ' milliseconds'
                        );
            }
            $this->sequence = 0;
            $this->lastTime = $t;
        }
        $this->sequence++;
        
        if (PHP_INT_SIZE == 4) {
            return $this->mintId32($t, $this->machine, $this->sequence);
        } else {
            return $this->mintId64($t, $this->machine, $this->sequence);
        }
    }
    
    private function mintTime()
    {
        // Q: will this ever be > 32 bit int?
        return (int)(microtime(TRUE) * 1000 - $this->epoch);
    }
    
    private function mintId32($timestamp, $machine, $sequence)
    {
        $hi = (int)($timestamp / pow(2,9));
        $lo = (int)(($timestamp * pow(2, 23)) & 0xFFFFFFFF);
        
        // stick in the machine + sequence to the low bit
        $lo = $lo | ($machine << 10) | $sequence;

        // reconstruct into a string of numbers
        $hex = pack('N2', $hi, $lo);
        $unpacked = unpack('H*', $hex);
        $value = $this->hexdec($unpacked[1]);
        return (string)$value;
    }
    
    private function mintId64($timestamp, $machine, $sequence)
    {
        $value = ($timestamp << 23) | ($machine << 10) | $sequence;
        return (string)$value;
    }
    
    private function hexdec($hex)
    {
        $dec = 0;
        for ($i = strlen($hex) - 1, $e = 1; $i >= 0; $i--, $e = bcmul($e, 16)) {
            $factor = self::$hexdec[$hex[$i]];
            $dec = bcadd($dec, bcmul($factor, $e));
        }
        return $dec;
    }
}
