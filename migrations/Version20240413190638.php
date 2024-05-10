<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240413190638 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__deal_order AS SELECT id, total_amount, created_at, updated_at, product_variants, shipping, promotions FROM deal_order');
        $this->addSql('DROP TABLE deal_order');
        $this->addSql('CREATE TABLE deal_order (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, total_amount INTEGER NOT NULL, created_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        , updated_at DATETIME DEFAULT NULL --(DC2Type:datetime_immutable)
        , product_variants CLOB DEFAULT NULL --(DC2Type:order_variant)
        , shipping CLOB DEFAULT NULL --(DC2Type:json)
        , promotions CLOB DEFAULT NULL --(DC2Type:json)
        )');
        $this->addSql('INSERT INTO deal_order (id, total_amount, created_at, updated_at, product_variants, shipping, promotions) SELECT id, total_amount, created_at, updated_at, product_variants, shipping, promotions FROM __temp__deal_order');
        $this->addSql('DROP TABLE __temp__deal_order');
        $this->addSql('CREATE TEMPORARY TABLE __temp__scenario AS SELECT id, type, name, steps, project_id, bot_id, deleted_at, uuid, alias FROM scenario');
        $this->addSql('DROP TABLE scenario');
        $this->addSql('CREATE TABLE scenario (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, type VARCHAR(25) NOT NULL, name VARCHAR(255) NOT NULL, steps CLOB DEFAULT NULL --(DC2Type:scenario_step_dto_array)
        , project_id INTEGER NOT NULL, bot_id INTEGER DEFAULT NULL, deleted_at DATETIME DEFAULT NULL --(DC2Type:datetime_immutable)
        , uuid VARCHAR(36) NOT NULL, alias VARCHAR(100) NOT NULL)');
        $this->addSql('INSERT INTO scenario (id, type, name, steps, project_id, bot_id, deleted_at, uuid, alias) SELECT id, type, name, steps, project_id, bot_id, deleted_at, uuid, alias FROM __temp__scenario');
        $this->addSql('DROP TABLE __temp__scenario');
        $this->addSql('CREATE TEMPORARY TABLE __temp__shipping AS SELECT id, title, type, project_id, price, created_at, updated_at FROM shipping');
        $this->addSql('DROP TABLE shipping');
        $this->addSql('CREATE TABLE shipping (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, title VARCHAR(100) NOT NULL, type VARCHAR(20) NOT NULL, project_id INTEGER NOT NULL, price CLOB DEFAULT NULL --(DC2Type:shipping_price_type)
        , created_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        , updated_at DATETIME DEFAULT NULL --(DC2Type:datetime_immutable)
        , description VARCHAR(255) NOT NULL, calculation_type VARCHAR(50) NOT NULL, is_active BOOLEAN NOT NULL, apply_from_amount INTEGER DEFAULT NULL, apply_to_amount INTEGER DEFAULT NULL, free_from INTEGER DEFAULT NULL, is_not_fixed BOOLEAN NOT NULL, fields CLOB NOT NULL --(DC2Type:shipping_field_req_dto_array)
        )');
        $this->addSql('INSERT INTO shipping (id, title, type, project_id, price, created_at, updated_at) SELECT id, title, type, project_id, price, created_at, updated_at FROM __temp__shipping');
        $this->addSql('DROP TABLE __temp__shipping');
        $this->addSql('CREATE TEMPORARY TABLE __temp__visitor_session AS SELECT id, visitor_event, name, channel, chat_id, created_at, project_id, cache, bot_id FROM visitor_session');
        $this->addSql('DROP TABLE visitor_session');
        $this->addSql('CREATE TABLE visitor_session (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, visitor_event INTEGER DEFAULT NULL, name VARCHAR(255) DEFAULT NULL, channel VARCHAR(30) NOT NULL, chat_id INTEGER NOT NULL, created_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        , project_id INTEGER DEFAULT NULL, cache CLOB NOT NULL --(DC2Type:visitor_session_cache_dto_array)
        , bot_id INTEGER NOT NULL)');
        $this->addSql('INSERT INTO visitor_session (id, visitor_event, name, channel, chat_id, created_at, project_id, cache, bot_id) SELECT id, visitor_event, name, channel, chat_id, created_at, project_id, cache, bot_id FROM __temp__visitor_session');
        $this->addSql('DROP TABLE __temp__visitor_session');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__deal_order AS SELECT id, product_variants, shipping, promotions, total_amount, created_at, updated_at FROM deal_order');
        $this->addSql('DROP TABLE deal_order');
        $this->addSql('CREATE TABLE deal_order (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, product_variants CLOB NOT NULL, shipping CLOB NOT NULL, promotions CLOB NOT NULL, total_amount INTEGER NOT NULL, created_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        , updated_at DATETIME DEFAULT NULL --(DC2Type:datetime_immutable)
        )');
        $this->addSql('INSERT INTO deal_order (id, product_variants, shipping, promotions, total_amount, created_at, updated_at) SELECT id, product_variants, shipping, promotions, total_amount, created_at, updated_at FROM __temp__deal_order');
        $this->addSql('DROP TABLE __temp__deal_order');
        $this->addSql('CREATE TEMPORARY TABLE __temp__scenario AS SELECT id, uuid, name, type, project_id, bot_id, steps, deleted_at, alias FROM scenario');
        $this->addSql('DROP TABLE scenario');
        $this->addSql('CREATE TABLE scenario (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, uuid VARCHAR(36) NOT NULL, name VARCHAR(255) NOT NULL, type VARCHAR(25) NOT NULL, project_id INTEGER NOT NULL, bot_id INTEGER DEFAULT NULL, steps CLOB NOT NULL --(DC2Type:json)
        , deleted_at DATETIME DEFAULT NULL --(DC2Type:datetime_immutable)
        , alias VARCHAR(100) NOT NULL, owner_uuid VARCHAR(36) DEFAULT NULL)');
        $this->addSql('INSERT INTO scenario (id, uuid, name, type, project_id, bot_id, steps, deleted_at, alias) SELECT id, uuid, name, type, project_id, bot_id, steps, deleted_at, alias FROM __temp__scenario');
        $this->addSql('DROP TABLE __temp__scenario');
        $this->addSql('CREATE TEMPORARY TABLE __temp__shipping AS SELECT id, title, type, project_id, price, created_at, updated_at FROM shipping');
        $this->addSql('DROP TABLE shipping');
        $this->addSql('CREATE TABLE shipping (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, title VARCHAR(100) NOT NULL, type VARCHAR(20) NOT NULL, project_id INTEGER NOT NULL, price CLOB DEFAULT NULL --(DC2Type:json)
        , created_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        , updated_at DATETIME DEFAULT NULL --(DC2Type:datetime_immutable)
        )');
        $this->addSql('INSERT INTO shipping (id, title, type, project_id, price, created_at, updated_at) SELECT id, title, type, project_id, price, created_at, updated_at FROM __temp__shipping');
        $this->addSql('DROP TABLE __temp__shipping');
        $this->addSql('CREATE TEMPORARY TABLE __temp__visitor_session AS SELECT id, name, channel, visitor_event, created_at, project_id, cache, bot_id, chat_id FROM visitor_session');
        $this->addSql('DROP TABLE visitor_session');
        $this->addSql('CREATE TABLE visitor_session (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(255) DEFAULT NULL, channel VARCHAR(30) NOT NULL, visitor_event INTEGER DEFAULT NULL, created_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        , project_id INTEGER DEFAULT NULL, cache CLOB NOT NULL --(DC2Type:json)
        , bot_id INTEGER NOT NULL, chat_id INTEGER NOT NULL)');
        $this->addSql('INSERT INTO visitor_session (id, name, channel, visitor_event, created_at, project_id, cache, bot_id, chat_id) SELECT id, name, channel, visitor_event, created_at, project_id, cache, bot_id, chat_id FROM __temp__visitor_session');
        $this->addSql('DROP TABLE __temp__visitor_session');
    }
}
