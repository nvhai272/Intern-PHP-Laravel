@extends('layouts.app')

@section('title', 'Create Team')

@section('content')

    <div class="container d-flex mt-5">
        <div class="form-container">
            <h3 class="text-left">Team - Create</h3>
            <form action="{{ route('team.add-confirm') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="adminName" class="form-label">Name</label>
                    <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror"
                           placeholder="Enter name"
                           value="{{ old('name', session('dataCreateTeam.name')) }}">

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
