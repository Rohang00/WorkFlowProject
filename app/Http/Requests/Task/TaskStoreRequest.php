<?php

namespace App\Http\Requests\Task;

use Illuminate\Foundation\Http\FormRequest;

class TaskStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'assigned_to' => 'required|exists:users,id',
            'project_id' => 'required|exists:projects,id',
            'status' => 'required|integer|in:0,1,2',
            'due_date' => 'required|date|after_or_equal:today',
            'created_by' => 'required|exists:users,id',
        ];
    }
}
