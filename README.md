# 賃貸料金予測App
## 目的
誰でも賃貸料金を相場と比較し適切な賃料か判断できるようになること
## 概要
* 諸条件から適切な賃貸料金を予測する。
* 学習データを不動産サイトから取得する。
## 実装機能
### app
* ログイン機能
* apiに対して必要情報を送り、返却されたデータをDBに登録。
* apiに対して必要情報を送り、返却されたデータを更新。
* apiに対して諸条件を送り、予測データを取得後表示。

＊ データの登録、更新はログインユーザーのみ利用可
### api
* 取得したURLから賃貸情報を取得。取得した情報とLaravelから受け取ったデータをもとにCSVファイルを作成。一部情報を返却。
* 取得した情報をもとに賃貸料金を予測し、結果を返却。
## 起動手順
1. composerを利用できるようにする  
docker run --rm \
    -u "$(id -u):$(id -g)" \
    -v "$(pwd):/var/www/html" \
    -w /var/www/html \
    laravelsail/php82-composer:latest \
    composer install --ignore-platform-reqs
2. コンテナ立ち上げ  
./vendor/bin/sail up
3. マイグレーション実行  
./vendor/bin/sail artisan migrate
4. テストデータ挿入  
./vendor/bin/sail artisan db:seed
## その他
### 学習データ用のURL
下記のサイトから対象のエリアまで移動し、URLを取得してください。
https://suumo.jp/chintai/kansai/
### テストユーザー
ユーザー名　　：MastaerUser  
メールアドレス：test@test.com  
パスワード　　：testTEST@1  