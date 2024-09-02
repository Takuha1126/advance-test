@extends('layouts.add')

@section('css')
<link rel="stylesheet" href="{{ asset('css/feedbacks/feedback.css')}}">
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet" />
@endsection

@section('content')
<div class="main">
    <form action="{{ route('feedbacks.storeFeedback', $shop->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="main__ttl">
            <div class="main__item">
                <div class="main__title">
                    <p class="main__title-ttl">今回のご利用はいかがでしたか？</p>
                </div>
                <div class="main__group">
                    <div class="card">
                        <img src="{{ $shop->photo_url }}">
                    </div>
                    <div class="main__content">
                        <p class="content__title">{{ $shop->shop_name }}</p>
                        <div class="content__tag">
                            <p class="content__area">#{{ $shop->area->area_name }}</p>
                            <p class="content__genre">#{{ $shop->genre->genre_name }}</p>
                        </div>
                        <div class="button">
                            <div class="button__ttl">
                                <a href="{{ route('detail', ['shop_id' => $shop->id]) }}" class="button__title" data-shop-id="{{ $shop->id }}">詳しく見る</a>
                                <a href="{{ route('evaluation.show', ['shopId' => $shop->id]) }}" class="button__rating" data-shop-id="{{ $shop->id }}">評価</a>
                            </div>
                            <button class="heart-button" data-shop-id="{{ $shop->id }}">
                                &#10084;
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="main__about">
                <div class="main__feedback-form">
                    <div class="main__rating">
                        <p class="rating__title">体験を評価してください</p>
                        <div class="star__rating">
                            <i class="fas fa-star" data-rating="1"></i>
                            <i class="fas fa-star" data-rating="2"></i>
                            <i class="fas fa-star" data-rating="3"></i>
                            <i class="fas fa-star" data-rating="4"></i>
                            <i class="fas fa-star" data-rating="5"></i>
                            <input type="hidden" name="rating" id="rating" value="">
                        </div>
                    </div>
                    @error('rating')
                        <p class="error">{{ $message }}</p>
                    @enderror
                    <div class="main__comment">
                        <p class="comment__title">口コミを投稿</p>
                        <textarea name="comment" id="comment" class="form-control" rows="4" placeholder="カジュアルな夜のお出かけにおすすめのスポット"></textarea>
                        <span class="comment__count" id="char-count">0/400 (最高文字数)</span>
                    </div>
                    @error('comment')
                        <p class="error">{{ $message }}</p>
                    @enderror
                    <div class="main__image">
                        <p class="image__title">画像の追加</p>
                        <label for="image" class="file-upload-label">
                            <div class="file-upload-text" id="drop-zone">
                                <p>クリックして写真を追加</p>
                                <p>またはドラッグ＆ドロップ</p>
                            </div>
                            <input type="file" name="image" id="image" class="form-image" accept="image/*">
                        </label>
                    </div>
                    @error('image')
                        <p class="error">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>
        <div class="main__button">
            <button type="submit" class="custom-btn">口コミを投稿</button>
        </div>
    </form>
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
    const textarea = document.getElementById('comment');
    const charCount = document.getElementById('char-count');
    const dropZone = document.getElementById('drop-zone');
    const fileInput = document.getElementById('image');

    textarea.addEventListener('input', function() {
        const currentLength = textarea.value.length;
        charCount.textContent = `${currentLength}/400 (最高文字数)`;
    });

    fetchFavoritesAndUpdateHearts();
    setupHeartButtons();
    setupSelect2();
    setupStarRating();
    setupDragAndDrop();

    function setupHeartButtons() {
        $('.heart-button').on('click', function(event) {
            event.preventDefault();
            const shopId = $(this).data('shopId');
            if (shopId) {
                toggleFavorite($(this), shopId);
            } else {
                console.error('Shop ID is undefined');
            }
        });
    }

    function fetchFavoritesAndUpdateHearts() {
        const favorites = JSON.parse(localStorage.getItem('favorites')) || {};

        $('.heart-button').each(function() {
            const shopId = $(this).data('shopId');
            updateFavoriteStatus($(this), shopId, favorites);

            fetch(`/favorite/status/${shopId}`, {
                method: 'GET',
                headers: {
                    'Content-Type': 'application/json',
                }
            })
            .then(response => response.json())
            .then(data => {
                updateFavoriteStatus($(this), shopId, favorites, data.isFavorite);
            })
            .catch(error => {
                console.error('Error fetching favorite status:', error);
            });
        });
    }

    function updateFavoriteStatus(button, shopId, favorites, isFavorite = favorites[shopId] || false) {
        button.toggleClass('liked', isFavorite);
        favorites[shopId] = isFavorite;
        localStorage.setItem('favorites', JSON.stringify(favorites));
    }

    function toggleFavorite(button, shopId) {
        const isLiked = button.hasClass('liked');
        const method = isLiked ? 'DELETE' : 'POST';

        fetch(`/favorite/toggle/${shopId}`, {
            method: method,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ shop_id: shopId })
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            updateFavoriteStatus(button, shopId, JSON.parse(localStorage.getItem('favorites')), method === 'POST');
        })
        .catch(error => {
            console.error('Error:', error);
            alert('お気に入りの操作に失敗しました。');
        });
    }

    function setupSelect2() {
        $('.first, .second').select2({
            minimumResultsForSearch: Infinity,
            templateSelection: function(data) {
                return $('<span>').css('font-size', '13px').text(data.text);
            }
        });

        $('.select2-selection__arrow').css('display', 'none');
    }

    function setupStarRating() {
        let rating = $('#rating').val() || 0;
        $('.star__rating .fas').on('click', function() {
            rating = $(this).data('rating');
            $('#rating').val(rating);
            updateStars(rating);
        });

        $('.star__rating .fas').on('mouseenter', function() {
            const hoverRating = $(this).data('rating');
            updateStars(hoverRating);
        });

        $('.star__rating .fas').on('mouseleave', function() {
            updateStars(rating);
        });

        function updateStars(rating) {
            $('.star__rating .fas').each(function() {
                const starRating = $(this).data('rating');
                $(this).toggleClass('active', starRating <= rating);
            });
        }

        updateStars(rating);
    }

    function setupDragAndDrop() {
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
            if (files.length > 0) {
                const fileName = files[0].name;
                dropZone.querySelector('p').textContent = `ファイルが選択されました: ${fileName}`;
            } else {
                dropZone.querySelector('p').textContent = 'クリックして写真を追加 またはドラッグ＆ドロップ';
            }
        }
    }
});
</script>
@endsection
