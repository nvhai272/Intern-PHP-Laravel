@extends('layouts.app')

@section('title', 'Create Project')

@section('content')

    <div class="container d-flex">
        <div class="form-container">
            <h2 class="text-left">Project - Create</h2>
            <form action="{{ route('project.add-confirm') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <b for="adminName" class="form-label">Name:</b>
                    <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror"
                           placeholder="Enter name"
                           value="{{ old('name', session('dataCreateProject.name')) }}">

                    @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="d-flex">
                    <a href="/" class="btn btn-secondary">Back</a>
                    <button type="submit" class="btn btn-success ms-3">Confirm</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Hide error message when typing --}}
    {{--  using addEventListener click or input  --}}
    <script>
        document.getElementById("name").addEventListener("click", function () {
            this.classList.remove("is-invalid");
            let errorFeedback = this.nextElementSibling;
            if (errorFeedback && errorFeedback.classList.contains("invalid-feedback")) {
                errorFeedback.style.display = "none";
            }
        });
    </script>

@endsection
