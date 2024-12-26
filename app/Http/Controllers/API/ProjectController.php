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
        // Pagination Part, need to ask regarding how to make API's for pagination and non-pagination
        // $projects = Project::paginate(10);
        // return ProjectResource::collection($projects);

        return ProjectResource::collection(Project::all());
    }

    public function show(Project $project): ProjectResource
    {
        $project = Project::findorFail($project);
        return new ProjectResource($project);
    }

    public function store(ProjectStoreRequest $request): JsonResponse
    {
        $project = Project::create($request->validated());
        return response()->json([
            'message' => 'Project Created Successfully',
            'data'=> new ProjectResource($project),
        ],201);
    }

    public function update(ProjectUpdateRequest $request, Project $project): JsonResponse
    {
        $project->update($request->validated());
        return response()->json([
            'message' => 'Member Updated Successfully',
            'data' => new ProjectResource($project)
        ],200);
    }

    public function destroy(Project $project):JsonResponse
    {
        $project->delete();
        return response()->json([
            'message'=>'Project deleted successfully'
        ],200);
    }
}
