<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>レビュー一覧</title>
    <link rel="stylesheet" href="{{ asset('css/sanitize.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/shops/reviews.css') }}" />
</head>
<body>
    <header class="header">
        <div class="header__ttl">
            <div class="header__title">
                <p class="header__item">評価一覧</p>
            </div>
            <nav class="nav">
                <div class="nav__button">
                    <a class="button" href="{{ route('shops.reservations.list')}}" >予約一覧</a>
                </div>
                <div class="nav__button">
                    <a class="button" href="{{ route('shops.create-update', ['id' => $shopId]) }}">店舗情報の作成・更新</a>
                </div>
                <div class="nav__button">
                    <a class="button" href="{{ route('shop.verify.show') }}">QRコード照合</a>
                </div>
                <div class="nav__button">
                    <a class="button" href="{{ route('shops.upload') }}">アップロード</a>
                </div>
                <div class="logout">
                    <form action="{{ route('shop.logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="logout__button">Logout</button>
                    </form>
                </div>
            </nav>
        </div>
    </header>
    <main class="main">
        <div class="main__ttl">
            <table class="table">
                <thead class="thead">
                    <tr class="table__tr">
                        <th class="table__th">投稿日時</th>
                        <th class="table__th">評価</th>
                        <th class="table__th">コメント</th>
                    </tr>
                </thead>
                <tbody class="tbody">
                    @foreach($reviews as $review)
                    <tr class="table__tr">
                        <td class="table__td">{{ $review->created_at }}</td>
                        <td class="table__td">{{ $review->rating }}</td>
                        <td class="table__td">{{ $review->comment }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        {{ $reviews->links('page.custom-pagination') }}
    </main>
</body>
</html>
