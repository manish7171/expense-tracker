<?php

declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Types\Types;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240617101945 extends AbstractMigration
{
  public function getDescription(): string
  {
    return '';
  }

  public function up(Schema $schema): void
  {
    $users = $schema->createTable('users');
    $users->addColumn('id', Types::INTEGER)->setAutoincrement(true);
    $users->addColumn('user_name', Types::STRING);
    $users->addColumn('createdAt', Types::DATE_MUTABLE);
  }

  public function down(Schema $schema): void
  {
    $schema->dropTable('users');
  }
}
