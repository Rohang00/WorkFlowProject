<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use App\Http\Resources\UserResource;
use App\Http\Resources\ProjectResource;
use Illuminate\Http\Resources\Json\JsonResource;

class TaskResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'task_id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'assigned_to' => new UserResource($this->whenLoaded('assignedTo')),
            'completed_by' => new UserResource($this->whenLoaded('completedBy')),
            'project' => new ProjectResource($this->whenLoaded('project')),
            'status' => $this->status,
            'due_at' => $this->due_at,
            'completed_at' => $this->completed_at,
        ];
    }
}
