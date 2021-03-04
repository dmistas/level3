<?php

use App\Models\QueryBuilder;
use League\Plates\Engine;

$query = new QueryBuilder();

$user = ['name'=>'John Dou'];

// Create new Plates instance
$templates = new Engine('../app/views');

// Render a template
echo $templates->render('user', ['user' => $user]);

