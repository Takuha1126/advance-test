<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>アップロード</title>
    <link rel="stylesheet" href="{{ asset('css/sanitize.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/shops/upload.css') }}" />
</head>
<body>
    <header class="header">
        <div class="header__ttl">
            <div class="header__title">
                <p class="header__item">アップロード</p>
            </div>
            <nav class="nav">
                <div class="nav__button">
                    <a class="button" href="{{ route('shops.reservations.list')}}" >予約一覧</a>
                </div>
                <div class="nav__button">
                    <a class="button" href="{{ route('shops.create-update', ['id' => $shopId]) }}">店舗情報の更新</a>
                </div>
                <div class="nav__button">
                    <a class="button" href="{{ route('reviews.create') }}">評価一覧</a>
                </div>
                <div class="nav__button">
                    <a class="button" href="{{ route('shop.verify.show') }}">照合</a>
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
    @if(session('success'))
    <div class="success-message">
        {{ session('success') }}
    </div>
    @endif
    <main class="main">
        <div class="upload">
            <p class="upload-title">もし使いたい画像があればここでアップロードしてください</p>
            <form action="{{ route('upload.image') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="file" name="image" id="imageInput">
                <div class="upload-button">
                    <button type="submit" onclick="uploadImage()">アップロード</button>
                </div>
            </form>
        <div>
    </main>
    <script>
    function uploadImage() {
    event.preventDefault();

    var formData = new FormData();
    formData.append('image', document.getElementById('imageInput').files[0]);
    formData.append('path', 'atte-ui');

    fetch('{{ route("upload.image") }}', {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('ネットワークの応答が正常ではありませんでした');
        }
        return response.json();
    })
    .then(data => {
        alert(data.message);
    })
    .catch(error => {
        console.error('エラー:', error);
        alert('画像のアップロード中にエラーが発生しました');
    });
    }

    </script>
</body>
</html>