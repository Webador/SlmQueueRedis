<?php

namespace SlmQueueRedis\Adapter;

use Zend\ServiceManager\AbstractPluginManager;
use SlmQueueRedis\Exception;
/**
 * QueuePluginManager
 */
class RedisAdapterPluginManager extends AbstractPluginManager
{
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
