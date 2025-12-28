FROM php:8.2-cli

# system deps + PHP extensions (pdo_pgsql) + netcat for startup wait
RUN apt-get update \
  && apt-get install -y libpq-dev zip unzip git netcat-openbsd \
  && docker-php-ext-install pdo pdo_pgsql \
  && rm -rf /var/lib/apt/lists/*

# Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# copy app files (build context should be the back-end folder)
COPY . /var/www/html

# install PHP deps if composer.json exists
RUN if [ -f composer.json ]; then composer install --no-dev --optimize-autoloader --no-interaction; fi

# start script
COPY start.sh /usr/local/bin/start.sh
RUN chmod +x /usr/local/bin/start.sh

EXPOSE 10000

# start script will use $PORT (fallback 10000)
CMD ["start.sh"]
