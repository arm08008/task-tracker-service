<?php

namespace App\Task\Application\Commands\Handlers;

use App\Task\Application\Commands\UpdateTaskStatusCommand;
use App\Task\Domain\Exceptions\TaskNotFoundException;
use App\Task\Domain\Models\Task;
use App\Task\Domain\Repositories\TaskRepositoryInterface;
use App\Task\Domain\ValueObjects\Ids\TaskId;
use App\Task\Domain\ValueObjects\TaskStatus;

class UpdateTaskStatusHandler
{
    public function __construct(
        private readonly TaskRepositoryInterface $taskRepository
    ) {
    }

    public function __invoke(UpdateTaskStatusCommand $command): Task
    {
        $task = $this->taskRepository->findById(TaskId::fromString($command->taskId));

        if ($task === null) {
            throw new TaskNotFoundException(TaskId::fromString($command->taskId));
        }

        $task->setStatus(TaskStatus::fromString($command->status));
        $this->taskRepository->save($task);

        return $task;
    }
}