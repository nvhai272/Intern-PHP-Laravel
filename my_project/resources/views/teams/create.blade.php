@extends('layouts.app')

@section('title', 'Create Team Page')

@section('content')

    <div class="container d-flex">
        <div class="form-container">
            <h3 class="text-left">Create New Team</h3>
            <form action="{{ route('team.confirm') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="adminName" class="form-label">Name</label>
                    <input type="text" name="name" class="form-control" placeholder="Enter name">
                </div>
            </form>
            <div class="d-flex">
                <a href="#" class="btn btn-secondary">Back</a>
                <button type="submit" class="btn btn-success ms-3">Create</button>
            </div>
        </div>
    </div>

@endsection
