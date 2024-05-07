<?php

use Slim\Views\Twig;
use Slim\Views\TwigMiddleware;

$app = require __DIR__ . "/../bootstrap.php";
$container = $app->getContainer();
$router = require CONFIG_PATH . "/router.php";
$router($app);
// Add Twig-View Middleware
$app->add(TwigMiddleware::create($app, $container->get(Twig::class)));

$app->run();
