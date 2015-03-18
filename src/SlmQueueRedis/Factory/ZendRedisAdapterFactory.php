<?php

namespace SlmQueueRedis\Factory;

use SlmQueueRedis\Adapter\PhpRedisAdapter;
use SlmQueueRedis\Options\ZendRedisAdapterOptions;
use Zend\Cache\Storage\Adapter\RedisResourceManager;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * RedisQueueFactory
 */
class ZendRedisAdapterFactory implements FactoryInterface
{
    /**
     * @var array
     */
    protected $options;

    /**
     * @param array $options
     */
    public function __construct(array $options = array()) {
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
        /** @var ServiceLocatorInterface $parentLocator */
        $parentLocator = $serviceLocator->getServiceLocator();

        $options = new ZendRedisAdapterOptions($this->options);

        /** @var RedisResourceManager $redisResourceManager */
        $redisResourceManager = $parentLocator->get($options->getServiceName());
        $redis = $redisResourceManager->getResource($options->getResourceId());

        return new PhpRedisAdapter($redis, $options->getNamespace());
    }

}
