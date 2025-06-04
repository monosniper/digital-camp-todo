<?php

require_once __DIR__ . '/../vendor/autoload.php';

$router = require __DIR__ . '/../src/Routes/web.php';

$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'POST' && !empty($_POST['_method'])) {
    $method = strtoupper($_POST['_method']);

    unset($_POST['_method']);
}

$uri = $_SERVER['REQUEST_URI'];

$router->dispatch($method, $uri);