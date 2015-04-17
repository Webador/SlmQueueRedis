<?php

namespace SlmQueueRedis\Adapter;

/**
 * RedisQueue
 */
interface AdapterInterface
{
    public function push($queue, $value);
    public function pop($queue, $leaseTime = 30);
    public function delete($queue, $id);

    //public function release($queue, $id);
    public function recover($queue);
    //public function expire($queue);
    public function peek($queue, $id);

    public function slice($queue, $offset, $count);
    public function count($queue);
    public function purge($queue);
}
