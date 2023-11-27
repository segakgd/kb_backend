<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231012202938 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE deal_order ADD COLUMN products CLOB DEFAULT NULL');
        $this->addSql('ALTER TABLE deal_order ADD COLUMN shipping CLOB DEFAULT NULL');
        $this->addSql('ALTER TABLE deal_order ADD COLUMN promotions CLOB DEFAULT NULL');
        $this->addSql('ALTER TABLE deal_order ADD COLUMN total_amount INTEGER NOT NULL');
        $this->addSql('ALTER TABLE deal_order ADD COLUMN created_at DATETIME NOT NULL');
        $this->addSql('ALTER TABLE deal_order ADD COLUMN updated_at DATETIME NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__deal_order AS SELECT id FROM deal_order');
        $this->addSql('DROP TABLE deal_order');
        $this->addSql('CREATE TABLE deal_order (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL)');
        $this->addSql('INSERT INTO deal_order (id) SELECT id FROM __temp__deal_order');
        $this->addSql('DROP TABLE __temp__deal_order');
    }
}
