<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;
use App\Models\Employee;

class LoginRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'email' => [
                'required',
                'email',
                'max:128',
                Rule::exists('m_employees', 'email')
            ],
            'password' => 'required|min:6|max:128',
        ];
    }

    /**
     * Custom error messages for validation.
     */
    public function messages(): array
    {
        return [
            'email.required' => 'Email is required',
            'email.email' => 'Email must be a valid email address',
            'email.max' => 'Email must not exceed 128 characters',
            'email.exists' => 'Email does not exist',
            'password.required' => 'Password is required',
            'password.min' => 'Password must be at least 6 characters',
            'password.max' => 'Password must not exceed 128 characters',
        ];
    }

// hàm này được chạy sau khi rules pass -> nếu không dừng ở rules luôn
//    public function withValidator(Validator $validator)
//    {
//        $validator->after(function ($validator) {
//            $password = $this->input('password');
//
//            $user = Employee::where('email', $this->input('email'))->first();
//
//            if (!$user || !Hash::check($this->input('password'), $user->password)) {
//                $validator->errors()->add('password', 'The email or password is incorrect');
//            }
//        });
//    }

}
