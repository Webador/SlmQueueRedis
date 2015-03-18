<?php

namespace SlmQueueRedis\Factory;


use SlmQueueRedis\Controller\RedisWorkerController;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * WorkerFactory
 */
class RedisWorkerControllerFactory implements FactoryInterface
{
    /**
     * {@inheritDoc}
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $serviceLocator     = $serviceLocator->getServiceLocator();
        $worker             = $serviceLocator->get('SlmQueueRedis\Worker\RedisWorker');
        $queuePluginManager = $serviceLocator->get('SlmQueue\Queue\QueuePluginManager');

        return new RedisWorkerController($worker, $queuePluginManager);
    }
}
