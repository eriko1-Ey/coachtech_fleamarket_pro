# COACHTECH FLEAMARKET

## 環境構築

### Docker Build

1.　コードをcloneする。
```
git clone git@github.com:eriko1-Ey/coachtech_fleamarket_pro.gitを実行する。
```

2.　 docker を起動する。
```
 docker compose up -d --build
```

※Mac の M1・M2 チップの PC の場合、no matching manifest for linux/arm64/v8 in the manifest list entries の
　メッセージが表示されビルドができないことがある。
エラーが発生する場合は、docker-compose.yml ファイルの「mysql」内に以下のような項目を追加してください。

```
platform: linux/x86_64
```

    
### Laravel環境構築

1.  phpコンテナを実行する。

```
docker compose exec php bash
```

2.　 Laravelのパッケージをインストールする。

```
composer install
```

3.　 .envファイルを作成する。

```
cp .env.example .env
```

4.　.env に以下の環境変数に編集する。

```
DB_CONNECTION=mysql

DB_HOST=mysql

DB_PORT=3306

DB_DATABASE=laravel_db

DB_USERNAME=laravel_user

DB_PASSWORD=laravel_pass
```

5.　アプリケーションキーの作成(php コンテナ内）

```
php artisan key:generate
```

6.　マイグレーションの実行(php コンテナ内）

```
php artisan migrate
```

7.　シーディングの実行(php コンテナ内）

```
php artisan db:seed
```

8.　シンボリックリンクの作成

```
php artisan storage/link
```

※失敗した場合は、下記を実行する。
```
cd src/publilc

unlink storage

ln -s ../storage/app/public storage
```

【ER図】


![Image](https://github.com/user-attachments/assets/86156d6a-1b47-4cbb-aa52-2d73d1ea355e)


  
【使用技術(実行環境)】
・php 8.0 ・laravel 8 ・MySQL 8.0

【URL環境構築】
・開発環境：http://localhost/
・phpMyAdmin：http://localhost:8080/
