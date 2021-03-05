<?php
if (!session_id()) @session_start();

require_once "../vendor/autoload.php";

$dispatcher = FastRoute\simpleDispatcher(function (FastRoute\RouteCollector $r) {
    $r->addRoute('GET', '/users', ['App\Controllers\UserController', 'index']);
    $r->addRoute('GET', '/posts', ['App\Controllers\PostController', 'index']);
    // {id} must be a number (\d+)
    $r->addRoute('GET', '/post/{id:\d+}', ['App\Controllers\PostController', 'show']);
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
        $class = $handler[0];
        $method = $handler[1];
        $controller = new $class();
        call_user_func([$controller, $method], $vars);

        break;
}