@extends('layouts.add')

@section('css')
<link rel="stylesheet" href="{{ asset('css/show.css')}}">
@endsection

@section('content')
<div class="main">
    <div class="main__title">
        <p class="evaluation__title">評価ページ</p>
    </div>
    @if ($errors->any())
    <div>
        @foreach ($errors->all() as $error)
            <p class="error">{{ $error }}</p>
        @endforeach
    </div>
    @endif
    <form action="{{ route('evaluation.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
        <input type="hidden" name="shop_id" value="{{ $shop->id }}">
        <div class="main__ttl">
            <div class="evaluation__item">
                <p class="evaluation__about-second">{{ $shop->shop_name }}はどうでしたでしょうか？</p>
            </div>
            <div class="evaluation__rating">
                <p class="evaluation__rating-title">評価を選択してください</p>
                <div class="star-rating">
                    @for ($i = 1; $i <= 5; $i++)
                        <i class="fas fa-star {{ $review && $review->rating >= $i ? 'checked' : '' }}" data-rating="{{ $i }}"></i>
                    @endfor
                    <input type="hidden" name="rating" id="rating" value="{{ $review ? $review->rating : '' }}">
                </div>
            </div>
            <div class="evaluation__comment">
                <p class="evaluation__comment-title">コメントを入力してください</p>
                <textarea name="comment" class="evaluation__comment-field" rows="4" placeholder="ここにコメントをお願いします。">{{ $review ? $review->comment : '' }}</textarea>
            </div>
            <div class="main__image">
                <p class="image__title">画像の追加</p>
                <label for="image" class="file-upload-label">
                    <div class="file-upload-text" id="drop-zone">
                        <p class="image__item">クリックして写真を追加</p>
                        <p class="image__item-drop">またはドラッグ＆ドロップ</p>
                        @if ($review && $review->image)
                            <p id="existing-image-text" class="image__name">現在使用中の画像: {{ basename($review->image) }}</p>
                        @endif
                    </div>
                    <input type="file" name="image" id="image" class="form-image" accept="image/*">
                </label>
            </div>
            <div class="button">
                <button type="submit" class="button__title">送信</button>
            </div>
        </div>
    </form>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const stars = document.querySelectorAll('.fas.fa-star');
        const ratingInput = document.getElementById('rating');
        const dropZone = document.getElementById('drop-zone');
        const fileInput = document.getElementById('image');

        stars.forEach(star => {
            star.addEventListener('click', function() {
                let rating = parseInt(this.getAttribute('data-rating'));
                ratingInput.value = rating;
                stars.forEach(star => {
                    if (parseInt(star.getAttribute('data-rating')) <= rating) {
                        star.classList.add('checked');
                    } else {
                        star.classList.remove('checked');
                    }
                });
            });
        });

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
                updateFileNameDisplay(files);

                const dataTransfer = new DataTransfer();
                for (const file of files) {
                    dataTransfer.items.add(file);
                }
                fileInput.files = dataTransfer.files;
            }
        });

        fileInput.addEventListener('change', function(event) {
            updateFileNameDisplay(event.target.files);
        });

        function updateFileNameDisplay(files) {
            const imageItems = dropZone.querySelectorAll('.image__item');
            const imageItemDrop = dropZone.querySelector('.image__item-drop');

            if (files.length > 0) {
                const fileName = files[0].name;
                if (imageItems.length > 0) {
                    imageItems[0].textContent = `ファイルが選択されました: ${fileName}`;
                    imageItems[0].style.display = 'block';

                    if (imageItemDrop) {
                        imageItemDrop.style.display = 'none';
                    }
                }
            } else {
                if (imageItems.length > 0) {
                    imageItems[0].textContent = 'クリックして写真を追加';
                    imageItems[0].style.display = 'block';

                    if (imageItemDrop) {
                        imageItemDrop.style.display = 'block';
                    }
                }
            }
        }
    });
</script>
@endsection

