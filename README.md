# API TERRITORIAL DE LA REPUBLICA DOMINICANA

Este proyecto provee Provincias, Municipios y Sectores de República Dominicana en formato JSON y SQL. La información está actualizada al _27 de junio del 2023_.

## API Rest Laravel::Swagger

## 🗃️ Arrancar Proyecto

### MySQL/MariaDB

```shell
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate:refresh --seed
```

### Definir variables de entorno de la base de datos en el archivo .env

```shell
-   DB_CONNECTION=mysql
-   DB_HOST=127.0.0.1
-   DB_PORT=3306
-   DB_DATABASE=territorial2
-   DB_USERNAME=laravel
-   DB_PASSWORD=laravel
```
