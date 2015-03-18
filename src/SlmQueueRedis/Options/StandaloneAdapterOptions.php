<?php
namespace SlmQueueRedis\Options;

use Zend\Stdlib\AbstractOptions;

/**
 * ZendBridgeOptions
 */
class StandaloneAdapterOptions extends AbstractOptions
{
    /**
     * @var string
     */
    protected $namespace;

    /**
     * @return string
     */
    public function getNamespace()
    {
        return $this->namespace;
    }

    /**
     * @param string $namespace
     */
    public function setNamespace($namespace)
    {
        $this->namespace = $namespace;
    }
}
