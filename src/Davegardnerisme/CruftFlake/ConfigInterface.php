<?php
/**
 * Cruft flake config interface
 * 
 * Implement this if you want some other way to configure machines.
 * 
 * @author @davegardnerisme
 */

namespace Davegardnerisme\CruftFlake;

interface ConfigInterface
{
    /**
     * Get machine identifier
     * 
     * @return integer Should be a 10-bit int (decimal 0 to 1023)
     */
    public function getMachine();
}