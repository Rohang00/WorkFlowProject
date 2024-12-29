<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\Project\ProjectStoreRequest;
use App\Http\Requests\Project\ProjectUpdateRequest;
use App\Http\Resources\ProjectResource;
use App\Models\Project;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ProjectController extends Controller
{
    public function index(): AnonymousResourceCollection|JsonResponse
    {
        $perPage = request()->query('per_page', 5);
        $projects = Project::with(['creator', 'organization', 'tasks'])->paginate($perPage);
        return ProjectResource::collection($projects);
    }

    public function show(Project $project): ProjectResource
    {
        $project->load(['creator', 'organization', 'tasks']);
        return new ProjectResource($project);
    }

    public function store(ProjectStoreRequest $request): JsonResponse
    {
        $project = Project::create($request->validated());

        // Eager load the 'creator' relationship
        $project->load('creator', 'organization', 'tasks');

        return response()->json([
            'message' => 'Project Created Successfully',
            'data'=> new ProjectResource($project),
        ],201);
    }

    public function update(ProjectUpdateRequest $request, Project $project): JsonResponse
    {
        $project->update($request->validated());

        // Eager load the 'creator' relationship after updating
        $project->load('creator', 'organization', 'tasks');

        return response()->json([
            'message' => 'Project Updated Successfully',
            'data' => new ProjectResource($project)
        ],200);
    }

    public function destroy(Project $project):JsonResponse
    {
        $project->delete();
        return response()->json([
           'message' => 'Project deleted successfully'
        ],200);
    }
}
