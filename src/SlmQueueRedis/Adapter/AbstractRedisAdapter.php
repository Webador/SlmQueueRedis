<?php
namespace SlmQueueRedis\Adapter;

abstract class AbstractRedisAdapter implements AdapterInterface {

    const ID_GENERATOR = 'counter';
    const JOB_DATA     = 'jobs';
    const PENDING_LIST = 'pending';
    const WORKING_LIST = 'working';
    const LEASE        = 'lease';
    const QUEUES       = 'queues';

    /**
     * @var string
     */
    protected $namespace = 'slmqueue';

    /**
     * @var string
     */
    protected $namespaceSeparator = ':';

    /**
     * @param null|string $namespace
     */
    public function __construct($namespace = null) {
        if($namespace) {
            $this->namespace = $namespace;
        }
    }

    /**
     * Genenerates a cache key based on namespace, queue name, key type and optional an id
     *
     * @param string $type
     * @param null $id
     * @return string
     */
    protected function normalize()
    {
        $args = func_get_args();
        array_unshift($args, $this->namespace);
        return implode($this->namespaceSeparator, $args);
    }
}