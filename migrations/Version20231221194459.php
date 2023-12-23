<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231221194459 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE project ADD COLUMN status VARCHAR(20) DEFAULT NULL');
        $this->addSql('ALTER TABLE project ADD COLUMN active_from DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE project ADD COLUMN active_to DATETIME DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__project AS SELECT id, name FROM project');
        $this->addSql('DROP TABLE project');
        $this->addSql('CREATE TABLE project (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(100) NOT NULL)');
        $this->addSql('INSERT INTO project (id, name) SELECT id, name FROM __temp__project');
        $this->addSql('DROP TABLE __temp__project');
    }
}
