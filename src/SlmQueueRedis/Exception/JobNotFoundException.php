<?php

namespace SlmQueueRedis\Exception;

use RuntimeException as BaseRuntimeException;

/**
 * JobNotFoundException
 */
class JobNotFoundException extends BaseRuntimeException implements ExceptionInterface
{
}
