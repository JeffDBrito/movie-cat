# Imagem oficial do PHP
FROM php:8.2-fpm

# Dependências e extensões necessárias para o Laravel
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libonig-dev \
    libzip-dev \
    libmariadb-dev \
    zip \
    curl \
    git \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo pdo_mysql mbstring zip gd

# Instalação do Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Definição do diretório de trabalho
WORKDIR /var/www/html

# Copia os arquivos do backend para dentro do container
COPY . .

# Instala as dependências do Laravel
RUN composer install

# Configura as variáveis de ambiente para o Laravel
ENV DB_HOST=${DB_HOST}
ENV DB_PORT=${DB_PORT}
ENV DB_DATABASE=${DB_DATABASE}
ENV DB_USERNAME=${DB_USERNAME}
ENV DB_PASSWORD=${DB_PASSWORD}

# Expõe a porta configurada no .env
EXPOSE ${BACKEND_PORT:-8000}

# Comando para iniciar o Laravel
CMD php artisan serve --host=0.0.0.0 --port=${BACKEND_PORT:-8000}
