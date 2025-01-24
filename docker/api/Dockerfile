FROM php:8.4-fpm

ARG USERNAME=user
ARG USER_UID=1000
ARG USER_GID=$USER_UID

RUN apt-get update && apt-get install -y --no-install-recommends \
    git \
    && rm -rf /var/lib/apt/lists/*

# Create a non-root user.
RUN addgroup --gid $USER_GID $USERNAME \
    && adduser $USERNAME \
        --uid $USER_UID \
        --ingroup $USERNAME \
        --home /home/$USERNAME \
    && sed -i "/user =/c\user = $USERNAME" /usr/local/etc/php-fpm.d/www.conf \
    && sed -i "/group =/c\group = $USERNAME" /usr/local/etc/php-fpm.d/www.conf

RUN mv "$PHP_INI_DIR/php.ini-development" "$PHP_INI_DIR/php.ini"

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer \
    && composer clear-cache \
    && rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/* /usr/share/doc/* ~/.composer

# Install extensions
ADD https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions /usr/local/bin/
RUN chmod uga+x /usr/local/bin/install-php-extensions && sync \
    && install-php-extensions \
        bcmath \
        pdo_mysql \
        xdebug \
        zip \
    && rm /usr/local/bin/install-php-extensions

USER $USERNAME
