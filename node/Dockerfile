FROM node:10


COPY entrypoint.sh /entrypoint.sh

# Instala o supervisor no sistema
RUN apt-get update && apt-get install -y supervisor

RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Inicializando os processos com o supervisord
COPY supervisord.conf /etc/supervisor/supervisord.conf
# Copiando os programas

WORKDIR /sites/he/

ENTRYPOINT [ "/entrypoint.sh" ]