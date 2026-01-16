# 予約差分検出アプリ
- 現時点での予約一覧リストをエクセルでインポートし、任意のインポート時点の予約との差分を検出するアプリです。

## 環境構築

1. ```
   git clone git@git@github.com:yoko-bessho/ReservationConfirmationApp.git
   ```
2. DockerDesktopを立ち上げる。
3. プロジェクト直下で以下のコマンドを実行する。
   ```
   make init
   ```

   Mac の M1・M2 チップの PC の場合、no matching manifest for linux/arm64/v8 in the manifest list entries のメッセージが表示されビルドができないことがあります。 エラーが発生する場合は、docker-compose.yml ファイルの「mysql」内に「platform」の項目を追加で記載してください。
   ```
   mysql:
      image: mysql:8.0.26
      platform: linux/x86_64   //(この文追加)
   ```

4. 作成された .envへ以下の環境変数と管理者ユーザー情報を追加してください
   ```
   DB_HOST=mysql
   DB_PORT=3306
   DB_DATABASE=laravel_db
   DB_USERNAME=laravel_user
   DB_PASSWORD=laravel_pass

   管理者用ユーザ
	ADMIN_EMAIL=				#メールアドレス"
   ADMIN_PASSWORD=		   #パスワード"
   ```

5. .envの編集後、以下を実行
   ```
   make after-env
   ```

## テーブル仕様

**1. reservationsテーブル**

| カラム名 | 型 | PK | UNIQUE | NOT NULL | FK |
| :--- | :--- | :--- | :--- | :--- | :--- |
| id | unsigned bigint | ○ | | ○ | |
| import_at | datetime | | | ○ | |
| visit_date | date | | | ○ | |
| patient_id | varchar(50) | | | ○ | |
| patient_name | varchar(100) | | | ○ | |
| reservation_content | varchar(255) | | | ○ | |
| created_at | timestamp | | | | |
| updated_at | timestamp | | | | |



## ER図
![ER図](ER.drawio.png)


## PHPUnitを使用したテストは以下を実行してください
**1. テスト用データベースの作成**
```
docker-compose exec mysql bash
mysql -u root -p
//パスワードはrootと入力
create database demo_test;
```

**2. env.testingを作成**
.env.testing.example を .env.testing にコピーしkey生成
```
cp .env.testing.example .env.testing
```

```
docker compose exec php bash
```
```
php artisan key:generate --env=testing
```
```
php artisan config:clear
```

**2.テスト実行**
```
php artisan migrate:fresh --env=testing
./vendor/bin/phpunit
```

### 使用技術（実行環境）

- PHP 8.4.1
- Laravel Framework 8.83.29
- mysql from 11.8.3-MariaDB, client 15.2 for debian-linux-gnu (aarch64)


### URL

- 開発環境：http://localhost/
- phpMyadmin：http://localhost:8080/

