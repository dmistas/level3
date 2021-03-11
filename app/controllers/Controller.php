<?php


namespace App\controllers;

use App\Models\QueryBuilder;
use League\Plates\Engine;

class Controller
{
    protected $query, $templates;

    public function __construct(QueryBuilder $queryBuilder, Engine $engine)
    {
        $this->query = $queryBuilder;
        // Create new Plates instance
        $this->templates = $engine;
    }

}