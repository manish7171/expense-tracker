<?php

use App\Config;
use App\Controllers\HomeController;
use App\Controllers\InvoiceController;
use DI\Container;
use Doctrine\DBAL\DriverManager;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\ORMSetup;
use Dotenv\Dotenv;
use Slim\Views\Twig;
use Slim\Views\TwigMiddleware;
use Slim\Factory\AppFactory;
use Twig\Extra\Intl\IntlExtension;
use function DI\create;

require __DIR__ . "/../vendor/autoload.php";

define('STORAGE_PATH', __DIR__ . '/../storage');
define('VIEW_PATH', __DIR__ . '/../resources/views');
// Create Container using PHP-DI
$dotenv = Dotenv::createImmutable(dirname(__DIR__));
$dotenv->load();

// Create Container using PHP-DI
$container = new Container();

$container->set(Config::class, create(Config::class)->constructor($_ENV));
$container->set(
  EntityManager::class,
  function (Config $config) {
    $setup = ORMSetup::createAttributeMetadataConfiguration(
      paths: array(__DIR__ . "../app/Entity"),
      isDevMode: true,
    );
    $connection = DriverManager::getConnection($config->db, $setup);
    return new EntityManager($connection, $setup);
  }
);


// Set container to create App with on AppFactory
AppFactory::setContainer($container);

$app = AppFactory::create();

// $app->get('/', function (Request $request, Response $response, $args) {
//   $view = Twig::fromRequest($request);
//   return $view->render($response, 'index.twig');
// });
//

$app->get('/', [HomeController::class, 'index']);
$app->get('/invoices', [InvoiceController::class, 'index']);

$twig = Twig::create(VIEW_PATH, [
  'cache' => STORAGE_PATH . '/cache',
  'auto_reload' => true
]);

$twig->addExtension(new IntlExtension());

// Add Twig-View Middleware
$app->add(TwigMiddleware::create($app, $twig));
$app->run();

// use App\Config;
// use App\Controllers\AuthController;
// use App\Controllers\HomeController;
// use App\Controllers\InvoiceController;
// use App\Controllers\TransactionController;
// use App\Controllers\UserController;
// use App\Controllers\CurlController;
// use Dotenv\Dotenv;
// use App\App;
// use Illuminate\Container\Container;
// use App\Exceptions\SessionException;
//
// $dotenv = Dotenv::createImmutable(dirname(__DIR__));
//
// $dotenv->load();
//
// if (session_status() === PHP_SESSION_ACTIVE) {
//   throw new SessionException('Session has already been started');
// }
//
// if (headers_sent($filename, $line)) {
//   throw new SessionException('Headers already sent');
// }
//
// session_start();
//
// require __DIR__ . '/../configs/path_constants.php';
// #define('STORAGE_PATH', __DIR__ . '/../storage');
// #define('VIEW_PATH', __DIR__ . '/../views');
// //$container = new \App\Container();
// $container = new Container();
// $router = new \App\Router($container);
// $router->registerRoutesFromControllerAttributes([
//   HomeController::class,
//   AuthController::class,
//   InvoiceController::class,
//   TransactionController::class,
//   UserController::class,
//   CurlController::class
// ]);
//
//
// (new App(
//   $container,
//   $router,
//   ['uri' => $_SERVER['REQUEST_URI'], 'method' => $_SERVER['REQUEST_METHOD']],
//   new Config($_ENV)
// ))->boot()
//   ->run();
//
// session_write_close();
