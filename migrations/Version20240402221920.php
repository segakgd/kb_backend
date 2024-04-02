<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240402221920 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE history');
        $this->addSql('CREATE TEMPORARY TABLE __temp__scenario AS SELECT id, type, name, project_id, bot_id, deleted_at, uuid, alias FROM scenario');
        $this->addSql('DROP TABLE scenario');
        $this->addSql('CREATE TABLE scenario (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, type VARCHAR(25) NOT NULL, name VARCHAR(255) NOT NULL, project_id INTEGER NOT NULL, bot_id INTEGER DEFAULT NULL, deleted_at DATETIME DEFAULT NULL --(DC2Type:datetime_immutable)
        , uuid VARCHAR(36) NOT NULL, alias VARCHAR(100) NOT NULL)');
        $this->addSql('INSERT INTO scenario (id, type, name, project_id, bot_id, deleted_at, uuid, alias) SELECT id, type, name, project_id, bot_id, deleted_at, uuid, alias FROM __temp__scenario');
        $this->addSql('DROP TABLE __temp__scenario');
        $this->addSql('CREATE TEMPORARY TABLE __temp__visitor_event AS SELECT id, type, status, created_at, project_id, error, scenario_uuid, contract FROM visitor_event');
        $this->addSql('DROP TABLE visitor_event');
        $this->addSql('CREATE TABLE visitor_event (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, type VARCHAR(255) NOT NULL, status VARCHAR(15) NOT NULL, created_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        , project_id INTEGER DEFAULT NULL, error VARCHAR(255) DEFAULT NULL, scenario_uuid VARCHAR(36) NOT NULL, contract CLOB NOT NULL --(DC2Type:json)
        )');
        $this->addSql('INSERT INTO visitor_event (id, type, status, created_at, project_id, error, scenario_uuid, contract) SELECT id, type, status, created_at, project_id, error, scenario_uuid, contract FROM __temp__visitor_event');
        $this->addSql('DROP TABLE __temp__visitor_event');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE history (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, created_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        , type VARCHAR(255) NOT NULL COLLATE "BINARY", status VARCHAR(255) NOT NULL COLLATE "BINARY", sender VARCHAR(255) DEFAULT NULL COLLATE "BINARY", recipient VARCHAR(255) DEFAULT NULL COLLATE "BINARY", error CLOB NOT NULL COLLATE "BINARY" --(DC2Type:json)
        , project_id INTEGER NOT NULL)');
        $this->addSql('ALTER TABLE scenario ADD COLUMN steps CLOB NOT NULL');
        $this->addSql('ALTER TABLE scenario ADD COLUMN owner_uuid VARCHAR(36) DEFAULT NULL');
        $this->addSql('CREATE TEMPORARY TABLE __temp__visitor_event AS SELECT id, type, status, project_id, error, created_at, scenario_uuid, contract FROM visitor_event');
        $this->addSql('DROP TABLE visitor_event');
        $this->addSql('CREATE TABLE visitor_event (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, type VARCHAR(255) NOT NULL, status VARCHAR(15) NOT NULL, project_id INTEGER DEFAULT NULL, error VARCHAR(255) DEFAULT NULL, created_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        , scenario_uuid VARCHAR(36) NOT NULL, contract CLOB NOT NULL)');
        $this->addSql('INSERT INTO visitor_event (id, type, status, project_id, error, created_at, scenario_uuid, contract) SELECT id, type, status, project_id, error, created_at, scenario_uuid, contract FROM __temp__visitor_event');
        $this->addSql('DROP TABLE __temp__visitor_event');
    }
}
