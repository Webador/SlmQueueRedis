<?php

namespace SlmQueueRedis\Factory;

use SlmQueueRedis\Adapter\RedisAdapterPluginManager;
use SlmQueueRedis\Options\RedisOptions;
use SlmQueueRedis\Adapter\AdapterInterface;
use SlmQueueRedis\Queue\RedisQueue;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * RedisQueueFactory
 */
class RedisQueueFactory implements FactoryInterface
{
    /**
     * {@inheritDoc}
     */
    public function createService(ServiceLocatorInterface $serviceLocator, $name = '', $requestedName = '')
    {
        /** @var ServiceLocatorInterface $parentLocator */
        $parentLocator = $serviceLocator->getServiceLocator();

        $config        = $parentLocator->get('Config');
        $queuesOptions = $config['slm_queue']['queues'];
        $options       = isset($queuesOptions[$requestedName]) ? $queuesOptions[$requestedName] : array();
        $queueOptions  = new RedisOptions($options);

        /** @var RedisAdapterPluginManager $redisAdapterPluginManager */
        $redisAdapterPluginManager = $parentLocator->get('SlmQueueRedis\Adapter\RedisAdapterPluginManager');

        /** @var $adapter AdapterInterface */
        $adapter          = $redisAdapterPluginManager->get($queueOptions->getAdapter(), $queueOptions->getAdapterOptions());
        $jobPluginManager = $parentLocator->get('SlmQueue\Job\JobPluginManager');

        $queue = new RedisQueue($adapter, $queueOptions, $requestedName, $jobPluginManager);
        return $queue;
    }
}
