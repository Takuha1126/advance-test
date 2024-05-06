@extends('layouts.add')

@section('css')
<link rel="stylesheet" href="{{ asset('css/payment.css')}}">
@endsection

@section('content')
<div class="payment-container">
    @if(session('success_message'))
    <div class="alert alert-success">
        {{ session('success_message') }}
    </div>
    @endif

    @if(session('error_message'))
    <div class="alert alert-danger">
        {{ session('error_message') }}
    </div>
    @endif

    <h2 class="payment__title">支払い情報を入力してください</h2>
    <div id="message" role="alert"></div>
    <form action="{{ route('payment.handle') }}" method="post" id="payment-form">
        @csrf
        <div class="form-group">
            <input type="hidden" id="amount" name="amount" value="2000">
            <input type="hidden" id="paymentIntentId" value="{{ $paymentIntent->id }}">
            <div id="card-element" class="form-control"></div>
        </div>

        <div class="form-group">
            <label for="card-number" class="label">カード番号</label>
            <div id="card-number-element"></div>
        </div>

        <div class="form-group">
            <label for="card-expiry-month" class="label">有効期限</label>
            <div id="card-expiry-element"></div>
        </div>

        <div class="form-group">
            <label for="card-cvc" class="label">CVC</label>
            <div id="card-cvc-element"></div>
        </div>

        <div class="form-group">
            <label for="card-holder-name" class="label">口座名義</label>
            <input type="text" id="card-holder-name" class="form-control" placeholder="口座名義">
        </div>

        <div id="card-errors" role="alert"></div>

        <button type="submit" class="button__ttl">支払いを完了する</button>
    </form>
</div>

<script src="https://js.stripe.com/v3/"></script>
<script>
// メッセージを表示する関数
function showMessage(message, isError = false) {
    const messageElement = document.getElementById('card-errors');
    messageElement.textContent = message;
    messageElement.style.color = isError ? 'red' : 'green';
}

// Stripeの初期化
var stripe = Stripe('{{ $stripeKey }}');
var elements = stripe.elements();

// カード情報の要素を作成・マウント
var cardNumber = elements.create('cardNumber');
cardNumber.mount('#card-number-element');
var cardExpiry = elements.create('cardExpiry');
cardExpiry.mount('#card-expiry-element');
var cardCvc = elements.create('cardCvc');
cardCvc.mount('#card-cvc-element');

// フォームの送信イベントリスナー
document.getElementById('payment-form').addEventListener('submit', function(event) {
    event.preventDefault();

    var cardHolderName = document.getElementById('card-holder-name').value;

    // FetchリクエストでサーバーからclientSecretを取得
    fetch('/create-payment-intent', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content') // CSRFトークン
        },
        body: JSON.stringify({
            amount: document.getElementById('amount').value,
        })
    })
    .then(response => {
        if (response.ok) {
            return response.json(); // レスポンスをJSONとして解析
        } else {
            throw new Error('Server response wasn\'t OK');
        }
    })
    .then(data => {
        var clientSecret = data.clientSecret;

        // clientSecretを使用して支払い方法を作成
        stripe.createPaymentMethod({
            type: 'card',
            card: cardNumber,
            billing_details: {
                name: cardHolderName,
            },
        }).then(function(result) {
            if (result.error) {
                // エラーをユーザーに通知
                showMessage(result.error.message, true);
            } else {
                // 支払いを確認
                stripe.confirmCardPayment(clientSecret, {
                    payment_method: result.paymentMethod.id
                }).then(function(confirmResult) {
                    if (confirmResult.error) {
                        // エラーをユーザーに通知
                        showMessage(confirmResult.error.message, true);
                    } else {
                        // 成功メッセージを表示
                        showMessage('支払いが完了しました。', false);
                    }
                });
            }
        });
    })
    .catch(error => {
        console.error('Error:', error);
        showMessage('エラーが発生しました。もう一度お試しください。', true);
    });
});
</script>



@endsection

