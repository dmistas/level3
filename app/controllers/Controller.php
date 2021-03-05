<?php


namespace App\Controllers;

use App\Models\QueryBuilder;
use League\Plates\Engine;

class Controller
{
    protected $query, $templates;

    public function __construct()
    {
        $this->query = new QueryBuilder();
        // Create new Plates instance
        $this->templates = new Engine('../app/views');
    }

}