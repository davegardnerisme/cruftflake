<?php
/**
 * ZooKeeper-based configuration
 * 
 * @author @davegardnerisme
 */

namespace Davegardnerisme\CruftFlake;

class ZkConfig implements ConfigInterface
{
    /**
     * Parent path
     * 
     * @var string
     */
    private $parentPath;
    
    /**
     * ZK
     * 
     * @var \Zookeeper
     */
    private $zk;
    
    /**
     * Constructor
     * 
     * @param string $hostnames A comma separated list of hostnames (including
     *      port)
     * @param string $zkPath The ZK path we look to find other machines under
     */
    public function __construct($hostnames, $zkPath = '/cruftflake')
    {
        if (!class_exists('\Zookeeper')) {
            throw new \BadMethodCallException(
                    'ZooKeeper extension not installed. Try hitting PECL.'
                    );
        }
        $this->zk = new \Zookeeper($hostnames);
        $this->parentPath = $zkPath;
    }
    
    /**
     * Get machine identifier
     * 
     * @return integer Should be a 10-bit int (decimal 0 to 1023)
     */
    public function getMachine()
    {
        $this->createParentIfNeeded($this->parentPath);
        
        // get current machine list
        $children = $this->zk->getChildren($this->parentPath);
        
        // find an unused machine number
        for ($i=0; $i<1024; $i++) {
            $machineNode = $this->machineToNode($i);
            if (in_array($machineNode, $children)) {
                continue;   // already used
            }
            
            // attempt to claim
            $created = $this->zk->create(
                    "{$this->parentPath}/{$machineNode}",
                    '<add mac address here and perhaps IP etc>',
                    array(array(                    // acl
                        'perms'     => \Zookeeper::PERM_ALL,
                        'scheme'    => 'world',
                        'id'        => 'anyone'
                        )),
                    \Zookeeper::EPHEMERAL
                    );
            if ($created !== NULL) {
                break;
            }
        }
        
        if ($created === NULL) {
            throw new \RuntimeException(
                    "Cannot locate and claim a free machine ID via ZK"
                    );
        }
        
        return (int)$i;
    }
    
    /**
     * Create parent node, if needed
     * 
     * @param string $nodePath
     */
    private function createParentIfNeeded($nodePath)
    {
        if (!$this->zk->exists($nodePath)) {
            $this->zk->create(
                    $nodePath,
                    'Cruftflake machines',
                    array(array(                    // acl
                        'perms'     => \Zookeeper::PERM_ALL,
                        'scheme'    => 'world',
                        'id'        => 'anyone'
                        ))
                    );
        }
    }

    /**
     * Machine ID to ZK node
     * 
     * @param integer $id
     * 
     * @return string The node path to use in ZK
     */
    private function machineToNode($id)
    {
        return str_pad($id, 4, '0', STR_PAD_LEFT);
    }
}