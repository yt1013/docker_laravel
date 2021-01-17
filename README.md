# docker_laravel

### ■環境構築
下記スクリプト実行
```angular2html
docker-compose up --build -d
docker-compose exec app bash
```
コンテナ内下記実行
```
cp .env.example .env
composer install
```

下記にアクセス
* Docker
  http://localhost/

* Docker toolbox
  http://192.168.99.100/