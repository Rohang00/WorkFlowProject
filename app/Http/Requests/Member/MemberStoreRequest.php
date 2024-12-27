<?php

namespace App\Http\Requests\Member;

use Illuminate\Foundation\Http\FormRequest;

class MemberStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            "role"=>"required|integer|in:0,1,2,3",
            "status"=>"required|integer|in:0,1,2",
            "user_id"=>"required|exists:users,id",
            "org_id"=>"required|exists:organizations,id"
        ];
    }
}
