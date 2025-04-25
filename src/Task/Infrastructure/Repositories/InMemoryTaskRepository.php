<?php

namespace App\Task\Infrastructure\Repositories;

use App\Task\Application\Filters\TaskCriteria;
use App\Task\Domain\Repositories\TaskRepositoryInterface;
use App\Task\Domain\Models\Task;
use App\Task\Domain\ValueObjects\Ids\TaskId;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;

class InMemoryTaskRepository implements TaskRepositoryInterface
{
    private const CACHE_KEY = 'tasks';
    private CacheInterface $cache;

    public function __construct(CacheInterface $cache)
    {
        $this->cache = $cache;
    }

    public function save(Task $task): void
    {
        $tasks = $this->getAllTasks();
        $tasks[$task->getId()->toString()] = $task;
        $this->cache->delete(self::CACHE_KEY);
        $this->cache->get(self::CACHE_KEY, function(ItemInterface $item) use ($tasks) {
            $item->expiresAfter(3600);
            return $tasks;
        });
    }

    public function findById(TaskId $id): ?Task
    {
        $tasks = $this->getAllTasks();
        return $tasks[$id->toString()] ?? null;
    }

    public function findAll(): array
    {
        return array_values($this->getAllTasks());
    }

    public function findByCriteria(TaskCriteria $criteria): array
    {
        return array_filter($this->getAllTasks(), function(Task $task) use ($criteria) {
            return (empty($criteria->status) || $task->getStatus()->value === strtolower($criteria->status))
                && (empty($criteria->assigneeId) || $task->getAssigneeId() === $criteria->assigneeId);
        });
    }

    private function getAllTasks(): array
    {
        return $this->cache->get(self::CACHE_KEY, function() {
            return [];
        }) ?? [];
    }
}