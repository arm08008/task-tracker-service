<?php

namespace App\Task\Application\Services;

use App\Task\Application\Commands\AssignTaskCommand;
use App\Task\Application\Commands\CreateTaskCommand;
use App\Task\Application\Commands\Handlers\AssignTaskHandler;
use App\Task\Application\Commands\Handlers\CreateTaskHandler;
use App\Task\Application\Commands\Handlers\UpdateTaskStatusHandler;
use App\Task\Application\Commands\UpdateTaskStatusCommand;
use App\Task\Application\Dto\TaskDto;
use App\Task\Application\Queries\GetTasksQuery;
use App\Task\Application\Queries\Handlers\GetTasksHandler;
use App\Task\Domain\Exceptions\TaskNotFoundException;
use App\Task\Domain\Exceptions\Validation\ValidationException;
use App\Task\Domain\Models\Task;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class TaskService
{
    public function __construct(
        private readonly CreateTaskHandler $createTaskHandler,
        private readonly GetTasksHandler $getTasksHandler,
        private readonly UpdateTaskStatusHandler $updateTaskStatusHandler,
        private readonly AssignTaskHandler $assignTaskHandler,
        private readonly ValidatorInterface $validator
    ) {}

    /**
     * @throws ValidationException
     */
    public function createTask(CreateTaskCommand $command): TaskDto
    {
        $errors = $this->validator->validate($command);
        if (count($errors) > 0) {
            throw new ValidationException($errors);
        }
        $task = ($this->createTaskHandler)($command);

        return $this->mapToDto($task);
    }

    public function getTasks(GetTasksQuery $query): array
    {
        $tasks =  ($this->getTasksHandler)($query);

        return array_map([$this, 'mapToDto'], $tasks);
    }

    /**
     * @throws ValidationException
     * @throws TaskNotFoundException
     */
    public function updateTaskStatus(UpdateTaskStatusCommand $command): TaskDto
    {
        $errors = $this->validator->validate($command);
        if (count($errors) > 0) {
            throw new ValidationException($errors);
        }
        $task = ($this->updateTaskStatusHandler)($command);

        return $this->mapToDto($task);
    }

    /**
     * @throws ValidationException
     * @throws TaskNotFoundException
     */
    public function assignTask(AssignTaskCommand $command): TaskDto
    {
        $errors = $this->validator->validate($command);
        if (count($errors) > 0) {
            throw new ValidationException($errors);
        }

        $task = ($this->assignTaskHandler)($command);

        return $this->mapToDto($task);
    }

    public function formatValidationErrors(ValidationException $e): array
    {
        $errors = [];
        foreach ($e->getViolations() as $violation) {
            $propertyPath = $violation->getPropertyPath();
            $errors[$propertyPath][] = $violation->getMessage();
        }
        return $errors;
    }

    public function getRequestPayload(Request $request)
    {
        return json_decode($request->getContent(), true);
    }

    private function mapToDto(Task $task): TaskDto
    {
        return new TaskDto(
            id: $task->getId()->toString(),
            title: $task->getTitle(),
            description: $task->getDescription(),
            status: $task->getStatus()->value,
            assigneeId: $task->getAssigneeId(),
            createdAt: $task->getCreatedAt()->format(\DateTimeInterface::ATOM)
        );
    }
}