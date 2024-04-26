<?php

declare(strict_types=1);

namespace App;

use App\Contracts\EmailValidationInterface;
use App\Services\AbstractEmailApi\EmailValidationService;
use Dotenv\Dotenv;
use Illuminate\Events\Dispatcher;
use App\Services\PaymentGatewayService;
use App\Services\PaymentGatewayServiceInterface;
use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Container\Container;

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

    $this->initDb($this->config->db);
    $this->container->bind(PaymentGatewayServiceInterface::class, PaymentGatewayService::class);
    //$this->container->bind(EmailValidationInterface::class, fn () => new EmailValidationService($this->config->apiKeys['emailable']));
    $this->container->bind(EmailValidationInterface::class, fn () => new EmailValidationService($this->config->apiKeys['abstract']));
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
