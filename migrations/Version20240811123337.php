<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240811123337 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE event (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, type VARCHAR(255) NOT NULL, status VARCHAR(15) NOT NULL, project_id INTEGER DEFAULT NULL, error VARCHAR(255) DEFAULT NULL, created_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        , scenario_uuid VARCHAR(36) NOT NULL, responsible CLOB NOT NULL --(DC2Type:json)
        , session_id INTEGER NOT NULL)');
        $this->addSql('CREATE TABLE session (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, bot_id INTEGER DEFAULT NULL, cache_id INTEGER DEFAULT NULL, name VARCHAR(255) DEFAULT NULL, channel VARCHAR(30) NOT NULL, created_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        , project_id INTEGER DEFAULT NULL, chat_id INTEGER NOT NULL, CONSTRAINT FK_D044D5D492C1C487 FOREIGN KEY (bot_id) REFERENCES bot (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_D044D5D4A45D650B FOREIGN KEY (cache_id) REFERENCES session_cache (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_D044D5D492C1C487 ON session (bot_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_D044D5D4A45D650B ON session (cache_id)');
        $this->addSql('DROP TABLE visitor_event');
        $this->addSql('DROP TABLE visitor_session');
        $this->addSql('CREATE TEMPORARY TABLE __temp__session_cache AS SELECT id, created_at, updated_at, event, content, cart FROM session_cache');
        $this->addSql('DROP TABLE session_cache');
        $this->addSql('CREATE TABLE session_cache (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, created_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        , updated_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        , event CLOB DEFAULT NULL --(DC2Type:session_event_dto_array)
        , content VARCHAR(255) DEFAULT NULL, cart CLOB DEFAULT NULL --(DC2Type:session_cart_dto_array)
        )');
        $this->addSql('INSERT INTO session_cache (id, created_at, updated_at, event, content, cart) SELECT id, created_at, updated_at, event, content, cart FROM __temp__session_cache');
        $this->addSql('DROP TABLE __temp__session_cache');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE visitor_event (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, type VARCHAR(255) NOT NULL COLLATE "BINARY", status VARCHAR(15) NOT NULL COLLATE "BINARY", project_id INTEGER DEFAULT NULL, error VARCHAR(255) DEFAULT NULL COLLATE "BINARY", created_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        , scenario_uuid VARCHAR(36) NOT NULL COLLATE "BINARY", responsible CLOB NOT NULL COLLATE "BINARY" --(DC2Type:json)
        , session_id INTEGER NOT NULL)');
        $this->addSql('CREATE TABLE visitor_session (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, bot_id INTEGER DEFAULT NULL, cache_id INTEGER DEFAULT NULL, name VARCHAR(255) DEFAULT NULL COLLATE "BINARY", channel VARCHAR(30) NOT NULL COLLATE "BINARY", chat_id INTEGER NOT NULL, created_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        , project_id INTEGER DEFAULT NULL, CONSTRAINT FK_1257451C92C1C487 FOREIGN KEY (bot_id) REFERENCES bot (id) ON UPDATE NO ACTION ON DELETE NO ACTION NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_1257451CA45D650B FOREIGN KEY (cache_id) REFERENCES session_cache (id) ON UPDATE NO ACTION ON DELETE NO ACTION NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_1257451C92C1C487 ON visitor_session (bot_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_1257451CA45D650B ON visitor_session (cache_id)');
        $this->addSql('DROP TABLE event');
        $this->addSql('DROP TABLE session');
        $this->addSql('CREATE TEMPORARY TABLE __temp__session_cache AS SELECT id, cart, event, content, created_at, updated_at FROM session_cache');
        $this->addSql('DROP TABLE session_cache');
        $this->addSql('CREATE TABLE session_cache (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, cart CLOB DEFAULT NULL, event CLOB DEFAULT NULL --(DC2Type:session_event_dto_array)
        , content VARCHAR(255) DEFAULT NULL, created_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        , updated_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        )');
        $this->addSql('INSERT INTO session_cache (id, cart, event, content, created_at, updated_at) SELECT id, cart, event, content, created_at, updated_at FROM __temp__session_cache');
        $this->addSql('DROP TABLE __temp__session_cache');
    }
}
