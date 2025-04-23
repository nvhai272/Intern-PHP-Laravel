@extends('layouts.app')

@section('content')
    <div class="container">
        <h2 class="">Project - List</h2>
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
                    <th><x-sort-link column="name" label="Name" /></th>
                    <th><x-sort-link column="ins_datetime" label="Created At" /></th>
                    <th><x-sort-link column="upd_datetime" label="Updated At" /></th>

                    <th>Action</th>
                </tr>


            </thead>
            <tbody>
                @foreach ($projects as $project)
                    <tr class="{{ $loop->odd ? 'table-warning' : '' }}">
                        <td>{{ $project->id }}</td>
                        <td>{{ $project->name }}</td>
                        <td>{{ $project->ins_datetime }}</td>
                        <td>{{ $project->upd_datetime }}</td>
                        <td>
                            <!-- Nút Details -->
                            <a href="{{ route('project.detail', $project->id) }}" class="btn btn-primary btn-sm">Details</a>

                            <!-- Nút Edit -->
                            <a href="{{ route('project.edit', $project->id) }}" class="btn btn-warning btn-sm">Edit</a>

                            <!-- Nút Xóa -->
                            <form action="{{ route('project.delete', $project->id) }}" method="POST" class="d-inline">
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
        {{ $projects->links('pagination::bootstrap-5') }}
    </div>
@endsection
