<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240105160846 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__visitor AS SELECT id, name, channel, channel_visitor_id, created_at, updated_at FROM visitor');
        $this->addSql('DROP TABLE visitor');
        $this->addSql('CREATE TABLE visitor (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(255) DEFAULT NULL, channel VARCHAR(30) NOT NULL, channel_id INTEGER NOT NULL, created_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        , updated_at DATETIME DEFAULT NULL --(DC2Type:datetime_immutable)
        )');
        $this->addSql('INSERT INTO visitor (id, name, channel, channel_id, created_at, updated_at) SELECT id, name, channel, channel_visitor_id, created_at, updated_at FROM __temp__visitor');
        $this->addSql('DROP TABLE __temp__visitor');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__visitor AS SELECT id, name, channel, channel_id, created_at, updated_at FROM visitor');
        $this->addSql('DROP TABLE visitor');
        $this->addSql('CREATE TABLE visitor (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(255) DEFAULT NULL, channel VARCHAR(30) NOT NULL, channel_visitor_id INTEGER NOT NULL, created_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        , updated_at DATETIME DEFAULT NULL --(DC2Type:datetime_immutable)
        )');
        $this->addSql('INSERT INTO visitor (id, name, channel, channel_visitor_id, created_at, updated_at) SELECT id, name, channel, channel_id, created_at, updated_at FROM __temp__visitor');
        $this->addSql('DROP TABLE __temp__visitor');
    }
}
