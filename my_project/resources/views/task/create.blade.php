@extends('layouts.app')

@section('title', 'Create Task')

@section('content')

    <div class="container d-flex">
        <div class="form-container">
            <h2 class="text-left">Task - Create</h2>
            <form action="{{ route('task.add-confirm') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <b for="team_id" class="form-label font-weight-bold">Project:</b>

                    <select name="project_id" id="project_id" class="form-select  @error('project_id') is-invalid @enderror"">
                        <option value="">Select here</option>
                        @php
                            $selectedProject = old('project_id', session('dataCreateTask.project_id') ?? '');
                        @endphp

{{-- {{dd($projects)}} --}}
                        @foreach ($projects as $project)
                            <option value="{{ $project->id }}" {{ $selectedProject == $project->id ? 'selected' : '' }}>
                                {{ $project->name }}
                            </option>
                        @endforeach

                    </select>

                    @error('project_id')
                        <small class="text-danger invalid-feedback">
                            <b>{{ $message }}</b>
                        </small>
                    @enderror

                </div>

                <div class="mb-3">
                    <b for="status" class="form-label">Status:</b>

                    <select name="task_status" id="task_status" class="form-select  @error('task_status') is-invalid @enderror" >
                        <option value="">Select here</option>
                        <option value="1" {{ old('task_status', session('dataCreateTask.task_status') ?? '') == '1' ? 'selected' : '' }}>
                            Not doing task</option>
                        <option value="2" {{ old('task_status', session('dataCreateTask.task_status') ?? '') == '2' ? 'selected' : '' }}>
                            Doing task</option>
                        <option value="3" {{ old('task_status', session('dataCreateTask.task_status') ?? '') == '3' ? 'selected' : '' }}>
                            Finish task</option>

                    </select>

                    @error('task_status')
                        <small class="text-danger invalid-feedback">
                            <b>{{ $message }}</b>
                        </small>
                    @enderror
                </div>

                <div class="mb-3">
                    <b for="adminName" class="form-label">Name:</b>
                    <input type="text" name="name" id="name"
                        class="form-control @error('name') is-invalid @enderror" placeholder="Enter name"
                        value="{{ old('name', session('dataCreateTask.name')) }}">

                        @error('name')
                        <small class="text-danger invalid-feedback">
                            <b>{{ $message }}</b>
                        </small>
                    @enderror
                </div>

                <div class="d-flex">
                    <a href="/" class="btn btn-secondary">Back</a>
                    <button type="submit" class="btn btn-success ms-3">Confirm</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        const fields = ["name", "project_id", "task_status"];
        fields.forEach(function(id) {
            const element = document.getElementById(id);
            if (element) {
                element.addEventListener("click", function () {
                    this.classList.remove("is-invalid");
                    let errorFeedback = this.nextElementSibling;
                    if (errorFeedback && errorFeedback.classList.contains("invalid-feedback")) {
                        errorFeedback.style.display = "none";
                    }
                });
            }
        });
    </script>


@endsection
