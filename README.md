# Sistema de Gestión Interna — Gobernación del Guayas

Sistema web de gestión interna para la Gobernación del Guayas (Ecuador), construido en PHP puro con arquitectura MVC, sin dependencias de frameworks externos.

---

## Módulos del sistema

| Módulo | Descripción |
|---|---|
| **Colegios** | Registro de instituciones educativas con datos del rector, dirección, teléfono, distrito y vinculación a CNEL |
| **CNEL / Luminarias** | Registro de trabajos de luminarias públicas (nuevas, mantenimiento, tipo, cantidad de postes, estado, trabajador) |
| **Encuentros Ciudadanos** | Registro de encuentros con ciudadanos por parroquia (dirección, estado, contacto) |
| **Actas / Trabajadores** | Registro de trabajadores con código, cédula y parroquia para actas de entrega-recepción |

---

## Arquitectura MVC

El proyecto sigue el patrón **Modelo-Vista-Controlador** con un front controller único:

```
PHP-repo/
├── config/
│   └── app.php                    # Configuración centralizada (DB, URL, sesión)
│
├── database/
│   └── schema.sql                 # Esquema SQL completo con relaciones
│
├── public/                        # ← Document Root (apuntar Apache aquí)
│   ├── index.php                  # Front controller — único punto de entrada HTTP
│   ├── .htaccess                  # Reescritura de URLs (mod_rewrite)
│   ├── assets/
│   │   ├── css/app.css
│   │   └── img/
│   └── documentos/                # PDFs accesibles públicamente
│
├── src/                           # Código fuente (protegido del navegador)
│   ├── bootstrap.php              # Autoloader PSR-4 + definición de rutas
│   ├── Core/
│   │   ├── Router.php             # Router HTTP (GET/POST)
│   │   └── helpers.php            # e(), url(), asset(), csrf*()
│   ├── Config/
│   │   └── Database.php           # Singleton PDO
│   ├── Controllers/
│   │   ├── BaseController.php     # render(), redirect(), requireAuth(), flash
│   │   ├── AuthController.php
│   │   ├── HomeController.php
│   │   ├── ColegioController.php
│   │   ├── CnelController.php
│   │   ├── EncuentroController.php
│   │   └── ActaController.php
│   ├── Models/
│   │   ├── BaseModel.php          # all(), find(), count(), create()
│   │   ├── User.php
│   │   ├── Colegio.php
│   │   ├── Cnel.php
│   │   ├── Encuentro.php
│   │   └── Acta.php
│   └── Views/
│       ├── layout/{header,nav,footer}.php
│       ├── auth/login.php
│       ├── home/index.php
│       ├── colegios/{form,report}.php
│       ├── cnel/{form,report}.php
│       ├── encuentros/{form,report}.php
│       └── actas/{form,report}.php
│
└── setup.php                      # Script de instalación (ejecutar 1 vez)
```

### Flujo de una petición HTTP

```
Navegador → public/index.php → bootstrap.php → Router → Controller → Model → View → HTML
```

---

## Base de datos

**Motor:** MySQL 5.7+ / MariaDB 10.3+  
**Nombre:** `gobernacion_guayas`

### Diagrama de relaciones

```
┌──────────┐
│  users   │   (autenticación del sistema)
└──────────┘

┌──────────┐     1:N    ┌────────────────┐     1:N    ┌───────────────┐
│   acta   │◄───────────│  registrocnel  │◄───────────│   formulario  │
│(trabajad.)│            │  (luminarias)  │            │   (colegios)  │
└──────────┘            └────────────────┘            └───────────────┘

┌──────────────┐
│  encuentros  │   (tabla independiente)
└──────────────┘
```

| Tabla | Propósito | Relaciones |
|---|---|---|
| `users` | Usuarios del sistema | — |
| `acta` | Trabajadores (código único, cédula única) | Referenciada por `registrocnel` |
| `registrocnel` | Registros de luminarias CNEL | FK → `acta.codtrabajador` |
| `formulario` | Colegios registrados | FK → `registrocnel.idregistrocnel` |
| `encuentros` | Encuentros ciudadanos | Independiente |

---

## Instalación

### Requisitos

- PHP 8.1 o superior
- MySQL 5.7+ o MariaDB 10.3+
- Apache con `mod_rewrite` habilitado (XAMPP / Laragon)

### Pasos

**1. Clonar el repositorio**
```bash
git clone https://github.com/Lualvarad0/PHP.git PHP-repo
```

**2. Importar la base de datos**

Desde phpMyAdmin o terminal:
```bash
mysql -u root -p < database/schema.sql
```

**3. Configurar la aplicación**

Editar `config/app.php`:
```php
'app' => [
    'url' => 'http://localhost/PHP-repo/public',  // Ajustar según tu entorno
],
'db' => [
    'host'   => 'localhost',
    'dbname' => 'gobernacion_guayas',
    'user'   => 'root',
    'pass'   => '',
],
```

**4. Crear el usuario administrador**
```bash
php setup.php
```
Credenciales iniciales: `admin` / `admin123`

**5. Acceder al sistema**

Con XAMPP apuntando a la carpeta `public/`:
```
http://localhost/PHP-repo/public/
```

---

## Rutas del sistema

| Método | Ruta | Descripción |
|---|---|---|
| GET | `/login` | Formulario de login |
| POST | `/login` | Procesar autenticación |
| GET | `/logout` | Cerrar sesión |
| GET | `/` | Dashboard con estadísticas |
| GET | `/colegios` | Listado de colegios |
| GET | `/colegios/nuevo` | Formulario de registro |
| POST | `/colegios` | Guardar colegio |
| GET | `/cnel` | Listado de registros CNEL |
| GET | `/cnel/nuevo` | Formulario de registro |
| POST | `/cnel` | Guardar registro CNEL |
| GET | `/encuentros` | Listado de encuentros |
| GET | `/encuentros/nuevo` | Formulario de registro |
| POST | `/encuentros` | Guardar encuentro |
| GET | `/actas` | Listado de trabajadores |
| GET | `/actas/nuevo` | Formulario de registro |
| POST | `/actas` | Guardar trabajador |

---

## Buenas prácticas implementadas

| Práctica | Implementación |
|---|---|
| **Sin SQL Injection** | PDO con prepared statements en todas las consultas |
| **Sin XSS** | Función `e()` (htmlspecialchars) en todo output de variables |
| **CSRF Protection** | Token de seguridad en todos los formularios POST |
| **Contraseñas seguras** | `password_hash()` bcrypt + `password_verify()` |
| **Sesiones seguras** | `session_regenerate_id()`, `httponly`, `strict_mode` |
| **Código protegido** | `src/` fuera del document root; no accesible por URL |
| **Un único punto de entrada** | `public/index.php` front controller |
| **Separación de responsabilidades** | MVC estricto |
| **Flash Messages** | Mensajes de éxito/error que se muestran una sola vez |
| **Autoloader PSR-4** | Sin `require` manuales; clases cargadas automáticamente |

---

## Seguridad en producción

1. Cambiar contraseña del admin en el primer inicio de sesión
2. Cambiar credenciales de BD en `config/app.php`
3. Establecer `'debug' => false`
4. Habilitar HTTPS
5. Eliminar `setup.php` tras la instalación

---

## Tecnologías

- **Backend:** PHP 8.1+ sin framework
- **Base de datos:** MySQL / MariaDB via PDO
- **Frontend:** Bootstrap 5.3.2 + Bootstrap Icons 1.11.3
- **Servidor:** Apache con mod_rewrite (XAMPP / Laragon)
