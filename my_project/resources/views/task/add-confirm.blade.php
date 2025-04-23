@extends('layouts.app')

@section('content')
    <div class="container">
        <div class=" p-4 form-container">

            <h3 class="text-danger text-center mb-5">Are you sure to create this task?</h3>

            @foreach ($task->toArray() as $key => $value)
                @if ($key === 'project_id')
                    <label class="form-label"><strong>Project name:</strong></label>
                    <p>{{ $task->project->name }}</p>
                @elseif ($key === 'task_status')
                    <label class="form-label"><strong>Status:</strong></label>
                    <p>{{ $task->status }}</p>
                @else
                    <label class="form-label"><strong>{{ ucfirst($key) }}:</strong></label>
                    <p>{{ $value }}</p>
                @endif
            @endforeach
        </div>

        <form action="{{ route('task.create') }}" method="POST">
            @csrf
            @foreach ($task->getAttributes() as $key => $value)
                <input class="form-control" type="hidden" name="{{ $key }}" value="{{ e($value) }}">
            @endforeach


            <a href="{{ route('task.form.create') }}" class="ml-3 mt-3 btn btn-secondary">Back</a>
            <button type="submit" class="btn btn-success mt-3">Save</button>
        </form>

    </div>
@endsection
