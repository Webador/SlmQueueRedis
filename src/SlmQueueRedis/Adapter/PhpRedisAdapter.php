<?php

namespace SlmQueueRedis\Adapter;


use Redis;
/**
 * RedisQueue
 */
class PhpRedisAdapter implements AdapterInterface
{
    /**
     * @var Redis
     */
    protected $redis;

    /**
     *
     * @var string
     */
    protected $namespace = 'slmqueue';

    /**
     * @var null|int
     */
    protected $blockingTimeout = -1;

    /**
     * @param Redis $redis
     */
    public function __construct(Redis $redis) {
        $this->redis = $redis;
    }

    /**
     * @return int|null
     */
    public function getBlockingTimeout()
    {
        return $this->blockingTimeout;
    }

    /**
     * @param int|null $blockingTimeout
     */
    public function setBlockingTimeout($blockingTimeout)
    {
        $this->blockingTimeout = $blockingTimeout;
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


    public function push($queue, $value) {
        $qid = $this->createQid($queue);

        $this->redis->multi()
            ->hsetnx($this->normalize($queue, 'jobs'), $qid, $value)
            ->lpush($this->normalize($queue), $qid)
            ->exec();

        return $qid;
    }

    public function pop($queue, $worker = 1, $leaseTime = 30) {
        $pendingList = $this->normalize($queue);
        $workingList = $this->normalize($queue, 'working');

        // get next id from pending list and move id to working list
        if($this->getBlockingTimeout() == -1) {
            $qid = $this->redis->rpoplpush($pendingList, $workingList);
        }
        else {
            $qid = $this->redis->brpoplpush($pendingList, $workingList, $this->getBlockingTimeout());
        }

        if(!$qid) {
            return null;
        }

        // get job data from map
        $jobMap = $this->normalize($queue, 'jobs');
        $value = $this->redis->hget($jobMap, $qid);
        if(!$value) {
            // Todo Illegal state throw exception?!
            return null;

        }
        // Create a expiring key based on the job id
        $leaseKey = $this->normalize($queue, 'lease') .':'. $qid;
        $this->redis->setex($leaseKey, $leaseTime, $worker);

        return $value;
    }

    public function delete($queue, $qid) {
        $this->redis->multi()
            ->lrem($this->normalize($queue, 'working'), $qid, -1)
            ->hdel($this->normalize($queue, 'jobs'), $qid)
            ->exec();
    }

    public function release($queue, $qid) {
        $this->redis->multi()
            ->lrem($this->normalize($queue, 'working'), $qid, -1)
            ->lpush($this->normalize($queue), $qid)
            ->exec();
    }


    public function expire($queue) {
        $expired = 0;

        foreach($this->redis->lrange($this->normalize('queue', 'working'), 0, -1) as $qid) {
            if(!$this->redis->exists($this->normalize($queue, 'lease') .':'. $qid)) {
                $this->redis->multi()
                    ->lrem($this->normalize($queue, 'working'), $qid, -1)
                    ->lpush($this->normalize($queue), $qid)
                    ->exec();
                $expired++;
            }
        }

        return $expired;
    }


    protected function createQid($queue) {
        return $this->redis->incr($this->normalize($queue, 'counter'));
    }

    private function normalize($name, $type = 'pending')
    {
        return $this->namespace . ':' . $name . ':' . $type;
    }


}
