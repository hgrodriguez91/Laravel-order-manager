# Laravel DDD + Arquitectura Hexagonal — Ejemplo Práctico

Este proyecto es un ejemplo educativo de cómo estructurar una aplicación **Laravel** usando **DDD (Domain-Driven Design)** y **Arquitectura Hexagonal (Ports & Adapters)**.

La meta es **separar claramente la lógica de negocio del framework**, permitiendo que el dominio sea independiente de Laravel, la base de datos o cualquier tecnología externa.

---

# 🧠 Conceptos Clave

| Concepto                   | Significado en este proyecto                      |
| -------------------------- | ------------------------------------------------- |
| **DDD**                    | El dominio contiene las reglas reales del negocio |
| **Arquitectura Hexagonal** | El dominio no depende de infraestructura          |
| **Puertos (Ports)**        | Interfaces que el dominio define                  |
| **Adaptadores (Adapters)** | Implementaciones técnicas (Eloquent, API, etc.)   |
| **Caso de Uso**            | Orquesta una acción del sistema                   |

---

# 📁 Estructura del Proyecto

```
app/
 ├── Domain/
 │    └── Order/
 │         ├── Entities/
 │         ├── ValueObjects/
 │         ├── Repositories/
 │         ├── Services/
 │         └── Events/
 │
 ├── Application/
 │    └── Order/
 │         └── UseCases/
 │
 ├── Infrastructure/
 │    └── Persistence/
 │         └── Eloquent/
 │              ├── Models/
 │              └── Repositories/
 │
 └── Http/
      ├── Controllers/
      ├── Requests/
      └── Resources/
```

---

# 🧩 Capa de Dominio (`Domain`)

Aquí vive **el corazón del sistema**. No hay Laravel, no hay Eloquent, no hay HTTP.

### Contiene:

### ✅ Entidades

Representan objetos con identidad y reglas de negocio.

Ejemplo: `Order`

Responsabilidades:

* Confirmar una orden
* Calcular total
* Disparar eventos de dominio

### ✅ Value Objects

Objetos inmutables que representan conceptos del negocio.

Ejemplo: `Money`

Ventaja: evita errores como mezclar monedas o usar floats.

### ✅ Repositorios (Interfaces)

Definen **qué necesita el dominio** para persistir datos.

```php
interface OrderRepository {
    public function save(Order $order): void;
}
```

⚠️ No hay implementación aquí, solo contratos.

### ✅ Eventos de Dominio

Representan cosas que *ya ocurrieron* dentro del negocio.

Ejemplo: `OrderConfirmed`

---

# ⚙️ Capa de Aplicación (`Application`)

Contiene los **Casos de Uso**, que coordinan el dominio.

Ejemplo: `CreateOrderUseCase`

Responsabilidades:

* Recibir datos (DTO)
* Crear entidad
* Llamar métodos del dominio
* Guardar usando el repositorio

No sabe nada de HTTP ni de Laravel Controllers.

---

# 🏗 Capa de Infraestructura (`Infrastructure`)

Aquí vive todo lo técnico.

### Adaptadores incluidos:

| Adaptador                 | Función                            |
| ------------------------- | ---------------------------------- |
| **Eloquent Models**       | Representación de tablas           |
| **Eloquent Repositories** | Implementan interfaces del dominio |

Ejemplo: `EloquentOrderRepository implements OrderRepository`

Traduce:

* Entidades → Base de datos
* Value Objects → tipos primitivos

---

# 🌐 Capa de Presentación (`Http`)

Esta capa sí depende de Laravel.

### Contiene:

| Elemento         | Función                                |
| ---------------- | -------------------------------------- |
| **Controllers**  | Reciben requests y llaman casos de uso |
| **FormRequests** | Validan datos de entrada               |
| **Resources**    | Transforman respuestas JSON            |

---

# 🔄 Flujo Completo de una Petición

1. Cliente envía request HTTP
2. FormRequest valida datos
3. Controller llama Caso de Uso
4. Caso de Uso crea Entidad
5. Dominio ejecuta reglas de negocio
6. Repositorio guarda datos
7. Evento de dominio se dispara
8. Resource devuelve respuesta

---

# 🐳 Ejecutar el Proyecto con Docker

## 1️⃣ Estructura de Docker

```
/docker
   ├── nginx
   └── php
Dockerfile
docker-compose.yml
```

---

## 2️⃣ docker-compose.yml

```yaml
version: '3.8'

services:
  app:
    build:
      context: .
      dockerfile: Dockerfile
    container_name: laravel_app
    volumes:
      - .:/var/www
    networks:
      - laravel

  nginx:
    image: nginx:alpine
    container_name: laravel_nginx
    ports:
      - "8000:80"
    volumes:
      - .:/var/www
      - ./docker/nginx/default.conf:/etc/nginx/conf.d/default.conf
    depends_on:
      - app
    networks:
      - laravel

  db:
    image: mysql:8.0
    container_name: laravel_db
    restart: unless-stopped
    environment:
      MYSQL_DATABASE: laravel
      MYSQL_ROOT_PASSWORD: root
    ports:
      - "3306:3306"
    networks:
      - laravel

networks:
  laravel:
```

---

## 3️⃣ Dockerfile

```Dockerfile
FROM php:8.2-fpm

RUN apt-get update && apt-get install -y \
    git curl libpng-dev libonig-dev libxml2-dev zip unzip

RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www
```

---

## 4️⃣ Configuración de Nginx

`docker/nginx/default.conf`

```nginx
server {
    listen 80;
    index index.php index.html;
    root /var/www/public;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass app:9000;
        fastcgi_index index.php;
        include fastcgi_params;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
    }
}
```

---

# 🚀 Pasos para levantar el proyecto

```bash
cp .env.example .env

docker-compose up -d --build

docker exec -it laravel_app composer install

docker exec -it laravel_app php artisan key:generate

docker exec -it laravel_app php artisan migrate
```

Abrir en navegador:

👉 [http://localhost:8000](http://localhost:8000)

---

# 🎯 Beneficios de esta Arquitectura

✅ Dominio independiente de Laravel
✅ Fácil de testear
✅ Reglas de negocio protegidas
✅ Infraestructura intercambiable
✅ Código mantenible a largo plazo

---

Si este proyecto fuera real, el siguiente paso sería:

* Tests unitarios del dominio
* Tests de integración para repositorios
* Uso de colas para eventos
