@extends('layouts.app')

@section('content')
    <div class="container mt-5">
        <div class=" p-4 form-container">

            <h3 class="text-danger text-center mb-5">Are you sure to create this Team data?</h3>

            @foreach ($validated as $key => $value)
                    <label class="form-label "><strong>{{ ucfirst($key) }}:</strong></label>
                <p>{{$value}}</p>
            @endforeach
        </div>

            <form action="{{ route('team.create') }}" method="POST">
                @csrf
                @foreach ($validated as $key => $value)
                    <input class="form-control" type="hidden" name="{{ $key }}" value="{{ $value }}">
                @endforeach
                <button type="submit" class="btn btn-success mt-3">Save</button>
                <a href="{{ route('team.form.create') }}" class="ml-3 mt-3 btn btn-secondary">Back</a>
            </form>

    </div>
@endsection
