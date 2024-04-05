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

Para conectarnos con nuestro usuario en lugar de root:
```
docker compose exec --user 1001 app bash
```

Se uso el siguiente comando para crear la aplicación de Laravel 8 (No es necesario ejecutar este comando):

```
composer create-project --prefer-dist laravel/laravel:^8.0 .
```

## Correr las migraciones y los seeders

```
php artisan migrate:fresh --seed
```

## Eliminar las migraciones
En caso de que hagamos cambios en los modelos y queremos eliminar toda la información de la base de datos podemos usar el siguiente comando.

```
php artisan migrate:rollback
```
