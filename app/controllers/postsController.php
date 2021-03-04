<?php

use App\Models\QueryBuilder;
use League\Plates\Engine;

$query = new QueryBuilder();

$posts = $query->getAll('posts');

// Create new Plates instance
$templates = new Engine('../app/views');
$templates->addData(['posts' => $posts], 'posts');

// Render a template
echo $templates->render('posts', ['name' => 'Jonathan']);

