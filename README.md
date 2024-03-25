# Backend
Repositorio para el desarrollo del lado del Backend del sistema de asignación de ambientes para la universidad mayor de san simón.

## Uso

Correr todos los contenedores de docker para usar la aplicación:

```
docker compose up -d --build
```

Cada vez que queramos conectarnos a la aplicación de Laravel usaremos el siguiente comando:

```
docker compose exec app bash
```

Se uso el siguiente comando para crear la aplicación de Laravel 8 (No es necesario ejecutar este comando):

```
composer create-project --prefer-dist laravel/laravel:^8.0 .
```