<?php

namespace Code\Controller;

use Code\DB\Connection;
use Code\Entity\User;
use Code\Security\PasswordHash;
use Code\Session\Flash;
use Code\Security\Validator\Sanitizer;
use Code\Security\Validator\Validator;
use Code\View\View;

class UsersController
{
    public function index()
    {
        $view = new View('admin/users/index.phtml');
        $view->posts = (new User(Connection::getInstance()))->findAll();

        return $view->render();
    }

    public function new()
    {
        try {
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                $data = $_POST;
                $data = Sanitizer::sanitizerData($data, User::$filters);

                if (!Validator::validareRequiredFields($data)) {
                    Flash::add('warning', 'Preencha todos os campos!');
                    return header('Location: ' . HOME . '/users/new');
                }

                if (!Validator::validatePasswordMinStringLenght($data['password'])) {
                    Flash::add('warning', 'Senha deve conter pelo menos 6 caracters!');
                    return header('Location: ' . HOME . '/users/new');
                }

                if (!Validator::validatePasswordConfirm($data['password'], $data['password_confirm'])) {
                    Flash::add('warning', 'Senhas não conferem!');
                    return header('Location: ' . HOME . '/users/new');
                }

                $post = new User(Connection::getInstance());

                $data['password'] = PasswordHash::hash($data['password']);
                unset($data['password_confirm']);

                if (!$post->insert($data)) {
                    Flash::add('error', 'Erro ao criar Usuário!');
                    return header('Location: ' . HOME . '/users/new');
                }

                Flash::add('success', 'Usuário criado com sucesso!');
                return header('Location: ' . HOME . '/users');
            }

            $view = new View('admin/users/new.phtml');
            $view->users = (new User(Connection::getInstance()))->findAll('id, first_name, last_name');

            return $view->render();
        } catch (\Exception $e) {
            if (APP_DEBUG) {
                Flash::add('error', $e->getMessage());
                return header('Location: ' . HOME . '/users');
            }

            Flash::add('error', 'Ocorreu um problema interno, por favor contacte o Admin.');
            return header('Location: ' . HOME . '/users');
        }
    }

    public function edit($id = null)
    {
        try {
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                $data = $_POST;

                $data = Sanitizer::sanitizerData($data, User::$filters);

                $data['id'] = (int) $id;

                if (!Validator::validareRequiredFields($data)) {
                    Flash::add('warning', 'Preencha todos os campos!');
                    return header('Location: ' . HOME . '/users/edit/' . $id);
                }

                $post = new User(Connection::getInstance());

                if ($data['password']) {

                    if (!Validator::validatePasswordMinStringLenght($data['password'])) {
                        Flash::add('warning', 'Senha deve conter pelo menos 6 caracters!');
                        return header('Location: ' . HOME . '/users/new');
                    }

                    if (!Validator::validatePasswordConfirm($data['password'], $data['password_confirm'])) {
                        Flash::add('warning', 'Senhas não conferem!');
                        return header('Location: ' . HOME . '/users/new');
                    }

                    $data['password'] = PasswordHash::hash($data['password']);
                } else {
                    unset($data['password']);
                }

                unset($data['password_confirm']);

                if (!$post->update($data)) {
                    Flash::add('error', 'Erro ao actualizar usuário!');
                    return header('Location: ' . HOME . '/users/edit/' . $id);
                }

                Flash::add('success', 'Usuário actualizado com sucesso!');
                return header('Location: ' . HOME . '/users/edit/' . $id);
            }

            $view = new View('admin/users/edit.phtml');
            $view->user = (new User(Connection::getInstance()))->find($id);

            return $view->render();
        } catch (\Exception $e) {
            if (APP_DEBUG) {
                Flash::add('error', $e->getMessage());
                return header('Location: ' . HOME . '/users');
            }

            Flash::add('error', 'Ocorreu um problema interno, por favor contacte o Admin.');
            return header('Location: ' . HOME . '/users');
        }
    }

    public function remove($id = null)
    {
        try {
            $post = new User(Connection::getInstance());

            if (!$post->delete($id)) {
                Flash::add('error', 'Erro ao realizar remoção do Usuário!');
                return header('Location: ' . HOME . '/users/edit/' . $id);
            }

            Flash::add('success', 'Usuário removido com sucesso!');
            return header('Location: ' . HOME . '/users');
        } catch (\Exception $e) {
            if (APP_DEBUG) {
                Flash::add('error', $e->getMessage());
                return header('Location: ' . HOME . '/users');
            }

            Flash::add('error', 'Ocorreu um problema interno, por favor contacte o Admin.');
            return header('Location: ' . HOME . '/users');
        }
    }
}
