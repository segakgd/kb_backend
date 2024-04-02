<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240402225204 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__scenario AS SELECT id, type, name, project_id, bot_id, deleted_at, uuid, alias, steps FROM scenario');
        $this->addSql('DROP TABLE scenario');
        $this->addSql('CREATE TABLE scenario (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, type VARCHAR(25) NOT NULL, name VARCHAR(255) NOT NULL, project_id INTEGER NOT NULL, bot_id INTEGER DEFAULT NULL, deleted_at DATETIME DEFAULT NULL --(DC2Type:datetime_immutable)
        , uuid VARCHAR(36) NOT NULL, alias VARCHAR(100) NOT NULL, steps CLOB DEFAULT NULL --(DC2Type:json)
        )');
        $this->addSql('INSERT INTO scenario (id, type, name, project_id, bot_id, deleted_at, uuid, alias, steps) SELECT id, type, name, project_id, bot_id, deleted_at, uuid, alias, steps FROM __temp__scenario');
        $this->addSql('DROP TABLE __temp__scenario');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__scenario AS SELECT id, uuid, name, type, project_id, bot_id, steps, deleted_at, alias FROM scenario');
        $this->addSql('DROP TABLE scenario');
        $this->addSql('CREATE TABLE scenario (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, uuid VARCHAR(36) NOT NULL, name VARCHAR(255) NOT NULL, type VARCHAR(25) NOT NULL, project_id INTEGER NOT NULL, bot_id INTEGER DEFAULT NULL, steps CLOB DEFAULT NULL, deleted_at DATETIME DEFAULT NULL --(DC2Type:datetime_immutable)
        , alias VARCHAR(100) NOT NULL)');
        $this->addSql('INSERT INTO scenario (id, uuid, name, type, project_id, bot_id, steps, deleted_at, alias) SELECT id, uuid, name, type, project_id, bot_id, steps, deleted_at, alias FROM __temp__scenario');
        $this->addSql('DROP TABLE __temp__scenario');
    }
}
