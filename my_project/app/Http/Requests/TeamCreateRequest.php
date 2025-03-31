<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class TeamCreateRequest extends FormRequest
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
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            "name" => [
                'required', 'string', 'max:128',
                // check trùng tên với các record không bị xóa mềm
                Rule::unique('m_teams', 'name')
//                    ->whereNot('del_flag', IS_DELETED)
            ],
        ];
    }
    public function messages(): array{
        return [
            'name.required' => 'Name is required',
            'name.string' => 'Name must be string',
            'name.unique' => 'Name already exists',
        ];
    }

    // mặc định redirect về trang trước hoặc trang mà bạn muốn nếu fail validation sử dụng thuộc tính dứoi
    // protected $redirect = '';


}
