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

    /**
     * Gestiona la petició GET /users/{id}.
     * Retorna les dades d'un usuari concret pel seu ID.
     *
     * @param int $id Identificador de l'usuari.
     * @return void
     */
    public function show(int $id): void {
        if (!Validator::isPositiveInt($id)) Response::error('L\'ID ha de ser un número enter positiu.', 422);

        try {
            $user = $this->model->getById($id);
            if (!$user) Response::notFound("No s'ha trobat cap usuari amb ID $id.");
            
            Response::success($user);
        } catch (Exception $e) {
            Response::serverError('Error en obtenir l\'usuari: ' . $e->getMessage());
        }
    }

    /**
     * Gestiona la petició POST /users
     * Crea un nou usuari amb les dades rebudes al cos de la petició.
     *
     * @return void
     */
    public function store(): void {
        $data = $this->getJsonBody();

        $errors = Validator::createUser($data);
        if (!empty($errors)) Response::error('Dades no vàlides: ' . implode(' ', $errors), 422);

        if ($this->model->emailExists($data['email'])) Response::error('Aquest correu electrònic ja està registrat.', 409);

        try {
            $user = $this->model->create($data['name'], $data['email'], $data['password']);
            Response::success($user, 201, 'Usuari creat correctament');
        } catch (Exception $e) {
            Response::serverError('Error en crear l\'usuari: ' . $e->getMessage());
        }
    }

    /**
     * Gestiona la petició PUT /users/{id}
     * Actualitza el nom i el correu d'un usuari existent.
     *
     * @param int $id Identificador de l'usuari a actualitzar.
     * @return void
     */
    public function update(int $id): void {
        if (!Validator::isPositiveInt($id)) Response::error('L\'ID ha de ser un número enter positiu.', 422);

        $data = $this->getJsonBody();

        $errors = Validator::updateUser($data);
        if (!empty($errors)) Response::error('Dades no vàlides: ' . implode(' ', $errors), 422);

        if ($this->model->emailExists($data['email'], $id)) Response::error('Aquest correu electrònic ja l\'utilitza un altre usuari.', 409);

        try {
            $user = $this->model->update($id, $data['name'], $data['email']);
            
            if (!$user) Response::notFound("No s'ha trobat cap usuari amb ID $id.");
            
            Response::success($user, 200, 'Usuari actualitzat correctament');
        } catch (Exception $e) {
            Response::serverError('Error en actualitzar l\'usuari: ' . $e->getMessage());
        }
    }

    /**
     * Gestiona la petició DELETE /users/{id}
     * Elimina un usuari de la base de dades pel seu ID.
     *
     * @param int $id Identificador de l'usuari a eliminar.
     * @return void
     */
    public function destroy(int $id): void {
        if (!Validator::isPositiveInt($id)) Response::error('L\'ID ha de ser un número enter positiu.', 422);

        try {
            $deleted = $this->model->delete($id);
            if (!$deleted) {
                Response::notFound("No s'ha trobat cap usuari amb ID $id.");
            }
            Response::success(null, 200, "Usuari amb ID $id eliminat correctament");
        } catch (Exception $e) {
            Response::serverError('Error en eliminar l\'usuari: ' . $e->getMessage());
        }
    }

    /**
     * Llegeix i descodifica el cos de la petició HTTP com a JSON.
     * Si el cos no és un JSON vàlid, retorna un error 400.
     *
     * @return array Les dades descodificades del cos.
     */
    private function getJsonBody(): array {
        $body = file_get_contents('php://input');
        $data = json_decode($body, true);

        if (json_last_error() !== JSON_ERROR_NONE) Response::error('El cos de la petició ha de ser un JSON vàlid.', 400);

        return $data ?? [];
    }
}