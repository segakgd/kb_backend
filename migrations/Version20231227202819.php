<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231227202819 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__project_setting AS SELECT id, project_id, tariff_id, notification FROM project_setting');
        $this->addSql('DROP TABLE project_setting');
        $this->addSql('CREATE TABLE project_setting (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, project_id INTEGER NOT NULL, tariff_id INTEGER NOT NULL, notification CLOB NOT NULL --(DC2Type:json)
        , basic CLOB NOT NULL --(DC2Type:json)
        )');
        $this->addSql('INSERT INTO project_setting (id, project_id, tariff_id, notification) SELECT id, project_id, tariff_id, notification FROM __temp__project_setting');
        $this->addSql('DROP TABLE __temp__project_setting');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__project_setting AS SELECT id, project_id, tariff_id, notification FROM project_setting');
        $this->addSql('DROP TABLE project_setting');
        $this->addSql('CREATE TABLE project_setting (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, project_id INTEGER NOT NULL, tariff_id INTEGER NOT NULL, notification CLOB NOT NULL --(DC2Type:array)
        , Ñbasic CLOB NOT NULL --(DC2Type:array)
        )');
        $this->addSql('INSERT INTO project_setting (id, project_id, tariff_id, notification) SELECT id, project_id, tariff_id, notification FROM __temp__project_setting');
        $this->addSql('DROP TABLE __temp__project_setting');
    }
}
