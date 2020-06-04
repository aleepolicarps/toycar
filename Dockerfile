RUN composer install
RUN curl -sL https://deb.nodesource.com/setup_10.x | sudo bash -
RUN apt install nodejs
RUN npm install
RUN npm run build