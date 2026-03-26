FROM php:8.2-apache

# ติดตั้ง System Dependencies และ PHP Extensions ที่จำเป็นสำหรับ CodeIgniter 4
RUN apt-get update && apt-get install -y \
    libicu-dev \
    libonig-dev \
    libzip-dev \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    zip \
    unzip \
    git \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install \
    intl \
    mbstring \
    pdo_mysql \
    mysqli \
    zip \
    gd \
    opcache \
    && a2enmod rewrite

# ตั้งค่า Opcache (Performance Tuning)
RUN { \
    echo 'opcache.memory_consumption=128'; \
    echo 'opcache.interned_strings_buffer=8'; \
    echo 'opcache.max_accelerated_files=4000'; \
    echo 'opcache.revalidate_freq=2'; \
    echo 'opcache.fast_shutdown=1'; \
    echo 'opcache.enable_cli=0'; \
    } > /usr/local/etc/php/conf.d/opcache-recommended.ini

# ตั้งค่า Document Root (ไม่ใช้ public ตามแบบ skj2025)
ENV APACHE_DOCUMENT_ROOT /var/www/html

# ตั้งค่า Apache ให้ยอมรับการเข้าถึงไฟล์นอก Document Root (/domains) และสร้าง Symlink
RUN mkdir -p /domains/librarie_skj && \
    ln -s /domains/librarie_skj /var/www/html/librarie_skj && \
    echo "<Directory /domains/librarie_skj>\n\
    Options Indexes FollowSymLinks\n\
    AllowOverride All\n\
    Require all granted\n\
    </Directory>" >> /etc/apache2/apache2.conf

RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# เปิดใช้งาน SSL และสร้าง Self-Signed Certificate
RUN a2enmod ssl
RUN openssl req -x509 -nodes -days 365 -newkey rsa:2048 -keyout /etc/ssl/private/apache-selfsigned.key -out /etc/ssl/certs/apache-selfsigned.crt -subj "/C=TH/ST=Bangkok/L=Bangkok/O=Organization/OU=Unit/CN=localhost"
RUN sed -i 's!/etc/ssl/certs/ssl-cert-snakeoil.pem!/etc/ssl/certs/apache-selfsigned.crt!g' /etc/apache2/sites-available/default-ssl.conf
RUN sed -i 's!/etc/ssl/private/ssl-cert-snakeoil.key!/etc/ssl/private/apache-selfsigned.key!g' /etc/apache2/sites-available/default-ssl.conf
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/default-ssl.conf
RUN a2ensite default-ssl

# Copy custom PHP configuration
COPY custom.ini /usr/local/etc/php/conf.d/custom.ini

# กำหนด Working Directory
WORKDIR /var/www/html

# ติดตั้ง Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copy entrypoint script
COPY docker-entrypoint.sh /usr/local/bin/docker-entrypoint.sh
RUN chmod +x /usr/local/bin/docker-entrypoint.sh

ENTRYPOINT ["docker-entrypoint.sh"]
CMD ["apache2-foreground"]
