<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240121192306 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__scenario AS SELECT id, type, name, content, owner_step_id, action_after, project_id, group_type, bot_id, deleted_at FROM scenario');
        $this->addSql('DROP TABLE scenario');
        $this->addSql('CREATE TABLE scenario (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, type VARCHAR(25) NOT NULL, name VARCHAR(255) NOT NULL, content CLOB NOT NULL --(DC2Type:json)
        , owner_step_id INTEGER DEFAULT NULL, action_after CLOB DEFAULT NULL --(DC2Type:json)
        , project_id INTEGER NOT NULL, group_type VARCHAR(50) NOT NULL, bot_id INTEGER DEFAULT NULL, deleted_at DATETIME DEFAULT NULL --(DC2Type:datetime_immutable)
        )');
        $this->addSql('INSERT INTO scenario (id, type, name, content, owner_step_id, action_after, project_id, group_type, bot_id, deleted_at) SELECT id, type, name, content, owner_step_id, action_after, project_id, group_type, bot_id, deleted_at FROM __temp__scenario');
        $this->addSql('DROP TABLE __temp__scenario');
        $this->addSql('ALTER TABLE visitor_event ADD COLUMN error VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__scenario AS SELECT id, type, name, content, owner_step_id, action_after, project_id, group_type, bot_id, deleted_at FROM scenario');
        $this->addSql('DROP TABLE scenario');
        $this->addSql('CREATE TABLE scenario (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, type VARCHAR(25) NOT NULL, name VARCHAR(255) NOT NULL, content CLOB NOT NULL --(DC2Type:json)
        , owner_step_id INTEGER DEFAULT NULL, action_after CLOB DEFAULT NULL --(DC2Type:json)
        , project_id INTEGER NOT NULL, group_type VARCHAR(50) NOT NULL, bot_id INTEGER DEFAULT NULL, deleted_at DATETIME DEFAULT NULL)');
        $this->addSql('INSERT INTO scenario (id, type, name, content, owner_step_id, action_after, project_id, group_type, bot_id, deleted_at) SELECT id, type, name, content, owner_step_id, action_after, project_id, group_type, bot_id, deleted_at FROM __temp__scenario');
        $this->addSql('DROP TABLE __temp__scenario');
        $this->addSql('CREATE TEMPORARY TABLE __temp__visitor_event AS SELECT id, type, behavior_scenario, action_before, action_after, status, created_at, project_id FROM visitor_event');
        $this->addSql('DROP TABLE visitor_event');
        $this->addSql('CREATE TABLE visitor_event (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, type VARCHAR(255) NOT NULL, behavior_scenario INTEGER NOT NULL, action_before CLOB DEFAULT NULL --(DC2Type:json)
        , action_after CLOB DEFAULT NULL --(DC2Type:json)
        , status VARCHAR(15) NOT NULL, created_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        , project_id INTEGER DEFAULT NULL)');
        $this->addSql('INSERT INTO visitor_event (id, type, behavior_scenario, action_before, action_after, status, created_at, project_id) SELECT id, type, behavior_scenario, action_before, action_after, status, created_at, project_id FROM __temp__visitor_event');
        $this->addSql('DROP TABLE __temp__visitor_event');
    }
}
