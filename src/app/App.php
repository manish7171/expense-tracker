<?php

declare(strict_types=1);

namespace App;

use App\Contracts\EmailValidationInterface;
//use App\Services\AbstractEmailApi\EmailValidationService;
use App\Services\Emaillable\EmailValidationService;
use Dotenv\Dotenv;
use Illuminate\Events\Dispatcher;
use App\Services\PaymentGatewayService;
use App\Services\PaymentGatewayServiceInterface;
use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Container\Container;
use Symfony\Bridge\Twig\Extension\AssetExtension;
use Symfony\WebpackEncoreBundle\Twig\EntryFilesTwigExtension;
use Twig\Environment;

class App
{

  public function __construct(protected Container $container, protected Router $router, protected array $request, protected Config $config)
  {
  }


  public function initDb(array $config)
  {
    $capsule = new Capsule();

    $capsule->addConnection($config);
    $capsule->setEventDispatcher(new Dispatcher($this->container));
    $capsule->setAsGlobal();
    $capsule->bootEloquent();
  }

  public function boot(): static
  {
    $dotenv = Dotenv::createImmutable(dirname(__DIR__));
    $dotenv->load();

    $this->config = new Config($_ENV);

    $loader = new \Twig\Loader\FilesystemLoader(VIEW_PATH);
    $twig = new \Twig\Environment($loader, [
      'cache' => STORAGE_PATH . '/cache',
      'auto_reload' => $this->config->environment === 'development'
    ]);

    $twig->addExtension(new EntryFilesTwigExtension($this->container));
    //$twig->addExtension(new AssetExtension($this->container->get('webpack_encore.packages')));

    $this->initDb($this->config->db);
    $this->container->singleton(Environment::class, (fn () => $twig));
    $this->container->bind(PaymentGatewayServiceInterface::class, PaymentGatewayService::class);
    $this->container->bind(EmailValidationInterface::class, fn () => new EmailValidationService($this->config->apiKeys['emailable']));
    //$this->container->bind(EmailValidationInterface::class, fn () => new EmailValidationService($this->config->apiKeys['abstract']));
    //$this->container->set(PaymentGatewayServiceInterface::class, fn (Container $c) => $c->get(PaymentGatewayService::class));

    return $this;
  }

  public function run()
  {
    try {
      echo $this->router->resolve($this->request['uri'], strtolower($this->request['method']));
    } catch (\App\Exceptions\RouteNotFoundException) {
      header('HTTP/1.1 404 NOT FOUND');
      echo View::make('errors/404');
    }
  }
}
