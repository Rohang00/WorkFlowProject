<?php

namespace App\Http\Requests\Task;

use Illuminate\Foundation\Http\FormRequest;

class TaskUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => 'sometimes|string|max:255',
            'description' => 'sometimes|string',
            'assigned_to' => 'sometimes|exists:users,id',
            'project_id' => 'sometimes|exists:projects,id',
            'status' => 'sometimes|integer|in:0,1,2',
            'due_date' => 'sometimes|date|after_or_equal:today',
        ];
    }
}
