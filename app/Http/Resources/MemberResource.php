<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MemberResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // return parent::toArray($request);

        return [
            "id"=>$this->id,
            "role"=>$this->role,
            "status"=>$this->status,
            "user_id"=>$this->user_id,
            "org_id"=>$this->org_id,
            "user"=> new UserResource($this->users),
            "organization"=> new OrganizationResource($this->organizations),

        ];
    }
}
