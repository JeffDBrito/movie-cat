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

# Instalação o Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Definição do diretório de trabalho
WORKDIR /var/www/html

# Copia os arquivos do backend para dentro do container
COPY . .

# Instala as dependências do Laravel
RUN composer install

# Expõe a porta 8000 para o Laravel
EXPOSE 8000

# Comando para iniciar o Laravel
CMD php artisan serve --host=0.0.0.0 --port=8000
