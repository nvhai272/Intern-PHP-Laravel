@extends('layouts.app')

@section('content')
    <div class="container">
        <h2>Employee - Edit</h2>

        <!-- Hiển thị lỗi nếu có -->
        {{-- @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif --}}

        {{-- {{ dd($emp) }} --}}

        <form action="{{ route('emp.edit-confirm') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <input type="text" id="oldPass" name="current_password" class="form-control" value="{{ $emp['password'] }}"
                hidden>

            <input type="text" id="avatar" name="current_avatar" class="form-control" value="{{ $emp['avatar'] }}"
                hidden>

            <input type="text" id="id" name="id" class="form-control" value="{{ $emp['id'] }}" hidden>

            <!-- Dòng 6: Avatar, Email -->
            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="avatar" class="form-label font-weight-bold">Avatar:</label>
                    @error('avatar_upload')
                        <small class="text-danger float-end">
                            <b>{{ $message }}</b>
                        </small>
                    @else
                        @error('avatar')
                            <small class="text-danger float-end">
                                <b>{{ $message }}</b>
                            </small>
                        @enderror
                    @enderror
                    <input type="file" id="avatar_upload" name="avatar_upload" class="form-control" accept="image/*"
                        onchange="previewImage(event)">
                    <div id="avatar-preview" class="mt-3">

                        @php
                            $avatarPath = session('new_avatar')
                                ? asset('storage/temp_avatars/' . basename(session('new_avatar')))
                                : asset('storage/avatars/' . $emp['avatar']);
                        @endphp

                        <img id="avatar-img" src="
                            {{ $avatarPath }}" alt="Avatar Preview"
                            style="width: 100px; display: block;">
                    </div>



                </div>
                <div class="col-md-6">
                    <label for="email" class="form-label font-weight-bold">Email:</label>
                    @error('email')
                        <small class="text-danger float-end">
                            <b>{{ $message }}</b>
                        </small>
                    @enderror


                    {{-- {{dd($oldData)}} --}}
                    <input type="email" id="email" name="email" class="form-control"
                        value="{{ $oldData['email'] ?? ($emp['email'] ?? '') }}">

                </div>
            </div>

            <script>
                // Preview image before upload
                function previewImage(event) {
                    const file = event.target.files[0];
                    const reader = new FileReader();

                    if (!file) return; // Nếu không có file thì thoát

                    // Kiểm tra nếu file là ảnh
                    if (!file.type.startsWith('image/')) {
                        return;
                    }

                    reader.onload = function() {
                        const output = document.getElementById('avatar-img');
                        output.src = reader.result; // Cập nhật ảnh trước khi upload
                        output.style.display = 'block'; // Hiển thị ảnh
                    };

                    reader.readAsDataURL(file); // Đọc file ảnh
                }
            </script>

            <!-- Dòng 5: Status, Team -->
            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="status" class="form-label font-weight-bold">Status:</label>
                    @error('status')
                        <small class="text-danger float-end">
                            <b>{{ $message }}</b>
                        </small>
                    @enderror
                    <br>

                    {{-- {{$emp->getRawOriginal('status')}} --}}
                    <input type="radio" id="status_working" name="status" value="1"
                        {{ ((int) ($oldData['status'] ?? ($emp->getRawOriginal('status') ?? ''))) === 1 ? 'checked' : '' }}>
                    On working
                    {{-- {{ (int) old('status', $emp->getRawOriginal('status') ?? '') === 1 ? 'checked' : '' }}> On working --}}
                    <input type="radio" id="status_retired" name="status" value="2"
                        {{ ((int) ($oldData['status'] ?? ($emp->getRawOriginal('status') ?? ''))) === 2 ? 'checked' : '' }}>
                    Retired
                    {{-- {{ (int) old('status', $emp->getRawOriginal('status') ?? '') === 2 ? 'checked' : '' }}> Retired --}}
                </div>
                <div class="col-md-6">
                    <label for="team_id" class="form-label font-weight-bold">Team:</label>

                    @error('team_id')
                        <small class="text-danger float-end">
                            <b>{{ $message }}</b>
                        </small>
                    @enderror

                    <select name="team_id" id="team_id" class="form-select">
                        <option value="">Select here</option>
                        @php
                            $selectedTeam = $oldData['team_id'] ?? ($emp['team_id'] ?? '');
                        @endphp

                        @foreach ($teams as $team)
                            <option value="{{ $team->id }}" {{ $selectedTeam == $team->id ? 'selected' : '' }}>
                                {{ $team->name }}
                            </option>
                        @endforeach
                    </select>

                </div>
            </div>

            <!-- Dòng 1: Firstname, Lastname -->
            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="firstname" class="form-label font-weight-bold">First Name:</label>

                    @error('first_name')
                        <small class="text-danger float-end">
                            <b>{{ $message }}</b>
                        </small>
                    @enderror

                    <input type="text" id="firstname" name="first_name" class="form-control"
                        value="{{ $oldData['first_name'] ?? ($emp['first_name'] ?? '') }}">

                </div>
                <div class="col-md-6">
                    <label for="lastname" class="form-label font-weight-bold">Last Name:</label>

                    @error('last_name')
                        <small class="text-danger float-end">
                            <b>{{ $message }}</b>
                        </small>
                    @enderror

                    <input type="text" id="lastname" name="last_name" class="form-control"
                        value="{{ $oldData['last_name'] ?? ($emp['last_name'] ?? '') }}">
                </div>
            </div>

            <!-- Dòng 2: Gender, Birthday -->
            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="gender" class="form-label font-weight-bold">Gender:</label>
                    @error('gender')
                        <small class="text-danger float-end">
                            <b>{{ $message }}</b>
                        </small>
                    @enderror
                    <br>
                    <input type="radio" id="gender_male" name="gender" value="1"
                        {{ (int) ($oldData['gender'] ?? ($emp->getRawOriginal('gender') ?? '')) === 1 ? 'checked' : '' }}>
                    Male
                    <input type="radio" id="gender_female" name="gender" value="2"
                        {{ (int) ($oldData['gender'] ?? ($emp->getRawOriginal('gender') ?? '')) === 2 ? 'checked' : '' }}>
                    Female

                </div>
                <div class="col-md-6">
                    <label for="birthday" class="form-label font-weight-bold">Birthday:</label>

                    @error('birthday')
                        <small class="text-danger float-end">
                            <b>{{ $message }}</b>
                        </small>
                    @enderror

                    <input type="date" id="birthday" name="birthday" class="form-control"
                        value="{{ $oldData['birthday'] ?? ($emp->getRawOriginal('birthday') ?? '') }}">
                </div>
            </div>

            <!-- Dòng 4: Position, Type of Work -->
            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="position" class="form-label font-weight-bold">Position:</label>
                    @error('position')
                        <small class="text-danger float-end">
                            <b>{{ $message }}</b>
                        </small>
                    @enderror
                    <select name="position" id="position" class="form-select">
                        <option value="">Select here</option>
                        <option value="1"
                            {{ ($oldData['position'] ?? ($emp->getRawOriginal('position') ?? '')) == '1' ? 'selected' : '' }}>
                            Manager</option>
                        <option value="2"
                            {{ ($oldData['position'] ?? ($emp->getRawOriginal('position') ?? '')) == '2' ? 'selected' : '' }}>
                            Leader</option>
                        <option value="3"
                            {{ ($oldData['position'] ?? ($emp->getRawOriginal('position') ?? '')) == '3' ? 'selected' : '' }}>
                            BSE</option>
                        <option value="4"
                            {{ ($oldData['position'] ?? ($emp->getRawOriginal('position') ?? '')) == '4' ? 'selected' : '' }}>
                            Developer</option>
                        <option value="5"
                            {{ ($oldData['position'] ?? ($emp->getRawOriginal('position') ?? '')) == '5' ? 'selected' : '' }}>
                            Tester</option>
                    </select>

                </div>
                <div class="col-md-6">
                    <label for="type_of_work" class="form-label font-weight-bold">Type of Work:</label>

                    @error('type_of_work')
                        <small class="text-danger float-end">
                            <b>{{ $message }}</b>
                        </small>
                    @enderror

                    <select name="type_of_work" id="type_of_work" class="form-select">
                        <option value="">Select here</option>
                        <option value="1"
                            {{ ($oldData['type_of_work'] ?? ($emp['type_of_work'] ?? '')) == '1' ? 'selected' : '' }}>
                            Fulltime
                        </option>
                        <option value="2"
                            {{ ($oldData['type_of_work'] ?? ($emp['type_of_work'] ?? '')) == '2' ? 'selected' : '' }}>Part
                            time
                        </option>
                        <option value="3"
                            {{ ($oldData['type_of_work'] ?? ($emp['type_of_work'] ?? '')) == '3' ? 'selected' : '' }}>Test
                        </option>
                        <option value="4"
                            {{ ($oldData['type_of_work'] ?? ($emp['type_of_work'] ?? '')) == '4' ? 'selected' : '' }}>Intern
                        </option>
                    </select>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-6">
                    <label for="salary" class="form-label font-weight-bold">Salary:</label>

                    @error('salary')
                        <small class="text-danger float-end">
                            <b>{{ $message }}</b>
                        </small>
                    @enderror

                    <input type="number" id="salary" name="salary" class="form-control" step="0.01"
                        value="{{ (int) ($oldData['salary'] ?? ($emp['salary'] ?? '')) }}">
                </div>
                <div class="col-md-6">
                    <label for="password" class="form-label font-weight-bold">Password - use old pass if no has new
                        password</label>

                    @error('password')
                        <small class="text-danger float-end">
                            <b>{{ $message }}</b>
                        </small>
                    @enderror

                    <input id="password" name="password" class="form-control" type="password">
                    </input>

                    <input id="address" name="address" class="form-control" value="{{ $emp['address'] }}"
                        hidden></input>
                </div>
            </div>

            <a href="{{ route('emp.form.edit', ['id' => $emp['id']]) }}" class="btn btn-secondary">Reset</a>
            <button type="submit" class="btn btn-primary">Confirm</button>

        </form>
    </div>

    <style>
        .form-control {
            width: 100%;
        }

        .form-label {
            margin-right: 15px;
        }

        .form-label.font-weight-bold {
            font-weight: bold;
        }
    </style>
@endsection
