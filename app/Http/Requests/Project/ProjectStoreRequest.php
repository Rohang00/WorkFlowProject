<?php

namespace App\Http\Requests\Project;

use Illuminate\Foundation\Http\FormRequest;

class ProjectStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'slug' => 'required|string|unique:projects,slug',
            'description' => 'required|string',
            'deadline' => 'required|date',
            'created_by' => 'required|exists:users,id',
            'org_id' => 'required|exists:organizations,id',
        ];
    }
}
