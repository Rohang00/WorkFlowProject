<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use App\Http\Resources\UserResource;
use App\Http\Resources\TaskResource;
use App\Http\Resources\OrganizationResource;
use Illuminate\Http\Resources\Json\JsonResource;

class ProjectResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'slug' => $this->slug,
            'description' => $this->description,
            'created_by' => $this->whenLoaded('creator', function () {
                return new UserResource($this->creator);
            }),
            'deadline' => $this->deadline,
            'organization' => $this->whenLoaded('organization', function () {
                return new OrganizationResource($this->organization);
            }),
            'tasks' => $this->whenLoaded('tasks', function () {
                return TaskResource::collection($this->tasks);
            }),
        ];
    }
}
