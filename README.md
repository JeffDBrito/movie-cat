# Detalhes

> Você pode acessar mais detalhes sobre esse projeto no arquivo de documentação através desse link:
> https://www.notion.so/Documenta-o-do-Projeto-Movie-Cat-1d80cdaec7b0803d847ff2b501a2835e?pvs=4

## Instalação da aplicação

> 1 - Faça uma clonagem do projeto com o comando

```bash
git clone git@github.com:JeffDBrito/movie-cat.git
```

> 2 - Após clonar a aplicação você precisará configurar as variáveis de ambiente

```bash
cp .env.example .env
cp ./backend/.env.example ./backend/.env
cp ./frontend/.env.example ./frontend/.env
```

> 3 - Insira no `./backend/.env` a sua chave da **api do TMDB** na variável

```bash
# TMDB API
TMDB_API_KEY=
```

> 4 - Depois instale as dependências

```bash
cd backend
composer install

cd ../frontend
npm install
```

> 5 - Depois suba os containers docker

```bash
# Build normal
docker compose up --build
# Ou Builds Detached | Para executar a aplicação em segundo plano
docker compose up --build -d
```

> 6 - Depois gere a chave da aplicação

```bash
# Fora do container de backend
docker exec -it backend-laravel php artisan key:generate
```

> 7 - Depois execute a migration

```bash
docker exec -it backend-laravel php artisan migrate
```

> 8 - E pronto! A Aplicação estará pronta para ser utilizada acessando
> 
- http://localhost:5173/