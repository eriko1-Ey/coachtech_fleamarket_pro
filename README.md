COACHTECH FLEAMARKET

【Dockerビルド】

1.　git clone git@github.com:差し替える
2.　DockerDesktopアプリを立ち上げる
3.　docker　compose up -d --build
　　※MacのM1・M2チップのPCの場合、no matching manifest for linux/arm64/v8 in the manifest list entriesの
  　メッセージが表示されビルドができないことがあります。 
   エラーが発生する場合は、docker-compose.ymlファイルの「mysql」内に「platform」の項目を追加で記載してください。

mysql:
    platform: linux/x86_64　(この文を追加する)
    image: mysql:8.0.26
    environment:
    
【Laravel環境構築】

1.　docker　compose exec php bash
2.　composer install
3.　「.env.example」ファイルを 「.env」ファイルに命名を変更。または、新しく.envファイルを作成
4.　.envに以下の環境変数に編集する
　　　DB_CONNECTION=mysql
　　　DB_HOST=mysql
　　　DB_PORT=3306
　　　DB_DATABASE=laravel_db
　　　DB_USERNAME=laravel_user
　　　DB_PASSWORD=laravel_pass
   
5.　アプリケーションキーの作成
　　php artisan key:generate
  
6.　マイグレーションの実行
　　php artisan migrate
  
7.　シーディングの実行
　　php artisan db:seed

8.　シンボリックリンクの作成
　　php artisan storage/link

【使用技術(実行環境)】
・php 8.0 ・laravel 8 ・MySQL 8.0

【URL環境構築】
・開発環境：http://localhost/
・phpMyAdmin：http://localhost:8080/
