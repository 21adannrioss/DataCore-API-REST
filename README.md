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

## Ejecución

```bash
git clone https://github.com/21adannrioss/DataCore-API-REST.git
cd DataCore-API-REST

psql -U postgres -c "CREATE DATABASE datacore;"
psql -U postgres -d datacore -f database/schema.sql

cp .env.example .env