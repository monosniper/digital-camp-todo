<?php

use App\Core\Router;
use App\Http\Controllers\HomeController;

$router = new Router;
$controller = new HomeController;

$router->get('/', [$controller, 'index']);
$router->post('/todos', [$controller, 'store']);
$router->patch('/todos/{id}', [$controller, 'update']);
$router->delete('/todos/{id}', [$controller, 'delete']);

return $router;