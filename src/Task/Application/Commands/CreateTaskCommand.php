<?php

namespace App\Task\Application\Commands;

use App\Task\Domain\ValueObjects\TaskStatus;
use Symfony\Component\Validator\Constraints as Assert;

final class CreateTaskCommand
{
    public function __construct(
        #[Assert\Uuid]
        public $id,

        #[Assert\NotBlank]
        #[Assert\Type('string')]
        #[Assert\Length(min: 3, max: 100)]
        public $title,

        #[Assert\NotBlank]
        #[Assert\Type('string')]
        #[Assert\Length(min: 10, max: 1000)]
        public $description,

        #[Assert\Choice(callback: [TaskStatus::class, 'values'])]
        public $status,

        #[Assert\Type('integer')]
        public $assigneeId = null
    ) {
    }
}