# Contacts API

API de contactos con autenticación usando Laravel + Sanctum.

## Requisitos

- PHP 8.2+
- Composer
- MySQL

## Instalación

```bash
git clone <url-del-repositorio>
cd Contacts_api
composer install
cp .env.example .env
```

Configura la base de datos en `.env`:

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=contacts_api
DB_USERNAME=root
DB_PASSWORD=tu_password
```

Generar clave y ejecutar migraciones:

```bash
php artisan key:generate
php artisan migrate
```

(Opcional) Poblar la base de datos con datos de prueba:

```bash
php artisan db:seed
```

## Levantar servidor

```bash
php artisan serve
```

## Endpoints

### Autenticación

**Registrar usuario**
```bash
curl -X POST http://127.0.0.1:8000/api/register \
  -H "Content-Type: application/json" \
  -d "{\"name\":\"Juan\",\"email\":\"juan@test.com\",\"password\":\"12345678\",\"password_confirmation\":\"12345678\"}"
```

**Iniciar sesión**
```bash
curl -X POST http://127.0.0.1:8000/api/login \
  -H "Content-Type: application/json" \
  -d "{\"email\":\"juan@test.com\",\"password\":\"12345678\"}"
```

**Cerrar sesión**
```bash
curl -X POST http://127.0.0.1:8000/api/logout \
  -H "Authorization: Bearer TU_TOKEN"
```

### Usuario

**Actualizar perfil**
```bash
curl -X POST http://127.0.0.1:8000/api/user/update \
  -H "Content-Type: application/json" \
  -H "Authorization: Bearer TU_TOKEN" \
  -d "{\"name\":\"Juan Actualizado\",\"email\":\"juan2@test.com\"}"
```

### Contactos

**Crear contacto**
```bash
curl -X POST http://127.0.0.1:8000/api/contacts \
  -H "Content-Type: application/json" \
  -H "Authorization: Bearer TU_TOKEN" \
  -d "{\"name\":\"Pedro\",\"phone_number\":\"123456789\"}"
```

**Listar contactos**
```bash
curl -X GET http://127.0.0.1:8000/api/contacts \
  -H "Authorization: Bearer TU_TOKEN"
```

## Resumen de endpoints

| Método | Ruta | Auth | Descripción |
|--------|------|------|-------------|
| POST | `/api/register`  | Registrar usuario |
| POST | `/api/login`  | Iniciar sesión |
| POST | `/api/logout`  | Cerrar sesión |
| POST | `/api/user/update` | Actualizar perfil |
| GET | `/api/contacts` | Listar contactos |
| POST | `/api/contacts`  | Crear contacto |

## Usuarios de prueba (db:seed)

| ID | Email | Contraseña |
|----|-------|------------|
| 2 | nat89@example.org | `password` |
| 3 | nnienow@example.net | `password` |
| 4 | abshire.santina@example.org | `password` |
| 5 | harmon.greenfelder@example.org | `password` |
| 6 | stacey.rodriguez@example.com | `password` |
| 7 | humberto43@example.org | `password` |
| 8 | dcronin@example.net | `password` |
| 9 | amaya.heathcote@example.com | `password` |
| 10 | adams.emmanuel@example.org | `password` |
| 11 | else.hodkiewicz@example.com | `password` |

## Ejecutar tests

```bash
php artisan test
```
