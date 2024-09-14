<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>代表者登録</title>
    <link rel="stylesheet" href="{{ asset('css/sanitize.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/admin/feedbacks.css') }}" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
</head>
<body>
    <header class="header">
        <div class="header__ttl">
            <div class="header__title">
                <p class="header__item">口コミ閲覧</p>
            </div>
            <nav class="nav">
                <div class="nav__button">
                    <a class="button" href="{{ route('admin.create') }}">代表者一覧</a>
                </div>
                <div class="nav__button">
                    <a class="button" href="{{ route('send-notification.form') }}">メール送信</a>
                </div>
                <div class="nav__button">
                    <a class="button" href="{{ route('import.form') }}">CSVインポート</a>
                </div>
                <div class="nav__button">
                    <a class="button" href="{{ route('shops.showShops') }}">口コミ</a>
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
    <main class="main">
        <div class="main__ttl">
            <p class="main__title">{{ $shop->shop_name }}の全ての口コミ</p>
        </div>
        <div class="main__item">
            @forelse ($reviews as $review)
                <div class="feedback__item">
                    <div class="feedback__actions">
                        <form action="{{ route('feedback.adminDestroy', $review->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="feedback__delete">口コミを削除</button>
                        </form>
                    </div>
                    @if ($review->image)
                        <img src="{{ asset('storage/review_images/' . $review->image) }}" alt="口コミ画像" class="feedback__image">
                    @endif
                    <div class="feedback__rating">
                        @for ($i = 1; $i <= 5; $i++)
                            <i class="fas fa-star {{ $i <= $review->rating ? 'filled' : 'empty' }}"></i>
                        @endfor
                    </div>
                    <p class="feedback__comment">{{ $review->comment }}</p>
                </div>
            @empty
                <div class="feedback__message">
                    <p class="no__feedback-message">まだ口コミがありません。</p>
                </div>
            @endforelse
        </div>
    </main>
</body>
</html>
