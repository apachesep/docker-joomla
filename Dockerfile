FROM php:fpm
MAINTAINER Shingo <shingo@tellustek.com> 

# Install PHP extensions
RUN apt-get update && apt-get install -y libpng12-dev libjpeg-dev zip unzip && rm -rf /var/lib/apt/lists/* \
	&& docker-php-ext-configure gd --with-png-dir=/usr --with-jpeg-dir=/usr \
	&& docker-php-ext-install gd
RUN docker-php-ext-install mysqli

VOLUME /var/www/html

# Define Joomla version and expected SHA1 signature
ENV JOOMLA_VERSION 3.4.4
ENV JOOMLA_SHA1 371ed0a987a4c66b4f711b8e27a785f84050de90

# Download package and extract to web volume
RUN curl -o joomla.zip -SL https://github.com/joomla/joomla-cms/releases/download/${JOOMLA_VERSION}/Joomla_${JOOMLA_VERSION}-Stable-Full_Package.zip \
    && echo "$JOOMLA_SHA1 *joomla.zip" | sha1sum -c - \
    && mkdir /usr/src/joomla \
    && unzip joomla.zip -d /usr/src/joomla \
    && rm joomla.zip \
    && chown -R www-data:www-data /usr/src/joomla

# Install Git 
RUN apt-get install -y git 
RUN curl -o .gitignore -SL https://raw.githubusercontent.com/shingo0620/gitignore/master/Joomla.gitignore \
    && chown www-data:www-data .gitignore \
    && mv .gitignore /usr/src/joomla

# Install Nginx
RUN apt-get install -y nginx

# Copy joomla into /var/www/html
# Forward request and error logs to docker log collector 
# Remove useless source
RUN tar cf - --one-file-system -C /usr/src/joomla /var/www/html | tar xf - \
    && ln -sf /dev/stdout /var/log/nginx/access.log \
    && ln -sf /dev/stderr /var/log/nginx/error.log \
    && rm -rf /var/lib/apt/lists/*

EXPOSE 80 443

CMD ["nginx", "-g", "daemon off;"]
