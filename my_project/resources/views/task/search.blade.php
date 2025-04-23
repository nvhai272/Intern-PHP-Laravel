@extends('layouts.app')

@section('content')
    <div class="container">

        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if(session('msgErr'))
    <div class="alert alert-danger">
        {{ session('msgErr') }}
    </div>
@endif

        <div class="card">
            <div class="card-header"><b>Search - Task</b></div>
            <div class="card-body">

                <form method="GET" action="{{ route('task.search') }}">
                    <div class="row">
                        <div class="col-md-4">
                            <b for="team">Project</b>
                            <select name="team_id" id="team" class="form-control">
                                <option value="">All Projects</option>
                                @foreach ($projects as $pro)
                                    <option value="{{ $pro->id }}"
                                        {{ request('team_id') == $pro->id ? 'selected' : '' }}>
                                        {{ $pro->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-4">
                            <b for="name">Name</b>
                            <input type="text" name="full_name" id="name" class="form-control"
                            value="{{ request('full_name') }}">
                        </div>

                    </div>

                    <div class="mt-3">
                        <button type="submit" class="btn btn-primary" name="search" value="1">Search</button>
                        <a href="{{ route('emp.search') }}" class="btn btn-secondary">Reset</a>
                    </div>
                </form>
            </div>
        </div>

        <div class="table-responsive mt-3">
            <table class="table table-bordered text-center">
                <thead>
                    <tr class="table-primary">
                        <th>ID</th>
                        <th>Team</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @if ($tasks->isNotEmpty())
                        @foreach ($tasks as $employee)
                            <tr class="{{ $loop->odd ? 'table-warning' : '' }}">
                                <td>{{ $employee->id }}</td>
                                {{-- <td>{{ $employee->team->name }}</td> --}}
                                {{-- <td>{{ $employee->full_name }}</td> --}}
                                <td>{{ $employee->email }}</td>
                                <td>
                                    {{-- <a href="{{ route('team.detail', $team->id) }}"
                                        class="btn btn-primary btn-sm">Details</a>
                                    <a href="{{ route('emp.edit', $employee->id) }}"
                                        class="btn btn-sm btn-warning">Edit</a> --}}
                                    <form action="{{ route('emp.delete', $employee->id) }}" method="POST"
                                        class="d-inline">
                                        @csrf
                                        {{-- @method('DELETE') --}}
                                        <button type="submit" class="btn btn-sm btn-danger"
                                            onclick="return confirm('Are you sure?')">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="5" class="text-center"> <b>No exist data employee</b> </td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>

        @if (isset($emps) && $emps->isNotEmpty())
            {{ $emps->appends(request()->query())->links('pagination::bootstrap-5') }}
        @endIf
    </div>
@endsection
