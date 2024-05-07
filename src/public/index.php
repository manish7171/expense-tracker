<?php

use Dotenv\Dotenv;
use Slim\Views\Twig;
use Slim\Views\TwigMiddleware;
use Slim\Factory\AppFactory;

require __DIR__ . "/../vendor/autoload.php";
require __DIR__ . "/../configs/path_constants.php";

// Create Container using PHP-DI
$dotenv = Dotenv::createImmutable(dirname(__DIR__));
$dotenv->load();

$container = require CONFIG_PATH . "/container.php";
$router = require CONFIG_PATH . "/router.php";

AppFactory::setContainer($container);

$app = AppFactory::create();

$router($app);
// Add Twig-View Middleware
$app->add(TwigMiddleware::create($app, $container->get(Twig::class)));

$app->run();
