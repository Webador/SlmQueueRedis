<?php

namespace SlmQueueRedis\Options;

use SlmQueueDoctrine\Queue\DoctrineQueue;
use SlmQueueRedis\Client\ClientInterface;
use Zend\Stdlib\AbstractOptions;

/**
 * RedisQueueOptions
 */
class RedisOptions extends AbstractOptions
{
    /**
     * Name of the registered redis adapter service
     *
     * @var string
     */
    protected $adapter;

    /**
     * Adapter specific options
     *
     * @var array
     */
    protected $adapterOptions = array();

    /**
     * @var string
     */
    protected $namespace;

    /**
     * Timeout used for blocking operations in seconds
     *
     * @var int
     */
    protected $timeout = null;

    /**
     * how long to keep deleted (successful) jobs (in minutes)
     *
     * @var int
     */
    protected $deletedLifetime = RedisQueue::LIFETIME_DISABLED;

    /**
     * how long to keep buried (failed) jobs (in minutes)
     *
     * @var int
     */
    protected $buriedLifetime = RedisQueue::LIFETIME_DISABLED;

    /**
     * @return string
     */
    public function getAdapter()
    {
        return $this->adapter;
    }

    /**
     * @param string $adapter
     */
    public function setAdapter($adapter)
    {
        $this->adapter = $adapter;
    }

    /**
     * @return array
     */
    public function getAdapterOptions()
    {
        return $this->adapterOptions;
    }

    /**
     * @param array $adapterOptions
     */
    public function setAdapterOptions($adapterOptions)
    {
        $this->adapterOptions = $adapterOptions;
    }

    /**
     * @return string
     */
    public function getNamespace()
    {
        return $this->namespace;
    }

    /**
     * @param string $namespace
     */
    public function setNamespace($namespace)
    {
        $this->namespace = $namespace;
    }

    /**
     * @return int
     */
    public function getTimeout()
    {
        return $this->timeout;
    }

    /**
     * @param int $timeout
     */
    public function setTimeout($timeout)
    {
        $this->timeout = $timeout;
    }
}
