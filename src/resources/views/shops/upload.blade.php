<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>画像アップロード</title>
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
                    <a class="button" href="{{ route('shops.reservations.list') }}">予約一覧</a>
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
    <div class="message" id="message"></div>
    <main class="main">
        <div class="upload">
            <p class="upload-title">もし使いたい画像があればここでアップロードしてください</p>
            <form id="uploadForm" enctype="multipart/form-data">
                @csrf
                <input type="file" name="image" id="imageInput">
                <div class="upload-button">
                    <button type="submit">アップロード</button>
                </div>
            </form>
        </div>
    </main>
    <script>
        document.getElementById('uploadForm').addEventListener('submit', uploadImage);

        function uploadImage(event) {
            event.preventDefault();

            const imageInput = document.getElementById('imageInput');
            const messageDiv = document.getElementById('message');
            messageDiv.innerHTML = '';

            if (!imageInput.files.length) {
                messageDiv.innerHTML = '<div class="error">画像ファイルを選択してください。</div>';
                return;
            }

            var formData = new FormData();
            formData.append('image', imageInput.files[0]);

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
                if (data.success) {
                    messageDiv.innerHTML = `<div class="success">${data.message}</div>`;
                } else {
                    messageDiv.innerHTML = `<div class="error">${data.message}</div>`;
                }
            })
            .catch(error => {
                console.error('エラー:', error);
                messageDiv.innerHTML = `<div class="error">画像のアップロード中にエラーが発生しました</div>`;
            });
        }
    </script>
</body>
</html>
