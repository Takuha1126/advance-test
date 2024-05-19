<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="{{ asset('css/admin/registermail.css')}}">
</head>
<body>
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('メールアドレスを確認してください') }}</div>

                <div class="card-body">
                    @if (session('resent'))
                        <div class="alert alert-success" role="alert">
                            {{ __('新しい確認リンクがあなたのメールアドレスに送信されました。') }}
                        </div>
                    @endif

                    {{ __('続行する前に、確認リンクを含むメールをご確認ください。') }}
                    {{ __('もしメールが届かない場合は、') }}
                    <form class="d-inline" method="POST" action="{{ route('admin.verification.resend') }}">
                        @csrf
                        <button type="submit" class="btn  p-0 m-0 align-baseline">{{ __('こちらをクリックして別のリンクをリクエストしてください') }}</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
