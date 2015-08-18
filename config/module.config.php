<?php

return array(
    'service_manager' => array(
        'factories' => array(
            'SlmQueueRedis\Worker\RedisWorker' => 'SlmQueue\Factory\WorkerFactory',
            'SlmQueueRedis\Adapter\RedisAdapterPluginManager' => 'SlmQueueRedis\Factory\RedisAdapterPluginManagerFactory',
        )
    ),

    'controllers' => array(
        'factories' => array(
            'SlmQueueRedis\Controller\RedisWorkerController' => 'SlmQueueRedis\Factory\RedisWorkerControllerFactory',
        ),
    ),

    'console'   => array(
        'router' => array(
            'routes' => array(
                'slm-queue-redis-worker' => array(
                    'type'    => 'Simple',
                    'options' => array(
                        'route'    => 'queue redis <queue> [--timeout=] --start',
                        'defaults' => array(
                            'controller' => 'SlmQueueRedis\Controller\RedisWorkerController',
                            'action'     => 'process'
                        ),
                    ),
                ),
                'slm-queue-redis-recover' => array(
                    'type'    => 'Simple',
                    'options' => array(
                        'route'    => 'queue redis <queue> --recover [--executionTime=]',
                        'defaults' => array(
                            'controller' => 'SlmQueueRedis\Controller\RedisWorkerController',
                            'action'     => 'recover'
                        ),
                    ),
                ),
            ),
        ),
    ),
    'slm_queue' => array(
        /**
         * Worker Strategies
         */
        'worker_strategies' => array(
            'default' => array(
               'SlmQueueRedis\Strategy\IdleNapStrategy' => array('nap_duration' => 1),
            ),
            'queues' => array(
            ),
        ),
        /**
         * Strategy manager configuration
         */
        'strategy_manager' => array(
            'invokables' => array(
                'SlmQueueRedis\Strategy\IdleNapStrategy' => 'SlmQueueRedis\Strategy\IdleNapStrategy',
            ),
        ),
    )
);
