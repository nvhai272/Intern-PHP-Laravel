@extends('layouts.app')

@section('content')
    <div class="container">
        <h2>Team - Search </h2>
        {{--        @if ($errors->has('err'))--}}
        {{--            <small class="text-danger">{{ $errors->first('err') }}</small>--}}
        {{--        @endif--}}

        @if (session('msgErr'))
            <small class="text-danger">{{ session('msgErr') }}</small>
        @endif

        <form method="GET" action="{{ route('team.search') }}" class="mb-3 d-flex">
            <input type="text" name="name" id="name" class="form-control me-2" placeholder="Name"
                   value="{{ old('name', request('name')) }}">
            <button type="submit" class="btn btn-primary" name="search" value="1">Search</button>
            <a href="{{ route('team.search') }}" class="btn btn-secondary ms-2">Reset</a>
        </form>

        <table class="table table-bordered">
            <thead>
            <tr class="table-primary text-center">
                <th>
                    <a href="{{ route('team.search', ['sort_by' => 'id', 'order' => $order === 'asc' ? 'desc' : 'asc']) }}">ID</a>
                </th>
                <th>Name</th>
                <th>Action</th>
            </tr>
            </thead>
            <tbody>
            @if($teams->isNotEmpty())
                @foreach($teams as $team)
                    <tr class="{{ $loop->odd ? 'table-warning' : '' }}">
                        <td>{{ $team->id }}</td>
                        <td>{{ $team->name }}</td>
                        <td>
                            <a href="{{ route('team.detail', $team->id) }}" class="btn btn-primary btn-sm">Details</a>
                            <a href="{{ route('team.edit', $team->id) }}" class="btn btn-sm btn-primary">Edit</a>
                            <form method="POST" action="{{ route('team.delete', $team->id) }}" class="d-inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="3" class="text-center"><b>No exist data team</b></td>
                </tr>
            @endif
            </tbody>
        </table>

        <!-- Pagination -->
        {{ $teams->appends(request()->query())->links('pagination::bootstrap-5') }}
    </div>
@endsection
