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
Só dar docker-compose up 



## Comandos
- Baixar dependencias
``./composer install``
- Executar migrações do banco de dados (https://laravel.com/docs/5.8/migrations)
``./artisan migrate``
- Inserir Indicadores no banco, irá pedir confirmação para adicionar dados aleatórias para testar
``./artisan db:seed``
- Instalar as dependencias do node
``./npm install``
- Watch changes in assets
``./watch
``
## PgAdmin
- Importar dump do HE, instruções no ava

Caso alguém queira, da para automatizar este processo, não fiz pois não senti necessidade!

# Artisan

Linha de comandos do laravel, é preciso executar as migrações do banco de dados com o comando

``` bash
    # Entra em um terminal interativo com o laravel onde é possivel testar instruções
    ./artisan tinker
```
Ler mais em https://laravel.com/docs/5.8/migrations

### Laravel

http://localhost:80

### Pg Admin
Para acessar o pgadmin é só acessar o http://localhost:8081
### Postgre
Porta 54320 aberta para o host

### Onde estão as coisas?

#### Rotas
As rotas basicamente dizem qual url chama qual função, para as nossas rotas estamos utilizando o arquivo `routes/web.php` para definir quais funções chamar!


#### Indicadores
A estrutura do banco dos indicadores se encontra nos arquivos dentro da pasta app/Indicators 
- `ModelIndicators` logica de pegar/salvar do banco os indicadores
- `Indicator` classe pai de todos os indicadores, contem todos os atributos base do indicador(nome,id,update_type...) e a lógica para salvar e pegar o ultimo valor
- `IndicatorSpreadsheet` classe pai de todos os indicadores que usam planilhas, contendo lógica para pegar as informações necessárias
- `IndicatorSql` classe pai de todos os indicadores que usam o banco de dados, ela fornece uma conexão para o banco de dados!
- `IndicatorSimpleSql` Indicadores que são resolvidos com uma query

Os indicadores são criados no seeder(`database/seeders/IndicatorTableSeeder`), quando é executa o comando ``db:seed`` são adicionados todos os indicadores no banco.

O `IndicatorsController` chama as views com os indicadores puxados do banco!

#### Views
As views estão localizadas dentro de `resources/views/`

