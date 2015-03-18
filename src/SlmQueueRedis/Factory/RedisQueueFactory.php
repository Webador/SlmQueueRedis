<?php

namespace SlmQueueRedis\Factory;

use SlmQueueRedis\Options\RedisOptions;
use SlmQueueRedis\Queue\RedisQueue;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use Redis;

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

        $jobPluginManager = $parentLocator->get('SlmQueue\Job\JobPluginManager');

        /** @var \SlmQueueRedis\Adapter\RedisAdapterPluginManager $resourceProviderPluginManager */
        $redisAdapterPluginManager = $parentLocator->get('SlmQueueRedis\Adapter\RedisAdapterPluginManager');

        /** @var \SlmQueueRedis\Adapter\AdapterInterface $adapter */
        $adapter = $redisAdapterPluginManager->get($queueOptions->getAdapter(), $queueOptions->getAdapterOptions());

        $queue = new RedisQueue($adapter, $queueOptions, $requestedName, $jobPluginManager);
        return $queue;
    }

    /**
     * @param ServiceLocatorInterface $serviceLocator
     * @param $options
     * @return Redis
     */
    protected function getRedis(ServiceLocatorInterface $serviceLocator, $options) {

    }

    /**
     * @param $options
     * @return RedisOptions
     */
    protected function getOptions($options)
    {

    }
}
