<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>メニュー</title>
    <link rel="stylesheet" href="{{ asset('css/sanitize.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/menu.css') }}" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
</head>
<body>
    <header class="header">
        <div class="header__ttl">
            <a class="header__title" id="back-button" href="#"><i class="fas fa-square fa-times fa-xs fa-2x" style= "color: #fff;"></i></a>
        </div>
    </header>
    <main class="main">
        <div class="menu">
            <div class="menu__ttl">
                <a href="{{ route('home') }}">Home</a>
            </div>
            <div class="button">
                <form action="{{ route('logout') }}" method="POST">
                @csrf
                    <button type="submit" class="button__title">Logout</button>
                </form>
            </div>
            <div class="menu__ttl">
                <a href="{{ route('mypage') }}">Mypage</a>
            </div>
        </div>
    </main>
    <script>
    document.getElementById('back-button').addEventListener('click', function(event) {
        event.preventDefault();
        history.back();
    });
    </script>
</body>
</html>