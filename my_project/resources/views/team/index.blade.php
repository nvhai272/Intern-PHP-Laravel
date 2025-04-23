@extends('layouts.app')

@section('content')

    <div class="container">
        <h2 class="">Team - List</h2>
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
                <th><x-sort-link column="name" label="Team Name" /></th>
                <th><x-sort-link column="ins_datetime" label="Inserted" /></th>
                <th><x-sort-link column="upd_datetime" label="Updated" /></th>
                <th>Action</th>
            </tr>
            </thead>
            <tbody>
            @foreach ($teams as $team)
                <tr class="{{ $loop->odd ? 'table-warning' : '' }}">
                    <td>{{ $team->id }}</td>
                    <td>{{ $team->name }}</td>
                    {{-- <td>{{ $team->ins_id }}</td>
                    <td>{{ $team->upd_id }}</td> --}}
                    <td>{{ $team->ins_datetime }}</td>
                    <td>{{ $team->upd_datetime }}</td>
                    <td>
                        <!-- Nút Details -->
                        <a href="{{ route('team.detail', $team->id) }}" class="btn btn-primary btn-sm">Details</a>

                        <!-- Nút Edit -->
                        <a href="{{ route('team.edit', $team->id) }}" class="btn btn-warning btn-sm">Edit</a>

                        <!-- Nút Xóa -->
                        <form action="{{ route('team.delete', $team->id) }}" method="POST" class="d-inline">
                            @csrf

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
        {{ $teams->links('pagination::bootstrap-5') }}
    </div>
@endsection
