<?php

namespace App\Task\Application\Dto;

final readonly class TaskDto
{
    public function __construct(
        public string $id,
        public string $title,
        public string $description,
        public string $status,
        public ?int $assigneeId,
        public string $createdAt
    ) {}
}