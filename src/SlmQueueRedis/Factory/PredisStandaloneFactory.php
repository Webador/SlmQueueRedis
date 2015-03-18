<?php

namespace SlmQueueRedis\Factory;

use Redis;
use SlmQueueRedis\Options\ZendBridgeOptions;
use Zend\Cache\Storage\Adapter\RedisResourceManager;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class PredisStandaloneFactory implements FactoryInterface
{
    /**
     * @var array
     */
    protected $options;

    public function __construct($options) {
        $this->options = $options;
    }

    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     * @return mixed
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
  
    }

}
