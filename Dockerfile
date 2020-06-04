FROM webdevops/php-nginx:7.2

COPY . /app
WORKDIR /app
RUN composer install
RUN curl -sL https://deb.nodesource.com/setup_10.x | bash -
RUN apt install nodejs npm -y
RUN npm install
RUN npm run build