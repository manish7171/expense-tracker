<?php

declare(strict_types=1);

namespace App;

use App\DB;

class App
{
  private static DB $db;

  public function __construct(protected Router $router, protected array $request, protected Config $config)
  {
    static::$db = new DB($config->db);
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
