<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class EmployeeCreateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation()
    {

        if ($this->hasFile('avatar_upload')) {
            $file = $this->file('avatar_upload');

            if ($file->isValid() && strpos($file->getMimeType(), 'image/') === 0) {
                if ($file->getSize() <= 2 * 1024 * 1024) {  // 2MB

                    $oldPath = session('temp_avatar');

                    if ($oldPath && \Illuminate\Support\Facades\Storage::exists($oldPath)) {
                        Storage::delete($oldPath);
                    }

                    $tempPath = $file->store('temp_avatars');

                    // đường dẫn ảnh tạm
                    session()->put('temp_avatar', $tempPath);

                    $this->merge([
                        'avatar' => basename($tempPath),
                    ]);
                }
            }
        }

        $oldPathName = session('temp_avatar');
        if ($oldPathName) {
            $this->merge([
                'avatar' => basename($oldPathName)
            ]);
        }
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'avatar' => [
                'required'
            ],
            'avatar_upload' => [
                'nullable',  // Nếu không tải lên ảnh, trường này có thể trống
                'image',     // Kiểm tra xem có phải là ảnh không
                'mimes:jpeg,png,jpg,webp', // Chỉ chấp nhận ảnh định dạng jpeg, png, jpg
                'max:2048',  // Giới hạn kích thước ảnh (2MB)
            ],
            'password' => [
                'required'
            ],
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('m_employees', 'email')
            ],

            'status' => [
                'required',
                'in:1,2',
            ],
            'team_id' => [
                'required',
                'exists:m_teams,id', // Kiểm tra rằng team_id tồn tại trong bảng teams
            ],
            'first_name' => [
                'required',
                'string',
                'max:128',
            ],
            'last_name' => [
                'required',
                'string',
                'max:128',
            ],
            'gender' => [
                'required',
                'in:1,2',
            ],
            'birthday' => [
                'required',
                'date',
                'before:today',
            ],
            'position' => [
                'required',
                'in:1,2,3,4,5',
            ],
            'type_of_work' => [
                'required',
                'in:1,2,3,4',
            ],
            'salary' => [
                'required',
                'numeric',
                'min:0',
            ],
            'address' => [
                'required',
                'string',
                'max:255',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'password.required' => 'Password is required',

            'avatar.required' => 'Avatar is required',

            'avatar_upload.image' => 'The file must be an image',
            'avatar_upload.mimes' => 'The image must be a file of type: jpeg, png, jpg',
            'avatar_upload.max' => 'The image size should not exceed 2MB',

            'email.required' => 'Email is required',
            'email.email' => 'Please enter a valid email address',
            'email.unique' => 'The email has already been taken',

            'status.required' => 'Status is required',
            'status.in' => 'Status must be either On working or Retired',

            'team_id.required' => 'Team selection is required',
            'team_id.exists' => 'Selected team does not exist',

            'first_name.required' => 'First Name is required',
            'first_name.string' => 'First Name must be a string',
            'first_name.max' => 'First Name cannot exceed 128 characters',

            'last_name.required' => 'Last Name is required',
            'last_name.string' => 'Last Name must be a string',
            'last_name.max' => 'Last Name cannot exceed 128 characters',

            'gender.required' => 'Gender selection is required',
            'gender.in' => 'Gender must be either Male or Female',

            'birthday.required' => 'Birthday is required',
            'birthday.date' => 'Please enter a valid date',
            'birthday.before' => 'Birthday must be before today',

            'position.required' => 'Position selection is required',
            'position.in' => 'Please select a valid position',

            'type_of_work.required' => 'Type of work selection is required',
            'type_of_work.in' => 'Please select a valid type of work',

            'salary.required' => 'Salary is required',
            'salary.numeric' => 'Salary must be a number',
            'salary.min' => 'Salary must be greater than or equal to 0',

            'address.required' => 'Address is required',
            'address.string' => 'Address must be a string',
            'address.max' => 'Address cannot exceed 255 characters',
        ];
    }
}
