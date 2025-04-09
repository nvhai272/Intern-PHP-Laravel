@extends('layouts.app')

@section('content')
    <div class="container">

        @if (session('loginErr'))
            <div class="alert alert-success" id="show">{{ session('loginErr') }}</div>
        @endif

        <script>
            setTimeout(() => {
                let alertBox = document.getElementById('show');
                if (alertBox) {
                    alertBox.style.display = 'none';
                }
            }, 1500);
        </script>

        <h2>Login</h2>

        {{--        @if ($errors->any()) --}}
        {{--            <div class="alert alert-danger"> --}}
        {{--                @foreach ($errors->all() as $error) --}}
        {{--                    <p>{{ $error }}</p> --}}
        {{--                @endforeach --}}
        {{--            </div> --}}
        {{--        @endif --}}

        <form action="{{ route('login') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label>Email:</label>
                <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                    value="{{ old('email', request()->cookie('remembered_email')) }}" placeholder="Enter email">

                {{--                @if ($errors->has('email')) --}}
                {{--                    <p class="text-danger">{{ $errors->first('email') }} </p> --}}
                {{--                @endif --}}

                {{--        withErrors()        --}}
                @error('email')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label>Password:</label>
                <input type="password" name="password" class="form-control  @error('password') is-invalid @enderror"
                    placeholder="Enter password">

                {{--        cách 1        --}}
                {{--                @if ($errors->has('password')) --}}
                {{--                    <p class="text-danger">{{ $errors->first('password') }} </p> --}}
                {{--                @endif --}}

                {{--        cách 2        --}}
                @error('password')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>
            <label>
                <input type="checkbox" name="remember_username"
                    {{ request()->cookie('remembered_email') ? 'checked' : '' }}>
                Save Account
            </label> <br><br>
            <h3>hehe@example.com</h3>
            <h3>Pass: password123</h3>
            <br>
            <button type="submit" class="btn btn-primary">Login</button>
        </form>


    </div>
    <script>
        document.querySelectorAll("input.is-invalid").forEach(input => {
            input.addEventListener("click", function() {
                this.classList.remove("is-invalid");
                let errorFeedback = this.nextElementSibling;
                if (errorFeedback && errorFeedback.classList.contains("text-danger")) {
                    errorFeedback.style.display = "none";
                }
            });
        });
    </script>
@endsection
