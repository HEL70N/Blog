<?php

namespace Code\Controller;

use Code\DB\Connection;
use Code\Entity\Post;
use Code\View\View;

class PostsController
{
    public function index()
    {
        $view = new View('admin/posts/index.phtml');
        $view->posts = (new Post(Connection::getInstance()))->findAll();

        return $view->render();
    }
}