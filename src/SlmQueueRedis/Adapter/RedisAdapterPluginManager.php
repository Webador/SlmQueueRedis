<?php

namespace SlmQueueRedis\Adapter;

use Zend\ServiceManager\AbstractPluginManager;
use SlmQueueRedis\Exception;
use Zend\ServiceManager\FactoryInterface;

/**
 * QueuePluginManager
 */
class RedisAdapterPluginManager extends AbstractPluginManager
{

    /**
     * @var string|callable|\Closure|FactoryInterface[]
     */
    protected $factories = array(
        'redis'  => 'SlmQueueRedis\Factory\RedisStandaloneFactory',
        'predis' => 'SlmQueueRedis\Factory\PredisStandaloneFactory',
        'zend'   => 'SlmQueueRedis\Factory\ZendRedisAdapterFactory',
    );

    /**
     * {@inheritDoc}
     */
    public function validatePlugin($plugin)
    {
        if ($plugin instanceof AdapterInterface) {
            return; // we're okay!
        }

        throw new Exception\RuntimeException(sprintf(
            'Plugin of type %s is invalid; must implement SlmQueueRedis\Adapter\AdapterInterface',
            (is_object($plugin) ? get_class($plugin) : gettype($plugin))
        ));
    }
}
