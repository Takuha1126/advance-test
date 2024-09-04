@extends('layouts.add')

@section('css')
<link rel="stylesheet" href="{{ asset('css/feedbacks/index.css')}}">
@endsection

@section('content')
    <div class="main">
        <div class="main__ttl">
            <p class="main__title">{{ $shop->shop_name }}の全ての口コミ</p>
        </div>
        <div class="main__item">
        @forelse ($feedbacks as $feedback)
            <div class="feedback__item">
                @if (auth()->check() && $feedback->user_id === auth()->id())
                    <div class="feedback__actions">
                        <a href="{{ route('feedbacks.edit', $feedback->id) }}" class="feedback__edit">口コミを編集</a>
                        <form action="{{ route('feedbacks.destroy', $feedback->id) }}" method="POST" class="feedbacks__form">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="feedback__delete">口コミを削除</button>
                        </form>
                    </div>
                @endif
                @if ($feedback->image)
                    <img src="{{ asset('storage/feedback_images/' . $feedback->image) }}" alt="口コミ画像" class="feedback__image">
                @endif
                <div class="feedback__rating">
                    @for ($i = 1; $i <= 5; $i++)
                        <i class="fas fa-star {{ $i <= $feedback->rating ? 'filled' : 'empty' }}"></i>
                    @endfor
                </div>
                <p class="feedback__comment">{{ $feedback->comment }}</p>
            </div>
        @empty
            <div class="feedback__message">
                <p class="no__feedback-message">まだ口コミがありません。</p>
            </div>
        @endforelse
    </div>
@endsection