@extends('layouts.app')

@section('title', 'Home Page')

@section('content')

    @if (session('msg'))
        <div id="show" class="alert alert-success text-center">{{ session('msg') }}!</div>
    @endif

    @if (session('accountLogin'))
        <h1 class="text-center text-primary">
            Hello, {{ session('accountLogin')->first_name }} {{ session('accountLogin')->last_name }}! How are U?
        </h1>
    @else
        <h1 class="text-center text-primary"> {{ MSG_WELCOME }} </h1>
    @endif

    <script>
        setTimeout(() => {
            let alertBox = document.getElementById('show');
            if (alertBox) {
                alertBox.style.display = 'none';
            }
        }, 3000);
    </script>
@endsection
