@extends('layouts.app')

@section('content')
    <div class="row mb-0">
        <div class="col-md-6 mb-0">
            <h3>
                <a href="{{ route('project.detail', $task->project->id) }}">
                    Project {{ $task->project->name }}
                </a>
                &gt; Task {{ $task->name }}
            </h3>

        </div>
        <div class="col-md-6 mb-0 text-end text-danger mb-3"><b class="">Status:</b> {{$task->status}}</div>


        <div class="col-md-6 mb-0">
            <b>Created by: {{ $task->createBy }}</b>
        </div>
        <div class="col-md-6 mb-0 text-primary text-end">
            <p>Created at {{ $task->ins_datetime }} and last updated at {{ $task->upd_datetime }}</p>
        </div>
    </div>

    <table class="table table-bordered">
        <thead>
            <tr class="table-primary text-center">
                <th>Employee Name</th>
                <th>Team</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($employees as $i => $emp)
                <tr class="{{ $loop->odd ? 'table-warning' : '' }}">
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
{{ $employees->appends(['section' => 'emp'])->links('pagination::bootstrap-5') }}


@endsection
