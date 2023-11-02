ARG PHP_VERSION=8.1.8
FROM php:${PHP_VERSION}-fpm as base

# App variables
ENV APP_USER www-data
ENV PORT 8000
ENV WORK_DIR /var/www
ENV SCRIPTS_DIR_NAME scripts
ENV LOG_DIR /var/log
ENV UTIL_SCRIPTS_DIR /etc/util_scripts

# Config files variables
ENV SUPERVISOR_CONF=/etc/supervisord.conf
ENV PHP_INI_CONF=/usr/local/etc/php/conf.d/app.ini
ENV NGINX_DEFAULT_CONF=/etc/nginx/sites-enabled/default

# Set working directory
WORKDIR ${WORK_DIR}

# Add docker php ext repo
ADD https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions /usr/local/bin/

# Install php extensions
RUN chmod +x /usr/local/bin/install-php-extensions && sync && \
    install-php-extensions mbstring pdo_mysql zip exif pcntl gd memcache

# Install dependencies
RUN apt-get update && apt-get install -y \
    build-essential \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    locales \
    zip \
    jpegoptim optipng pngquant gifsicle \
    unzip \
    curl \
    lua-zlib-dev \
    libmemcached-dev \
    nginx \
    supervisor

# Install composer and set permissions for binaries
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer \
    && chgrp ${APP_USER} /usr/local/bin/composer /usr/bin/supervisord /usr/sbin/nginx \
    && chmod g+x /usr/local/bin/composer /usr/bin/supervisord /usr/sbin/nginx

# Set permissions for nginx files
RUN chgrp ${APP_USER} -R /var/lib/nginx/ /run/ \
    && chmod -R g+wr /var/lib/nginx/ /run/

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Copy nginx/php/supervisor configs
COPY --chown=${APP_USER}:${APP_USER} ./${SCRIPTS_DIR_NAME}/supervisor.conf ${SUPERVISOR_CONF}
COPY --chown=${APP_USER}:${APP_USER} ./${SCRIPTS_DIR_NAME}/php.ini ${PHP_INI_CONF}
COPY --chown=${APP_USER}:${APP_USER} ./${SCRIPTS_DIR_NAME}/nginx.conf ${NGINX_DEFAULT_CONF}

RUN mkdir -p ${UTIL_SCRIPTS_DIR}

# Copy util scripts
COPY --chown=${APP_USER}:${APP_USER} ./${SCRIPTS_DIR_NAME}/run.sh ${UTIL_SCRIPTS_DIR}/run.sh

# Set permissions for scripts
RUN chmod ug+x ${UTIL_SCRIPTS_DIR}/run.sh

# Logs Files
RUN mkdir -p ${LOG_DIR}/php ${LOG_DIR}/nginx ${LOG_DIR}/supervisor \
    && chown -R ${APP_USER}:${APP_USER} ${LOG_DIR}/php ${LOG_DIR}/nginx ${LOG_DIR}/supervisor \
    && chmod -R 774 ${LOG_DIR}/php ${LOG_DIR}/nginx ${LOG_DIR}/supervisor

EXPOSE $PORT
ENTRYPOINT ${UTIL_SCRIPTS_DIR}/run.sh

# Define Labels
LABEL maintainer="soporte@madlab.com.do" \
      project="API Base 10" \
      org.label-schema.name="api-madlab-description" \
      org.label-schema.description="API Base 10 image" \
      org.label-schema.url="https://madlab.com.do" \
      org.label-schema.schema-version="1.0"

WORKDIR ${WORK_DIR}

# Copy code to ${WORK_DIR}
COPY --chown=${APP_USER}:${APP_USER} . ${WORK_DIR}

# add root to www group
RUN chmod -R ug+w ${WORK_DIR}/storage

RUN composer install

RUN touch storage/logs/laravel.log \
    && chmod -R 777 $WORK_DIR \
    && chown -R $APP_USER:$APP_USER $WORK_DIR \
    && find $WORK_DIR -type f -exec chmod 644 {} \; \
    && find $WORK_DIR -type d -exec chmod 755 {} \; \
    && chgrp -R $APP_USER $WORK_DIR/storage $WORK_DIR/bootstrap/cache \
    && chmod -R ug+rwx $WORK_DIR/storage $WORK_DIR/bootstrap/cache

####################### Development Stage #######################
FROM base as dev
WORKDIR ${WORK_DIR}

####################### Development Stage #######################
FROM base as dev-rebuild
WORKDIR ${WORK_DIR}
ENV FRESH_INSTALL=1

####################### Production Stage #######################
FROM base as release
WORKDIR ${WORK_DIR}
USER ${APP_USER}
