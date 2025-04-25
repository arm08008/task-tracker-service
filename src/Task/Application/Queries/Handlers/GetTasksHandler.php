<?php

namespace App\Task\Application\Queries\Handlers;

use App\Task\Application\Filters\TaskCriteria;
use App\Task\Application\Queries\GetTasksQuery;
use App\Task\Domain\Repositories\TaskRepositoryInterface;

class GetTasksHandler
{
    public function __construct(private readonly TaskRepositoryInterface $taskRepository) {
    }

    public function __invoke(GetTasksQuery $query): array
    {
        return $this->taskRepository->findByCriteria(new TaskCriteria($query->status, $query->assigneeId));
    }
}