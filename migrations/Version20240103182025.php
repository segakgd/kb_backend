<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240103182025 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE bot ADD COLUMN active BOOLEAN DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__bot AS SELECT id, type, token, project_id, name FROM bot');
        $this->addSql('DROP TABLE bot');
        $this->addSql('CREATE TABLE bot (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, type VARCHAR(50) NOT NULL, token VARCHAR(255) NOT NULL, project_id INTEGER NOT NULL, name VARCHAR(50) NOT NULL)');
        $this->addSql('INSERT INTO bot (id, type, token, project_id, name) SELECT id, type, token, project_id, name FROM __temp__bot');
        $this->addSql('DROP TABLE __temp__bot');
    }
}
