<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240623222129 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__visitor_session AS SELECT id, name, channel, chat_id, created_at, project_id, cache, bot_id FROM visitor_session');
        $this->addSql('DROP TABLE visitor_session');
        $this->addSql('CREATE TABLE visitor_session (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(255) DEFAULT NULL, channel VARCHAR(30) NOT NULL, chat_id INTEGER NOT NULL, created_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        , project_id INTEGER DEFAULT NULL, cache CLOB NOT NULL --(DC2Type:visitor_session_cache_dto_array)
        , bot_id INTEGER NOT NULL)');
        $this->addSql('INSERT INTO visitor_session (id, name, channel, chat_id, created_at, project_id, cache, bot_id) SELECT id, name, channel, chat_id, created_at, project_id, cache, bot_id FROM __temp__visitor_session');
        $this->addSql('DROP TABLE __temp__visitor_session');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE visitor_session ADD COLUMN visitor_event INTEGER DEFAULT NULL');
    }
}
