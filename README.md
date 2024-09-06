#アプリケーション名
店舗予約アプリ

##作成した目的
お店を手軽に予約できるようにするため

##アプリケーション URL
利用者
https://3.112.255.116/
お店代表者
https://3.112.255.116/shops/reservations
管理者
https://3.112.255.116/admin

店代表者の画面には新規登録機能がなく、ログイン機能しかありません。管理者のページで登録した店代表者の情報を店代表者ログインページで入力するとログインできます。

##機能一覧
利用者
新規登録機能,ログイン機能,ログアウト機能,ユーザー情報取得, お気に入り登録,お気に入り削除,予約機能,予約削除,予約変更機能,店舗一覧取得, 店舗詳細取得,エリア、ジャンル、店名で検索機能,評価機能,予約リマインダー, 予約 QR コード表示,認証メール送信,決済機能,予約メール送信,15分のキャンセル機能,キャンセルメール送信


店舗代表者
店舗情報の作成、更新,予約情報の確認,QRコードの照合,評価一覧,店の画像アップロード機能,照合したら予約を承認し予約一覧から削除機能,ページネーション

管理者
店舗代表者作成、削除,お知らせメッセージの送信,店舗代表者一覧,認証メール送信

##使用技術
Laravel,php

##テーブルの画像
<img width="1440" alt="スクリーンショット 2024-05-19 14 33 13" src="https://github.com/Takuha1126/advance-test/assets/150105635/8601fd2a-395a-484e-87bb-b5ca970e375d">
<img width="1440" alt="スクリーンショット 2024-05-19 14 33 19" src="https://github.com/Takuha1126/advance-test/assets/150105635/46347b45-9d5c-4020-b2cf-d035e5ac1df3">
<img width="1440" alt="スクリーンショット 2024-05-19 14 33 30" src="https://github.com/Takuha1126/advance-test/assets/150105635/3231dbc7-d837-40cd-a776-318fe8c62c66">
<img width="1440" alt="スクリーンショット 2024-05-19 14 33 42" src="https://github.com/Takuha1126/advance-test/assets/150105635/51cdce51-05b6-424d-be0d-b495bf83b8a3">

##ER図の画像
<img width="1440" alt="スクリーンショット 2024-05-21 18 03 46" src="https://github.com/Takuha1126/advance-test/assets/150105635/fdf337d1-48d1-476f-bb82-6d40917e21ef">


##csvインポート
webに公開されている画像を使うやり方

1.CSVファイルを作成  
   下記のフォーマットに従って、CSVファイルを作成します
   ここではshops.csvという名前にします。
   touch shops.csv

   サンプルフォーマット
   
   shop_name,area,genre,description,image_url
   
   寿司屋,東京都,寿司,新鮮な寿司を提供する店舗です。,http://example.com/image.jpg

   

2.CSVファイルをアップロード 
   1.管理画面にログインします。
   2.インポート機能を選択します。
   3.作成したCSVファイルを選択し、アップロードします。


webに公開されていない画像を使うやり方（シンボリックの使用)
もしシンボリックが作成されていなかったらphp artisan  storage:linkで作成してください

1.シンボリックの中に画像を入れる
　  cd advance-test
    mkdir src/storage/app/public/images
    mv /path/to/source/sample_image.jpg src/storage/app/public/images/
　　　/path/to/source/sample_image.jpgここの部分は各自のパスに書き換える

2.CSVファイルを作成  
   下記のフォーマットに従って、CSVファイルを作成します
   ここではshops.csvという名前にします。
   touch shops.csv

   サンプルフォーマット
   
   shop_name,area,genre,description,image_url
   
   寿司屋,東京都,寿司,新鮮な寿司を提供する店舗です。,storage/images/sample_image.jpg

3.CSVファイルをアップロード 
   1.管理画面にログインします。
   2.インポート機能を選択します。
   3.作成したCSVファイルを選択し、アップロードします。

スプレッドシートのやり方
 1.スプレッドシートの作成
   スプレッドシートソフトウェアを開き、新しいシートを作成します。
   
 2.データの入力

  サンプルフォーマット
   shop_name|area|genre|description|image_url
   寿司屋|東京都|寿司|新鮮な寿司を提供する店舗です。|http://example.com/image.jpg
   *(|)これはスプレッドシートのセルを表している

　　注意: Webに公開されていない画像を使用する場合は、シンボリックリンクを使って画像を処理する必要があります。
        詳しくは「Webに公開されていない画像を使う方法」を参照してください。

 3.CSVとして保存
    1.メニューから「ファイル」を選択します。
    2.ダウンロードを選択し、「CSV（カンマ区切り）（.csv）」を選択します。

 4.CSVファイルのアップロード
    1.管理画面にログインします。
    2.インポート機能を選択します。
    3.作成したCSVファイルを選択し、アップロードします。
   

注意事項
- CSVファイルの形式が正しくない場合、インポートに失敗することがあります。必ず指定されたフォーマットに従ってください。
- CSVファイルを作成する際、各フィールドはカンマ（,）で区切ります。
  フィールド内にカンマが含まれる場合は、ダブルクォーテーション (") で
  そのフィールドを囲んでください。
例："新鮮な寿司、刺身を提供する店舗です。"
- インポート後に問題が発生した場合は、エラーメッセージを確認して対応してください。
- 項目は全て入力必須
- 店舗名：50文字以内
- 地域：「東京都」「大阪府」「福岡県」のいずれから選択
- ジャンル：「寿司」「焼肉」「イタリアン」「居酒屋」「ラーメン」のいずれから選択
- 店舗概要：400文字以内
- 画像URL：jpeg、pngのみアップロード可能


##環境構築
開発環境をクローンします
git clone git@github.com:Takuha1126/advance-test.git

ここではadvance-testこのディレクトリ名でします

cd advance-test

Dockerで開発環境構築
docker-compose up -d --build


docker-compose exec php bash

QRコードのためにインストールしておく
apt-get update
apt-get install -y libpng-dev libjpeg-dev libfreetype6-dev
docker-php-ext-configure gd --with-freetype --with-jpeg
docker-php-ext-install gd

Laravelパッケージのインストール
composer install

envファイルの作成
cp .env.example .env


.envファイルの書き換えます
DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=laravel_db
DB_USERNAME=laravel_user
DB_PASSWORD=laravel_pass

各自でメールの設定
MAIL_MAILER=smtp
MAIL_HOST=mailhog
MAIL_PORT=1025
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS=null
MAIL_FROM_NAME="${APP_NAME}"

各自でクレジットカードの設定
STRIPE_KEY=your_stripe_publishable_key
STRIPE_SECRET=your_stripe_secret_key

アプリケーションキーの生成
php artisan key:generate

シンボリック作成
php artisan  storage:link

データベースマイグレーション
php artisan migrate:refresh

初期データ挿入
php artisan db:seed --class=ShopsTableSeeder
php artisan db:seed --class=GenresTableSeeder
php artisan db:seed --class=AreasTableSeeder




