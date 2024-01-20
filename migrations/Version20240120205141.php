<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240120205141 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__scenario_template AS SELECT id, scenario, project_id, name FROM scenario_template');
        $this->addSql('DROP TABLE scenario_template');
        $this->addSql('CREATE TABLE scenario_template (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, scenario CLOB NOT NULL --(DC2Type:json)
        , project_id INTEGER NOT NULL, name VARCHAR(50) NOT NULL)');
        $this->addSql('INSERT INTO scenario_template (id, scenario, project_id, name) SELECT id, scenario, project_id, name FROM __temp__scenario_template');
        $this->addSql('DROP TABLE __temp__scenario_template');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__scenario_template AS SELECT id, scenario, project_id, name FROM scenario_template');
        $this->addSql('DROP TABLE scenario_template');
        $this->addSql('CREATE TABLE scenario_template (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, scenario CLOB NOT NULL --(DC2Type:array)
        , project_id INTEGER NOT NULL, name VARCHAR(50) NOT NULL)');
        $this->addSql('INSERT INTO scenario_template (id, scenario, project_id, name) SELECT id, scenario, project_id, name FROM __temp__scenario_template');
        $this->addSql('DROP TABLE __temp__scenario_template');
    }
}
