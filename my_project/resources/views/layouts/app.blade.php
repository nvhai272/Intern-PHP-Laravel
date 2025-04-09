<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Laravel')</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
</head>
<body>

@include('layouts.header')

<div class="container" style="padding-top: 70px; padding-bottom: 50px;">
    @yield('content')
</div>

@include('layouts.footer')

{{-- Tự động logout nếu session hết hạn --}}
@auth
<script>
    const sessionLifetime = {{ config('session.lifetime') }}; // phút
    const loginTime = {{ now()->timestamp }}; // lấy thời điểm hiện tại

    setInterval(() => {
        const now = Math.floor(Date.now() / 1000); // giây
        const diff = (now - loginTime) / 60; // phút

        if (diff >= sessionLifetime) {
            alert("Session expired. Logging out...");
            window.location.href = "{{ route('logout') }}";
        }
    }, 30000); // Check mỗi 30s
</script>
@endauth

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
