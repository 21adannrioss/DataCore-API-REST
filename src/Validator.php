<?php
/**
 * @file Validator.php
 * @package DataCore
 *
 * Classe de validació de dades d'entrada per a les peticions HTTP.
 * Centralitza les comprovacions per mantenir el codi net i reutilitzable.
 */

class Validator {
    /**
     * Valida les dades per crear un usuari nou.
     *
     * @param array $data Dades rebudes del cos de la petició.
     * @return array Llista d'errors trobats (buit si tot és correcte).
     */
    public static function createUser(array $data): array {
        $errors = [];

        if(empty($data['name'])) $errors['name'] = 'El nom és obligatori.';
        elseif(strlen($data['name']) < 2 || strlen($data['name']) > 100) $errors['name'] = 'El nom ha de tenir entre 2 i 100 caràcters.';

        if(empty($data['email'])) $errors['email'] = 'El correu electrònic és obligatori.';
        elseif(!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) $errors['email'] = 'El format del correu no és vàlid.';
        
        if(empty($data['password'])) $errors['password'] = 'La contrasenya és obligatòria.';
        elseif(strlen($data['password']) < 8) $errors['password'] = 'La contrasenya ha de tenir almenys 8 caràcters.';

        return $errors;
    }

    /**
     * Valida les dades per actualitzar un usuari existent.
     *
     * @param array $data Dades rebudes del cos de la petició.
     * @return array Llista d'errors trobats (buit si tot és correcte).
     */
    public static function updateUser(array $data): array {
        $errors = [];

        if(empty($data['name'])) $errors['name'] = 'El nom és obligatori.';
        elseif(strlen($data['name']) < 2 || strlen($data['name']) > 100) $errors['name'] = 'El nom ha de tenir entre 2 i 100 caràcters.';

        if(empty($data['email'])) $errors['email'] = 'El correu electrònic és obligatori.';
        elseif(!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) $errors['email'] = 'El format del correu no és vàlid.';

        return $errors;
    }

    /**
     * Comprova si un valor és un enter positiu vàlid.
     *
     * @param mixed $value El valor a comprovar.
     * @return bool True si és un enter positiu, false en cas contrari.
     */
    public static function isPositiveInt(mixed $value): bool {
        return filter_var($value, FILTER_VALIDATE_INT) !== false && (int)$value > 0;
    }
}