<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TaskResource extends JsonResource
{
    public function toArray($request)
    {
        $statusLabels = [
            0 => 'To-Do',
            1 => 'In-progress',
            2 => 'Completed',
            3 => 'On-hold',
        ];

        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'status' => $this->status,
            'status_label' => $statusLabels[$this->status] ?? 'Unknown',
            'due_at' => $this->due_at,
            'completed_at' => $this->completed_at,
            'assigned_to' => $this->whenLoaded('assignedTo', function () {
                return new UserResource($this->assignedTo);
            }),
            'completed_by' => $this->whenLoaded('completedBy', function () {
                return new UserResource($this->completedBy);
            }),
            'project' => $this->whenLoaded('project', function () {
                return new ProjectResource($this->project);
            }),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
