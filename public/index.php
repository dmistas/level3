<?php

if (!session_id()) @session_start();
require_once "../vendor/autoload.php";



$builder = new DI\ContainerBuilder();
$builder->addDefinitions([
    PDO::class => function () {
        $driver = "mysql";
        $host = "127.0.0.1";
        $database_name = "level3";
        $username = "homestead";
        $password = "secret";

        return new PDO("$driver:host=$host;dbname=$database_name", $username, $password);
    },
    Aura\SqlQuery\QueryFactory::class => function () {
        return new Aura\SqlQuery\QueryFactory('mysql');
    },
    League\Plates\Engine::class => function () {
        return new League\Plates\Engine('../app/views');
    },
    Delight\Auth\Auth::class => function($container){
        return new Delight\Auth\Auth($container->get('PDO'));
    }

]);

$container = $builder->build();

$dispatcher = FastRoute\simpleDispatcher(function (FastRoute\RouteCollector $r) {

    $r->addRoute('GET', '/user/{id:\d+}', ['App\controllers\UserController', 'show']);
    $r->addRoute('GET', '/', ['App\controllers\UserController', 'index']);
    
    $r->get('/register', ['App\controllers\auth\RegisterController', 'show']);
    $r->post('/register', ['App\controllers\auth\RegisterController', 'register']);
    $r->get('/verification', ['App\controllers\auth\RegisterController', 'emailVerification']);

    $r->addRoute('GET', '/posts', ['App\controllers\PostController', 'index']);
    $r->addRoute('GET', '/mail', ['App\controllers\MailController', 'index']);
    // {id} must be a number (\d+)
    $r->addRoute('GET', '/post/{id:\d+}', ['App\controllers\PostController', 'show']);
    // The /{title} suffix is optional
    $r->addRoute('GET', '/articles/{id:\d+}[/{title}]', 'get_article_handler');
});

// Fetch method and URI from somewhere
$httpMethod = $_SERVER['REQUEST_METHOD'];
$uri = $_SERVER['REQUEST_URI'];

// Strip query string (?foo=bar) and decode URI
if (false !== $pos = strpos($uri, '?')) {
    $uri = substr($uri, 0, $pos);
}
$uri = rawurldecode($uri);

$routeInfo = $dispatcher->dispatch($httpMethod, $uri);
switch ($routeInfo[0]) {
    case FastRoute\Dispatcher::NOT_FOUND:
        // ... 404 Not Found
        break;
    case FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
        $allowedMethods = $routeInfo[1];
        // ... 405 Method Not Allowed
        break;
    case FastRoute\Dispatcher::FOUND:
        $handler = $routeInfo[1];
        $vars = $routeInfo[2];
//        d($handler, $vars, __DIR__);die();
        $container->call($handler, [$vars]);

        break;
}