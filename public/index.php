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
    Delight\Auth\Auth::class => function ($container) {
        return new Delight\Auth\Auth($container->get('PDO'),null,null,false);
    },

]);

$container = $builder->build();

$dispatcher = FastRoute\simpleDispatcher(function (FastRoute\RouteCollector $r) {

    $r->addRoute('GET', '/users/{id:\d+}', ['App\controllers\UserController', 'showUserProfile']);
    $r->addRoute('GET', '/', ['App\controllers\UserController', 'index']);

    $r->addGroup('/edit', function (FastRoute\RouteCollector $r) {
        $r->get( '/profile/{id:\d+}', ['App\controllers\UserController', 'showEditProfile']);
        $r->post('/profile/{id:\d+}', ['App\controllers\UserController', 'editProfile']);

        $r->get('/security/{id:\d+}', ['App\controllers\UserController', 'showEditSecurity']);
        $r->post('/security', ['App\controllers\UserController', 'editSecurity']);

        $r->get( '/status/{id:\d+}', ['App\controllers\UserController', 'showEditStatus']);
        $r->post( '/status/{id:\d+}', ['App\controllers\UserController', 'EditStatus']);

        $r->get( '/media/{id:\d+}', ['App\controllers\UserController', 'showUploadAvatar']);
        $r->post( '/media/{id:\d+}', ['App\controllers\UserController', 'uploadAvatar']);

    });


    $r->get('/register', ['App\controllers\auth\RegisterController', 'show']);
    $r->post('/register', ['App\controllers\auth\RegisterController', 'register']);
    $r->get('/verification', ['App\controllers\auth\RegisterController', 'emailVerification']);

    $r->get('/login', ['App\controllers\auth\LoginController', 'show']);
    $r->post('/login', ['App\controllers\auth\LoginController', 'login']);

    $r->get('/logout', ['App\controllers\auth\LoginController', 'logout']);

    // Admin
    $r->addGroup('/admin', function (FastRoute\RouteCollector $r){
        $r->get('/add-user', ['App\controllers\AdminController', 'showAddUser']);
        $r->post('/add-user', ['App\controllers\AdminController', 'addUser']);
        $r->post('/edit/security/{id:\d+}', ['App\controllers\AdminController', 'editUserSecurity']);

    });


    $r->get('/seed', ['App\controllers\AdminController', 'seed']);
    $r->get('/test', ['App\controllers\AdminController', 'test']);


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