<?php

namespace SlmQueueRedis\Queue;

use SlmQueue\Job\JobInterface;
use SlmQueue\Job\JobPluginManager;
use SlmQueue\Queue\AbstractQueue;
use SlmQueueRedis\Adapter\AdapterInterface;
use SlmQueueRedis\Options\RedisOptions;
use SlmQueueRedis\Exception;

/**
 * RedisQueue
 */
class RedisQueue extends AbstractQueue implements RedisQueueInterface
{
    const LIFETIME_DISABLED  = 0;
    const LIFETIME_UNLIMITED = -1;

    const BLOCKING_DISABLED  = -1;
    const BLOCKING_UNLIMITED = 0;

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
    public function __construct(AdapterInterface $adapter, RedisOptions $options, $name, JobPluginManager $jobPluginManager)
    {
        $this->adapter = $adapter;
        $this->options = $options;

        parent::__construct($name, $jobPluginManager);
    }

    /**
     * @return AdapterInterface
     */
    public function getAdapter() {
        return $this->adapter;
    }

    /**
     * {@inheritDoc}
     */
    public function push(JobInterface $job, array $options = array())
    {
        $id = $this->adapter->push(
            $this->getName(),
            $this->serializeJob($job)
        );
        $job->setId($id);

        return $job;
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
        $blockingTimeout   = $this->options->getBlockingTimeout();
        $processingTimeout = $this->options->getProcessingTimeout();

        $result = $this->adapter->pop(
            $this->getName(),
            $processingTimeout,
            $blockingTimeout
        );

        if($result === null) {
            return null;
        }

        return $this->unserializeJob($result['value'], array('__id__' => $result['id']));
    }

    /**
     * {@inheritDoc}
     */
    public function delete(JobInterface $job)
    {
        return $this->adapter->delete(
            $this->getName(),
            $job->getId()
        );
    }

    /**
     * {@inheritDoc}
     */
    public function recover()
    {
        return $this->adapter->recover(
            $this->getName()
        );
    }

    /**
     * {@inheritDoc}
     */
    public function peek($id) {
        $value = $this->adapter->peek(
            $this->getName(),
            $id
        );
        if(!$value) {
            return null;
        }

        // Cast to int because the Redis PHP serializer uses the type
        // as prefix for the value (eg. i:<id>;). Others types do cause
        // problems if deleting the job afterwards.
        return $this->unserializeJob($value, array('__id__' => (int) $id));
    }

    /**
     * {@inheritDoc}
     */
    public function purge() {
        $this->getAdapter()->purge($this->getName());
    }


//    /**
//     * Create a job instance based on serialized input
//     *
//     * Instantiate a job based on a serialized data string. The string
//     * is a JSON string containing job name, content and metadata. Use
//     * the decoded JSON value to create a job instance, configure it
//     * and return it.
//     *
//     * @param  string $string
//     * @param  array  $metadata
//     * @return \SlmQueue\Job\JobInterface
//     */
//    public function unserializeJob($string, array $metadata = array())
//    {
//        $data     =  $string; //json_decode($string, true);
//        $name     =  $data['metadata']['__name__'];
//        $metadata += $data['metadata'];
//        $content  =  $data['content']; //unserialize($data['content']);
//
//        /** @var $job \SlmQueue\Job\JobInterface */
//        $job = $this->getJobPluginManager()->get($name);
//
//        $job->setContent($content);
//        $job->setMetadata($metadata);
//
//        if ($job instanceof QueueAwareInterface) {
//            $job->setQueue($this);
//        }
//
//        return $job;
//    }
//
//    /**
//     * Serialize job to allow persistence
//     *
//     * The serialization format is a JSON object with keys "content",
//     * "metadata" and "__name__". When a job is fetched from the SL, a job name
//     * will be set and be available as metadata. An invokable job has no service
//     * name and therefore the FQCN will be used.
//     *
//     * @param  JobInterface $job The job to persist
//     * @return string
//     */
//    public function serializeJob(JobInterface $job)
//    {
//        $job->setMetadata('__name__', $job->getMetadata('__name__', get_class($job)));
//
//        $data = array(
//            'content'  => $job->getContent(),
//            'metadata' => $job->getMetadata()
//        );
//
//        return $data;
//    }
}