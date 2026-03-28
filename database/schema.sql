-- DataCore – Esquema de base de dades PostgreSQL
-- Fitxer per crear les taules necessàries.

-- Executar: psql -U postgres -d datacore -f database/schema.sql

-- Elimina la taula si ja existeix
DROP TABLE IF EXISTS users;

-- Taula principal d'usuaris
CREATE TABLE users (
    id SERIAL PRIMARY KEY, -- Identificador autoincrementat
    name VARCHAR(100) NOT NULL, -- Nom de l'usuari
    email VARCHAR(255) NOT NULL UNIQUE, -- Correu electrònic únic
    password VARCHAR(255) NOT NULL, -- Contrasenya amb hash bcrypt
    created_at TIMESTAMP DEFAULT NOW() -- Data de creació automàtica
);

-- Índex per accelerar les cerques per email
CREATE INDEX idx_users_email ON users(email);

-- Dades de prova, per poder provar l'API de seguida
INSERT INTO users (name, email, password) VALUES
    ('Anna García', 'anna@example.com', '$2y$10$examplehash1'),
    ('Marc Puig', 'marc@example.com', '$2y$10$examplehash2'),
    ('Laura Torres', 'laura@example.com', '$2y$10$examplehash3');