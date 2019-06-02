# Desenvolvimento-de-Software-2019-1
Repositório utilizado para o desenvolvimento do projeto para a disciplina de Desenvolvimento de Software

# Docker Compose
Com o docker compose fica mais facil de criar multiplos containers e conectar todos com uma network, para isso é criado o docker-compose.yml com instruções de criação dos containers

Para executar os comandos é necessário estar no diretorio do arquivo

## Comandos

``` bash
# Para criar os containers/networks/volumes e startar eles, -d de detached
docker-compose up -d
# Para iniciar os serviços dos containers
docker-compose start
# Para 'desligar' os containers
docker-compose stop
# Para remover os containers
docker-compose rm
# Para entrar em um terminal dentro do container com o php
./container
```

# Configurações Iniciais
## Permissões
É necessário criar um grupo na sua maquina com o ID 33 e adicionar seu usuario a ele, pois todos os arquivos do dentro do app/ estão com permissões para este grupo.
Você pode fazer isto com os seguintes comandos:
```bash
# Provavelmente se você já instalou o apache na sua maquina(não no docker), você já tem este grupo
groupadd --gid 33 www-data
# Adicionando seu usuario ao grupo
usermod -a -G www-data $USER
```

## Dentro do container
- Para entrar na linha de comando do container basta executar o ``./container``
- Baixar dependencias
``composer install``
- Executar migrações do banco de dados
``php artisan migrate``
- Inserir Indicadores no banco
``php artisan db:seed``

## PgAdmin
- Importar dump do HE, instruções no ava

# Artisan

Linha de comandos do laravel, é preciso executar as migrações do banco de dados com o comando

``` bash
    # Cria a tabela de migrações no banco de dados
    php artisan migrate:install
    # Cria as tabelas de migração
    php artisan migrate
```
Ler mais em https://laravel.com/docs/5.8/migrations

## Acesso
### Laravel

http://localhost:80

### Pg Admin
Para acessar o pgadmin é só acessar o http://localhost:8081
### Postgre
Porta 54320 aberta para o host
