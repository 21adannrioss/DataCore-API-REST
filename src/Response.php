<?php
/**
 * @file Response.php
 * @package DataCore
 *
 * Classe auxiliar per enviar respostes JSON estandarditzades
 * amb els codis HTTP correctes.
 */

class Response {
    /**
     * Envia una resposta JSON amb èxit.
     *
     * @param mixed $data Dades a retornar.
     * @param int $code Codi HTTP (per defecte 200).
     * @param string $message Missatge descriptiu opcional.
     * @return void
     */
    public static function success(mixed $data, int $code = 200, string $message = 'OK'): void {
        http_response_code($code);
        echo json_encode([
            'status' => 'success',
            'code' => $code,
            'message' => $message,
            'data' => $data,
        ]);
        exit;
    }

    /**
     * Envia una resposta JSON d'error.
     *
     * @param string $message Descripció de l'error.
     * @param int $code Codi HTTP de l'error (per defecte 400 Bad Request).
     * @return void
     */
    public static function error(string $message, int $code = 400): void {
        http_response_code($code);
        echo json_encode([
            'status' => 'error',
            'code' => $code,
            'message' => $message,
            'data' => null,
        ]);
        exit;
    }

    /**
     * Envia una resposta 404 Not Found estàndard.
     *
     * @param string $message Missatge personalitzat (per defecte 'Recurs no trobat').
     * @return void
     */
    public static function notFound(string $message = 'Recurs no trobat'): void {
        self::error($message, 404);
    }

    /**
     * Envia una resposta.
     * S'utilitza quan el mètode HTTP no és suportat per la ruta.
     *
     * @return void
     */
    public static function methodNotAllowed(): void {
        self::error('Mètode HTTP no permès', 405);
    }

    /**
     * Envia una resposta 500 Internal Server Error.
     *
     * @param string $message Descripció de l'error intern.
     * @return void
     */
    public static function serverError(string $message = 'Error intern del servidor'): void {
        self::error($message, 500);
    }
}