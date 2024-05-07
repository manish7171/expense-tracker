<?php

declare(strict_types=1);

use App\Config;
use App\Commands\MyCommand;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Tools\Console\ConsoleRunner;
use Doctrine\ORM\Tools\Console\EntityManagerProvider\SingleManagerProvider;

use Symfony\Component\Console\Application;

use Doctrine\Migrations\Tools\Console\Command\CurrentCommand;
use Doctrine\Migrations\Tools\Console\Command\DiffCommand;
use Doctrine\Migrations\Tools\Console\Command\DumpSchemaCommand;
use Doctrine\Migrations\Tools\Console\Command\ExecuteCommand;
use Doctrine\Migrations\Tools\Console\Command\GenerateCommand;
use Doctrine\Migrations\Tools\Console\Command\LatestCommand;
use Doctrine\Migrations\Tools\Console\Command\ListCommand;
use Doctrine\Migrations\Tools\Console\Command\MigrateCommand;
use Doctrine\Migrations\Tools\Console\Command\RollupCommand;
use Doctrine\Migrations\Tools\Console\Command\StatusCommand;
use Doctrine\Migrations\Tools\Console\Command\SyncMetadataCommand;
use Doctrine\Migrations\Tools\Console\Command\UpToDateCommand;
use Doctrine\Migrations\Tools\Console\Command\VersionCommand;
use Doctrine\Migrations\Configuration\EntityManager\ExistingEntityManager;
use Doctrine\Migrations\DependencyFactory;
use Doctrine\Migrations\Configuration\Migration\PhpFile;
// replace with path to your own project bootstrap file
$app = require "bootstrap.php";
$container = $app->getContainer();

// replace with mechanism to retrieve EntityManager in your app
$entityManager = $container->get(EntityManager::class);
$appConfig = $container->get(Config::class);
$config = new PhpFile(CONFIG_PATH . '/migrations.php'); // Or use one of the Doctrine\Migrations\Configuration\Configuration\* loaders
$dependencyFactory = DependencyFactory::fromEntityManager($config, new ExistingEntityManager($entityManager));

$commands = [
  // If you want to add your own custom console commands,
  // you can do so here.
  new CurrentCommand($dependencyFactory),
  new DumpSchemaCommand($dependencyFactory),
  new ExecuteCommand($dependencyFactory),
  new GenerateCommand($dependencyFactory),
  new LatestCommand($dependencyFactory),
  new MigrateCommand($dependencyFactory),
  new RollupCommand($dependencyFactory),
  new StatusCommand($dependencyFactory),
  new VersionCommand($dependencyFactory),
  new UpToDateCommand($dependencyFactory),
  new SyncMetadataCommand($dependencyFactory),
  new ListCommand($dependencyFactory),
  new DiffCommand($dependencyFactory),
  $container->get(MyCommand::class)
];

ConsoleRunner::run(
  new SingleManagerProvider($entityManager),
  $commands
);

var_dump($appConfig);
die;
$application = new Application($appConfig->appName, $appConfig->appVersion);

ConsoleRunner::addCommands($application, new SingleManagerProvider($entityManager));

$application->addCommands($commands);

$application->run();
