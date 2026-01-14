# Gunakan image PHP-FPM resmi
FROM php:8.2-fpm

RUN curl -sL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs

# Set working directory di dalam container
WORKDIR /var/www

# Install dependensi sistem (Linux)
# libonig-dev diperlukan untuk mbstring
# libzip-dev diperlukan untuk zip
# libpng-dev dll diperlukan untuk gd (image processing)
RUN apt-get update && apt-get install -y \
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
    libonig-dev \
    libxml2-dev

# Clear cache apt untuk mengecilkan ukuran image
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install ekstensi PHP yang dibutuhkan Laravel
RUN docker-php-ext-install pdo_mysql mbstring zip exif pcntl bcmath

# Konfigurasi dan install ekstensi GD (untuk manipulasi gambar)
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd

# Install Node.js dan NPM (versi 20 LTS) untuk Vite
RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs

# Install Composer secara resmi dari image composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Salin seluruh kode project ke dalam container
COPY . /var/www

# Berikan izin akses ke folder storage dan cache Laravel
# Ini krusial agar Laravel bisa menulis log dan session
RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache

# Expose port 9000 untuk PHP-FPM dan 5173 untuk Vite
EXPOSE 9000 5173

# Jalankan PHP-FPM
CMD ["php-fpm"]