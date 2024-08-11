<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240811125727 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__message_history AS SELECT id, message, key_board, images, type FROM message_history');
        $this->addSql('DROP TABLE message_history');
        $this->addSql('CREATE TABLE message_history (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, session_id INTEGER DEFAULT NULL, message CLOB NOT NULL, key_board CLOB NOT NULL --(DC2Type:json)
        , images CLOB NOT NULL --(DC2Type:json)
        , type VARCHAR(20) NOT NULL, CONSTRAINT FK_B7324A47613FECDF FOREIGN KEY (session_id) REFERENCES session (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO message_history (id, message, key_board, images, type) SELECT id, message, key_board, images, type FROM __temp__message_history');
        $this->addSql('DROP TABLE __temp__message_history');
        $this->addSql('CREATE INDEX IDX_B7324A47613FECDF ON message_history (session_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__message_history AS SELECT id, message, key_board, images, type FROM message_history');
        $this->addSql('DROP TABLE message_history');
        $this->addSql('CREATE TABLE message_history (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, message CLOB NOT NULL, key_board CLOB NOT NULL --(DC2Type:json)
        , images CLOB NOT NULL --(DC2Type:json)
        , type VARCHAR(20) NOT NULL)');
        $this->addSql('INSERT INTO message_history (id, message, key_board, images, type) SELECT id, message, key_board, images, type FROM __temp__message_history');
        $this->addSql('DROP TABLE __temp__message_history');
    }
}
