<?php
namespace SlmQueueRedis\Paginator\Adapter;

use Zend\Paginator\Adapter\AdapterInterface;

class RedisQueue implements AdapterInterface {

    /**
     * @var \SlmQueueRedis\Queue\RedisQueue
     */
    protected $queue;

    public function __construct(\SlmQueueRedis\Queue\RedisQueue $queue) {
        $this->queue = $queue;
    }

    /**
     * Returns a collection of items for a page.
     *
     * @param  int $offset Page offset
     * @param  int $itemCountPerPage Number of items per page
     * @return array
     */
    public function getItems($offset, $itemCountPerPage)
    {
        $ids = $this->queue->getAdapter()->slice(
            $this->queue->getName(),
            $offset,
            $offset + $itemCountPerPage
        );


        $jobs = array();
        foreach($ids as $id) {
            $jobs[$id] = $this->queue->peek($id);
        }
        return $jobs;
    }

    /**
     * (PHP 5 &gt;= 5.1.0)<br/>
     * Count elements of an object
     * @link http://php.net/manual/en/countable.count.php
     * @return int The custom count as an integer.
     * </p>
     * <p>
     * The return value is cast to an integer.
     */
    public function count()
    {
        return $this->queue->getAdapter()->count(
            $this->queue->getName()
        );
    }
}