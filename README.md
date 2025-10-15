# 🏢 Laravel ERP API

<div align="center">

![Laravel](https://img.shields.io/badge/Laravel-12.x-red?style=for-the-badge&logo=laravel&logoColor=white)
![PHP](https://img.shields.io/badge/PHP-8.2-blue?style=for-the-badge&logo=php&logoColor=white)
![PostgreSQL](https://img.shields.io/badge/PostgreSQL-15+-blue?style=for-the-badge&logo=postgresql&logoColor=white)
![License](https://img.shields.io/badge/License-MIT-green?style=for-the-badge)

**API RESTful robusta para sistema ERP empresarial construida con Laravel 12**

[📚 Documentación](#-documentación) •
[🚀 Instalación](#-instalación) •
[🔧 Configuración](#️-configuración) •
[📋 Endpoints](#-endpoints-principales) •
[🤝 Contribuir](#-contribuir)

</div>

---

## 📋 Tabla de Contenidos

- [🏢 Laravel ERP API](#-laravel-erp-api)
  - [📋 Tabla de Contenidos](#-tabla-de-contenidos)
  - [📖 Descripción](#-descripción)
  - [✨ Características](#-características)
  - [🛠️ Tecnologías Utilizadas](#️-tecnologías-utilizadas)
  - [📋 Requisitos del Sistema](#-requisitos-del-sistema)
  - [🚀 Instalación](#-instalación)
  - [⚙️ Configuración](#️-configuración)
  - [🗄️ Base de Datos](#️-base-de-datos)
  - [🔐 Autenticación](#-autenticación)
  - [📊 Endpoints Principales](#-endpoints-principales)
  - [📱 Códigos de Estado HTTP](#-códigos-de-estado-http)
  - [📚 Documentación de la API](#-documentación-de-la-api)
  - [🧪 Testing](#-testing)
  - [📦 Deployment](#-deployment)
  - [🤝 Contribuir](#-contribuir)
  - [📄 Licencia](#-licencia)
  - [👥 Equipo](#-equipo)

---

## 📖 Descripción

Esta API RESTful ha sido desarrollada con Laravel 12 para proporcionar una solución integral de ERP (Enterprise Resource Planning) que permite la gestión eficiente de recursos empresariales, incluyendo inventario, ventas, compras, contabilidad y recursos humanos.

## ✨ Características

- 🔐 **Autenticación Sanctum** - Sistema de autenticación seguro con tokens de API
- 🌐 **Multiidioma** - Soporte para español e inglés (es_PE locale)
- 🕐 **Zona Horaria** - Configurado para America/Lima
- 🏢 **Gestión de Empresas** - Múltiples empresas en una sola instalación
- 👥 **Gestión de Usuarios** - Control de acceso basado en roles
- 📦 **Inventario** - Control completo de productos y stock
- 💰 **Facturación** - Generación de facturas y cotizaciones con PDF
- 📊 **Reportes** - Dashboard con métricas en tiempo real
- 🔄 **API Versionada** - Versionado semántico de endpoints
- 📱 **CORS Configurado** - Compatible con aplicaciones frontend
- 🛡️ **Validación** - Validación robusta de datos de entrada
- 📝 **Logging** - Sistema de logs detallado
- 🚀 **Broadcasting** - WebSockets con Pusher para tiempo real

## 🛠️ Tecnologías Utilizadas

| Tecnología | Versión | Descripción |
|------------|---------|-------------|
| ![Laravel](https://img.shields.io/badge/-Laravel-red?logo=laravel&logoColor=white) | 12.x    | Framework PHP principal |
| ![PHP](https://img.shields.io/badge/-PHP-blue?logo=php&logoColor=white) | 8.2     | Lenguaje de programación |
| ![PostgreSQL](https://img.shields.io/badge/-PostgreSQL-blue?logo=postgresql&logoColor=white) | 15+     | Base de datos principal |
| ![MySQL](https://img.shields.io/badge/-MySQL-orange?logo=mysql&logoColor=white) | 8.0+    | Base de datos alternativa |
| ![MariaDB](https://img.shields.io/badge/-MariaDB-brown?logo=mariadb&logoColor=white) | 10.6+   | Base de datos alternativa |
| ![Sanctum](https://img.shields.io/badge/-Sanctum-red?logo=laravel&logoColor=white) | 4.x     | Autenticación API |
| ![Pusher](https://img.shields.io/badge/-Pusher-purple?logo=pusher&logoColor=white) | 7.x     | WebSockets y broadcasting |

## 📋 Requisitos del Sistema

- **PHP:** 8.2
- **Composer:** >= 2.0
- **PostgreSQL:** >= 15 (recomendado) o **MySQL:** >= 8.0 o **MariaDB:** >= 10.6
- **Node.js:** >= 18 (para Vite)
- **wkhtmltopdf** (para generación de PDFs)
- **Extensiones PHP:**
  - BCMath
  - Ctype
  - JSON
  - Mbstring
  - OpenSSL
  - PDO
  - pdo_pgsql (para PostgreSQL)
  - pdo_mysql (para MySQL/MariaDB)
  - Tokenizer
  - XML
  - GD o Imagick
  - Redis (opcional)

## 🚀 Instalación

### 1️⃣ Clonar el Repositorio

```bash
git clone https://github.com/tu-usuario/laravel-erp-api.git
cd laravel-erp-api
```

### 2️⃣ Instalar Dependencias

```bash
composer install
```

### 3️⃣ Configurar Variables de Entorno

```bash
cp .env.example .env
php artisan key:generate
```

### 4️⃣ Configurar Base de Datos

```bash
php artisan migrate
php artisan db:seed
```

### 5️⃣ Instalar Sanctum

```bash
php artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider"
```

### 6️⃣ Configurar Frontend (opcional)

```bash
npm install
npm run build
```

### 7️⃣ Iniciar Servidor de Desarrollo

```bash
php artisan serve
```

La API estará disponible en `https://api-dezainfullerp.test` (usando Valet) o `http://localhost:8000`

## ⚙️ Configuración

### Variables de Entorno Principales

```env
# 🌐 Aplicación
APP_NAME=Laravel
APP_ENV=local
APP_URL=https://api-dezainfullerp.test
APP_TIMEZONE="America/Lima"
APP_LOCALE=es
APP_FALLBACK_LOCALE=en
APP_FAKER_LOCALE=es_PE

# 🗄️ Base de Datos (PostgreSQL - Principal)
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=db_api_dezainpo
DB_USERNAME=postgres
DB_PASSWORD=postgres

# 🗄️ Base de Datos (MySQL - Alternativa)
# DB_CONNECTION=mysql
# DB_HOST=127.0.0.1
# DB_PORT=3306
# DB_DATABASE=tu_database
# DB_USERNAME=tu_usuario
# DB_PASSWORD=tu_contraseña

# 🗄️ Base de Datos (MariaDB - Alternativa)
# DB_CONNECTION=mysql
# DB_HOST=127.0.0.1
# DB_PORT=3306
# DB_DATABASE=tu_database
# DB_USERNAME=tu_usuario
# DB_PASSWORD=tu_contraseña

# 📧 Email
MAIL_MAILER=log
MAIL_HOST=127.0.0.1
MAIL_PORT=2525
MAIL_FROM_ADDRESS="hello@example.com"
MAIL_FROM_NAME="${APP_NAME}"

# 🔐 Sanctum
SANCTUM_STATEFUL_DOMAINS=""
CORS_ALLOWED_ORIGINS="http://localhost:3000"

# 📦 Cache y Sesiones
CACHE_STORE=database
SESSION_DRIVER=database
SESSION_LIFETIME=120
QUEUE_CONNECTION=database

# 🚀 Broadcasting (Pusher)
BROADCAST_CONNECTION=pusher
PUSHER_APP_ID=tu_app_id
PUSHER_APP_KEY=tu_app_key
PUSHER_APP_SECRET=tu_app_secret
PUSHER_APP_CLUSTER=us2
PUSHER_PORT=443
PUSHER_SCHEME=https

# 📄 PDF Generation
WKHTML_PDF_BINARY="ruta/a/wkhtmltopdf"
WKHTML_IMG_BINARY="ruta/a/wkhtmltoimage"

# 🛠️ Desarrollo
VITE_APP_NAME="${APP_NAME}"
```

## 🗄️ Base de Datos

### Estructura Principal

```
📊 Módulos Principales:
├── 👥 users (usuarios)
├── 🏢 companies (empresas)  
├── 🏷️ roles (roles y permisos)
├── 📦 products (productos)
├── 📋 categories (categorías)
├── 🛒 orders (órdenes)
├── 💰 invoices (facturas)
├── 👤 customers (clientes)
└── 📈 reports (reportes)
```

### Configuraciones de Base de Datos

#### PostgreSQL (Recomendado)
```env
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=db_api_dezainpo
DB_USERNAME=postgres
DB_PASSWORD=postgres
```

#### MySQL/MariaDB (Alternativa)
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=tu_database
DB_USERNAME=tu_usuario
DB_PASSWORD=tu_contraseña
```

### Migrar y Sembrar

```bash
# Ejecutar migraciones
php artisan migrate

# Sembrar datos de prueba
php artisan db:seed

# Refrescar base de datos
php artisan migrate:fresh --seed
```

## 🔐 Autenticación

La API utiliza **Laravel Sanctum** para la autenticación mediante tokens de API.

### Login

```http
POST /api/v1/auth/login
Content-Type: application/json

{
  "email": "admin@example.com",
  "password": "password"
}
```

### Respuesta Exitosa

```json
{
  "success": true,
  "data": {
    "token": "1|eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9...",
    "token_type": "Bearer",
    "user": {
      "id": 1,
      "name": "Administrador",
      "email": "admin@example.com",
      "email_verified_at": "2024-01-01T00:00:00.000000Z"
    }
  }
}
```

### Headers de Autorización

```http
Authorization: Bearer tu_sanctum_token
Content-Type: application/json
Accept: application/json
X-Requested-With: XMLHttpRequest
```

### Revocar Token

```http
POST /api/v1/auth/logout
Authorization: Bearer tu_sanctum_token
```

## 📊 Endpoints Principales

### 🔐 Autenticación

| Método | Endpoint | Descripción |
|--------|----------|-------------|
| `POST` | `/api/v1/auth/login` | 🔑 Iniciar sesión |
| `POST` | `/api/v1/auth/logout` | 🚪 Cerrar sesión / Revocar token |
| `GET` | `/api/v1/auth/user` | 👤 Perfil del usuario autenticado |

### 👥 Usuarios

| Método | Endpoint | Descripción |
|--------|----------|-------------|
| `GET` | `/api/v1/users` | 📋 Listar usuarios |
| `POST` | `/api/v1/users` | ➕ Crear usuario |
| `GET` | `/api/v1/users/{id}` | 👁️ Ver usuario |
| `PUT` | `/api/v1/users/{id}` | ✏️ Actualizar usuario |
| `DELETE` | `/api/v1/users/{id}` | 🗑️ Eliminar usuario |

### 📦 Productos

| Método | Endpoint | Descripción |
|--------|----------|-------------|
| `GET` | `/api/v1/products` | 📋 Listar productos |
| `POST` | `/api/v1/products` | ➕ Crear producto |
| `GET` | `/api/v1/products/{id}` | 👁️ Ver producto |
| `PUT` | `/api/v1/products/{id}` | ✏️ Actualizar producto |
| `DELETE` | `/api/v1/products/{id}` | 🗑️ Eliminar producto |

### 🛒 Órdenes

| Método | Endpoint | Descripción |
|--------|----------|-------------|
| `GET` | `/api/v1/orders` | 📋 Listar órdenes |
| `POST` | `/api/v1/orders` | 🛒 Crear órden |
| `GET` | `/api/v1/orders/{id}` | 👁️ Ver órden |
| `PUT` | `/api/v1/orders/{id}` | ✏️ Actualizar órden |

### 💰 Facturación

| Método | Endpoint | Descripción |
|--------|----------|-------------|
| `GET` | `/api/v1/invoices` | 📋 Listar facturas |
| `POST` | `/api/v1/invoices` | 💰 Crear factura |
| `GET` | `/api/v1/invoices/{id}` | 👁️ Ver factura |
| `GET` | `/api/v1/invoices/{id}/pdf` | 📄 Descargar PDF |

## 📱 Códigos de Estado HTTP

| Código | Descripción |
|--------|-------------|
| `200` | ✅ Éxito |
| `201` | ✅ Creado |
| `400` | ❌ Solicitud incorrecta |
| `401` | 🔐 No autorizado |
| `403` | 🚫 Prohibido |
| `404` | 🔍 No encontrado |
| `422` | ❌ Error de validación |
| `500` | ⚠️ Error interno del servidor |

## 📚 Documentación de la API

### 📖 Swagger/OpenAPI

La documentación completa de la API está disponible en:

```
https://api-dezainfullerp.test/api/documentation
```

### 📋 Postman Collection

Importa la colección de Postman desde:

```
docs/postman/Laravel-ERP-API.postman_collection.json
```

## 🧪 Testing

### Ejecutar Tests

```bash
# Todos los tests
php artisan test

# Tests específicos
php artisan test --filter=UserTest

# Con coverage
php artisan test --coverage
```

### Tests Disponibles

- ✅ **Unit Tests:** Lógica de negocio
- ✅ **Feature Tests:** Endpoints de API
- ✅ **Integration Tests:** Integración con servicios externos

## 📦 Deployment

### 🐳 Docker

```bash
# Construir imagen
docker build -t laravel-erp-api .

# Ejecutar contenedor
docker-compose up -d
```

### ☁️ Producción

1. **Optimizar aplicación:**
```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache
php artisan optimize
```

2. **Variables de entorno de producción:**
```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://tu-dominio.com
DB_CONNECTION=pgsql
CACHE_STORE=redis
SESSION_DRIVER=redis
QUEUE_CONNECTION=redis
```

3. **Configurar Supervisor para colas:**
```bash
php artisan queue:work --daemon
```

## 🤝 Contribuir

¡Las contribuciones son bienvenidas! Por favor sigue estos pasos:

1. 🍴 Fork el proyecto
2. 🌿 Crea una rama para tu feature (`git checkout -b feature/nueva-caracteristica`)
3. 📝 Commit tus cambios (`git commit -am 'Añade nueva característica'`)
4. 📤 Push a la rama (`git push origin feature/nueva-caracteristica`)
5. 🎯 Abre un Pull Request

### 📝 Estándares de Código

- **PSR-12:** Estándar de codificación PHP
- **PHPStan:** Análisis estático de código
- **Tests:** Cobertura mínima del 80%

## 📄 Licencia

Este proyecto está licenciado bajo la Licencia MIT. Ver el archivo [LICENSE](LICENSE) para más detalles.

---

## 👥 Equipo

<div align="center">

| Rol | Persona                       | Contacto             |
|-----|-------------------------------|----------------------|
| 💻 **Lead Developer** | Jonny Sebastian Ponce Avilez  | jonny47169@gmail.com |
| 🎨 **UI/UX** | Jonny Sebastian Ponce Avilez  | jonny47169@gmail.com   |
| ⚙️ **DevOps** | Jonny Sebastian Ponce Avilez  | jonny47169@gmail.com   |

</div>

---

<div align="center">

**⭐ Si este proyecto te ha sido útil, no olvides darle una estrella ⭐**

![Made with Laravel](https://img.shields.io/badge/Made%20with-Laravel%2012-red?style=for-the-badge&logo=laravel&logoColor=white)
![Made with Love](https://img.shields.io/badge/Made%20with-❤️-red?style=for-the-badge)

**📞 ¿Necesitas ayuda?** [Abrir Issue](https://github.com/tu-usuario/laravel-erp-api/issues/new)

</div>
