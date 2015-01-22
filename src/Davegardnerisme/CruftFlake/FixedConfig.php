<?php
/**
 * Fixed configuration
 *
 * This is designed to be used where each machine **knows** what its machine
 * ID is - eg: via some kind of automatically deployed configuration
 * (puppet etc.)
 *
 * @author @davegardnerisme
 */

namespace Davegardnerisme\CruftFlake;

class FixedConfig implements ConfigInterface
{
    /**
     * Machine ID
     *
     * @var integer
     */
    private $machineId;

    /**
     * Constructor
     *
     * @param integer $machineId
     */
    public function __construct($machineId)
    {
        $this->machineId = (int)$machineId;
    }

    /**
     * Get machine identifier
     *
     * @return integer Should be a 10-bit int (decimal 0 to 1023)
     */
    public function getMachine()
    {
        // echo "Claimed machine ID {$this->machineId} via fixed configuration.\n";

        return $this->machineId;
    }
}