@extends('layouts.app')

@section('title', 'Edit Team Page')

@section('content')

    <div class="container d-flex">
        <div class="form-container">
            <h3 class="text-left">Edit Team</h3>
            <form action="{{ route('team.edit-confirm') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="id" class="form-label">ID:</label>
                    <input type="text" name="id" value="{{$team->id}}" readonly class="form-control">
                    <label for="name" class="form-label">Name:</label>
                    <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror"
                           placeholder="Enter name"
                           value=" {{$team->name}}">

                    @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
                <div class="d-flex">
                    <a href="{{route('team.list')}}" class="btn btn-secondary">Back</a>
                    <button type="submit" class="btn btn-success ms-3">Edit</button>
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
