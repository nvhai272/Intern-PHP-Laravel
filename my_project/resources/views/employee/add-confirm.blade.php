@extends('layouts.app')

@section('content')
    <div class="container">
        <div class=" p-4 form-container">

            <h3 class="text-danger text-center mb-5">Are you sure to create this Employee data?</h3>

            @foreach ($model->toArray() as $key => $value)
                @if ($key === 'avatar')
                    <label class="form-label"><strong>Avatar:</strong></label>
                    <div>
                        <img src="{{ asset('storage/temp_avatars/' . $value) }}" alt="Avatar"
                            style="width: 120px; height: auto;">
                    </div>
                @elseif ($key === 'team_id')
                    <label class="form-label"><strong>Team:</strong></label>
                    <p>{{$model->team->name}}</p>
                @elseif ($key === 'password')
                    <label class="form-label"><strong>Password:</strong></label>
                    <p>••••••••</p>
                @elseif ($key === 'type_of_work')
                    <label class="form-label"><strong>Type of work:</strong></label>
                    <p>{{ $model->work }}</p>
                @else
                    <label class="form-label"><strong>{{ ucfirst($key) }}:</strong></label>
                    <p>{{ $value }}</p>
                @endif
            @endforeach
        </div>

        <form action="{{ route('emp.create') }}" method="POST">
            @csrf

            @foreach ($model->getAttributes() as $key => $value)
                <input class="form-control" type="hidden" name="{{ $key }}" value="{{ e($value) }}">
            @endforeach


            <a href="{{ route('emp.form.create') }}" class="ml-3 mt-3 btn btn-secondary">Back</a>

            <button type="submit" class="btn btn-success mt-3">Save</button>
        </form>

    </div>
@endsection
