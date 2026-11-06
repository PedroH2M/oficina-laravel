FROM php:7.2-fpm

# Configurar repositórios caso a versão Debian esteja arquivada
RUN sed -i s/deb.debian.org/archive.debian.org/g /etc/apt/sources.list || true && \
    sed -i s/security.debian.org/archive.debian.org/g /etc/apt/sources.list || true && \
    sed -i s/stretch-updates/stretch/g /etc/apt/sources.list || true && \
    sed -i s/buster-updates/buster/g /etc/apt/sources.list || true

# Instalar dependências do sistema necessárias para Laravel 7
RUN apt-get update && apt-get install -y --no-install-recommends \
    build-essential \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    locales \
    zip \
    jpegoptim optipng pngquant gifsicle \
    vim \
    unzip \
    git \
    curl \
    libzip-dev \
    default-mysql-client \
    && rm -rf /var/lib/apt/lists/*

# Instalar extensões PHP essenciais
RUN docker-php-ext-configure gd --with-gd --with-freetype-dir=/usr/include/ --with-jpeg-dir=/usr/include/ --with-png-dir=/usr/include/ \
    && docker-php-ext-install gd \
    && docker-php-ext-install pdo_mysql mbstring zip exif pcntl bcmath

# Instalar Composer 2.2 (LTS compatível com PHP 7.2)
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer --2.2

# Definir diretório de trabalho
WORKDIR /var/www

# Copiar script de inicialização
COPY docker/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

# Expor porta do PHP-FPM
EXPOSE 9000

ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]
