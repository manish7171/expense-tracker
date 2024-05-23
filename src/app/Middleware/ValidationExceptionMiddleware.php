<?php

declare(strict_types=1);

namespace App\Middleware;

use App\Exceptions\ValidationException;
use Psr\Http\Message\ResponseFactoryInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class ValidationExceptionMiddleware implements MiddlewareInterface
{
  public function __construct(private readonly ResponseFactoryInterface $responseFactory)
  {
  }

  public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
  {
    try {
      return $handler->handle($request);
    } catch (ValidationException $e) {
      $response = $this->responseFactory->createResponse();
      $referer  = $request->getServerParams()['HTTP_REFERER'];

      $_SESSION['errors']  = $e->errors;
      $oldData = $request->getParsedBody();
      $sensitiveData = ['password', 'confirmPassword'];


      $_SESSION['old'] = array_diff_key($oldData, array_flip($sensitiveData));

      return $response->withHeader('Location', $referer)->withStatus(302);
    }
  }
}
