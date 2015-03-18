<?php
namespace SlmQueueRedis\Options;

use Zend\Stdlib\AbstractOptions;

/**
 * ZendBridgeOptions
 */
class ZendRedisAdapterOptions extends AbstractOptions
{
    /**
     * @var string
     */
    protected $namespace;

    /**
     * @var string
     */
    protected $serviceName;

    /**
     * @var string
     */
    protected $resourceId;

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

    /**
     * @return string
     */
    public function getServiceName()
    {
        return $this->serviceName;
    }

    /**
     * @param string $serviceName
     */
    public function setServiceName($serviceName)
    {
        $this->serviceName = $serviceName;
    }

    /**
     * @return string
     */
    public function getResourceId()
    {
        return $this->resourceId;
    }

    /**
     * @param string $resourceId
     */
    public function setResourceId($resourceId)
    {
        $this->resourceId = $resourceId;
    }



}
