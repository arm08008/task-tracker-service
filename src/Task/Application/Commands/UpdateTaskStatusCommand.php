<?php

namespace App\Task\Application\Commands;

use App\Task\Domain\ValueObjects\TaskStatus;
use Symfony\Component\Validator\Constraints as Assert;

final class UpdateTaskStatusCommand
{
    public function __construct(
        #[Assert\Uuid]
        public $taskId,
        #[Assert\Choice(callback: [TaskStatus::class, 'values'])]
        public readonly string $status
    ) {
    }
}