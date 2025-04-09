@extends('layouts.app')

@section('content')
<div class="container">
    <div class="p-4 form-container">
        <h3 class="text-danger text-center mb-5">Are you sure to edit this Employee data?</h3>

        {{-- Avatar --}}
        <label class="form-label"><strong>Avatar:</strong></label>
        <div>
            @php
                $value = $model->avatar;
                $tempPath = 'temp_avatars/' . $value;
                $avatarPath = 'avatars/' . $value;

                $pathToShow = null;

                if (Storage::disk('public')->exists($tempPath)) {
                    $pathToShow = asset('storage/' . $tempPath);
                } elseif (Storage::disk('public')->exists($avatarPath)) {
                    $pathToShow = asset('storage/' . $avatarPath);
                }
            @endphp

            <img src="{{ $pathToShow }}" alt="Avatar" style="width: 120px; height: auto;">
        </div>

        {{-- Các trường khác --}}
        {{-- @foreach ($model->getAttributes() as $key => $value) --}}
        @foreach ($model->toArray() as $key => $value)

            @if ($key === 'password' || $key === 'avatar'||$key === 'type_of_work')
                @continue
            @endif

            @if($key ==='team_id')
            <label class="form-label"><strong>{{ ucfirst($key) }}:</strong></label>
            <p>{{ $model->team->name}}</p>
            @endif

            @if($key ==='type_of_work')
            <label class="form-label"><strong>{{ ucfirst($key) }}:</strong></label>
            <p>{{ $model->work}}</p>
            @endif

            <label class="form-label"><strong>{{ ucfirst($key) }}:</strong></label>
            <p>{{ $value }}</p>
        @endforeach
    </div>

    <form action="{{ route('emp.edit', ['id' => $id]) }}" method="POST">
        @csrf
        @foreach ($model->getAttributes() as $key => $value)
            <input class="form-control" type="hidden" name="{{ $key }}" value="{{ $value }}">
        @endforeach

        <a href="{{ route('emp.form.edit', ['id' => $id]) }}" class="ml-3 mt-3 btn btn-secondary">Back</a>
        <button type="submit" class="btn btn-success mt-3">Save</button>
    </form>
</div>
@endsection
