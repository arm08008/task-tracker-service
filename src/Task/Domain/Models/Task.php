<?php

namespace App\Task\Domain\Models;

use App\Task\Domain\ValueObjects\Ids\TaskId;
use App\Task\Domain\ValueObjects\TaskStatus;

class Task
{
    private TaskId $id;
    private string $title;
    private string $description;
    private TaskStatus $status;
    private ?int $assigneeId;
    private \DateTimeImmutable $createdAt;

    public function __construct(
        TaskId  $id,
        string  $title,
        string  $description,
        ?int $assigneeId = null
    )
    {
        $this->id = $id;
        $this->title = $title;
        $this->description = $description;
        $this->status = TaskStatus::TODO;
        $this->assigneeId = $assigneeId;
        $this->createdAt = new \DateTimeImmutable();
    }

    public function getId(): TaskId
    {
        return $this->id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getStatus(): TaskStatus
    {
        return $this->status;
    }

    public function getAssigneeId(): ?int
    {
        return $this->assigneeId;
    }

    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setStatus(TaskStatus $status): static
    {
        $this->status = $status;

        return $this;
    }

    public function setAssigneeId(?int $assigneeId): static
    {
        $this->assigneeId = $assigneeId;

        return $this;
    }
}