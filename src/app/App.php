<?php

declare(strict_types=1);

namespace App;

class App
{
  public function __construct(protected Router $router, protected array $request)
  {
  }

  public function run()
  {
    try {
      echo $this->router->resolve($this->request['uri'], $this->request['method']);
    } catch (\App\Exceptions\RouteNotFoundException) {
      header('HTTP/1.1 404 NOT FOUND');
      echo View::make('errors/404');
    }
  }
}
