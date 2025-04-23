@extends('layouts.app')

@section('title', 'Add Task To Project')

@section('content')
    <b><a href="{{ route('project.detail', $project->id) }}">Detail Project</a> > Add Task</b>

    <div class="container mt-3">
        {{-- Select2 CSS --}}
        <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
        {{-- Select2 JS + jQuery --}}
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

        <style>
            #employeeSelect {
                width: 100%;
            }

            #employeeSelect option {
                padding: 12px;
                font-size: 14px;
            }

            .select2-results__options {
                max-height: 300px !important;
            }
        </style>

        <form id="addTaskForm" method="POST" action="{{ route('project.confirm-add-task', $project) }}">
            @csrf

            <button type="button" class="mb-3 btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#confirmModal"
                id="addButton">
                + Add Task To Project
            </button>

            <input type="hidden" name="project_id" value="{{ $project->id }}">

            {{-- Status --}}
            <div class="mb-3">
                <b class="form-label">Status:</b>
                <select name="task_status" id="task_status" class="form-select @error('task_status') is-invalid @enderror">
                    <option value="">Select here</option>
                    <option value="1" {{ old('task_status') == '1' ? 'selected' : '' }}>
                        Not doing task</option>
                    <option value="2" {{ old('task_status') == '2' ? 'selected' : '' }}>
                        Doing task</option>
                    <option value="3" {{ old('task_status') == '3' ? 'selected' : '' }}>
                        Finish task</option>
                </select>
                @error('task_status')
                    <small class="text-danger"><b>{{ $message }}</b></small>
                @enderror
            </div>

            {{-- Task Name --}}
            <div class="mb-3">
                <b class="form-label">Name Task:</b>
                <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror"
                    placeholder="Enter name" value="{{ old('name') }}">
                @error('name')
                    <small class="text-danger"><b>{{ $message }}</b></small>
                @enderror
            </div>

            {{-- Employee Select --}}
            <div class="mb-3">
                <b class="form-label">Add Emp To Task:</b>
                <select id="employeeSelect" name="employees[]" multiple="multiple"
                    class="form-control @error('employees') is-invalid @enderror">
                    @foreach ($emps as $emp)
                    <option value="{{ $emp->id }}"
                        {{ in_array($emp->id, old('employees', [])) ? 'selected' : '' }}>
                        {{ $emp->full_name }} - Team: {{ $emp->team->name }}
                    </option>
                @endforeach

                </select>
                @error('employees')
                    <small class="text-danger"><b>{{ $message }}</b></small>
                @enderror
            </div>
        </form>

        {{-- Confirm Modal --}}
        @include('layouts.confirm_modal', [
            'title' => 'Confirm Add Task',
            'form' => 'addTaskForm',
            'slot' => 'Bạn có chắc muốn thêm Task Project không?',
        ])

        {{-- JS Script --}}
        <script>
            $(document).ready(function() {
                $('#employeeSelect').select2({
                    placeholder: "Select employee to task of project...",
                    allowClear: true,
                    width: '100%',
                    dropdownAutoWidth: true
                });

                // $('#employeeSelect').on('change', function() {
                //     $('#addButton').prop('disabled', $(this).val().length === 0);
                // });
            });
        </script>
    </div>
@endsection
