<?php

declare(strict_types=1);

use App\Auth;
use App\Config;
use App\Contracts\AuthInterface;
use App\Contracts\RequestValidatorFactoryInterface;
use App\RequestValidators\RequestValidatorFactory;
use App\Contracts\SessionInterface;
use App\Contracts\UserProviderServiceInterface;
use App\DTO\SessionConfig;
use App\Enums\AppEnvironment;
use App\Enums\SameSite;
use App\Services\UserProviderService;
use App\Session;
use Doctrine\DBAL\DriverManager;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\ORMSetup;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ResponseFactoryInterface;
use Slim\App;
use Slim\Factory\AppFactory;
use Slim\Views\Twig;
use Symfony\Bridge\Twig\Extension\AssetExtension;
use Symfony\Component\Asset\Package;
use Symfony\Component\Asset\Packages;
use Symfony\Component\Asset\VersionStrategy\JsonManifestVersionStrategy;
use Symfony\WebpackEncoreBundle\Asset\EntrypointLookup;
use Symfony\WebpackEncoreBundle\Asset\EntrypointLookupCollection;
use Symfony\WebpackEncoreBundle\Asset\TagRenderer;
use Symfony\WebpackEncoreBundle\Twig\EntryFilesTwigExtension;
use Twig\Extra\Intl\IntlExtension;
use function DI\create;
use Slim\Csrf\Guard;

return [
  App::class                      => function (ContainerInterface $container) {
    AppFactory::setContainer($container);

    $addMiddlewares = require CONFIG_PATH . '/middleware.php';
    $router         = require CONFIG_PATH . '/routes/web.php';

    $app = AppFactory::create();

    $router($app);

    $addMiddlewares($app);

    return $app;
  },
  Config::class                 => create(Config::class)->constructor(require CONFIG_PATH . '/app.php'),
  EntityManager::class          => function (Config $config) {
    $setup = ORMSetup::createAttributeMetadataConfiguration(
      paths: $config->get('doctrine.entity_dir'),
      isDevMode: $config->get('doctrine.dev_mode'),

    );
    $connection = DriverManager::getConnection($config->get('doctrine.connection'), $setup);
    return new EntityManager($connection, $setup);
  },
  Twig::class                    => function (Config $config,  ContainerInterface $container) {
    $twig = Twig::create(VIEW_PATH, [
      'cache' => STORAGE_PATH . '/cache',
      'auto_reload' =>  AppEnvironment::isDevelopment($config->get('app_environment')),
    ]);

    $twig->addExtension(new IntlExtension());
    $twig->addExtension(new EntryFilesTwigExtension($container));
    $twig->addExtension(new AssetExtension($container->get('webpack_encore.packages')));
    return $twig;
  },
  /**
   * The following two bindings are needed for EntryFilesTwigExtension & AssetExtension to work for Twig
   */
  'webpack_encore.packages'     => fn () => new Packages(
    new Package(new JsonManifestVersionStrategy(BUILD_PATH . '/manifest.json'))
  ),
  'webpack_encore.tag_renderer' => fn (ContainerInterface $container) => new TagRenderer(
    new EntrypointLookupCollection($container, BUILD_PATH . '/entrypoints.json'),
    $container->get('webpack_encore.packages')
  ),
  ResponseFactoryInterface::class => fn (App $app) => $app->getResponseFactory(),
  AuthInterface::class => fn (ContainerInterface $container) => $container->get(Auth::class),
  UserProviderServiceInterface::class => fn (ContainerInterface $container) => $container->get(UserProviderService::class),
  SessionInterface::class => fn (Config $config) => new Session(
    new SessionConfig(
      $config->get('session.name', ''),
      $config->get('session.flash_name', 'flash'),
      $config->get('session.secure', true),
      $config->get('session.httponly', true),
      SameSite::from($config->get('session.samesite', 'lax'))
    )
  ),
  RequestValidatorFactoryInterface::class => fn (ContainerInterface $container) => $container->get(RequestValidatorFactory::class),
  'csrf' => fn (ResponseFactoryInterface $responseFactory) => new Guard($responseFactory, persistentTokenMode: true),
];
