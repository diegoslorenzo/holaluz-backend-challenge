# Usamos una imagen oficial de PHP con extensiones necesarias
FROM php:8.4-cli-alpine

# Establecemos el directorio de trabajo dentro del contenedor
WORKDIR /app

# Instala dependencias del sistema necesarias para PHP y Composer
RUN apk add --no-cache \
    git \
    unzip \
    curl \
    bash \
    libzip-dev \
    && docker-php-ext-install zip

# Instala Composer (para gestionar dependencias de Symfony)
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Copiamos los archivos del proyecto dentro del contenedor
COPY . .

# Instalamos las dependencias de PHP
RUN composer install 

# Mantiene el contenedor en ejecución ejecutando un shell interactivo
CMD ["php", "-S", "0.0.0.0:8000", "-t", "public"]