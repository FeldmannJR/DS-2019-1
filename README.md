# Desenvolvimento-de-Software-2019-1
Repositório utilizado para o desenvolvimento do projeto para a disciplina de Desenvolvimento de Software

# Current containers setup
``` bash
docker pull postgres
docker pull dpage/pgadmin4
docker network create --driver bridge HE-network

docker run --name HE-postgres \
           --network=HE-network \
           -e "POSTGRES_PASSWORD=password" \
           -p 5432:5432 \
           -v /home/jonathas/Repositories/Desenvolvimento-de-Software-2019-1/Postgres:/var/lib/postgresql/data:z \
           -d postgres

docker run --name HE-pgadmin \
           --network=HE-network \
           -p 8080:80 \
           -e "PGADMIN_DEFAULT_EMAIL=foo@bar.com" \
           -e "PGADMIN_DEFAULT_PASSWORD=password" \
           -d dpage/pgadmin4

```
