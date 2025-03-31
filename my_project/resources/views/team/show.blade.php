@extends('layouts.app')

@section('content')
    <div class="container">
        <h2>Team Detail</h2>
        <p>ID: {{ $team->id }}</p>
        <p>Name: {{ $team->name }}</p>
        <p>Created By: {{ $team->ins_id }}</p>
        <p>Updated By: {{ $team->upd_id }}</p>
        <p>Created At: {{ $team->ins_datetime }}</p>
        <p>Updated At: {{ $team->upd_datetime }}</p>
    </div>
@endsection
