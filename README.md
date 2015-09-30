# joomla-fpm-nginx
讓Joomla的開發者可以輕鬆的建立起Nginx-fpm-Joomla的環境<br>
並且結合github, webhook來更輕鬆的進行開發與建置

# 執行方式
docker run --name 專案名稱 --link dev-mysql:mysql <br>
           -v /container/專案名稱:/var/www/html <br>
           -P -d tellustek/joomla-fpm-nginx
