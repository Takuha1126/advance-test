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
    <div id="card-errors" role="alert"></div>
    <form action="{{ route('payment.handle') }}" method="post" id="payment-form">
        @csrf
        <div class="form-group">
            <input type="hidden" id="amount" name="amount" value="2000">
            <input type="hidden" id="reservation_id" name="reservation_id" value="{{ $reservationId }}">
            <div id="card-element" class="form-control"></div>
        </div>
        <div class="form-group">
            <label for="card-number" class="label">カード番号</label>
            <div id="card-number-element" class="input-large"></div>
            <span id="card-number-error" class="text-danger" style="display: none;">カード番号を入力してください。</span>
        </div>
        <div class="form-group">
            <label for="card-expiry-month" class="label">有効期限</label>
            <div id="card-expiry-element"  class="input-large"></div>
            <span id="card-expiry-error" class="text-danger" style="display: none;">有効期限を入力してください。</span>
        </div>
        <div class="form-group">
            <label for="card-cvc" class="label">CVC</label>
            <div id="card-cvc-element" class="input-large"></div>
            <span id="card-cvc-error" class="text-danger" style="display: none;">CVCを入力してください。</span>
        </div>
        <div class="form-group">
            <label for="card-holder-name" class="label">口座名義</label>
            <input type="text" id="card-holder-name" class="form-control" placeholder="口座名義">
            <span id="card-holder-name-error" class="text-danger" style="display: none;">口座名義を入力してください。</span>
        </div>
        <button type="submit" class="button__ttl" id="payment-button">支払いを完了する</button>
    </form>
</div>
<script src="https://js.stripe.com/v3/"></script>
<script>
    function showMessage(message, isError = false) {
    const messageElement = document.getElementById('card-errors');
    messageElement.textContent = message;
    messageElement.style.color = isError ? 'red' : 'green';
}

var stripe = Stripe('{{ $stripeKey }}');
var elements = stripe.elements();

var style = {
    base: {
        fontSize: '16px',
        color: '#32325d',
        '::placeholder': {
            color: '#aab7c4',
        },
    },
    invalid: {
        color: '#fa755a',
        iconColor: '#fa755a',
    },
};

var cardNumber = elements.create('cardNumber', {style: style});
cardNumber.mount('#card-number-element');
var cardExpiry = elements.create('cardExpiry', {style: style});
cardExpiry.mount('#card-expiry-element');
var cardCvc = elements.create('cardCvc', {style: style});
cardCvc.mount('#card-cvc-element');

document.getElementById('payment-form').addEventListener('submit', function(event) {
    event.preventDefault();

    var cardHolderName = document.getElementById('card-holder-name').value;
    var cardHolderNameError = document.getElementById('card-holder-name-error');
    var cardNumberError = document.getElementById('card-number-error');
    var cardExpiryError = document.getElementById('card-expiry-error');
    var cardCvcError = document.getElementById('card-cvc-error');

    var isError = false;


    if (cardHolderName.trim() === '') {
        cardHolderNameError.style.display = 'block';
        isError = true;
    } else {
        cardHolderNameError.style.display = 'none';
    }

    if (!cardNumber._complete) {
        cardNumberError.style.display = 'block';
        isError = true;
    } else {
        cardNumberError.style.display = 'none';
    }

    if (!cardExpiry._complete) {
        cardExpiryError.style.display = 'block';
        isError = true;
    } else {
        cardExpiryError.style.display = 'none';
    }

    if (!cardCvc._complete) {
        cardCvcError.style.display = 'block';
        isError = true;
    } else {
        cardCvcError.style.display = 'none';
    }

    if (isError) {
        showMessage('入力にエラーがあります。全てのフィールドを確認してください。', true);
        return;
    }

    var paymentButton = document.getElementById('payment-button');
    paymentButton.textContent = '支払い処理中...';
    paymentButton.disabled = true;

    fetch('/create-payment-intent', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            amount: document.getElementById('amount').value,
            reservation_id: document.getElementById('reservation_id').value
        })
    })
    .then(response => {
        if (response.ok) {
            return response.json();
        } else {
            throw new Error('Server response wasn\'t OK');
        }
    })
    .then(data => {
        var clientSecret = data.clientSecret;

        stripe.createPaymentMethod({
            type: 'card',
            card: cardNumber,
            billing_details: {
                name: cardHolderName,
            },
        }).then(function(result) {
            if (result.error) {
                showMessage(result.error.message, true);
                paymentButton.textContent = '支払いを完了する';
                paymentButton.disabled = false;
            } else {
                stripe.confirmCardPayment(clientSecret, {
                    payment_method: result.paymentMethod.id
                }).then(function(confirmResult) {
                    if (confirmResult.error) {
                        showMessage(confirmResult.error.message, true);
                        paymentButton.textContent = '支払いを完了する';
                        paymentButton.disabled = false;
                    } else {
                        showMessage('支払いが完了しました。', false);
                        paymentButton.textContent = '支払い完了';
                        paymentButton.disabled = true;
                    }
                });
            }
        });
    })
    .catch(error => {
        console.error('Error:', error);
        showMessage('エラーが発生しました。もう一度お試しください。', true);
        paymentButton.textContent = '支払いを完了する';
        paymentButton.disabled = false;
    });
});
</script>
@endsection
