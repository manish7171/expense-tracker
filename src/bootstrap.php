<?php

declare(strict_types=1);

use Dotenv\Dotenv;

require __DIR__ . "/vendor/autoload.php";
require __DIR__ . "/configs/path_constants.php";

// Create Container using PHP-DI
$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

$container = require CONFIG_PATH . "/container/container.php";


return $container;
