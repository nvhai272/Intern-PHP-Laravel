@extends('layouts.app')

@section('content')
    <div class="container">
        <h2 class="">Employee - List</h2>
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
                    <th><x-sort-link column="team_id" label="Team" /></th>
                    <th><x-sort-link column="full_name" label="Full Name" /></th>
                    <th><x-sort-link column="email" label="Email" /></th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($employees as $emp)
                    <tr class="{{ $loop->odd ? 'table-warning' : '' }}">
                        <td>{{ $emp->id }}</td>
                        <td>{{ $emp->team->name }}</td>
                        <td>{{ $emp->full_name }}</td>
                        <td>{{ $emp->email }}</td>
                        <td>
                            <!-- Nút Details -->
                            <a href="{{ route('emp.detail', $emp->id) }}" class="btn btn-primary btn-sm">Details</a>

                            <!-- Nút Edit -->
                            <a href="{{ route('emp.edit', $emp->id) }}" class="btn btn-warning btn-sm">Edit</a>

                            <!-- Nút Xóa -->
                            <form action="{{ route('emp.delete', $emp->id) }}" method="POST" class="d-inline">
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

        {{ $employees->links('pagination::bootstrap-5') }}
    </div>
@endsection
