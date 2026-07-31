FROM php:8.2-apache

# ติดตั้ง OpenSSL
RUN apt-get update && apt-get install -y openssl

# คัดลอกไฟล์ทั้งหมดในโปรเจกต์เข้า Container
COPY . /var/www/html/

# เปิดใช้งาน mod_rewrite ของ Apache
RUN a2enmod rewrite

# สั่ง Sign และสร้าง cert
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
