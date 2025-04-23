<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AddTaskRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:128'],
            'task_status' => ['required', 'in:1,2,3'],
            'project_id' => ['required', 'exists:projects,id'],
            'employees' => ['required', 'array', 'min:1'],
            'employees.*' => ['exists:m_employees,id'],
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
            'name.max' => 'Name cannot exceed 128 characters',

            'employees.required' => 'At least one employee must be selected',
            'employees.array' => 'Employees must be an array',
            'employees.*.exists' => 'Some selected employees do not exist',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $project = $this->route('project'); // Lấy từ route binding

            if (!$project) {
                $validator->errors()->add('project', 'Invalid project');
                return;
            }

            $validEmployeeIds = $project->employees()->select('m_employees.id')->pluck('id')->toArray();

            $invalids = collect($this->employees)
                ->filter(fn($id) => !in_array($id, $validEmployeeIds));

            if ($invalids->isNotEmpty()) {
                $validator->errors()->add('employees', 'Some employees are not part of this project');
            }
        });
    }
}
