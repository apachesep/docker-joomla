# joomla-fpm-nginx
讓Joomla的開發者可以輕鬆的建立起Nginx-fpm-Joomla的環境<br>
並且結合github, webhook來更輕鬆的進行開發與建置

# 執行方式
docker run --name 專案名稱 --link dev-mysql:mysql <br>
           -v /container/專案名稱:/var/www/html <br>
           -e PROJECT_NAME=xxxxxx <br>
           -P -d tellustek/joomla-fpm-nginx

資料庫連結部份, 可用--link與另一mysql docker連結, 或是使用JOOMLA_DB_HOST變數指定

