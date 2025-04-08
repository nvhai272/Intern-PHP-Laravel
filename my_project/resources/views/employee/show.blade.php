@extends('layouts.app')

@section('content')
    <div class="mt-3">

        <h1 class="text-center text-danger">Employee Information </h1>
        <p>Team: {{ $emp->team->name }}</p>
        <p>Fullname: {{ $emp->full_name }}</p>
        <p>Sex: {{ $emp->gender}}</p>
        <p>Work: {{ $emp->work}}</p>
        <p>Status working: {{ $emp->status}}</p>
        <p>Position: {{ $emp->position}}</p>
    </div>
@endsection
