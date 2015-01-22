<?php

namespace SlmQueueRedis\Client;

/**
 * RedisQueue
 */
interface ClientInterface
{
    public function lpush($key, $value);

    public function brpoplpush($source, $destination, $timeout);

    public function lrem($key, $count, $value);
}
