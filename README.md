#アプリケーション名
店舗予約アプリ

##作成した目的
お店を手軽に予約できるようにするため

##アプリケーション URL

店代表者の画面には新規登録機能がなく、ログイン機能しかありません。管理者のページで登録した店代表者の情報を店代表者ログインページで入力するとログインできます。

##機能一覧
利用者
新規登録機能,ログイン機能,ログアウト機能,ユーザー情報取得, お気に入り登録,お気に入り削除,予約機能,予約削除,予約変更機能,店舗一覧取得, 店舗詳細取得,エリア、ジャンル、店名で検索機能,評価機能,予約リマインダー, 予約 QR コード表示,認証メール送信,決済機能,予約メール送信,15分のキャンセル機能

店舗代表者
店舗情報の作成、更新,予約情報の確認,QRコードの照合,評価一覧,店の画像アップロード機能,照合したら予約を承認し予約一覧から削除機能,ページネーション

管理者
店舗代表者作成、削除,お知らせメッセージの送信,店舗代表者一覧

##使用技術
Laravel,php

##テーブルの画像
<img width="1440" alt="スクリーンショット 2024-05-19 14 33 13" src="https://github.com/Takuha1126/advance-test/assets/150105635/8601fd2a-395a-484e-87bb-b5ca970e375d">
<img width="1440" alt="スクリーンショット 2024-05-19 14 33 19" src="https://github.com/Takuha1126/advance-test/assets/150105635/46347b45-9d5c-4020-b2cf-d035e5ac1df3">
<img width="1440" alt="スクリーンショット 2024-05-19 14 33 30" src="https://github.com/Takuha1126/advance-test/assets/150105635/3231dbc7-d837-40cd-a776-318fe8c62c66">
<img width="1440" alt="スクリーンショット 2024-05-19 14 33 42" src="https://github.com/Takuha1126/advance-test/assets/150105635/51cdce51-05b6-424d-be0d-b495bf83b8a3">



##ER図の画像
<img width="1440" alt="スクリーンショット 2024-05-19 12 54 20" src="https://github.com/Takuha1126/advance-test/assets/150105635/b7d47d45-d06c-4fbf-93d7-80b1d1488dc2">




##環境構築
Composer のインストール
Laravelプロジェクトの依存関係を管理するために、Composerが必要です。

プロジェクトのクローン
プロジェクトのリポジトリからソースコードをローカル環境にコピーします。
    git clone git@github.com:Takuha1126/test-laravel.git,

必要なパッケージのインストール
プロジェクトディレクトリに移動し、Composerを使用して必要なPHPパッケージをインストールします。
    cd test-laravel
    composer install

env ファイルの設定
.env.example ファイルをコピーして .env ファイルを作成し、データベース接続情報などの環境設定を行います。
    cp .env.example .env


アプリケーションキーの生成
Laravel アプリケーションで使用される暗号化キーを生成します。
    php artisan key:generate

データベースマイグレーション
データベースのテーブルを作成するために、マイグレーションを実行します。
    php artisan migrate

データベース接続情報などの設定は、.envファイルをテキストエディタで開いて編集します。

