FROM php

RUN apt-get update \
    && apt-get install -y \
    wget \
    nodejs \
    npm \
    git \
    net-tools \
    && wget https://getcomposer.org/installer \
    && chmod +x installer \
    && php installer --install-dir=/usr/local/bin/ --filename=composer \
