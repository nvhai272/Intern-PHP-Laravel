@extends('layouts.app')

@section('content')
    @if (session('msg') || session('msgErr'))
        <div id="alertMsg" class="alert
        {{ session('msg') ? 'alert-success' : 'alert-danger' }} fixed-alert">
            {{ session('msg') ?? session('msgErr') }}
        </div>

        <script>
            setTimeout(() => {
                let alertBox = document.getElementById('alertMsg');
                if (alertBox) {
                    alertBox.style.display = 'none';
                }
            }, 3000);
        </script>
    @endif

    <div class="row mb-0">
        <div class="col-md-6 mb-0">
            <h3>Project {{ $pro->name }}</h3>
        </div>
        <div class="col-md-6 mb-0">
            <b>Created by: </b>{{ $pro->createBy }}
        </div>
        <div class="col-md-6 mb-0">
            <p>Created at {{ $pro->ins_datetime }} and last updated at {{ $pro->upd_datetime }}</p>
        </div>
    </div>

    {{-- Teams Section --}}
    <form id="deleteFormTeam" method="POST" action="{{ route('project.confirm-delete-team', $pro) }}">


        <div class="d-flex justify-content-between align-items-center mb-2">
            <h4 class="mb-0">Teams of Project</h4>

            <div class="d-flex gap-2">
                @csrf
                <button type="button" class="btn btn-danger" id="deleteButtonTeam" data-bs-toggle="modal"
                    data-bs-target="#confirmModalTeam" disabled data-delete-button="deleteButtonTeam">
                    + Delete
                </button>

                <a href="{{ route('project.add-team', $pro) }}" class="btn btn-primary">+ Team</a>
            </div>
        </div>

        <table class="table table-bordered">
            <thead>
                <tr class="table-primary text-center">
                    <th>
                        <input type="checkbox" class="select-all" data-target=".team-checkbox" data-count="teamCount"
                            data-delete-button="deleteButtonTeam" name="select-all-team">
                        Select All Teams Of Page
                        <p id="teamCount" class="mb-0">selected: 0</p>
                    </th>
                    <th>Team Name</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($teams as $i => $team)
                    <tr class="{{ $loop->odd ? 'table-warning' : '' }}">
                        <td><input type="checkbox" name="teams[]" value="{{ $team->id }}" class="team-checkbox"></td>
                        <td>{{ $team->name }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="2">No teams found</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

    </form>
    {{ $teams->links('pagination::bootstrap-5') }}

    {{-- Employees Section --}}
    <form id="deleteFormEmp" method="POST" action="{{ route('project.confirm-delete-emp', $pro) }}">
        <div class="d-flex justify-content-between align-items-center mb-2">
            <h4 id="emp">Employees of Project</h4>

            <div class="d-flex gap-2"> <!-- nhóm 2 nút bên phải -->

                @csrf
                <button type="button" class="btn btn-danger" id="deleteButtonEmp" data-bs-toggle="modal"
                    data-bs-target="#confirmModalEmp" disabled data-delete-button="deleteButtonEmp">
                    + Delete
                </button>


                <a href="{{ route('project.add-emp', $pro) }}" class="btn btn-primary">+ Employee</a>
            </div>
        </div>

        <table class="table table-bordered">
            <thead>
                <tr class="table-primary text-center">
                    <th>
                        <input type="checkbox" class="select-all" data-target=".emp-checkbox" data-count="empCount"
                            data-delete-button="deleteButtonEmp">
                        Select All Employees Of Page
                        <p id="empCount" class="mb-0">selected: 0</p>
                    </th>
                    <th>Employee Name</th>
                    <th>Team</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($employees as $i => $emp)
                    <tr class="{{ $loop->odd ? 'table-warning' : '' }}">
                        <td><input type="checkbox" name="employees[]" value="{{ $emp->id }}" class="emp-checkbox">
                        </td>
                        <td>{{ $emp->full_name }}</td>
                        <td>{{ $emp->team->name }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3">No employees found</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </form>
    {{ $employees->appends(['section' => 'emp'])->links('pagination::bootstrap-5') }}

    <div class="d-flex justify-content-between align-items-center mb-2">
        <h4 class="mb-0" id="status">Tasks of Project</h4>

        <div class="d-flex gap-2">
            <a href="{{ route('project.add-task', $pro) }}" class="btn btn-primary">+ Task</a>
        </div>
    </div>



    <table class="table table-bordered text-center">
        <thead>
            <tr class="table-primary">
                <th><x-sort-link column="id" label="Task ID" :model="$pro" /></th>
                <th><x-sort-link column="name" label="Task Name" :model="$pro" /></th>
                <th><x-sort-link column="task_status" label="Task Status" :model="$pro" /></th>
                <th>Action</th>
            </tr>

        </thead>
        <tbody>
            @foreach ($tasks as $task)
                <tr class="{{ $loop->odd ? 'table-warning' : '' }}">
                    <td>{{ $task->id }}</td>
                    <td>{{ $task->name }}</td>
                    {{-- <td>{{ $task->project->name }}</td> --}}
                    <td>{{ $task->status }}</td>
                    <td>
                        <a href="{{ route('task.detail', $task->id) }}" class="btn btn-primary btn-sm">Details</a>

                        {{-- <a href="{{ route('task.edit', $task->id) }}" class="btn btn-warning btn-sm">Edit</a> --}}

                        <form action="{{ route('task.delete', $task->id) }}" method="POST" class="d-inline">
                            @csrf
                            {{-- @method('DELETE') --}}
                            <button type="submit" class="btn btn-danger btn-sm"
                                onclick="return confirm('Are you sure?')">Delete
                            </button>
                        </form>

                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Hiển thị link phân trang -->
    {{ $tasks->links('pagination::bootstrap-5') }}

    @include('layouts.confirm_modal', [
        'title' => 'Confirm Delete Teams',
        'form' => 'deleteFormTeam',
        'slot' => 'Bạn có chắc muốn xóa Team đã chọn cho Project không?',
        'modalId' => 'confirmModalTeam',
    ])

    @include('layouts.confirm_modal', [
        'title' => 'Confirm Delete Employees',
        'form' => 'deleteFormEmp',
        'slot' => 'Bạn có chắc muốn xóa Employee đã chọn cho Project không?',
        'modalId' => 'confirmModalEmp',
    ])

    <script>
        document.querySelectorAll('.select-all').forEach(function(selectAllCheckbox) {
            // lấy theo từng loại checkbox, loại đếm team - emp, loại xóa
            const targetSelector = selectAllCheckbox.getAttribute('data-target');

            const count = selectAllCheckbox.getAttribute('data-count');

            const deleteButtonId = selectAllCheckbox.getAttribute('data-delete-button');

            // khi chọn all checkbox tích hết và thay đổi số lượng
            selectAllCheckbox.addEventListener('change', function() {
                document.querySelectorAll(targetSelector).forEach(cb => cb.checked = this.checked);
                updateCount(targetSelector, count, deleteButtonId);
            });

            // Khi checkbox con thay đổi, thay đổi số lượng
            document.querySelectorAll(targetSelector).forEach(cb => {
                cb.addEventListener('change', function() {
                    updateCount(targetSelector, count, deleteButtonId);
                });
            });
        });


        function updateCount(targetSelector, count, deleteButtonId) {
            const allCheckboxes = document.querySelectorAll(targetSelector);
            const checkedCount = document.querySelectorAll(`${targetSelector}:checked`).length;

            // Cập nhật số đã chọn
            document.getElementById(count).innerText = `Selected ${checkedCount}`;

            // Kiểm tra nếu có checkbox được chọn, kích hoạt nút xóa
            const deleteButton = document.getElementById(deleteButtonId);
            if (checkedCount > 0) {
                deleteButton.disabled = false; // Kích hoạt nút xóa
            } else {
                deleteButton.disabled = true; // Vô hiệu hóa nút xóa
            }

            // Nếu tất cả checkbox con được chọn, đánh dấu "Select All"
            const selectAllCheckbox = document.querySelector(`.select-all[data-target="${targetSelector}"]`);
            if (selectAllCheckbox) {
                selectAllCheckbox.checked = (checkedCount === allCheckboxes.length);
            }

            // Nếu không có checkbox con nào được chọn, bỏ tích "Select All"
            if (checkedCount === 0) {
                selectAllCheckbox.checked = false;
            }
        }


        // scroll -> go to task
        document.addEventListener('DOMContentLoaded', function() {
            const section = new URLSearchParams(window.location.search).get('section');
            if (section) {
                const el = document.getElementById(section);
                if (el) {
                    el.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            }
        });
    </script>
@endsection
