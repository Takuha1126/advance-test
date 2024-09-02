@extends('layouts.add')

@section('css')
<link rel="stylesheet" href="{{ asset('css/detail.css')}}">
@endsection

@section('content')
@if(session('error'))
    <div class="error">
        {{session('error')}}
    </div>
@endif
<div class="main">
    <div class="detail">
        <div class="detail__content">
            <div class="detail__title">
                <a href="{{ route('home') }}"><i class="fas fa-angle-left fa-1x"></i></a>
                <p class="title__item">{{ $shop['shop_name'] }}</p>
            </div>
            <div class="img @if (!$feedbacks->isEmpty()) has-feedback @endif">
                <img src="{{ $shop['photo_url'] }}">
            </div>
            <div class="detail__info">
                <p class="detail__area">#{{ $shop->area->area_name }}</p>
                <p class="detail__genre">#{{ $shop->genre->genre_name }}</p>
            </div>
            <div class="detail__description">{{ $shop['description'] }}</div>
        </div>
        <div class="feedback @if ($feedbacks->isEmpty()) no-feedback @else has-feedback @endif">
            @if ($feedbacks->isEmpty())
                <a class="feedback__link" href="{{ route('feedbacks.create', $shop->id) }}">口コミを投稿する</a>
            @else
                <div class="feedback__list">
                    <a class="feedback__all" href="{{ route('feedbacks.index', $shop->id) }}">全ての口コミを見る</a>
                    <div class="feedback__item">
                        <div class="feedback__button">
                            <a href="{{ route('feedbacks.edit', $feedbacks->first()->id) }}" class="feedback__edit">口コミを編集</a>
                            <form action="{{ route('feedbacks.destroy', $feedbacks->first()->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                                <button type="submit" class="feedback__delete">口コミを削除</button>
                            </form>
                        </div>
                        <span class="rating-stars">
                            @for ($i = 1; $i <= 5; $i++)
                                <i class="fas fa-star {{ $i <= $feedbacks->first()->rating ? 'filled' : 'empty' }}"></i>
                            @endfor
                        </span>
                        <p class="feedback__comment">{{ $feedbacks->first()->comment }}</p>
                    </div>
                </div>
            @endif
        </div>
    </div>
    <div class="reservation">
        <form id="reservation-form" action="{{ route('reservations.store') }}" method="POST">
            @csrf
            <div class="reservation__ttl">
                <p class="reservation__title">予約</p>
            </div>
            <div class="form">
                <div class="form__ttl">
                    <div class="item">
                        <input type="date" name="date" id="date-input" value="{{ date('Y-m-d')}}">
                        @error('date')
                            <div class="error">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="about">
                        <select name="reservation_time" id="time-select">
                            @for ($i = 11; $i <= 23; $i++)
                        <option value="{{ str_pad($i, 2, '0', STR_PAD_LEFT) }}:00">{{ str_pad($i, 2, '0', STR_PAD_LEFT) }}:00</option>
                        <option value="{{ str_pad($i, 2, '0', STR_PAD_LEFT) }}:30">{{ str_pad($i, 2, '0', STR_PAD_LEFT) }}:30</option>
                        @endfor
                        </select>
                        @error('reservation_time')
                            <div class="error">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="about">
                        <select name="number_of_people" id="num-people-select">
                            @for ($i = 1; $i <= 10; $i++)
                            <option value="{{ $i }}">{{ $i }} 人</option>
                            @endfor
                        </select>
                        @error('number_of_people')
                            <div class="error">{{ $message }}</div>
                        @enderror
                    </div>
                <div class="form__item">
                    <table class="table" id="reservation-info">
                        <tr class="table__tr">
                            <th class="table__th">Shop</th>
                            <td class="table__td" id="shop-name">{{ $shop['shop_name'] }}</td>
                            <input type="hidden" name="shop_id" value="{{ $shop['id'] }}">
                        </tr>
                        <tr class="table__tr">
                            <th class="table__th">Date</th>
                            <td class="table__td" id="date-value"></td>
                        </tr>
                        <tr class="table__tr">
                            <th class="table__th">Time</th>
                            <td class="table__td" id="time-value"></td>
                        </tr>
                        <tr class="table__tr">
                            <th class="table__th">Number</th>
                            <td class="table__td" id="number-value"></td>
                        </tr>
                        <tr class="table__tr">
                            <th class="table__th" style="display: none;">Status</th>
                            <td class="table__td" style="display: none;">
                                <input type="hidden" name="status" value="pending">
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
            <div class="button">
                <button type="submit" class="button__title">予約する</button>
            </div>
        </form>
    </div>
</div>
<script>
const shopName = "{!! $shop['shop_name'] !!}";
const form = document.getElementById('reservation-form');
const dateInput = form.querySelector('input[name="date"]');
const timeSelect = form.querySelector('select[name="reservation_time"]');
const numPeopleSelect = form.querySelector('select[name="number_of_people"]');
const reservationInfoTable = document.getElementById('reservation-info');

dateInput.addEventListener('change', updateReservationInfo);
timeSelect.addEventListener('change', updateReservationInfo);
numPeopleSelect.addEventListener('change', updateReservationInfo);

updateReservationInfo();

function updateReservationInfo() {
    const date = dateInput.value;
    const time = timeSelect.value;
    const numPeople = numPeopleSelect.value;

    document.getElementById('shop-name').innerText = shopName;
    document.getElementById('date-value').innerText = date;
    document.getElementById('time-value').innerText = time;
    document.getElementById('number-value').innerText = numPeople + '人';
}
</script>
@endsection
