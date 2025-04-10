<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class EmployeeUpdateRequest extends FormRequest
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

        // dd($this->request->all());
        if ($this->hasFile('avatar_upload')) {
            $newFile = $this->file('avatar_upload');

            if ($newFile->isValid() && strpos($newFile->getMimeType(), 'image/') === 0) {
                if ($newFile->getSize() <= 2 * 1024 * 1024) {  // 2MB

                    $oldPath = session('new_avatar');

                    if ($oldPath && \Illuminate\Support\Facades\Storage::exists($oldPath)) {
                        // dd($oldPath);
                        Storage::delete($oldPath);
                    }

                    $new_tempPath = $newFile->store('temp_avatars');
                    // dùng session validate data là đủ
                    session()->put('new_avatar', $new_tempPath);

                    $this->merge([
                        'avatar' => basename($new_tempPath),
                    ]);
                }
            }
        }

        // dùng session validated data
        $new_avatar = session('new_avatar');
        if ($new_avatar) {
            $this->merge([
                'avatar' => basename($new_avatar)
            ]);

            // dd($this->request->all());

            // chỗ này lưu session làm gì nhỉ có field current_ava rồi mà
            session()->put('current_avatar', $this->input('current_avatar'));
            // dd(session('current_avatar'));
        } else {
            $this->merge([
                'avatar' => $this->input('current_avatar'),
            ]);
        }


        if (empty($this->input('password'))) {
            $this->merge([
                'password' => $this->input('current_password'),
            ]);
        } else {
            $this->merge([
                'password' => Hash::make($this->input('password')),
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
        $id = $this->input('id');

        return [
            'password' => [
                "nullable"
            ],

            'avatar' => [
                "required"
            ],

            'avatar_upload' => [
                'nullable',  // Nếu không tải lên ảnh, trường này có thể trống
                'image',     // Kiểm tra xem có phải là ảnh không
                'mimes:jpeg,png,jpg,webp', // Chỉ chấp nhận ảnh định dạng jpeg, png, jpg
                'max:2048',  // Giới hạn kích thước ảnh (2MB)
            ],

            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('m_employees', 'email')->whereNot('del_flag', IS_DELETED)
                    // loại trừ chính nó
                    ->ignore($id, 'id')
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
