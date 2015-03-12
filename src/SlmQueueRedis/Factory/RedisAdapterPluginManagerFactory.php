<?php

namespace SlmQueueRedis\Factory;

use SlmQueueRedis\Adapter\RedisAdapterPluginManager;
use Zend\ServiceManager\Config;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * RedisAdapterPluginManagerFactory
 */
class RedisAdapterPluginManagerFactory implements FactoryInterface
{
    /**
     * {@inheritDoc}
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $config = $serviceLocator->get('Config');
        $config = isset($config['slm_queue']['redis_adapter']) ? $config['slm_queue']['redis_adapter'] : array();

        $redisAdapterPluginManager = new RedisAdapterPluginManager(new Config($config));
        $redisAdapterPluginManager->setServiceLocator($serviceLocator);

        return $redisAdapterPluginManager;
    }
}
