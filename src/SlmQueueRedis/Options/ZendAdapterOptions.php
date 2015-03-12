<?php

namespace SlmQueueRedis\Options;

use Zend\Stdlib\AbstractOptions;

/**
 * RedisQueueOptions
 */
class ZendAdapterOptions extends AbstractOptions
{
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
