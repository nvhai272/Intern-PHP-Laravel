@extends('layouts.app')

@section('title', 'Edit Task Page')

@section('content')

    <div class="container d-flex">
        <div class="form-container">
            <h3 class="text-left">Edit Task</h3>
            <form action="{{ route('task.edit-confirm') }}" method="POST">
                @csrf

                <div class="mb-3">
                    <b for="project_id" class="form-label font-weight-bold">Project:</b>
                    <select name="project_id" id="project_id" class="form-select @error('project_id') is-invalid @enderror">
                        <option value="">Select here</option>
                        @php
                            $selectedProject = session('dataEditTask.project_id') ?? ($task['project_id'] ?? '');
                        @endphp

                        @foreach ($projects as $project)
                            <option value="{{ $project->id }}" {{ $selectedProject == $project->id ? 'selected' : '' }}>
                                {{ $project->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('project_id')
                        <small class="text-danger invalid-feedback d-block">
                            <b>{{ $message }}</b>
                        </small>
                    @enderror

                </div>

                <div class="mb-3">
                    {{-- <label for="id" class="form-label">ID:</label> --}}
                    <input type="text" name="id" value="{{ $task->id }}" readonly class="form-control" hidden>

                    <b for="name" class="form-label">Name:</b>
                    <input type="text" name="name" id="name"
                        class="form-control @error('name') is-invalid @enderror" placeholder="Enter name"
                        value="{{ session('dataEditTask.name') ?? $task->name }}">
                    @error('name')
                        <small class="text-danger invalid-feedback d-block">
                            <b>{{ $message }}</b>
                        </small>
                    @enderror
                </div>

                <div class="mb-3">
                    <b for="status" class="form-label">Status:</b>

                    <select name="task_status" id="task_status"
                        class="form-select  @error('task_status') is-invalid @enderror">
                        <option value="">Select here</option>
                        <option value="1"
                            {{ (session('dataEditTask.task_status') ?? $task->task_status) == '1' ? 'selected' : '' }}>
                            Not doing task</option>
                        <option value="2"
                            {{ (session('dataEditTask.task_status') ?? $task->task_status) == '2' ? 'selected' : '' }}>
                            Doing task</option>
                        <option value="3"
                            {{ (session('dataEditTask.task_status') ?? $task->task_status) == '3' ? 'selected' : '' }}>
                            Finish task</option>
                    </select>

                    @error('task_status')
                        <small class="text-danger invalid-feedback">
                            <b>{{ $message }}</b>
                        </small>
                    @enderror
                </div>

                <div class="d-flex">
                    <a href="{{ route('task.list') }}" class="btn btn-secondary">Back</a>
                    <button type="submit" class="btn btn-success ms-3">Edit</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Hide error message when typing --}}
    {{--  using addEventListener click or input  --}}
    <script>
        document.getElementById("name").addEventListener("click", function() {
            this.classList.remove("is-invalid");
            let errorFeedback = this.nextElementSibling;
            if (errorFeedback && errorFeedback.classList.contains("invalid-feedback")) {
                errorFeedback.style.display = "none";
            }
        });
    </script>

@endsection
