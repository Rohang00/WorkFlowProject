<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\Task\TaskStoreRequest;
use App\Http\Requests\Task\TaskUpdateRequest;
use App\Http\Resources\TaskResource;
use App\Models\Task;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;


class TaskController extends Controller
{
    public function index(): AnonymousResourceCollection|JsonResponse
    {
        $perPage = request()->query('per_page', 5);
        $tasks = Task::with(['assignedTo', 'completedBy', 'project'])->paginate($perPage);
        return TaskResource::collection($tasks);
    }

    public function show(Task $task): TaskResource
    {
        // load() adds specified relationships to the already resolved task instance so dont need to make n+1 query
        $task->load(['assignedTo', 'completedBy', 'project']);
        return new TaskResource($task);
    }

    public function store(TaskStoreRequest $request): JsonResponse
    {
        $task = Task::create($request->validated());

        // Eager load the assignedTo relationship
        $task->load('assignedTo','project');

        return response()->json([
            'message' => 'Task Created Successfully',
            'data'=> new TaskResource($task),
        ], 201);
    }

    public function update(TaskUpdateRequest $request, Task $task): JsonResponse
    {
        $task->update($request->validated());
        return response()->json([
            'message' => 'Task Updated Successfully',
            'data' => new TaskResource($task),
        ], 200);
    }

    public function destroy(Task $task): JsonResponse
    {
        $task->delete();
        return response()->json([
            'message' => 'Task Deleted Successfully',
        ], 200);
    }
}
