<?php

namespace App\Task\Domain\ValueObjects;

enum TaskStatus: string
{
    case TODO = 'todo';
    case IN_PROGRESS = 'in_progress';
    case DONE = 'done';

    public static function fromString(string $value): self
    {
        return match ($value) {
            self::TODO->value => self::TODO,
            self::IN_PROGRESS->value => self::IN_PROGRESS,
            self::DONE->value => self::DONE,
            default => throw new \InvalidArgumentException("Invalid status: $value")
        };
    }

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}
