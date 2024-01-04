<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231227202054 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE tariff (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(100) NOT NULL, price INTEGER NOT NULL, price_wf VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, code VARCHAR(100) NOT NULL, active BOOLEAN NOT NULL)');
        $this->addSql('DROP TABLE cart');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE cart (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, products CLOB NOT NULL COLLATE "BINARY" --(DC2Type:json)
        , contacts CLOB NOT NULL COLLATE "BINARY" --(DC2Type:json)
        , fields CLOB NOT NULL COLLATE "BINARY" --(DC2Type:json)
        , shipping CLOB NOT NULL COLLATE "BINARY" --(DC2Type:json)
        , promotion CLOB NOT NULL COLLATE "BINARY" --(DC2Type:json)
        , total_amount INTEGER NOT NULL, status VARCHAR(20) NOT NULL COLLATE "BINARY", visitor_id INTEGER DEFAULT NULL, created_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        )');
        $this->addSql('DROP TABLE tariff');
    }
}
