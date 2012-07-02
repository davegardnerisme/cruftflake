<?php
/**
 * Cruft flake timer interface
 * 
 * Implement this if you want some other way to provide time.
 * 
 * @author @davegardnerisme
 */

namespace Davegardnerisme\CruftFlake;

interface TimerInterface
{
    /**
     * Get unix timestamp to millisecond accuracy
     * 
     * (Number of whole milliseconds that have passed since 1970-01-01
     * 
     * @return integer
     */
    public function getUnixTimestamp();
}