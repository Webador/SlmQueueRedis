<?php

namespace SlmQueueRedis\Options;

use SlmQueueRedis\Queue\RedisQueue;
use Zend\Stdlib\AbstractOptions;

/**
 * RedisQueueOptions
 */
class RedisOptions extends AbstractOptions
{
    /**
     * @var string
     */
    protected $adapter;

    /**
     * @var array
     */
    protected $adapterOptions = array();

    /**
     * Timeout used for blocking operations in seconds
     *
     * @var int
     */
    protected $blockingTimeout = RedisQueue::BLOCKING_DISABLED;

    /**
     * @var int
     */
    protected $processingTimeout = 3600;

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
     * @return int
     */
    public function getBuriedLifetime()
    {
        return $this->buriedLifetime;
    }

    /**
     * @param int $buriedLifetime
     */
    public function setBuriedLifetime($buriedLifetime)
    {
        $this->buriedLifetime = $buriedLifetime;
    }

    /**
     * @return int
     */
    public function getBlockingTimeout()
    {
        return $this->blockingTimeout;
    }

    /**
     * @param int $blockingTimeout
     */
    public function setBlockingTimeout($blockingTimeout)
    {
        $this->blockingTimeout = $blockingTimeout;
    }

    /**
     * @return int
     */
    public function getProcessingTimeout()
    {
        return $this->processingTimeout;
    }

    /**
     * @param int $processingTimeout
     */
    public function setProcessingTimeout($processingTimeout)
    {
        $this->processingTimeout = $processingTimeout;
    }

    /**
     * @return int
     */
    public function getDeletedLifetime()
    {
        return $this->deletedLifetime;
    }

    /**
     * @param int $deletedLifetime
     */
    public function setDeletedLifetime($deletedLifetime)
    {
        $this->deletedLifetime = $deletedLifetime;
    }


}


