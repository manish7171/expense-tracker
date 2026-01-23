<?php

declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260118201412 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql("CREATE TABLE `my_db`.`recurring_transactions` ( `id` INT NOT NULL AUTO_INCREMENT , `type` VARCHAR(255) NOT NULL DEFAULT 'expense', `category_id` INT NOT NULL , `description` VARCHAR(255) NULL , `frequency` VARCHAR(29)  NULL , `start_date` DATE NULL , `end_date` DATE NULL , `last_generated` DATE NULL , `user_id` INT NOT NULL , `amount` DECIMAL(13,3) NOT NULL , `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP , `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP , PRIMARY KEY (`id`)) ENGINE = InnoDB; ");

    }

    public function down(Schema $schema): void
    {
        $this->addSql("Drop TABLE `my_db`.`recurring_expenses`");
        // this down() migration is auto-generated, please modify it to your needs

    }
}
