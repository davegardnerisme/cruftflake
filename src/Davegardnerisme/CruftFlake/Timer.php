<?php
/**
 * Cruft flake timer
 * 
 * @author @davegardnerisme
 */

namespace Davegardnerisme\CruftFlake;

class Timer implements TimerInterface
{
    /**
     * Get unix timestamp to millisecond accuracy
     * 
     * (Number of whole milliseconds that have passed since 1970-01-01
     * 
     * @return integer
     */
    public function getUnixTimestamp()
    {
        return floor(microtime(TRUE) * 1000);
    }
}