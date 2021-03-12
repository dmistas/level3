<?php


namespace App\controllers;


use App\Models\QueryBuilder;
use League\Plates\Engine;

class PostController
{
    protected $query, $templates;

    public function __construct(QueryBuilder $queryBuilder, Engine $engine)
    {
        $this->query = $queryBuilder;
        // Create new Plates instance
        $this->templates = $engine;
    }
    public function index()
    {
        $posts = $this->query->getAll('posts');
        echo $this->templates->render('posts', ['name' => 'Jonathan', 'posts' => $posts]);
    }

    public function show($vars)
    {
        echo $vars['id'];
    }
}
