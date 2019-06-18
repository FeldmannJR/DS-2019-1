FROM php:7.2.10-apache-stretch

RUN apt-get update -yqq && \
    apt-get install -y apt-utils zip unzip && \
    apt-get install -y nano && \
    apt-get install -y libzip-dev libpq-dev && \
    a2enmod rewrite && \
    docker-php-ext-install pdo_pgsql && \
    docker-php-ext-install pgsql && \
    docker-php-ext-configure zip --with-libzip && \
    docker-php-ext-install zip && \
    apt-get install -y --no-install-recommends \
    curl \
    libmemcached-dev \
    libz-dev \
    libpq-dev \
    libjpeg-dev \
    libpng-dev \
    libfreetype6-dev \
    libssl-dev \
    libmcrypt-dev \
    tzdata \
    supervisor \ 
    cron && \
    rm -rf /var/lib/apt/lists/*

# Instalando o node
RUN curl -sL https://deb.nodesource.com/setup_10.x | bash - && apt-get install -y nodejs

# Instalando o composer
RUN php -r "readfile('http://getcomposer.org/installer');" | php -- --install-dir=/usr/bin/ --filename=composer

# Copiando a config do apache
COPY default.conf /etc/apache2/sites-enabled/000-default.conf

# Instalando a extensão para as planilhas
RUN docker-php-ext-install gd

# Setando www-data como owner do app
RUN chown www-data:www-data /var/www/

# Crontab
COPY cronjobs /etc/cron.d/www-data
RUN chmod 0644 /etc/cron.d/www-data
RUN crontab -u www-data /etc/cron.d/www-data

# Gambiarra pro apache criar arquivos com permissão para o grupo
RUN echo 'umask 2' >> '/etc/apache2/envvars'

# Setando a timezone do container
ENV TZ=America/Sao_Paulo

# Inicializando os processos com o supervisord
COPY supervisord.conf /etc/supervisor/conf.d/supervisord.conf
CMD "/usr/bin/supervisord"

# Quando der attach já está na pasta certa
WORKDIR /var/www/app

# Porta do apache
EXPOSE 80
# Porta do websockets
EXPOSE 6001