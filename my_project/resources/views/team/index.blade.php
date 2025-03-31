@extends('layouts.app')

@section('content')

    <div class="container mt-3">
        <h2 class="">List Team</h2>
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

        <table class="table table-bordered">
            <thead>
            <tr>
                <th>{!! sort_link('id', $sortBy, $order, 'team.list') !!}</th>
                <th>{!! sort_link('name', $sortBy, $order, 'team.list') !!}</th>
                <th>Ins_Id</th>
                <th>Upd_Id</th>
                <th>{!! sort_link('ins_datetime', $sortBy, $order, 'team.list') !!}</th>
                <th>{!! sort_link('upd_datetime', $sortBy, $order, 'team.list') !!}</th>
                <th>Action</th>
            </tr>
            </thead>
            <tbody>
            @foreach ($teams as $team)
                <tr>
                    <td>{{ $team->id }}</td>
                    <td>{{ $team->name }}</td>
                    <td>{{ $team->ins_id }}</td>
                    <td>{{ $team->upd_id }}</td>
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
                            @method('DELETE')
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
