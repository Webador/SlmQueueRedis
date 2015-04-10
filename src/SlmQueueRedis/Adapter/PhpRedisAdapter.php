<?php

namespace SlmQueueRedis\Adapter;


use Redis;
use SlmQueueRedis\Queue\RedisQueue;
use Zend\Paginator\Adapter\AdapterInterface;

/**
 * RedisQueue
 */
class PhpRedisAdapter extends AbstractRedisAdapter
{
    /**
     * @var Redis
     */
    protected $redis;

    /**
     * @param Redis $redis
     * @param string $namespace
     */
    public function __construct(Redis $redis, $namespace = null)
    {
        $this->redis = $redis;
        parent::__construct($namespace);
    }


    /**
     * Pushes value into the queue
     *
     * @param string $queue
     * @param mixed $value
     * @return int
     */
    public function push($queue, $value)
    {
        $id = $this->redis->incr(
            $this->normalize($queue, static::ID_GENERATOR)
        );

        $this->redis->multi()
            ->sAdd($this->normalize(self::QUEUES), $queue)
            ->hSetNx($this->normalize($queue, static::JOB_DATA), $id, $value)
            ->lPush($this->normalize($queue, static::PENDING_LIST), $id)
            ->exec();

        return $id;
    }

    /**
     * @param string $queue
     * @param int $leaseTime
     * @param int $timeout
     * @return null|array
     */
    public function pop($queue, $leaseTime = 3600, $timeout = RedisQueue::BLOCKING_DISABLED)
    {
        // get next id from pending list and move id to working list
        if($timeout == RedisQueue::BLOCKING_DISABLED) {
            $id = $this->redis->rpoplpush(
                $this->normalize($queue, static::PENDING_LIST),
                $this->normalize($queue, static::WORKING_LIST)
            );
        }
        else {
            $id = $this->redis->brpoplpush(
                $this->normalize($queue, static::PENDING_LIST),
                $this->normalize($queue, static::WORKING_LIST),
                $timeout
            );
        }

        if(!$id) {
            return null;
        }

        // get job data from map
        $value = $this->redis->hGet(
            $this->normalize($queue, static::JOB_DATA),
            $id
        );

        if(!$value) {
            // Todo Illegal state throw exception?!
            return null;
        }

        // Create a expiring key based on the job id
        $this->redis->setex(
            $this->normalize($queue, static::LEASE, $id),
            $leaseTime,
            1 // dummy value
        );

        return array('id' => $id, 'value' => $value);
    }

    /**
     *
     */
    public function delete($queue, $id)
    {
        $this->redis->multi()
            ->lRem($this->normalize($queue, static::PENDING_LIST), $id, 0)
            ->lRem($this->normalize($queue, static::WORKING_LIST), $id, 0)
            ->hDel($this->normalize($queue, static::JOB_DATA), $id)
            ->del($this->normalize($queue, static::LEASE, $id))
            ->exec();
    }

    /**
     *
     */
    public function recover($queue)
    {
        $recovered = 0;
        foreach($this->redis->lRange($this->normalize($queue, static::WORKING_LIST), 0, -1) as $id) {
            if(!$this->redis->exists($this->normalize($queue, static::LEASE, $id))) {
                $this->redis->multi()
                    ->lRem($this->normalize($queue, static::WORKING_LIST), $id, 0)
                    ->lPush($this->normalize($queue, static::PENDING_LIST), $id)
                    ->exec();
                $recovered++;
            }
        }
        return $recovered;
    }


    public function flush($queue) {
        $this->redis->multi()
            ->del($this->normalize($queue, static::PENDING_LIST))
            ->del($this->normalize($queue, static::WORKING_LIST))
            ->del($this->normalize($queue, static::JOB_DATA))
            ->exec();
    }


    /**
     * @param $queue
     * @param $id
     * @return string
     */
    public function peek($queue, $id)
    {
        return $this->redis->hGet(
            $this->normalize($queue, static::JOB_DATA),
            $id
        );
    }


    public function slice($queue, $offset, $itemCountPerPage)
    {
        return $this->redis->lRange($this->normalize($queue, static::PENDING_LIST), $offset, $itemCountPerPage);
    }


    public function count($queue)
    {
        return $this->redis->lLen($this->normalize($queue, static::PENDING_LIST));
    }


}
