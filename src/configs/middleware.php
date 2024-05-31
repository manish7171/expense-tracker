<?php

declare(strict_types=1);

use App\Middleware\OldFormDataMiddleware;
use Slim\App;
use App\Config;
use App\Middleware\CsrfFieldsMiddleware;
use Slim\Views\Twig;
use Slim\Views\TwigMiddleware;
use App\Middleware\StartSessionMiddleware;
use App\Middleware\ValidationErrorsMiddleware;
use App\Middleware\ValidationExceptionMiddleware;
use Slim\Middleware\MethodOverrideMiddleware;

return function (App $app) {
  $container = $app->getContainer();
  $config    = $container->get(Config::class);
  $app->add(MethodOverrideMiddleware::class);
  $app->add(CsrfFieldsMiddleware::class);
  $app->add('csrf');
  $app->add(TwigMiddleware::create($app, $container->get(Twig::class)));
  $app->add(ValidationExceptionMiddleware::class);
  $app->add(ValidationErrorsMiddleware::class);
  $app->add(OldFormDataMiddleware::class);
  $app->add(StartSessionMiddleware::class);
  $app->addBodyParsingMiddleware();
  // Twig

  // Logger
  $app->addErrorMiddleware(
    (bool) $config->get('display_error_details'),
    (bool) $config->get('log_errors'),
    (bool) $config->get('log_error_details')
  );
};
