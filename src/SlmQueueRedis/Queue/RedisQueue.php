<?php

namespace SlmQueueRedis\Queue;

use SlmQueueRedis\Client\RedisClientInterface;
use SlmQueue\Job\JobInterface;
use SlmQueue\Job\JobPluginManager;
use SlmQueue\Queue\AbstractQueue;

/**
 * RedisQueue
 */
class RedisQueue extends AbstractQueue implements RedisQueueInterface
{
    /**
     * @var RedisClientInterface
     */
    protected $redis;

    /**
     * Constructor
     *
     * @param RedisClientInterface $redis
     * @param string               $name
     * @param JobPluginManager     $jobPluginManager
     */
    public function __construct(RedisClientInterface $redis, $name, JobPluginManager $jobPluginManager)
    {
        $this->redis = $redis;
        parent::__construct($name, $jobPluginManager);
    }

    /**
     * {@inheritDoc}
     */
    public function push(JobInterface $job, array $options = array())
    {
        // Redis doesn't have identifiers for lists!

        $identifier = $this->redis->lpush(
            $this->normalize($this->getName()),
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
        $job = $this->redis->brpoplpush(
            $this->normalize($this->getName()),
            $this->normalize($this->getName(), 'working'),
            isset($options['timeout']) ? $options['timeout'] : null
        );

        return $this->unserializeJob($job->getData(), array('__id__' => $job->getId()));
    }

    /**
     * {@inheritDoc}
     */
    public function delete(JobInterface $job)
    {
        $this->redis->lrem($this->normalize($this->getName(), 'working'), 0, $this->serializeJob($job));
    }

    private function normalize($name, $type = 'pending')
    {
        return $name . ':' . $type;
    }
}
