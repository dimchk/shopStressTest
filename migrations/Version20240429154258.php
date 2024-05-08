<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240429154258 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create order table';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE `order` (
            id INT UNSIGNED NOT NULL AUTO_INCREMENT,
            products JSON NOT NULL,
            total_amount DECIMAL(10, 2) NOT NULL,
            session_id VARCHAR(255) NOT NULL,
            created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY(id)
        ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');

    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE `order`');
    }
}
