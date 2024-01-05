<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240105165845 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE visitor_event ADD COLUMN created_at DATETIME NOT NULL');
        $this->addSql('CREATE TEMPORARY TABLE __temp__visitor_session AS SELECT id, visitor_id, visitor_event, name, channel, channel_id, created_at FROM visitor_session');
        $this->addSql('DROP TABLE visitor_session');
        $this->addSql('CREATE TABLE visitor_session (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, visitor_id INTEGER DEFAULT NULL, visitor_event INTEGER DEFAULT NULL, name VARCHAR(255) DEFAULT NULL, channel VARCHAR(30) NOT NULL, channel_id INTEGER NOT NULL, created_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        )');
        $this->addSql('INSERT INTO visitor_session (id, visitor_id, visitor_event, name, channel, channel_id, created_at) SELECT id, visitor_id, visitor_event, name, channel, channel_id, created_at FROM __temp__visitor_session');
        $this->addSql('DROP TABLE __temp__visitor_session');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__visitor_event AS SELECT id, type, behavior_scenario, action_before, action_after, status FROM visitor_event');
        $this->addSql('DROP TABLE visitor_event');
        $this->addSql('CREATE TABLE visitor_event (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, type VARCHAR(255) NOT NULL, behavior_scenario INTEGER NOT NULL, action_before CLOB DEFAULT NULL --(DC2Type:json)
        , action_after CLOB DEFAULT NULL --(DC2Type:json)
        , status VARCHAR(15) NOT NULL)');
        $this->addSql('INSERT INTO visitor_event (id, type, behavior_scenario, action_before, action_after, status) SELECT id, type, behavior_scenario, action_before, action_after, status FROM __temp__visitor_event');
        $this->addSql('DROP TABLE __temp__visitor_event');
        $this->addSql('CREATE TEMPORARY TABLE __temp__visitor_session AS SELECT id, name, channel, channel_id, visitor_id, visitor_event, created_at FROM visitor_session');
        $this->addSql('DROP TABLE visitor_session');
        $this->addSql('CREATE TABLE visitor_session (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(255) DEFAULT NULL, channel VARCHAR(30) NOT NULL, channel_id INTEGER NOT NULL, visitor_id INTEGER DEFAULT NULL, visitor_event INTEGER DEFAULT NULL, created_at DATETIME NOT NULL)');
        $this->addSql('INSERT INTO visitor_session (id, name, channel, channel_id, visitor_id, visitor_event, created_at) SELECT id, name, channel, channel_id, visitor_id, visitor_event, created_at FROM __temp__visitor_session');
        $this->addSql('DROP TABLE __temp__visitor_session');
    }
}
