<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240217224353 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE visitor_event ADD COLUMN scenario_uuid VARCHAR(36) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__visitor_event AS SELECT id, type, status, project_id, error, created_at FROM visitor_event');
        $this->addSql('DROP TABLE visitor_event');
        $this->addSql('CREATE TABLE visitor_event (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, type VARCHAR(255) NOT NULL, status VARCHAR(15) NOT NULL, project_id INTEGER DEFAULT NULL, error VARCHAR(255) DEFAULT NULL, created_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        )');
        $this->addSql('INSERT INTO visitor_event (id, type, status, project_id, error, created_at) SELECT id, type, status, project_id, error, created_at FROM __temp__visitor_event');
        $this->addSql('DROP TABLE __temp__visitor_event');
    }
}
