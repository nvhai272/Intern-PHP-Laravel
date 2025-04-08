@extends('layouts.app')

@section('content')
    <div class="container">
        <div class=" p-4 form-container">

            <h3 class="text-danger text-center mb-5">Are you sure to edit this Employee data?</h3>

            {{-- {{dd($validated)}} --}}
            @foreach ($validated as $key => $value)
                @if ($key === 'avatar' && session('new_avatar'))
                    <label class="form-label"><strong>Avatar:</strong></label>
                    <div>
                        <img src="{{ asset('storage/temp_avatars/' . $value) ??  asset('storage/avatars/' . $value) }}" alt="Avatar"
                            style="width: 120px; height: auto;">
                    </div>
                @elseif ($key === 'password')
                    {{-- <label class="form-label"><strong>Password:</strong></label> --}}
                    {{-- <p>••••••••</p> --}}
                @else
                    <label class="form-label"><strong>{{ ucfirst($key) }}:</strong></label>
                    <p>{{ $value }}</p>
                @endif
            @endforeach
        </div>

        <form action="{{ route('emp.edit', ['id' => $id]) }}" method="POST">
            @csrf

            @foreach ($validated as $key => $value)
                <input class="form-control" type="hidden" name="{{ $key }}" value="{{ $value }}">
            @endforeach


            <a href="{{ route('emp.form.edit', ['id' => $id]) }}" class="ml-3 mt-3 btn btn-secondary">Back</a>

            <button type="submit" class="btn btn-success mt-3">Save</button>
        </form>

    </div>
@endsection
