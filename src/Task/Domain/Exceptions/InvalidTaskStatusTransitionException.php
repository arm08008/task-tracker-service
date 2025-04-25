<?php

namespace App\Task\Domain\Exceptions;

class InvalidTaskStatusTransitionException extends \Exception
{
    public function __construct(
        string $currentStatus,
        string $attemptedStatus,
        string $message = '',
        int $code = 0,
        \Throwable $previous = null
    ) {
        $message = $message ?: sprintf(
            'Invalid status transition from %s to %s',
            $currentStatus,
            $attemptedStatus
        );
        parent::__construct($message, $code, $previous);
    }
}