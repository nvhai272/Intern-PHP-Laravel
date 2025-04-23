@extends('layouts.app')

@section('title', 'Add Team To Project')

@section('content')

    <form id="addTeamForm" method="POST" action="{{ route('project.confirm-add-team', $project) }}">
        @csrf

        <div class="d-flex justify-content-between align-items-center mb-2">
            <b><a href="{{ route('project.detail', $project->id) }}">Detail Project</a> > Add Team</b>

            <button type="button" class="btn btn-success" id="addButton" data-bs-toggle="modal" data-bs-target="#confirmModal" disabled>
                +Add
            </button>
        </div>

        <table class="table table-bordered mt-4">
            <thead>
                <tr class="table-primary text-center">
                    <th>
                        <input type="checkbox" class="select-all" data-target=".team-checkbox" data-count="teamCount">
                        Select All Teams
                        <p id="teamCount" class="mb-0">selected: 0</p>
                    </th>
                    <th>Team Name</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($listTeam as $i => $team)
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
    {{ $listTeam->appends(request()->except('teams_page'))->links('pagination::bootstrap-5') }}

    <!-- Gọi modal dùng lại -->
    @include('layouts.confirm_modal', [
        'title' => 'Confirm Add Teams',
        'form' => 'addTeamForm',
        'slot' => 'Bạn có chắc muốn thêm Team đã chọn cho Project không?',
    ])

    <script>
        document.addEventListener('DOMContentLoaded', function() {
    const addButton = document.getElementById('addButton');  // Lấy nút "Add"
    const checkboxes = document.querySelectorAll('.team-checkbox');  // Lấy tất cả checkbox nhỏ
    const selectAllCheckboxes = document.querySelectorAll('.select-all');  // Lấy checkbox "Select All"

    // Hàm cập nhật trạng thái nút "Add"
    function updateAddButtonState() {
        const checkedCount = document.querySelectorAll('.team-checkbox:checked').length;
        addButton.disabled = checkedCount === 0;  // Disable nếu không có checkbox nào được chọn
    }

    // Lắng nghe sự kiện thay đổi của checkbox "Select All"
    selectAllCheckboxes.forEach(function(selectAllCheckbox) {
        const targetSelector = selectAllCheckbox.getAttribute('data-target');
        const displayId = selectAllCheckbox.getAttribute('data-count');

        selectAllCheckbox.addEventListener('change', function() {
            document.querySelectorAll(targetSelector).forEach(cb => cb.checked = this.checked);
            updateCount(targetSelector, displayId);
            updateAddButtonState();  // Cập nhật trạng thái nút Add
        });

        // Đếm khi click từng checkbox nhỏ
        document.querySelectorAll(targetSelector).forEach(cb => {
            cb.addEventListener('change', function() {
                updateCount(targetSelector, displayId);
                updateAddButtonState();  // Cập nhật trạng thái nút Add
            });
        });
    });

    // Cập nhật số lượng đã chọn
    function updateCount(targetSelector, displayId) {
        const allCheckboxes = document.querySelectorAll(targetSelector);
        const checkedCount = document.querySelectorAll(`${targetSelector}:checked`).length;

        // Cập nhật số lượng đã chọn
        document.getElementById(displayId).innerText = `Selected ${checkedCount}`;

        // Nếu count = 0 → bỏ tích checkbox "Select All"
        if (checkedCount === 0) {
            document.querySelectorAll('.select-all').forEach(selectAll => {
                if (selectAll.getAttribute('data-target') === targetSelector) {
                    selectAll.checked = false;
                }
            });
        }
    }

    // Kiểm tra ngay khi load trang
    updateAddButtonState();
});

    </script>
@endsection
