    @extends('layouts.app')

@section('content')
    <div class="container">
        <h2 class="">Task - List</h2>
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

        <table class="table table-bordered text-center">
            <thead>
                <tr class="table-primary">
                    <th><x-sort-link column="id" label="ID" /></th>
                    <th><x-sort-link column="name" label="Task Name" /></th>
                    {{-- <th><x-sort-link column="project_id" label="Project Name" /></th> --}}
                    {{-- nếu sort theo khóa ngoại thì không cần xử lí join bảng trong repo --}}
                    <th><x-sort-link column="project-name" label="Project Name" /></th>
                    <th><x-sort-link column="task_status" label="Status" /></th>

                    {{-- <th><x-sort-link column="ins_datetime" label="Created At" /></th>
                    <th><x-sort-link column="upd_datetime" label="Updated At" /></th> --}}

                    <th>Action</th>
                </tr>

            </thead>
            <tbody>
                @foreach ($tasks as $task)
                    <tr class="{{ $loop->odd ? 'table-warning' : '' }}">
                        <td>{{ $task->id }}</td>
                        <td>{{ $task->name }}</td>
                        <td>{{ $task->project->name }}</td>
                        <td>{{ $task->status }}</td>
                        {{-- <td>{{ $task->ins_datetime }}</td>
                        <td>{{ $task->upd_datetime }}</td> --}}
                        <td>
                            <!-- Nút Details -->
                            <a href="{{ route('task.detail', $task->id) }}" class="btn btn-primary btn-sm">Details</a>

                            <!-- Nút Edit -->
                            {{-- <a href="{{ route('task.edit', $task->id) }}" class="btn btn-warning btn-sm">Edit</a> --}}

                            <!-- Nút Xóa -->
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
    </div>
@endsection
