<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240605194638 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__user AS SELECT id, email, roles, password, access_token, refresh_tokens, created_at FROM user');
        $this->addSql('DROP TABLE user');
        $this->addSql('CREATE TABLE user (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles CLOB NOT NULL --(DC2Type:json)
        , password VARCHAR(255) NOT NULL, access_token VARCHAR(255) DEFAULT NULL, refresh_tokens VARCHAR(255) DEFAULT NULL, created_at DATETIME DEFAULT NULL --(DC2Type:datetime_immutable)
        )');
        $this->addSql('INSERT INTO user (id, email, roles, password, access_token, refresh_tokens, created_at) SELECT id, email, roles, password, access_token, refresh_tokens, created_at FROM __temp__user');
        $this->addSql('DROP TABLE __temp__user');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649E7927C74 ON user (email)');
        $this->addSql('CREATE TEMPORARY TABLE __temp__visitor_event AS SELECT id, type, status, project_id, error, created_at, scenario_uuid, contract FROM visitor_event');
        $this->addSql('DROP TABLE visitor_event');
        $this->addSql('CREATE TABLE visitor_event (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, type VARCHAR(255) NOT NULL, status VARCHAR(15) NOT NULL, project_id INTEGER DEFAULT NULL, error VARCHAR(255) DEFAULT NULL, created_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        , scenario_uuid VARCHAR(36) NOT NULL, responsible CLOB NOT NULL --(DC2Type:json)
        )');
        $this->addSql('INSERT INTO visitor_event (id, type, status, project_id, error, created_at, scenario_uuid, responsible) SELECT id, type, status, project_id, error, created_at, scenario_uuid, contract FROM __temp__visitor_event');
        $this->addSql('DROP TABLE __temp__visitor_event');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__user AS SELECT id, email, roles, password, access_token, refresh_tokens, created_at FROM user');
        $this->addSql('DROP TABLE user');
        $this->addSql('CREATE TABLE user (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles CLOB NOT NULL --(DC2Type:json)
        , password VARCHAR(255) NOT NULL, access_token VARCHAR(255) DEFAULT NULL, refresh_tokens VARCHAR(255) DEFAULT NULL, created_at DATETIME DEFAULT NULL)');
        $this->addSql('INSERT INTO user (id, email, roles, password, access_token, refresh_tokens, created_at) SELECT id, email, roles, password, access_token, refresh_tokens, created_at FROM __temp__user');
        $this->addSql('DROP TABLE __temp__user');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649E7927C74 ON user (email)');
        $this->addSql('CREATE TEMPORARY TABLE __temp__visitor_event AS SELECT id, type, status, project_id, error, created_at, scenario_uuid, responsible FROM visitor_event');
        $this->addSql('DROP TABLE visitor_event');
        $this->addSql('CREATE TABLE visitor_event (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, type VARCHAR(255) NOT NULL, status VARCHAR(15) NOT NULL, project_id INTEGER DEFAULT NULL, error VARCHAR(255) DEFAULT NULL, created_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        , scenario_uuid VARCHAR(36) NOT NULL, contract CLOB NOT NULL --(DC2Type:json)
        )');
        $this->addSql('INSERT INTO visitor_event (id, type, status, project_id, error, created_at, scenario_uuid, contract) SELECT id, type, status, project_id, error, created_at, scenario_uuid, responsible FROM __temp__visitor_event');
        $this->addSql('DROP TABLE __temp__visitor_event');
    }
}
