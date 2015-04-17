<?php

namespace SlmQueueRedis\Queue;

use SlmQueue\Job\JobInterface;
use SlmQueue\Queue\QueueInterface;
use SlmQueueRedis\Adapter\AdapterInterface;

/**
 * Contract for a Redis queue
 */
interface RedisQueueInterface extends QueueInterface
{

    /**
     * @return AdapterInterface
     */
    public function getAdapter();

    /**
     * Recover jobs which are currently processed but are timed out
     *
     * @return mixed
     */
    public function recover();

    /**
     * Get a job from the queue without processing it
     *
     * @param  int          $id
     * @return JobInterface
     */
    public function peek($id);


    /**
     * Deletes all jobs from the queue
     */
    public function purge();

}
