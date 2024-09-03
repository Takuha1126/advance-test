<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>代表者登録</title>
    <link rel="stylesheet" href="{{ asset('css/sanitize.css') }}" />
    <link rel="stylesheet" href="{{ asset('css/admin/import.css') }}" />
</head>
<body>
    <header class="header">
        <div class="header__ttl">
            <div class="header__title">
                <p class="header__item">CSVインポート</p>
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
            <p class="main__title">CSVインポート</p>
            @if(session('error'))
                <p class="error">{{ session('error') }}</p>
            @endif
            @if(session('success'))
                <p class="success">{{ session('success') }}</p>
            @endif
            <form action="{{ route('import') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="main__upload">
                    <label for="csv_file" class="file__upload-label">
                        <input type="file" id="csv_file" name="csv_file" accept=".csv"  onchange="updateFileName()">
                        <span id="file_name" class="file__upload-text">ファイルを選択してください</span>
                        <div id="drop_zone" class="drop-zone">
                            <p class="drop__title"></p>
                        </div>
                    </label>
                </div>
                @error('csv_file')
                    <p class="text-danger">{{ $message }}</p>
                @enderror
                <div class="button">
                    <button type="submit" class="main__button">インポート</button>
                </div>
            </form>
        </div>
    </main>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const dropZone = document.getElementById('drop_zone');
        const fileInput = document.getElementById('csv_file');

        dropZone.addEventListener('dragover', function(event) {
            event.preventDefault();
            event.stopPropagation();
            dropZone.classList.add('dragover');
        });

        dropZone.addEventListener('dragleave', function(event) {
            event.preventDefault();
            event.stopPropagation();
            dropZone.classList.remove('dragover');
        });

        dropZone.addEventListener('drop', function(event) {
            event.preventDefault();
            event.stopPropagation();
            dropZone.classList.remove('dragover');

            const files = event.dataTransfer.files;
            if (files.length > 0) {
                fileInput.files = files;
                updateFileName();
            }
        });

        fileInput.addEventListener('change', function(event) {
            updateFileName();
        });

        function updateFileName() {
            const fileName = fileInput.files[0] ? `${fileInput.files[0].name} が選択されています` : 'ファイルを選択してください';
            document.getElementById('file_name').textContent = fileName;
        }
    });
</script>

</body>
</html>
