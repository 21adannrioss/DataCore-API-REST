<?php

/**
 * @file UserModel.php
 * @package DataCore
 *
 * Model de dades per a la taula 'users'.
 * Conté totes les operacions CRUD contra la base de dades.
 */

require_once __DIR__ . '/Database.php';

class UserModel {
    // Connexió activa a la base de dades
    private PDO $db;

    //Constructor: obté la connexió PDO des del Singleton Database.
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    /**
     * Retorna tots els usuaris de la base de dades.
     *
     * @return array Llista d'usuaris com a arrays associatius.
     */
    public function getAll(): array {
        $stmt = $this->db->query("SELECT id, name, email, created_at FROM users ORDER BY id");
        return $stmt->fetchAll();
    }

    /**
     * Cerca un usuari pel seu identificador únic.
     *
     * @param int $id Identificador de l'usuari.
     * @return array Dades de l'usuari, o null si no existeix.
     */
    public function getById(int $id): ?array {
        $stmt = $this->db->prepare("SELECT id, name, email, created_at FROM users WHERE id = :id");
        $stmt->execute([':id' => $id]);
        $user = $stmt->fetch();
        return $user?: null;
    }

    /**
     * Comprova si ja existeix un usuari amb el correu electrònic indicat.
     *
     * @param string $email Correu a comprovar.
     * @param int|null $excludeId ID a excloure de la cerca.
     * @return bool True si el correu ja està en ús, false en cas contrari.
     */
    public function emailExists(string $email, ?int $excludeId = null): bool {
        $sql = "SELECT COUNT(*) FROM users WHERE email = :email";
        $params = [':email' => $email];

        if ($excludeId !== null) {
            $sql .= " AND id != :id";
            $params[':id'] = $excludeId;
        }
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchColumn() > 0;
    }

    /**
     * Crea un nou usuari a la base de dades.
     *
     * @param string $name Nom de l'usuari.
     * @param string $email Correu electrònic.
     * @param string $password Contrasenya en text pla (es farà hash).
     * @return array Dades de l'usuari creat.
     */
    public function create(string $name, string $email, string $password): array {
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);

        $stmt = $this->db->prepare("INSERT INTO users (name, email, password) VALUES (:name, :email, :password) RETURNING id, name, email, created_at");
        $stmt->execute([
            ':name' => $name,
            ':email' => $email,
            ':password' => $hashedPassword,
        ]);
        return $stmt->fetch();
    }

    /**
     * Actualitza les dades d'un usuari existent.
     * Només modifica els camps proporcionats (nom i/o email).
     *
     * @param int $id Identificador de l'usuari a modificar.
     * @param string $name Nou nom de l'usuari.
     * @param string $email Nou correu electrònic.
     * @return array Dades actualitzades, o null si no existeix.
     */
    public function update(int $id, string $name, string $email): ?array {
        $stmt = $this->db->prepare("UPDATE users SET name = :name, email = :email WHERE id = :id RETURNING id, name, email, created_at");
        $stmt->execute([
            ':name' => $name,
            ':email' => $email,
            ':id' => $id,
        ]);
        $user = $stmt->fetch();
        return $user ?: null;
    }

    /**
     * Elimina un usuari de la base de dades.
     *
     * @param int $id Identificador de l'usuari a eliminar.
     * @return bool True si s'ha eliminat, false si no existia.
     */
    public function delete(int $id): bool {
        $stmt = $this->db->prepare("DELETE FROM users WHERE id = :id");
        $stmt->execute([':id' => $id]);
        return $stmt->rowCount() > 0;
    }
}