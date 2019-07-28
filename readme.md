<p align="center"><img src="https://laravel.com/assets/img/components/logo-laravel.svg"></p>

# Projeto Prático PMZ-SPA

> Desenvolvido em Laravel e VueJS

## Baixar o projeto
Primeiro passo, clonar o projeto:
``` bash
# Clonar
git clone https://github.com/dirsouza/pmz-spa.git

# Acessar
cd pmz-spa
```

## Configuração - Backend

``` bash
# Instalar dependências do projeto
composer install

# Configurar variáveis de ambiente
cp .env.example .env
php artisan key:generate

# Criar tabelas
php artisan migrate

# Criar dados faker
php artisan db:seed

# Criar tabelas e dados faker
php artisan migrate --seed
```

## Configuração - Frontend
``` bash
# Atualizar dependências
npm install

# Rodar em ambiente local
npm run dev

# Rodar em ambiente de produção
npm run build
```