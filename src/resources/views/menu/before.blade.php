<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>メニュー</title>
    <link rel="stylesheet" href="{{ asset('css/sanitize.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/menu.css') }}" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400..900;1,400..900&display=swap" rel="stylesheet">
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
                <a>Home</a>
            </div>
            <div class="menu__ttl">
                <a href="{{ route('register') }}">Registration</a>
            </div>
            <div class="menu__ttl">
                <a href="{{ route('login') }}">Login</a>
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