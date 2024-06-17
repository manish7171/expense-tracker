<?php

declare(strict_types=1);

namespace App\Services\Emaillable;

use App\Contracts\EmailValidationInterface;
use App\DataObjects\EmailValidationResult;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

class EmailValidationService implements EmailValidationInterface
{
  private string $baseUrl = 'https://api.emailable.com/v1/';

  public function __construct(private string $apiKey)
  {
  }
  public function verify(string $email): EmailValidationResult
  {
    $stack = HandlerStack::create();

    $maxRetry = 3;

    $stack->push($this->getMaxRetry($maxRetry));
    $client = new Client([
      'base_uri' => $this->baseUrl,
      'timeout' => 5,
      'handler' => $stack
    ]);

    $params = [
      'email' => $email,
      'api_key' => $this->apiKey
    ];

    $response = $client->get('verify', ['query' => $params]);

    $body = json_decode($response->getBody()->getContents(), true);

    return new EmailValidationResult((int)($body['score']), $body['state'] === 'deliverable');
  }

  private function getMaxRetry(int $maxRetry): callable
  {
    return Middleware::retry(
      function (
        int $retries,
        RequestInterface $request,
        ?ResponseInterface $response = null,
        ?\RuntimeException $e = null
      ) use ($maxRetry) {
        if ($retries >= $maxRetry) {
          return false;
        }

        if ($response && in_array($response->getStatusCode(), [249, 429, 503])) {
          return true;
        }

        if ($e instanceof ConnectException) {
          return true;
        }

        return false;
      }
    );
  }
}
