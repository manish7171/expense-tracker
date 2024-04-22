<?php

declare(strict_types=1);

namespace App;

use App\DB;
use App\Services\EmailService;
use App\Services\InvoiceService;
use App\Services\PaymentGatewayService;
use App\Services\SalesTaxService;

class App
{
  private static DB $db;
  public static Container $container;

  public function __construct(protected Router $router, protected array $request, protected Config $config)
  {
    static::$db = new DB($config->db);
    static::$container = new Container();
    static::$container->set(InvoiceService::class, function ($c) {
      return new InvoiceService(
        $c->get(SalesTaxService::class),
        $c->get(PaymentGatewayService::class),
        $c->get(EmailService::class)
      );
    });

    static::$container->set(SalesTaxService::class, fn () => new SalesTaxService());
    static::$container->set(PaymentGatewayService::class, fn () => new PaymentGatewayService());
    static::$container->set(EmailService::class, fn () => new EmailService());
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
