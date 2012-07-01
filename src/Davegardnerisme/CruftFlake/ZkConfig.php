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
    }
    
    /**
     * Get machine identifier
     * 
     * @return integer Should be a 10-bit int (decimal 0 to 1023)
     */
    public function getMachine()
    {
        return 1;
        
        $this->createParentIfNeeded($this->parentPath);
        
        // get current machine list
        $children = $this->zk->getChildren($this->parentPath);

        // find 
        

    }
    
    /**
     * Create parent node, if needed
     * 
     * @param string $nodePath
     */
    private function createParentIfNeeded()
    {
        if (!$this->zk->exists($nodePath)) {
            $zk->create(
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

}