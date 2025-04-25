<?php

namespace App\Task\Infrastructure\Controllers;

use App\Task\Application\Commands\AssignTaskCommand;
use App\Task\Application\Commands\CreateTaskCommand;
use App\Task\Application\Commands\UpdateTaskStatusCommand;
use App\Task\Application\Queries\GetTasksQuery;
use App\Task\Application\Services\TaskService;
use App\Task\Domain\Exceptions\TaskNotFoundException;
use App\Task\Domain\Exceptions\Validation\ValidationException;
use App\Task\Domain\ValueObjects\Ids\TaskId;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class TaskController extends AbstractController
{
    #[Route('/api/tasks/create', methods: ['POST'])]
    public function create(
        Request $request,
        TaskService $service
    ): JsonResponse
    {
        try {
            $payload = $service->getRequestPayload($request);
            $command = new CreateTaskCommand(
                id: TaskId::generate()->toString(),
                title: $payload['title'] ?? '',
                description: $payload['description'] ?? '',
                status: $payload['status'] ?? '',
                assigneeId: $payload['assigneeId'] ?? null
            );

            $task = $service->createTask($command);

            return new JsonResponse($task, Response::HTTP_CREATED);
        } catch (ValidationException $e) {
            return new JsonResponse(
                ['errors' => $service->formatValidationErrors($e)],
                Response::HTTP_BAD_REQUEST
            );
        }
    }

    #[Route('/api/tasks', methods: ['GET'])]
    public function list(
        Request $request,
        TaskService $service
    ): JsonResponse
    {
        $query = new GetTasksQuery(
            $request->query->get('status'),
            $request->query->get('assigneeId')
        );

        $tasks = $service->getTasks($query);

        return new JsonResponse($tasks);
    }

    #[Route('/api/tasks/{id}/update', methods: ['PATCH'])]
    public function updateStatus(
        string $id,
        Request $request,
        TaskService $service
    ): JsonResponse
    {
        $payload = $service->getRequestPayload($request);
        try {
            $command = new UpdateTaskStatusCommand(
                taskId: $id,
                status: $payload['status'] ?? ''
            );
            $updatedTask = $service->updateTaskStatus($command);

            return new JsonResponse($updatedTask);
        } catch (ValidationException $e) {
            return new JsonResponse(
                ['errors' => $service->formatValidationErrors($e)],
                Response::HTTP_BAD_REQUEST
            );
        } catch (\InvalidArgumentException $e) {
            return new JsonResponse(
                ['error' => $e->getMessage()],
                Response::HTTP_BAD_REQUEST
            );
        } catch (TaskNotFoundException $e) {
            return new JsonResponse(
                ['error' => $e->getMessage()],
                Response::HTTP_NOT_FOUND
            );
        }
    }

    #[Route('/api/tasks/{id}/assign', methods: ['PATCH'])]
    public function assignToUser(
        string $id,
        Request $request,
        TaskService $service
    ): JsonResponse
    {
        $payload = $service->getRequestPayload($request);
        try {
            $command = new AssignTaskCommand(
                taskId: $id,
                assigneeId: $payload['assigneeId'] ?? null,
            );
            $tasks = $service->assignTask($command);

            return new JsonResponse($tasks);
        } catch (ValidationException $e) {
            return new JsonResponse(
                ['errors' => $service->formatValidationErrors($e)],
                Response::HTTP_BAD_REQUEST
            );
        } catch (TaskNotFoundException $e) {
            return new JsonResponse(
                ['error' => $e->getMessage()],
                Response::HTTP_NOT_FOUND
            );
        }
    }
}