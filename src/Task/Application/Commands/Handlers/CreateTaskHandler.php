<?php

namespace App\Task\Application\Commands\Handlers;

use App\Task\Application\Commands\CreateTaskCommand;
use App\Task\Domain\Models\Task;
use App\Task\Domain\Repositories\TaskRepositoryInterface;
use App\Task\Domain\ValueObjects\Ids\TaskId;

class CreateTaskHandler
{
    public function __construct(
        private readonly TaskRepositoryInterface $taskRepository
    ) {
    }

    public function __invoke(CreateTaskCommand $command): Task
    {
        $task = new Task(
            TaskId::fromString($command->id),
            $command->title,
            $command->description,
            $command->assigneeId
        );
        $this->taskRepository->save($task);

        return $task;
    }
}