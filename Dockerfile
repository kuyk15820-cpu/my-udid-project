FROM php:8.2-apache

# 1. ติดตั้ง OpenSSL, git, unzip (จำเป็นสำหรับ Composer)
RUN apt-get update && apt-get install -y \
    openssl \
    git \
    unzip \
    && rm -rf /var/lib/apt/lists/*

# 2. ดาวน์โหลดและติดตั้ง Composer เข้ามาใน Docker Container
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# 3. กำหนด Working Directory
WORKDIR /var/www/html

# 4. คัดลอกไฟล์ทั้งหมดในโปรเจกต์เข้า Container
COPY . /var/www/html/

# 5. สั่งรัน Composer Install เพื่อติดตั้งไลบรารี Matomo Device Detector
RUN composer install --no-dev --optimize-autoloader

# 6. เปิดใช้งาน mod_rewrite ของ Apache
RUN a2enmod rewrite

# 7. สั่ง Sign และสร้าง cert (โค้ดเดิมของคุณ)
RUN openssl req -x509 -nodes -days 365 -newkey rsa:2048 \
    -keyout /var/www/html/server.key \
    -out /var/www/html/server.crt \
    -subj "/CN=GetUDID/O=F1X3R" && \
    openssl smime -sign -signer /var/www/html/server.crt \
    -inkey /var/www/html/server.key \
    -certfile /var/www/html/server.crt \
    -nodetach -outform der \
    -in /var/www/html/device.mobileconfig \
    -out /var/www/html/signed_device.mobileconfig

EXPOSE 80
