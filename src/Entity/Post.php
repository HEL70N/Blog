<?php

namespace Code\Entity;

use Code\DB\Entity;

class Post extends Entity
{
    protected $table = 'posts';
    static $filters = [
        'title' => FILTER_SANITIZE_STRING,
        'description' => FILTER_SANITIZE_STRING,
        'content' => FILTER_SANITIZE_STRING,
        'slug' => FILTER_SANITIZE_STRING,
        'user_id' => FILTER_SANITIZE_NUMBER_INT
    ];
}
