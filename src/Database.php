<?php

/**
 * @file Database.php
 * @package DataCore
 *
 * Gestiona la connexió a la base de dades PostgreSQL
 * mitjançant el patró Singleton per evitar connexions duplicades.
 */

class Database {
    // Instància única de la classe (Singleton)
    private static ?Database $instance = null;

    // Objecte de connexió PDO
    private PDO $pdo;

    /**
     * Llegeix les variables d'entorn i estableix la connexió PDO
     * amb PostgreSQL. Llança una excepció si la connexió falla.
     *
     * @throws PDOException Si no es pot connectar a la base de dades.
     */
    private function __construct() {
        $host     = getenv('DB_HOST')     ?: 'localhost';
        $port     = getenv('DB_PORT')     ?: '5432';
        $dbname   = getenv('DB_NAME')     ?: 'datacore';
        $user     = getenv('DB_USER')     ?: 'postgres';
        $password = getenv('DB_PASSWORD') ?: '';

        $dsn = "pgsql:host=$host;port=$port;dbname=$dbname";

        $this->pdo = new PDO($dsn, $user, $password, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]);
    }

    /**
     * Retorna la instància única de Database.
     *
     * Si no existeix cap instància, en crea una de nova.
     *
     * @return Database La instància activa de la connexió.
     */
    public static function getInstance(): Database {
        if(self::$instance === null) self::$instance = new Database();
        
        return self::$instance;
    }

    /**
     * Retorna l'objecte PDO per executar consultes.
     *
     * @return PDO La connexió activa a la base de dades.
     */
    public function getConnection(): PDO {
        return $this->pdo;
    }
}
