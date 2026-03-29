<?php
/**
 * @file UserController.php
 * @package DataCore
 *
 * Controlador per al recurs '/users'.
 * Gestiona totes les peticions HTTP relacionades amb usuaris
 * i coordina la validació, el model i la resposta.
 */

require_once __DIR__ . '/UserModel.php';
require_once __DIR__ . '/Response.php';
require_once __DIR__ . '/Validator.php';

class UserController {
    private UserModel $model;

    public function __construct() {
        $this->model = new UserModel();
    }

    /**
     * Gestiona la petició GET /users
     *
     * @return void
     */
    public function index(): void {
        try {
            $users = $this->model->getAll();
            Response::success($users, 200, 'Usuaris obtinguts correctament');
        } catch (Exception $e) {
            Response::serverError('Error en obtenir els usuaris: ' . $e->getMessage());
        }
    }
}
