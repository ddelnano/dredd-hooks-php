FROM php

RUN apt-get update \
    && apt-get install -y \
    wget \
    git \
    && wget https://getcomposer.org/installer \
    && chmod +x installer \
    && php installer --install-dir=/usr/local/bin/ --filename=composer
