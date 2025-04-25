<?php

namespace App\Task\Application\Commands;

use Symfony\Component\Validator\Constraints as Assert;

final class AssignTaskCommand
{
    public function __construct(
        #[Assert\Uuid]
        public $taskId,

        #[Assert\NotBlank]
        #[Assert\Type('integer')]
        public $assigneeId
    ) {
    }
}