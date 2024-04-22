<?php

declare(strict_types=1);

namespace App;

use App\DB;
use App\Services\PaymentGatewayService;
use App\Services\PaymentGatewayServiceInterface;

class App
{
  private static DB $db;

  public function __construct(private Container $container, protected Router $router, protected array $request, protected Config $config)
  {
    static::$db = new DB($config->db);
    //$this->container->set(PaymentGatewayServiceInterface::class, fn (Container $c) => $c->get(PaymentGatewayService::class));
    $this->container->set(PaymentGatewayServiceInterface::class, PaymentGatewayService::class);
  }

  public static function db(): DB
  {
    return static::$db;
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
