<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use App\Http\Resources\MemberResource;
use App\Http\Resources\TaskResource;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
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
            'email' => $this->email,
            'avatar' => $this->avatar,
            'is_active' => $this->is_active,
            'address' => $this->address,
            'contact' => $this->contact,
            'last_login_at' => $this->last_login_at,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'members' =>$this->whenLoaded('members',
            function (){
                return new MemberResource($this->members);
            }),
            'tasks' =>$this->whenLoaded('tasks',
            function () {
                return new TaskResource($this->tasks);
            }),
        ];
    }
}
