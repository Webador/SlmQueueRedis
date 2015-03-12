<?php

namespace SlmQueueRedis\Queue;

use Redis;
use SlmQueueRedis\Adapter\AdapterInterface;
use SlmQueue\Job\JobInterface;
use SlmQueue\Job\JobPluginManager;
use SlmQueue\Queue\AbstractQueue;
use SlmQueueRedis\Options\RedisOptions;

/**
 * RedisQueue
 */
class RedisQueue extends AbstractQueue implements RedisQueueInterface
{
    const LIFETIME_DISABLED  = 0;
    const LIFETIME_UNLIMITED = -1;

    /**
     * @var AdapterInterface
     */
    protected $adapter;

    /**
     * Options for this queue
     *
     * @var RedisOptions $options
     */
    protected $options;

    /**
     * Constructor
     *
     * @param AdapterInterface $adapter
     * @param RedisOptions     $options
     * @param string           $name
     * @param JobPluginManager $jobPluginManager
     */
    public function __construct(AdapterInterface $adapter, RedisOptions $options, $name, JobPluginManager $jobPluginManager) {
        $this->adapter = $adapter;
        $this->options = clone $options;

        parent::__construct($name, $jobPluginManager);
    }

    /**
     * @return AdapterInterface
     */
    public function getAdapter()
    {
        return $this->adapter;
    }

    /**
     * {@inheritDoc}
     */
    public function push(JobInterface $job, array $options = array())
    {
        // Redis doesn't have identifiers for lists!
        // Therefore use an artificial one
        $identifier = $this->adapter->push(
            $this->getName(),
            $this->serializeJob($job)
        );

        $job->setId($identifier);
    }

    /**
     * Valid option is:
     *      - timeout: by default, when we ask for a job, it will block until a job is found (possibly forever if
     *                 new jobs never come). If you set a timeout (in seconds), it will return after the timeout is
     *                 expired, even if no jobs were found
     *
     * {@inheritDoc}
     */
    public function pop(array $options = array())
    {
        $result = $this->adapter->pop(
            $this->getName(),
            null,
            isset($options['timeout']) ? $options['timeout'] : $this->options->getTimeout()
        );

        if(!$result) {
            return null;
        }

        return $this->unserializeJob($result);
    }

    /**
     * {@inheritDoc}
     */
    public function delete(JobInterface $job)
    {
        $this->adapter->delete($this->getName(), $job->getId());
    }
}