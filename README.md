# DataCore – API REST

![PHP](https://img.shields.io/badge/PHP-8%2B-blue)
![PostgreSQL](https://img.shields.io/badge/PostgreSQL-14%2B-blue)
![Status](https://img.shields.io/badge/status-active-success)
![License](https://img.shields.io/badge/license-MIT-green)

API REST desarrollada en **PHP** con **PostgreSQL** que implementa un CRUD básico sobre usuarios.

## Características

- Arquitectura por capas (Model / Controller)
- Conexión a base de datos con PDO (Singleton)
- Validación de datos
- Respuestas en formato JSON con códigos HTTP

## Tecnologías

- PHP 8+
- PostgreSQL
- Apache

## Instalación de dependencias

### PostgreSQL

#### Windows

1. Descarga el instalador oficial de PostgreSQL desde:
   https://www.postgresql.org/download/windows/
2. Ejecuta el instalador y sigue los pasos para instalar PostgreSQL y pgAdmin.
3. Durante la instalación, recuerda la contraseña del usuario `postgres`.
4. Abre una terminal PowerShell y verifica la instalación:

```powershell
psql --version
```

#### Alternativa con Winget (Windows)

```powershell
winget install --id PostgreSQL.PostgreSQL -e
```

#### Ubuntu

```bash
# Instalar PostgreSQL
sudo apt update && sudo apt install -y postgresql postgresql-contrib

# Iniciar y habilitar servicio
sudo systemctl enable --now postgresql

# Verificar instalación
psql --version
```

### PHP 8+ y Apache

#### Windows

1. **Instalar PHP:**
   - Descarga PHP 8+ desde https://windows.php.net/download/
   - Elige la versión Thread Safe para Apache.
   - Extrae el archivo ZIP en `C:\php`.
   - Agrega `C:\php` al PATH del sistema.

2. **Instalar Apache:**
   - Descarga Apache desde https://www.apachelounge.com/download/
   - Extrae en `C:\Apache24`.
   - Instala el módulo PHP copiando `php8apache2_4.dll` a `C:\Apache24\modules` y configurando `httpd.conf`.

3. **Verificar instalación:**

```powershell
php --version
# Apache se inicia desde servicios de Windows o con httpd.exe
```

#### Ubuntu

```bash
# Instalar PHP y Apache
sudo apt update
sudo apt install -y php php-pgsql apache2 libapache2-mod-php

# Habilitar módulo PHP en Apache
sudo a2enmod php8.1  # Ajusta la versión si es diferente

# Reiniciar Apache
sudo systemctl restart apache2

# Verificar instalación
php --version
```

## Configuración del entorno

1. Copia el archivo de configuración de ejemplo:

```bash
cp .env.example .env
```

2. Edita `.env` con tus credenciales reales de PostgreSQL:

```bash
# Cambia la contraseña por la que configuraste durante la instalación
DB_PASSWORD=tu_contraseña_real
```

## Ejecución

```bash
git clone https://github.com/21adannrioss/DataCore-API-REST.git
cd DataCore-API-REST

psql -U postgres -c "CREATE DATABASE datacore;"
psql -U postgres -d datacore -f database/schema.sql

cp .env.example .env
```

### Configurar Apache para servir el proyecto

#### Windows

1. Edita `C:\Apache24\conf\httpd.conf` y agrega:

```
DocumentRoot "C:/Users/YourUser/Downloads/DataCore-API-REST/public"
<Directory "C:/Users/YourUser/Downloads/DataCore-API-REST/public">
    AllowOverride All
    Require all granted
</Directory>
```

2. Reinicia Apache desde servicios de Windows.

#### Ubuntu

1. Crea un archivo de configuración para el sitio:

```bash
sudo nano /etc/apache2/sites-available/datacore.conf
```

2. Agrega el siguiente contenido (reemplaza `/path/to/project` con la ruta real):

```
<VirtualHost *:80>
    ServerName localhost
    DocumentRoot /path/to/DataCore-API-REST/public
    
    <Directory /path/to/DataCore-API-REST/public>
        AllowOverride All
        Require all granted
    </Directory>
</VirtualHost>
```

3. Habilita el sitio y reinicia Apache:

```bash
sudo a2ensite datacore.conf
sudo systemctl reload apache2
```

### Acceder a la API

Una vez configurado, accede a la API en: `http://localhost`

Ejemplos de endpoints:
- GET `/users` - Obtener todos los usuarios
- POST `/users` - Crear un usuario
- PUT `/users/{id}` - Actualizar un usuario
- DELETE `/users/{id}` - Eliminar un usuario