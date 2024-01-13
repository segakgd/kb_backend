<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240104211625 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE history ADD COLUMN project_id INTEGER NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__history AS SELECT id, created_at, type, status, sender, recipient, error FROM history');
        $this->addSql('DROP TABLE history');
        $this->addSql('CREATE TABLE history (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, created_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        , type VARCHAR(255) NOT NULL, status VARCHAR(255) NOT NULL, sender VARCHAR(255) NOT NULL, recipient VARCHAR(255) NOT NULL, error CLOB NOT NULL --(DC2Type:json)
        )');
        $this->addSql('INSERT INTO history (id, created_at, type, status, sender, recipient, error) SELECT id, created_at, type, status, sender, recipient, error FROM __temp__history');
        $this->addSql('DROP TABLE __temp__history');
    }
}
