<?php

namespace SlmQueueRedis\Factory;

use Redis;
use SlmQueueRedis\Adapter\ZendAdapter;
use SlmQueueRedis\Options\RedisQueueOptions;
use SlmQueueRedis\Options\ZendAdapterOptions;
use Zend\Cache\Storage\Adapter\RedisResourceManager;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * RedisQueueFactory
 */
class ZendAdapterFactory implements FactoryInterface
{
    /**
     * @var
     */
    protected $options;

    public function __construct($options = array()) {
        $this->options = new ZendAdapterOptions($options);
    }


    /**
     * {@inheritDoc}
     */
    public function createService(ServiceLocatorInterface $serviceLocator, $name = '', $requestedName = '')
    {
        /** @var ServiceLocatorInterface $parentLocator */
        $parentLocator = $serviceLocator->getServiceLocator();

        /** @var RedisResourceManager $redisResourceManager */
        $redisResourceManager = $parentLocator->get($this->options->getServiceName());

        /** @var Redis $redis */
        $redis = $redisResourceManager->getResource($this->options->getResourceId());

        $adapter = new ZendAdapter($redis);

        return $adapter;
    }
}
