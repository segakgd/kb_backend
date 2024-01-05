<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240105163124 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__visitor AS SELECT id, created_at, updated_at FROM visitor');
        $this->addSql('DROP TABLE visitor');
        $this->addSql('CREATE TABLE visitor (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, created_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        , updated_at DATETIME DEFAULT NULL --(DC2Type:datetime_immutable)
        )');
        $this->addSql('INSERT INTO visitor (id, created_at, updated_at) SELECT id, created_at, updated_at FROM __temp__visitor');
        $this->addSql('DROP TABLE __temp__visitor');
        $this->addSql('CREATE TEMPORARY TABLE __temp__visitor_session AS SELECT id, visitor_id, chat_event FROM visitor_session');
        $this->addSql('DROP TABLE visitor_session');
        $this->addSql('CREATE TABLE visitor_session (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, visitor_id INTEGER DEFAULT NULL, visitor_event INTEGER DEFAULT NULL, name VARCHAR(255) DEFAULT NULL, channel VARCHAR(30) NOT NULL, channel_id INTEGER NOT NULL)');
        $this->addSql('INSERT INTO visitor_session (id, visitor_id, visitor_event) SELECT id, visitor_id, chat_event FROM __temp__visitor_session');
        $this->addSql('DROP TABLE __temp__visitor_session');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE visitor ADD COLUMN name VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE visitor ADD COLUMN channel VARCHAR(30) NOT NULL');
        $this->addSql('ALTER TABLE visitor ADD COLUMN channel_id INTEGER NOT NULL');
        $this->addSql('CREATE TEMPORARY TABLE __temp__visitor_session AS SELECT id, visitor_id, visitor_event FROM visitor_session');
        $this->addSql('DROP TABLE visitor_session');
        $this->addSql('CREATE TABLE visitor_session (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, visitor_id INTEGER DEFAULT NULL, chat_event INTEGER DEFAULT NULL)');
        $this->addSql('INSERT INTO visitor_session (id, visitor_id, chat_event) SELECT id, visitor_id, visitor_event FROM __temp__visitor_session');
        $this->addSql('DROP TABLE __temp__visitor_session');
    }
}
