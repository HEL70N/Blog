<?php

namespace Code\Controller;

use Code\DB\Connection;
use Code\Entity\Post;
use Code\Entity\User;
use Code\Session\Flash;
use Code\Validator\Sanitizer;
use Code\View\View;

class PostsController
{
    public function index()
    {
        $view = new View('admin/posts/index.phtml');
        $view->posts = (new Post(Connection::getInstance()))->findAll();

        return $view->render();
    }

    public function new()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data = $_POST;
            $data = Sanitizer::sanitizerData($data, Post::$filters);
            var_dump($data);
            die;

            $post = new Post(Connection::getInstance());

            if (!$post->insert($data)) {
                Flash::add('error', 'Erro ao criar postagem!');
                return header('Location: ' . HOME . '/posts/new');
            }

            Flash::add('success', 'Postagem criada com sucesso!');
            return header('Location: ' . HOME . '/posts');
        }

        $view = new View('admin/posts/new.phtml');
        $view->users = (new User(Connection::getInstance()))->findAll('id, first_name, last_name');

        return $view->render();
    }

    public function edit($id = null)
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $data = $_POST;
            $data['id'] = $id;

            $post = new Post(Connection::getInstance());

            if (!$post->update($data)) {
                Flash::add('error', 'Erro ao actualizar postagem!');
                return header('Location: ' . HOME . '/posts/edit/' . $id);
            }

            Flash::add('success', 'Postagem actualizada com sucesso!');
            return header('Location: ' . HOME . '/posts/edit/' . $id);
        }

        $view = new View('admin/posts/edit.phtml');
        $view->post = (new Post(Connection::getInstance()))->find($id);
        $view->users = (new User(Connection::getInstance()))->findAll('id, first_name, last_name');

        return $view->render();
    }

    public function remove($id = null)
    {
        $post = new Post(Connection::getInstance());

        if (!$post->delete($id)) {
            Flash::add('error', 'Erro ao realizar remoção da postagem!');
            return header('Location: ' . HOME . '/posts/edit/' . $id);
        }

        Flash::add('success', 'Postagem removida com sucesso!');
        return header('Location: ' . HOME . '/posts');
    }
}
