<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Log;

class ProjectUpdateRequest extends FormRequest
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
        $id = $this->input('id');
        return [
            "name" => [
                'required',
                'string',
                'max:128',
                // check trùng tên với các record không bị xóa mềm
                Rule::unique('m_teams', 'name')->whereNot('del_flag', IS_DELETED)
                    // loại trừ chính nó
                    ->ignore($id, 'id')
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Name is required',
            'name.string' => 'Name must be string',
            'name.unique' => 'Name already exists',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        Log::info(ERR_VALIDATION . ' ' . static::class );

        throw new HttpResponseException(
            redirect()->back()->withErrors($validator)->withInput()
        );
    }

}
