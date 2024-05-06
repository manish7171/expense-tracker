<?php

declare(strict_types=1);

namespace App\Controllers;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

use Slim\Views\Twig;

class HomeController
{


  public function index(Request $request, Response $response, $args): Response
  {
    $view = Twig::fromRequest($request);
    return $view->render($response, 'index.twig');
  }
}
