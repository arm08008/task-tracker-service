<?php

namespace App\Task\Domain\Exceptions;

use App\Task\Domain\ValueObjects\Ids\TaskId;

class TaskNotFoundException extends \Exception
{
    public function __construct(TaskId $taskId)
    {
        parent::__construct(sprintf('Task with ID %s not found', $taskId->toString()));
    }
}