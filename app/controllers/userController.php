<?php

namespace App\controllers;

use App\Models\QueryBuilder;
use Delight\Auth\Auth;
use League\Plates\Engine;


class UserController
{
    protected $auth, $query, $templates;

    public function __construct(QueryBuilder $queryBuilder, Engine $engine, Auth $auth)
    {
        $this->query = $queryBuilder;
        // Create new Plates instance
        $this->templates = $engine;
        $this->auth = $auth;
    }

    public function index()
    {
        $users = $this->query->getAll('users');
        echo $this->templates->render('users', ['users' => $users]);

    }

    public function show($vars=null)
    {

        $user = $this->query->find($vars['id'], 'users');
        echo $this->templates->render('user', ['user' => $user]);
    }




}

