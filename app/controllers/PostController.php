<?php


namespace App\controllers;


class PostController extends Controller
{
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
