<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240406141915 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE IF NOT EXISTS bot  (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, type VARCHAR(50) NOT NULL, token VARCHAR(255) NOT NULL, project_id INTEGER NOT NULL, name VARCHAR(50) NOT NULL, active BOOLEAN DEFAULT NULL, webhook_uri VARCHAR(100) DEFAULT NULL)');
        $this->addSql('CREATE TABLE IF NOT EXISTS deal (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, contacts_id INTEGER DEFAULT NULL, order_id INTEGER DEFAULT NULL, project_id INTEGER NOT NULL, CONSTRAINT FK_E3FEC116719FB48E FOREIGN KEY (contacts_id) REFERENCES deal_contacts (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_E3FEC1168D9F6D38 FOREIGN KEY (order_id) REFERENCES deal_order (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE UNIQUE INDEX IF NOT EXISTS UNIQ_E3FEC116719FB48E ON deal (contacts_id)');
        $this->addSql('CREATE UNIQUE INDEX IF NOT EXISTS UNIQ_E3FEC1168D9F6D38 ON deal (order_id)');
        $this->addSql('CREATE TABLE IF NOT EXISTS deal_contacts (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, first_name VARCHAR(50) DEFAULT NULL, last_name VARCHAR(50) DEFAULT NULL, phone VARCHAR(25) DEFAULT NULL, email VARCHAR(50) DEFAULT NULL, created_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        )');
        $this->addSql('CREATE TABLE IF NOT EXISTS deal_field (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, deal_id INTEGER DEFAULT NULL, name VARCHAR(50) NOT NULL, value VARCHAR(50) NOT NULL, created_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        , CONSTRAINT FK_1D2A8644F60E2305 FOREIGN KEY (deal_id) REFERENCES deal (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IF NOT EXISTS IDX_1D2A8644F60E2305 ON deal_field (deal_id)');
        $this->addSql('CREATE TABLE IF NOT EXISTS deal_order (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, products CLOB DEFAULT NULL --(DC2Type:json)
        , shipping CLOB DEFAULT NULL --(DC2Type:json)
        , promotions CLOB DEFAULT NULL --(DC2Type:json)
        , total_amount INTEGER NOT NULL, created_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        , updated_at DATETIME DEFAULT NULL --(DC2Type:datetime_immutable)
        )');
        $this->addSql('CREATE TABLE IF NOT EXISTS message_history (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, message CLOB NOT NULL, key_board CLOB NOT NULL --(DC2Type:json)
        , images CLOB NOT NULL --(DC2Type:json)
        , type VARCHAR(20) NOT NULL)');
        $this->addSql('CREATE TABLE IF NOT EXISTS product (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, project_id INTEGER NOT NULL, created_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        , updated_at DATETIME DEFAULT NULL --(DC2Type:datetime_immutable)
        , visible BOOLEAN NOT NULL, name VARCHAR(100) NOT NULL, description VARCHAR(255) DEFAULT NULL)');
        $this->addSql('CREATE TABLE IF NOT EXISTS product_category (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(100) NOT NULL, project_id INTEGER NOT NULL, created_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        , updated_at DATETIME DEFAULT NULL --(DC2Type:datetime_immutable)
        )');
        $this->addSql('CREATE TABLE IF NOT EXISTS product_category_product (product_category_id INTEGER NOT NULL, product_id INTEGER NOT NULL, PRIMARY KEY(product_category_id, product_id), CONSTRAINT FK_9A1E202FBE6903FD FOREIGN KEY (product_category_id) REFERENCES product_category (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_9A1E202F4584665A FOREIGN KEY (product_id) REFERENCES product (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IF NOT EXISTS IDX_9A1E202FBE6903FD ON product_category_product (product_category_id)');
        $this->addSql('CREATE INDEX IF NOT EXISTS IDX_9A1E202F4584665A ON product_category_product (product_id)');
        $this->addSql('CREATE TABLE IF NOT EXISTS product_variant (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, product_id INTEGER DEFAULT NULL, name VARCHAR(50) NOT NULL, article VARCHAR(50) NOT NULL, image CLOB DEFAULT NULL --(DC2Type:json)
        , price CLOB NOT NULL --(DC2Type:variant_price_type)
        , count INTEGER DEFAULT NULL, promotion_distributed BOOLEAN DEFAULT NULL, percent_discount INTEGER DEFAULT NULL, active BOOLEAN DEFAULT NULL, active_from DATETIME DEFAULT NULL, active_to DATETIME DEFAULT NULL, created_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        , updated_at DATETIME DEFAULT NULL --(DC2Type:datetime_immutable)
        , CONSTRAINT FK_209AA41D4584665A FOREIGN KEY (product_id) REFERENCES product (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IF NOT EXISTS IDX_209AA41D4584665A ON product_variant (product_id)');
        $this->addSql('CREATE TABLE IF NOT EXISTS project (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(100) NOT NULL, status VARCHAR(20) DEFAULT NULL, active_from DATETIME DEFAULT NULL --(DC2Type:datetime_immutable)
        , active_to DATETIME DEFAULT NULL --(DC2Type:datetime_immutable)
        )');
        $this->addSql('CREATE TABLE IF NOT EXISTS project_setting (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, project_id INTEGER NOT NULL, tariff_id INTEGER NOT NULL, notification CLOB NOT NULL --(DC2Type:json)
        , basic CLOB NOT NULL --(DC2Type:json)
        )');
        $this->addSql('CREATE TABLE IF NOT EXISTS promotion (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(100) NOT NULL, price CLOB NOT NULL --(DC2Type:json)
        , type VARCHAR(20) NOT NULL, project_id INTEGER NOT NULL, amount INTEGER NOT NULL, code VARCHAR(100) DEFAULT NULL, discount_form VARCHAR(20) NOT NULL, count INTEGER DEFAULT NULL, active BOOLEAN NOT NULL, active_from DATETIME DEFAULT NULL, active_to DATETIME DEFAULT NULL, created_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        , updated_at DATETIME DEFAULT NULL --(DC2Type:datetime_immutable)
        )');
        $this->addSql('CREATE TABLE IF NOT EXISTS "refresh_tokens" (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, refresh_token VARCHAR(128) NOT NULL, username VARCHAR(255) NOT NULL, valid DATETIME NOT NULL)');
        $this->addSql('CREATE UNIQUE INDEX IF NOT EXISTS UNIQ_9BACE7E1C74F2195 ON "refresh_tokens" (refresh_token)');
        $this->addSql('CREATE TABLE IF NOT EXISTS scenario (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, uuid VARCHAR(36) NOT NULL, name VARCHAR(255) NOT NULL, type VARCHAR(25) NOT NULL, project_id INTEGER NOT NULL, bot_id INTEGER DEFAULT NULL, steps CLOB DEFAULT NULL --(DC2Type:scenario_step_dto_array)
        , deleted_at DATETIME DEFAULT NULL --(DC2Type:datetime_immutable)
        , alias VARCHAR(100) NOT NULL)');
        $this->addSql('CREATE TABLE IF NOT EXISTS scenario_template (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, scenario CLOB NOT NULL --(DC2Type:json)
        , project_id INTEGER NOT NULL, name VARCHAR(50) NOT NULL)');
        $this->addSql('CREATE TABLE IF NOT EXISTS shipping (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, title VARCHAR(100) NOT NULL, type VARCHAR(20) NOT NULL, project_id INTEGER NOT NULL, price CLOB DEFAULT NULL --(DC2Type:json)
        , created_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        , updated_at DATETIME DEFAULT NULL --(DC2Type:datetime_immutable)
        )');
        $this->addSql('CREATE TABLE IF NOT EXISTS tariff (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(100) NOT NULL, price INTEGER NOT NULL, price_wf VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, code VARCHAR(100) NOT NULL, active BOOLEAN NOT NULL)');
        $this->addSql('CREATE TABLE IF NOT EXISTS user (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles CLOB NOT NULL --(DC2Type:json)
        , password VARCHAR(255) NOT NULL)');
        $this->addSql('CREATE UNIQUE INDEX IF NOT EXISTS UNIQ_8D93D649E7927C74 ON user (email)');
        $this->addSql('CREATE TABLE IF NOT EXISTS user_project (user_id INTEGER NOT NULL, project_id INTEGER NOT NULL, PRIMARY KEY(user_id, project_id), CONSTRAINT FK_77BECEE4A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_77BECEE4166D1F9C FOREIGN KEY (project_id) REFERENCES project (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IF NOT EXISTS IDX_77BECEE4A76ED395 ON user_project (user_id)');
        $this->addSql('CREATE INDEX IF NOT EXISTS IDX_77BECEE4166D1F9C ON user_project (project_id)');
        $this->addSql('CREATE TABLE IF NOT EXISTS visitor_event (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, type VARCHAR(255) NOT NULL, status VARCHAR(15) NOT NULL, project_id INTEGER DEFAULT NULL, error VARCHAR(255) DEFAULT NULL, created_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        , scenario_uuid VARCHAR(36) NOT NULL, contract CLOB NOT NULL --(DC2Type:json)
        )');
        $this->addSql('CREATE TABLE IF NOT EXISTS visitor_session (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(255) DEFAULT NULL, channel VARCHAR(30) NOT NULL, visitor_event INTEGER DEFAULT NULL, created_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        , project_id INTEGER DEFAULT NULL, cache CLOB NOT NULL --(DC2Type:visitor_session_cache_dto_array)
        , bot_id INTEGER NOT NULL, chat_id INTEGER NOT NULL)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE bot');
        $this->addSql('DROP TABLE deal');
        $this->addSql('DROP TABLE deal_contacts');
        $this->addSql('DROP TABLE deal_field');
        $this->addSql('DROP TABLE deal_order');
        $this->addSql('DROP TABLE message_history');
        $this->addSql('DROP TABLE product');
        $this->addSql('DROP TABLE product_category');
        $this->addSql('DROP TABLE product_category_product');
        $this->addSql('DROP TABLE product_variant');
        $this->addSql('DROP TABLE project');
        $this->addSql('DROP TABLE project_setting');
        $this->addSql('DROP TABLE promotion');
        $this->addSql('DROP TABLE "refresh_tokens"');
        $this->addSql('DROP TABLE scenario');
        $this->addSql('DROP TABLE scenario_template');
        $this->addSql('DROP TABLE shipping');
        $this->addSql('DROP TABLE tariff');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE user_project');
        $this->addSql('DROP TABLE visitor_event');
        $this->addSql('DROP TABLE visitor_session');
    }
}
