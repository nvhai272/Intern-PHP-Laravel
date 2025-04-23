<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class TaskUpdateRequest extends FormRequest
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
            "name" => [
                'required',
                'string',
                'max:128',
            ],
            'task_status' => [
                'required',
                'in:1,2,3',
            ],
            'project_id' => [
                'required',
                'exists:projects,id',
            ],

        ];
    }

    public function messages(): array
    {
        return [

            'project_id.required' => 'Project selection is required',
            'project_id.exists' => 'Selected project does not exist',

            'task_status.required' => 'Task status selection is required',
            'task_status.in' => 'Please select a valid type of task status',

            'name.required' => 'Name is required',
            'name.string' => 'Name must be a string',
            'name.max' => 'Name cannot exceed 255 characters',
        ];
    }
}
