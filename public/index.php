<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../src/config/db.php';

$app = AppFactory::create();

//Students Route
require __DIR__ . '/../src/routes/students.php';


$app->get('/', function (Request $request, Response $response, array $args) {
    $headers = $request->getHeaders();
    foreach ($headers as $name => $values) {
        echo $name . ": " . implode(", ", $values);
    }
    return $response->withHeader('Content-Type', 'application/json');
});

$app->run();