<?php

namespace App\Task\Application\Commands\Handlers;

use App\Task\Application\Commands\AssignTaskCommand;
use App\Task\Domain\Exceptions\TaskNotFoundException;
use App\Task\Domain\Models\Task;
use App\Task\Domain\Repositories\TaskRepositoryInterface;
use App\Task\Domain\ValueObjects\Ids\TaskId;

class AssignTaskHandler
{
    public function __construct(
        private readonly TaskRepositoryInterface $taskRepository
    ) {
    }

    /**
     * @throws TaskNotFoundException
     */
    public function __invoke(AssignTaskCommand $command): Task
    {
        $task = $this->taskRepository->findById(TaskId::fromString($command->taskId));

        if ($task === null) {
            throw new TaskNotFoundException(TaskId::fromString($command->taskId));
        }

        $task->setAssigneeId($command->assigneeId);
        $this->taskRepository->save($task);

        return $task;
    }
}