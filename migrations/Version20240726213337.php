<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240726213337 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__visitor_session AS SELECT id, bot_id, name, channel, chat_id, created_at, project_id, cache FROM visitor_session');
        $this->addSql('DROP TABLE visitor_session');
        $this->addSql('CREATE TABLE visitor_session (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, bot_id INTEGER DEFAULT NULL, name VARCHAR(255) DEFAULT NULL, channel VARCHAR(30) NOT NULL, chat_id INTEGER NOT NULL, created_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        , project_id INTEGER DEFAULT NULL, cache CLOB NOT NULL --(DC2Type:visitor_session_cache_dto_array)
        , CONSTRAINT FK_1257451C92C1C487 FOREIGN KEY (bot_id) REFERENCES bot (id) ON UPDATE NO ACTION ON DELETE NO ACTION NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO visitor_session (id, bot_id, name, channel, chat_id, created_at, project_id, cache) SELECT id, bot_id, name, channel, chat_id, created_at, project_id, cache FROM __temp__visitor_session');
        $this->addSql('DROP TABLE __temp__visitor_session');
        $this->addSql('CREATE INDEX IDX_1257451C92C1C487 ON visitor_session (bot_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__visitor_session AS SELECT id, bot_id, name, channel, created_at, project_id, cache, chat_id FROM visitor_session');
        $this->addSql('DROP TABLE visitor_session');
        $this->addSql('CREATE TABLE visitor_session (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, bot_id INTEGER NOT NULL, name VARCHAR(255) DEFAULT NULL, channel VARCHAR(30) NOT NULL, created_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        , project_id INTEGER DEFAULT NULL, cache CLOB NOT NULL --(DC2Type:visitor_session_cache_dto_array)
        , chat_id INTEGER NOT NULL, CONSTRAINT FK_1257451C92C1C487 FOREIGN KEY (bot_id) REFERENCES bot (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO visitor_session (id, bot_id, name, channel, created_at, project_id, cache, chat_id) SELECT id, bot_id, name, channel, created_at, project_id, cache, chat_id FROM __temp__visitor_session');
        $this->addSql('DROP TABLE __temp__visitor_session');
        $this->addSql('CREATE INDEX IDX_1257451C92C1C487 ON visitor_session (bot_id)');
    }
}
