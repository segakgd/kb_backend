<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240727155611 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__session_cache AS SELECT id, created_at, updated_at, event, content FROM session_cache');
        $this->addSql('DROP TABLE session_cache');
        $this->addSql('CREATE TABLE session_cache (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, created_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        , updated_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        , event CLOB DEFAULT NULL --(DC2Type:session_event_dto_array)
        , content VARCHAR(255) DEFAULT NULL)');
        $this->addSql('INSERT INTO session_cache (id, created_at, updated_at, event, content) SELECT id, created_at, updated_at, event, content FROM __temp__session_cache');
        $this->addSql('DROP TABLE __temp__session_cache');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__session_cache AS SELECT id, event, content, created_at, updated_at FROM session_cache');
        $this->addSql('DROP TABLE session_cache');
        $this->addSql('CREATE TABLE session_cache (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, event CLOB DEFAULT NULL, content VARCHAR(255) DEFAULT NULL, created_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        , updated_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        , cart CLOB DEFAULT NULL)');
        $this->addSql('INSERT INTO session_cache (id, event, content, created_at, updated_at) SELECT id, event, content, created_at, updated_at FROM __temp__session_cache');
        $this->addSql('DROP TABLE __temp__session_cache');
    }
}
