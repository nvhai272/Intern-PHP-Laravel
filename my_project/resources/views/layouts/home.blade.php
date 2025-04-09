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

    <p>Case timeout khi vào màn list team -> 5s</p>
    <p>Gửi mail tự động sau khi create or update employee -> sử dụng observer lắng nghe và tạo job đưa vào queue -> hiện tại
        đang gửi luôn do cấu hình sync trong .env</p>
    <p>
        Xuất file CSV có xử lí và thông báo không không tải được file xuống nếu không có data
    </p>
    <p>
        Xử lí middleware cho timeout, login, xóa session ảnh và thông tin không được sử dụng
    </p>

    <p> Ghi log khi có lỗi hành động và log thông báo hành động </p>

    <p>Đăng nhập nhiều tab và trên nhiều broswer login tài khoản cũ -> Chưa làm được</p>

    <script>
        setTimeout(() => {
            let alertBox = document.getElementById('show');
            if (alertBox) {
                alertBox.style.display = 'none';
            }
        }, 3000);
    </script>
@endsection
