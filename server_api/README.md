## Project Setup

```sh
composer update
```
```sh
php artisan migrate:fresh
```
```sh
php artisan db:seed
```
```sh
php artisan storage:link
```
```sh
php artisan passport:install
```
- Copiar o token do Client 2 (que aparece na consola) e colar em server_api/app/Http/Controllers/api/AuthController na constante "CLIENT_SECRET"
