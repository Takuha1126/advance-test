<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>代表者登録</title>
    <link rel="stylesheet" href="{{ asset('css/sanitize.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/admin/list.css') }}" />
</head>
<body>
    <header class="header">
        <div class="header__ttl">
            <div class="header__title">
                <p class="header__item">口コミ</p>
            </div>
            <nav class="nav">
                <div class="nav__button">
                    <a class="button" href="{{ route('admin.index') }}">代表者登録</a>
                </div>
                <div class="nav__button">
                    <a class="button" href="{{ route('admin.create') }}">代表者一覧</a>
                </div>
                <div class="nav__button">
                    <a class="button" href="{{ route('send-notification.form') }}">メール送信</a>
                </div>
                <div class="nav__button">
                    <a class="button" href="{{ route('import.form') }}">CSVインポート</a>
                </div>
                <div class="logout">
                    <form action="{{ route('admin.logout') }}"  method="POST">
                        @csrf
                        <button type="submit" class="logout__button">Logout</button>
                    </form>
                </div>
            </nav>
        </div>
    </header>
    <div class="main__list">
        <p class="list__title">口コミを閲覧したい店舗を選択してください</p>
        <div class="list__container">
            @foreach ($shops as $shop)
                <div class="list__item">
                    <a href="{{ route('shops.feedbacks', $shop->id) }}">
                        <p class="list_name">{{ $shop->shop_name }}</p>
                    </a>
                </div>
            @endforeach
        </div>
    </div>
</body>
</html>
