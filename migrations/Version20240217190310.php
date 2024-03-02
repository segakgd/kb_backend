<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240217190310 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__scenario AS SELECT id, type, name, content, project_id, bot_id, deleted_at FROM scenario');
        $this->addSql('DROP TABLE scenario');
        $this->addSql('CREATE TABLE scenario (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, type VARCHAR(25) NOT NULL, name VARCHAR(255) NOT NULL, steps CLOB NOT NULL --(DC2Type:json)
        , project_id INTEGER NOT NULL, bot_id INTEGER DEFAULT NULL, deleted_at DATETIME DEFAULT NULL --(DC2Type:datetime_immutable)
        , uuid VARCHAR(36) NOT NULL, owner_uuid VARCHAR(36) DEFAULT NULL)');
        $this->addSql('INSERT INTO scenario (id, type, name, steps, project_id, bot_id, deleted_at) SELECT id, type, name, content, project_id, bot_id, deleted_at FROM __temp__scenario');
        $this->addSql('DROP TABLE __temp__scenario');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__scenario AS SELECT id, name, type, project_id, bot_id, steps, deleted_at FROM scenario');
        $this->addSql('DROP TABLE scenario');
        $this->addSql('CREATE TABLE scenario (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(255) NOT NULL, type VARCHAR(25) NOT NULL, project_id INTEGER NOT NULL, bot_id INTEGER DEFAULT NULL, content CLOB NOT NULL --(DC2Type:json)
        , deleted_at DATETIME DEFAULT NULL --(DC2Type:datetime_immutable)
        , owner_step_id INTEGER DEFAULT NULL, action_after CLOB DEFAULT NULL --(DC2Type:json)
        )');
        $this->addSql('INSERT INTO scenario (id, name, type, project_id, bot_id, content, deleted_at) SELECT id, name, type, project_id, bot_id, steps, deleted_at FROM __temp__scenario');
        $this->addSql('DROP TABLE __temp__scenario');
    }
}
