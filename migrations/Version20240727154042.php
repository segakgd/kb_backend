<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240727154042 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE session_cache ADD COLUMN cart CLOB DEFAULT NULL');
        $this->addSql('ALTER TABLE session_cache ADD COLUMN event CLOB DEFAULT NULL');
        $this->addSql('ALTER TABLE session_cache ADD COLUMN content VARCHAR(255) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__session_cache AS SELECT id, created_at, updated_at FROM session_cache');
        $this->addSql('DROP TABLE session_cache');
        $this->addSql('CREATE TABLE session_cache (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, created_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        , updated_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        )');
        $this->addSql('INSERT INTO session_cache (id, created_at, updated_at) SELECT id, created_at, updated_at FROM __temp__session_cache');
        $this->addSql('DROP TABLE __temp__session_cache');
    }
}
