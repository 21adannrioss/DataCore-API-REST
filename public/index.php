<?php

/**
 * @file index.php
 * @package DataCore
 *
 * Punt d'entrada principal de l'API REST DataCore.
 *
 * Configura les capçaleres CORS i Content-Type,
 * enruta les peticions HTTP als controladors adequats
 * i gestiona els errors globals no capturats.
 */

require_once __DIR__ . '/src/UserController.php';
require_once __DIR__ . '/src/Response.php';

// Capçaleres globals
header('Content-Type: application/json; charset=UTF-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

// Resposta preflight CORS (navegadors que envien OPTIONS abans de certes peticions)
if($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);
    exit;
}

/**
 * Gestiona errors fatals no capturats i els retorna com a JSON.
 * Evita que PHP mostri errors en HTML que trencarien el format JSON de l'API.
 *
 * @param Throwable $e L'error o excepció no capturada.
 * @return void
 */
set_exception_handler(function(Throwable $e): void {
    http_response_code(500);
    echo json_encode([
        'status' => 'error',
        'code' => 500,
        'message' => 'Error inesperat: ' . $e->getMessage(),
        'data' => null,
    ]);
    exit;
});


// Router
//Analitza la URL per extreure la ruta neta sense la base del projecte.
$requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$requestMethod = $_SERVER['REQUEST_METHOD'];

// Elimina el prefix del directori si l'API no és a l'arrel del servidor.
$basePath = '/public';
$path = str_replace($basePath, '', $requestUri);
$path = rtrim($path, '/');

$controller = new UserController();

//Rutes:
// GET /users
if($path === '/users' && $requestMethod === 'GET') {
    $controller->index();

// POST /users
} elseif($path === '/users' && $requestMethod === 'POST') {
    $controller->store();

// GET /users/{id}
} elseif(preg_match('/^\/users\/(\d+)$/', $path, $matches) && $requestMethod === 'GET') {
    $controller->show((int)$matches[1]);

// PUT /users/{id}
} elseif(preg_match('/^\/users\/(\d+)$/', $path, $matches) && $requestMethod === 'PUT') {
    $controller->update((int)$matches[1]);

// DELETE /users/{id}
} elseif(preg_match('/^\/users\/(\d+)$/', $path, $matches) && $requestMethod === 'DELETE') {
    $controller->destroy((int)$matches[1]);

// No reconeguda
} else {
    Response::notFound('Ruta no trobada. Comprova la URL i el mètode HTTP.');
}
