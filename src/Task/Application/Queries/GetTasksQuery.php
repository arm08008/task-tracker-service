<?php

namespace App\Task\Application\Queries;

final class GetTasksQuery
{
    public function __construct(
        public readonly ?string $status = null,
        public readonly ?string $assigneeId = null
    ) {}
}