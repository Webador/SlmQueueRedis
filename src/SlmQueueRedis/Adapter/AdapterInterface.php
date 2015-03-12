<?php

namespace SlmQueueRedis\Adapter;

/**
 * RedisQueue
 */
interface AdapterInterface
{
    public function push($queue, $value);
    public function pop($queue, $worker = 1, $leaseTime = 30);
    public function delete($queue, $qid);
    public function release($queue, $qid);
    public function expire($queue);

}
