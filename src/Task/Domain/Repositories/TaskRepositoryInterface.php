<?php

namespace App\Task\Domain\Repositories;

use App\Task\Application\Filters\TaskCriteria;
use App\Task\Domain\Models\Task;
use App\Task\Domain\ValueObjects\Ids\TaskId;

interface TaskRepositoryInterface
{
    public function save(Task $task): void;
    public function findById(TaskId $id): ?Task;
    public function findAll(): array;
    public function findByCriteria(TaskCriteria $criteria): array;
}