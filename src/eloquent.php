<?php

declare(strict_types=1);

use Illuminate\Events\Dispatcher;
use Illuminate\Container\Container;
use Illuminate\Database\Capsule\Manager as Capsule;
use Dotenv\Dotenv;

require_once __DIR__ . '/vendor/autoload.php';


$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

$params = [
  'host' => $_ENV['DB_HOST'],
  'username' => $_ENV['DB_USER'],
  'password' => $_ENV['DB_PASS'],
  'database' => $_ENV['DB_DATABASE'],
  'driver' => $_ENV['DB_DRIVER'] ?? 'mysql',
  'charset' => 'utf8',
  'collation' => 'utf8_unicode_ci',
  'prefix' => '',
];

$capsule = new Capsule;

$capsule->addConnection($params);

// Set the event dispatcher used by Eloquent models... (optional)

$capsule->setEventDispatcher(new Dispatcher(new Container));

// Make this Capsule instance available globally via static methods... (optional)
$capsule->setAsGlobal();

// Setup the Eloquent ORM... (optional; unless you've used setEventDispatcher())
$capsule->bootEloquent();
