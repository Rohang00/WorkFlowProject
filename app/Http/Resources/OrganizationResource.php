<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use App\Http\Resources\MemberResource;
use App\Http\Resources\ProjectResource;
use Illuminate\Http\Resources\Json\JsonResource;

class OrganizationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param Request $request
     * @return array<string, mixed>
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'avatar' => $this->avatar,
            'address' => $this->address,
            'phone' => $this->phone,
            'description' => $this->description,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'members' => MemberResource::collection($this->whenLoaded('members')),
            'projects' => ProjectResource::collection($this->whenLoaded('projects')),
        ];
    }
}
