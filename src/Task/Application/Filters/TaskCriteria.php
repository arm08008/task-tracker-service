<?php

namespace App\Task\Application\Filters;

readonly class TaskCriteria
{
    public function __construct(
        public ?string $status = null,
        public ?string $assigneeId = null
    ) {}
}