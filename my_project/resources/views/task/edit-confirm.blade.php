@extends('layouts.app')

@section('content')
    <div class="container mt-5">
        <div class=" p-4 form-container">

            <h3 class="text-danger text-center mb-5">Are you sure to update this Task data?</h3>

            @foreach ($model->toArray() as $key => $value)
                @if ($key === 'project_id')
                    <label class="form-label"><strong>Project name:</strong></label>
                    <p>{{ $model->project->name }}</p>
                @elseif ($key === 'task_status')
                    <label class="form-label"><strong>Status:</strong></label>
                    <p>{{ $model->status }}</p>
                @else
                    <label class="form-label"><strong>{{ ucfirst($key) }}:</strong></label>
                    <p>{{ $value }}</p>
                @endif
            @endforeach
        </div>

        <form action="{{ route('task.edit', ['id' => $id]) }}" method="POST">
            @csrf
            @foreach ($model->getAttributes() as $key => $value)
                <input class="form-control" type="hidden" name="{{ $key }}" value="{{ $value }}">
            @endforeach
            <button type="submit" class="btn btn-success mt-3">OK</button>
            <a href="{{ route('task.form.edit', ['id' => $id]) }}" class="ml-3 mt-3 btn btn-secondary">Back</a>
        </form>

    </div>
@endsection
