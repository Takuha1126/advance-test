<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>予約確認</title>
</head>
<body>
    <h1>予約確認</h1>
    <p>{{ $reservation->user->name }}様</p>
    <p>以下の内容でご予約が確定いたしました。</p>
    <p>予約詳細:</p>
    <ul>
        <li>日付: {{ $reservation->date }}</li>
        <li>時間: {{ $reservation->reservation_time }}</li>
        <li>人数: {{ $reservation->number_of_people }}名</li>
        <li>店舗: {{ $reservation->shop->shop_name }}</li>
    </ul>
    <p>ご予約ありがとうございます。</p>
</body>
</html>
