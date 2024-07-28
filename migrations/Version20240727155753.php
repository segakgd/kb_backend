<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240727155753 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE session_cache ADD COLUMN cart CLOB DEFAULT NULL');
        $this->addSql('CREATE TEMPORARY TABLE __temp__visitor_session AS SELECT id, bot_id, cache_id, name, channel, chat_id, created_at, project_id FROM visitor_session');
        $this->addSql('DROP TABLE visitor_session');
        $this->addSql('CREATE TABLE visitor_session (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, bot_id INTEGER DEFAULT NULL, cache_id INTEGER DEFAULT NULL, name VARCHAR(255) DEFAULT NULL, channel VARCHAR(30) NOT NULL, chat_id INTEGER NOT NULL, created_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        , project_id INTEGER DEFAULT NULL, CONSTRAINT FK_1257451C92C1C487 FOREIGN KEY (bot_id) REFERENCES bot (id) ON UPDATE NO ACTION ON DELETE NO ACTION NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_1257451CA45D650B FOREIGN KEY (cache_id) REFERENCES session_cache (id) ON UPDATE NO ACTION ON DELETE NO ACTION NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO visitor_session (id, bot_id, cache_id, name, channel, chat_id, created_at, project_id) SELECT id, bot_id, cache_id, name, channel, chat_id, created_at, project_id FROM __temp__visitor_session');
        $this->addSql('DROP TABLE __temp__visitor_session');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_1257451CA45D650B ON visitor_session (cache_id)');
        $this->addSql('CREATE INDEX IDX_1257451C92C1C487 ON visitor_session (bot_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__session_cache AS SELECT id, event, content, created_at, updated_at FROM session_cache');
        $this->addSql('DROP TABLE session_cache');
        $this->addSql('CREATE TABLE session_cache (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, event CLOB DEFAULT NULL --(DC2Type:session_event_dto_array)
        , content VARCHAR(255) DEFAULT NULL, created_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        , updated_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        )');
        $this->addSql('INSERT INTO session_cache (id, event, content, created_at, updated_at) SELECT id, event, content, created_at, updated_at FROM __temp__session_cache');
        $this->addSql('DROP TABLE __temp__session_cache');
        $this->addSql('ALTER TABLE visitor_session ADD COLUMN cache_dto CLOB NOT NULL');
    }
}
