<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231227221252 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE history (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, created_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        , type VARCHAR(255) NOT NULL, status VARCHAR(255) NOT NULL, sender VARCHAR(255) NOT NULL, recipient VARCHAR(255) NOT NULL, error CLOB NOT NULL --(DC2Type:json)
        )');
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
        $this->addSql('DROP TABLE history');
    }
}
