<?php

declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240618134601 extends AbstractMigration
{
  public function getDescription(): string
  {
    return '';
  }

  public function up(Schema $schema): void
  {
    $this->addSql('CREATE TABLE categories (id INT UNSIGNED AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, user_id INT UNSIGNED DEFAULT NULL, INDEX IDX_3AF34668A76ED395 (user_id), PRIMARY KEY(id))');
    $this->addSql('CREATE TABLE password_resets (id INT UNSIGNED AUTO_INCREMENT NOT NULL, email VARCHAR(255) NOT NULL, token VARCHAR(255) NOT NULL, is_active TINYINT(1) DEFAULT 1 NOT NULL, expiration DATETIME NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, UNIQUE INDEX UNIQ_9EDAFEA15F37A13B (token), PRIMARY KEY(id))');
    $this->addSql('CREATE TABLE receipts (id INT UNSIGNED AUTO_INCREMENT NOT NULL, filename VARCHAR(255) NOT NULL, storage_filename VARCHAR(255) NOT NULL, media_type VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, transaction_id INT UNSIGNED DEFAULT NULL, INDEX IDX_1DEBE3A22FC0CB0F (transaction_id), PRIMARY KEY(id))');
    $this->addSql('CREATE TABLE transactions (id INT UNSIGNED AUTO_INCREMENT NOT NULL, was_reviewed TINYINT(1) DEFAULT 0 NOT NULL, description VARCHAR(255) NOT NULL, date DATETIME NOT NULL, amount NUMERIC(13, 3) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, user_id INT UNSIGNED DEFAULT NULL, category_id INT UNSIGNED DEFAULT NULL, INDEX IDX_EAA81A4CA76ED395 (user_id), INDEX IDX_EAA81A4C12469DE2 (category_id), PRIMARY KEY(id))');
    $this->addSql('CREATE TABLE user_login_codes (id INT UNSIGNED AUTO_INCREMENT NOT NULL, code VARCHAR(6) NOT NULL, is_active TINYINT(1) DEFAULT 1 NOT NULL, expiration DATETIME NOT NULL, user_id INT UNSIGNED DEFAULT NULL, INDEX IDX_4AC6CF4CA76ED395 (user_id), PRIMARY KEY(id))');
    $this->addSql('CREATE TABLE users (id INT UNSIGNED AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, two_factor TINYINT(1) DEFAULT 0 NOT NULL, verified_at DATETIME DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, PRIMARY KEY(id))');
    $this->addSql('ALTER TABLE categories ADD CONSTRAINT FK_3AF34668A76ED395 FOREIGN KEY (user_id) REFERENCES users (id)');
    $this->addSql('ALTER TABLE receipts ADD CONSTRAINT FK_1DEBE3A22FC0CB0F FOREIGN KEY (transaction_id) REFERENCES transactions (id)');
    $this->addSql('ALTER TABLE transactions ADD CONSTRAINT FK_EAA81A4CA76ED395 FOREIGN KEY (user_id) REFERENCES users (id)');
    $this->addSql('ALTER TABLE transactions ADD CONSTRAINT FK_EAA81A4C12469DE2 FOREIGN KEY (category_id) REFERENCES categories (id)');
    $this->addSql('ALTER TABLE user_login_codes ADD CONSTRAINT FK_4AC6CF4CA76ED395 FOREIGN KEY (user_id) REFERENCES users (id)');
  }

  public function down(Schema $schema): void
  {
    // this down() migration is auto-generated, please modify it to your needs
    $this->addSql('ALTER TABLE categories DROP FOREIGN KEY FK_3AF34668A76ED395');
    $this->addSql('ALTER TABLE receipts DROP FOREIGN KEY FK_1DEBE3A22FC0CB0F');
    $this->addSql('ALTER TABLE transactions DROP FOREIGN KEY FK_EAA81A4CA76ED395');
    $this->addSql('ALTER TABLE transactions DROP FOREIGN KEY FK_EAA81A4C12469DE2');
    $this->addSql('ALTER TABLE user_login_codes DROP FOREIGN KEY FK_4AC6CF4CA76ED395');
    $this->addSql('DROP TABLE categories');
    $this->addSql('DROP TABLE password_resets');
    $this->addSql('DROP TABLE receipts');
    $this->addSql('DROP TABLE transactions');
    $this->addSql('DROP TABLE user_login_codes');
    $this->addSql('DROP TABLE users');
  }
}
