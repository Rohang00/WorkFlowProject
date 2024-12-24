<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;

use App\Http\Resources\UserResource;
use App\Http\Resources\TaskResource;
use App\Http\Resources\OrganizationResource;
use Illuminate\Http\Resources\Json\JsonResource;

class ProjectResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'slug' => $this->slug,
            'description' => $this->description,
            'created_by' => new UserResource($this->whenLoaded('creator')),
            'deadline' => $this->deadline,
            'organization' => new OrganizationResource($this->whenLoaded('organization')),
            'tasks' => TaskResource::collection($this->whenLoaded('tasks')),
        ];
    }
}
