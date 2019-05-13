<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" type="image/x-icon" href="{{asset('img/logo.png')}}" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Aplikasi Kompetensi Mahasiswa</title>
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body background="{{asset('img/bg.jpg')}}">
    <div class="line-top">
        <main class="py-4">
            @yield('content')
        </main>
    </div>
    @yield('script')
</body>
</html>
