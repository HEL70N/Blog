<?php

namespace Code\Controller;

use Code\DB\Connection;
use Code\Entity\Post;
use Code\Entity\User;
use Code\Session\Flash;
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
}