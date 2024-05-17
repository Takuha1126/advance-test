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
<img width="1440" alt="スクリーンショット 2024-05-09 0 54 20" src="https://github.com/Takuha1126/advance-test/assets/150105635/3737f67a-6049-4dc2-b687-b36c479df1d8">
<img width="1440" alt="スクリーンショット 2024-05-09 0 54 32" src="https://github.com/Takuha1126/advance-test/assets/150105635/3deb30df-0055-424a-82eb-eb574a2cb57c">
<img width="1440" alt="スクリーンショット 2024-05-09 0 54 49" src="https://github.com/Takuha1126/advance-test/assets/150105635/159a260b-3638-4e24-88ca-cbea7f09a6a4">
<img width="1440" alt="スクリーンショット 2024-05-09 0 54 55" src="https://github.com/Takuha1126/advance-test/assets/150105635/d1222a5c-2fcc-4b87-b8e0-de67c870d39d">


##ER図の画像
<img width="1440" alt="スクリーンショット 2024-04-29 12 46 38" src="https://github.com/Takuha1126/advance-test/assets/150105635/70666ff8-33ef-422d-9f87-99062c045cbf">



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

