<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240811143846 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__event AS SELECT id, type, status, project_id, error, created_at, scenario_uuid, responsible, session_id FROM event');
        $this->addSql('DROP TABLE event');
        $this->addSql('CREATE TABLE event (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, type VARCHAR(255) NOT NULL, status VARCHAR(15) NOT NULL, project_id INTEGER DEFAULT NULL, error VARCHAR(255) DEFAULT NULL, created_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        , scenario_uuid VARCHAR(36) NOT NULL, responsible CLOB DEFAULT NULL --(DC2Type:responsible_type)
        , session_id INTEGER NOT NULL)');
        $this->addSql('INSERT INTO event (id, type, status, project_id, error, created_at, scenario_uuid, responsible, session_id) SELECT id, type, status, project_id, error, created_at, scenario_uuid, responsible, session_id FROM __temp__event');
        $this->addSql('DROP TABLE __temp__event');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__event AS SELECT id, type, status, project_id, error, created_at, scenario_uuid, responsible, session_id FROM event');
        $this->addSql('DROP TABLE event');
        $this->addSql('CREATE TABLE event (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, type VARCHAR(255) NOT NULL, status VARCHAR(15) NOT NULL, project_id INTEGER DEFAULT NULL, error VARCHAR(255) DEFAULT NULL, created_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        , scenario_uuid VARCHAR(36) NOT NULL, responsible CLOB NOT NULL --(DC2Type:json)
        , session_id INTEGER NOT NULL)');
        $this->addSql('INSERT INTO event (id, type, status, project_id, error, created_at, scenario_uuid, responsible, session_id) SELECT id, type, status, project_id, error, created_at, scenario_uuid, responsible, session_id FROM __temp__event');
        $this->addSql('DROP TABLE __temp__event');
    }
}
