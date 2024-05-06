<?php

require __DIR__ . "/../vendor/autoload.php";

use App\Config;
use App\Controllers\AuthController;
use App\Controllers\HomeController;
use App\Controllers\InvoiceController;
use App\Controllers\TransactionController;
use App\Controllers\UserController;
use App\Controllers\CurlController;
use Dotenv\Dotenv;
use App\App;
use Illuminate\Container\Container;
use App\Exceptions\SessionException;

$dotenv = Dotenv::createImmutable(dirname(__DIR__));

$dotenv->load();

if (session_status() === PHP_SESSION_ACTIVE) {
  throw new SessionException('Session has already been started');
}

if (headers_sent($filename, $line)) {
  throw new SessionException('Headers already sent');
}

session_start();

require __DIR__ . '/../configs/path_constants.php';
#define('STORAGE_PATH', __DIR__ . '/../storage');
#define('VIEW_PATH', __DIR__ . '/../views');
//$container = new \App\Container();
$container = new Container();
$router = new \App\Router($container);
$router->registerRoutesFromControllerAttributes([
  HomeController::class,
  AuthController::class,
  InvoiceController::class,
  TransactionController::class,
  UserController::class,
  CurlController::class
]);


(new App(
  $container,
  $router,
  ['uri' => $_SERVER['REQUEST_URI'], 'method' => $_SERVER['REQUEST_METHOD']],
  new Config($_ENV)
))->boot()
  ->run();

session_write_close();
