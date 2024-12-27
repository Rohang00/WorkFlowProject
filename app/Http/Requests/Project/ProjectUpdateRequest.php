<?php

namespace App\Http\Requests\Project;

use Illuminate\Foundation\Http\FormRequest;

class ProjectUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => 'sometimes|string|max:255',
            'slug' => 'sometimes|string|max:255|unique:projects,slug,' . $this->project->id,
            'description' => 'sometimes|string',
            'deadline' => 'sometimes|date|after:today',
            'org_id' => 'sometimes|exists:organizations,id',
        ];
    }
}
