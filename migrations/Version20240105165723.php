<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240105165723 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE visitor_session ADD COLUMN created_at DATETIME NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__visitor_session AS SELECT id, name, channel, channel_id, visitor_id, visitor_event FROM visitor_session');
        $this->addSql('DROP TABLE visitor_session');
        $this->addSql('CREATE TABLE visitor_session (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(255) DEFAULT NULL, channel VARCHAR(30) NOT NULL, channel_id INTEGER NOT NULL, visitor_id INTEGER DEFAULT NULL, visitor_event INTEGER DEFAULT NULL)');
        $this->addSql('INSERT INTO visitor_session (id, name, channel, channel_id, visitor_id, visitor_event) SELECT id, name, channel, channel_id, visitor_id, visitor_event FROM __temp__visitor_session');
        $this->addSql('DROP TABLE __temp__visitor_session');
    }
}
