<?php

declare(strict_types=1);

namespace App\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use App\Contracts\AuthInterface;

class AuthenticateMiddleware implements MiddlewareInterface
{
  public function __construct(private readonly AuthInterface $auth)
  {
  }

  public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
  {
    return $handler->handle($request->withAttribute('user', $this->auth->user()));
  }
}
