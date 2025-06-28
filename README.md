# Sistema de Gestión de Equipos

Sistema web completo para la gestión de equipos y usuarios, desarrollado con Laravel (backend) y Vue.js (frontend).

## 🚀 Características

- **Autenticación completa** con Laravel Sanctum
- **Gestión de usuarios** (CRUD) con roles (admin/user)
- **Gestión de equipos** (CRUD) con búsqueda
- **Dashboard** con estadísticas
- **Validaciones** robustas en frontend y backend
- **Interfaz responsive** con Bootstrap

## 🛠️ Tecnologías

### Backend
- **Laravel 12** - Framework PHP
- **MySQL** - Base de datos
- **Laravel Sanctum** - Autenticación API
- **Bootstrap** - Framework CSS

### Frontend
- **Vue.js 3** - Framework JavaScript
- **Vite** - Build tool
- **Bootstrap 5** - Framework CSS
- **Axios** - Cliente HTTP
- **Pinia** - State management

## 📋 Requisitos

- PHP 8.2+
- Node.js 16+
- MySQL 8.0+
- Composer
- npm

## 🔧 Instalación

### 1. Clonar el repositorio
```bash
git clone <tu-repositorio>
cd examen
```

### 2. Configurar el Backend (Laravel)

```bash
cd back

# Instalar dependencias
composer install

# Copiar archivo de configuración
cp .env.example .env

# Generar clave de aplicación
php artisan key:generate

# Configurar base de datos en .env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=examen
DB_USERNAME=root
DB_PASSWORD=

# Ejecutar migraciones y seeders
php artisan migrate:fresh --seed

# Iniciar servidor
php artisan serve --host=0.0.0.0 --port=8000
```

### 3. Configurar el Frontend (Vue.js)

```bash
cd front

# Instalar dependencias
npm install

# Iniciar servidor de desarrollo
npm run dev
```

## 👥 Usuarios de Prueba

El sistema incluye usuarios de prueba creados automáticamente:

- **Administrador**: admin@example.com / password
- **Usuario**: user1@example.com / password

## 📁 Estructura del Proyecto

```
examen/
├── back/                 # Backend Laravel
│   ├── app/
│   │   ├── Http/Controllers/
│   │   ├── Models/
│   │   └── Providers/
│   ├── database/
│   │   ├── migrations/
│   │   └── seeders/
│   └── routes/
├── front/                # Frontend Vue.js
│   ├── src/
│   │   ├── components/
│   │   ├── views/
│   │   ├── stores/
│   │   └── router/
│   └── public/
└── README.md
```

## 🔐 API Endpoints

### Autenticación
- `POST /api/login` - Iniciar sesión
- `POST /api/register` - Registrar usuario
- `POST /api/logout` - Cerrar sesión
- `GET /api/me` - Obtener usuario actual

### Usuarios (Solo Admin)
- `GET /api/users` - Listar usuarios
- `POST /api/users` - Crear usuario
- `GET /api/users/{id}` - Obtener usuario
- `PUT /api/users/{id}` - Actualizar usuario
- `DELETE /api/users/{id}` - Eliminar usuario

### Equipos
- `GET /api/equipos` - Listar equipos
- `POST /api/equipos` - Crear equipo
- `GET /api/equipos/{id}` - Obtener equipo
- `PUT /api/equipos/{id}` - Actualizar equipo
- `DELETE /api/equipos/{id}` - Eliminar equipo

## 🎨 Funcionalidades

### Dashboard
- Estadísticas de equipos registrados
- Estadísticas de usuarios (solo admin)
- Navegación rápida

### Gestión de Equipos
- Lista de equipos con búsqueda
- Crear, editar y eliminar equipos
- Validaciones en tiempo real
- Campos: tipo de motor, nombre, fecha de fabricación, potencia, velocidad

### Gestión de Usuarios (Solo Admin)
- Lista de usuarios
- Crear, editar y eliminar usuarios
- Asignación de roles (admin/user)
- Validaciones completas

## 🔒 Seguridad

- Autenticación con tokens JWT
- Validaciones en frontend y backend
- Roles y permisos
- Protección CSRF
- Sanitización de datos

## 🚀 Despliegue

### Backend
```bash
cd back
composer install --optimize-autoloader --no-dev
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### Frontend
```bash
cd front
npm run build
```

## 📝 Licencia

Este proyecto es de uso educativo y demostrativo.

## 🤝 Contribución

1. Fork el proyecto
2. Crea una rama para tu feature
3. Commit tus cambios
4. Push a la rama
5. Abre un Pull Request 