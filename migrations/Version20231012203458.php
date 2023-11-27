<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231012203458 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__deal_order AS SELECT id, products, shipping, promotions, total_amount, created_at, updated_at FROM deal_order');
        $this->addSql('DROP TABLE deal_order');
        $this->addSql('CREATE TABLE deal_order (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, products CLOB DEFAULT NULL --(DC2Type:json)
        , shipping CLOB DEFAULT NULL --(DC2Type:json)
        , promotions CLOB DEFAULT NULL --(DC2Type:json)
        , total_amount INTEGER NOT NULL, created_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        , updated_at DATETIME DEFAULT NULL --(DC2Type:datetime_immutable)
        )');
        $this->addSql('INSERT INTO deal_order (id, products, shipping, promotions, total_amount, created_at, updated_at) SELECT id, products, shipping, promotions, total_amount, created_at, updated_at FROM __temp__deal_order');
        $this->addSql('DROP TABLE __temp__deal_order');
        $this->addSql('CREATE TEMPORARY TABLE __temp__product AS SELECT id, project_id, created_at, updated_at FROM product');
        $this->addSql('DROP TABLE product');
        $this->addSql('CREATE TABLE product (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, project_id INTEGER NOT NULL, created_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        , updated_at DATETIME DEFAULT NULL --(DC2Type:datetime_immutable)
        )');
        $this->addSql('INSERT INTO product (id, project_id, created_at, updated_at) SELECT id, project_id, created_at, updated_at FROM __temp__product');
        $this->addSql('DROP TABLE __temp__product');
        $this->addSql('CREATE TEMPORARY TABLE __temp__product_category AS SELECT id, name, project_id, created_at, updated_at FROM product_category');
        $this->addSql('DROP TABLE product_category');
        $this->addSql('CREATE TABLE product_category (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(100) NOT NULL, project_id INTEGER NOT NULL, created_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        , updated_at DATETIME DEFAULT NULL --(DC2Type:datetime_immutable)
        )');
        $this->addSql('INSERT INTO product_category (id, name, project_id, created_at, updated_at) SELECT id, name, project_id, created_at, updated_at FROM __temp__product_category');
        $this->addSql('DROP TABLE __temp__product_category');
        $this->addSql('CREATE TEMPORARY TABLE __temp__product_variant AS SELECT id, product_id, name, article, image, price, count, promotion_distributed, percent_discount, active, active_from, active_to, created_at, updated_at FROM product_variant');
        $this->addSql('DROP TABLE product_variant');
        $this->addSql('CREATE TABLE product_variant (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, product_id INTEGER DEFAULT NULL, name VARCHAR(50) NOT NULL, article VARCHAR(50) NOT NULL, image CLOB DEFAULT NULL --(DC2Type:json)
        , price CLOB NOT NULL --(DC2Type:json)
        , count INTEGER NOT NULL, promotion_distributed BOOLEAN NOT NULL, percent_discount INTEGER DEFAULT NULL, active BOOLEAN NOT NULL, active_from DATETIME DEFAULT NULL, active_to DATETIME DEFAULT NULL, created_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        , updated_at DATETIME DEFAULT NULL --(DC2Type:datetime_immutable)
        , CONSTRAINT FK_209AA41D4584665A FOREIGN KEY (product_id) REFERENCES product (id) ON UPDATE NO ACTION ON DELETE NO ACTION NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO product_variant (id, product_id, name, article, image, price, count, promotion_distributed, percent_discount, active, active_from, active_to, created_at, updated_at) SELECT id, product_id, name, article, image, price, count, promotion_distributed, percent_discount, active, active_from, active_to, created_at, updated_at FROM __temp__product_variant');
        $this->addSql('DROP TABLE __temp__product_variant');
        $this->addSql('CREATE INDEX IDX_209AA41D4584665A ON product_variant (product_id)');
        $this->addSql('CREATE TEMPORARY TABLE __temp__promotion AS SELECT id, name, price, type, project_id, amount, code, discount_form, count, active, active_from, active_to, created_at, updated_at FROM promotion');
        $this->addSql('DROP TABLE promotion');
        $this->addSql('CREATE TABLE promotion (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(100) NOT NULL, price CLOB NOT NULL --(DC2Type:json)
        , type VARCHAR(20) NOT NULL, project_id INTEGER NOT NULL, amount INTEGER NOT NULL, code VARCHAR(100) DEFAULT NULL, discount_form VARCHAR(20) NOT NULL, count INTEGER DEFAULT NULL, active BOOLEAN NOT NULL, active_from DATETIME DEFAULT NULL, active_to DATETIME DEFAULT NULL, created_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        , updated_at DATETIME DEFAULT NULL --(DC2Type:datetime_immutable)
        )');
        $this->addSql('INSERT INTO promotion (id, name, price, type, project_id, amount, code, discount_form, count, active, active_from, active_to, created_at, updated_at) SELECT id, name, price, type, project_id, amount, code, discount_form, count, active, active_from, active_to, created_at, updated_at FROM __temp__promotion');
        $this->addSql('DROP TABLE __temp__promotion');
        $this->addSql('CREATE TEMPORARY TABLE __temp__shipping AS SELECT id, title, type, project_id, price, created_at, updated_at FROM shipping');
        $this->addSql('DROP TABLE shipping');
        $this->addSql('CREATE TABLE shipping (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, title VARCHAR(100) NOT NULL, type VARCHAR(20) NOT NULL, project_id INTEGER NOT NULL, price CLOB DEFAULT NULL --(DC2Type:json)
        , created_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        , updated_at DATETIME DEFAULT NULL --(DC2Type:datetime_immutable)
        )');
        $this->addSql('INSERT INTO shipping (id, title, type, project_id, price, created_at, updated_at) SELECT id, title, type, project_id, price, created_at, updated_at FROM __temp__shipping');
        $this->addSql('DROP TABLE __temp__shipping');
        $this->addSql('CREATE TEMPORARY TABLE __temp__visitor AS SELECT id, name, channel, channel_visitor_id, created_at, updated_at FROM visitor');
        $this->addSql('DROP TABLE visitor');
        $this->addSql('CREATE TABLE visitor (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(255) DEFAULT NULL, channel VARCHAR(30) NOT NULL, channel_visitor_id INTEGER NOT NULL, created_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        , updated_at DATETIME DEFAULT NULL --(DC2Type:datetime_immutable)
        )');
        $this->addSql('INSERT INTO visitor (id, name, channel, channel_visitor_id, created_at, updated_at) SELECT id, name, channel, channel_visitor_id, created_at, updated_at FROM __temp__visitor');
        $this->addSql('DROP TABLE __temp__visitor');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__deal_order AS SELECT id, products, shipping, promotions, total_amount, created_at, updated_at FROM deal_order');
        $this->addSql('DROP TABLE deal_order');
        $this->addSql('CREATE TABLE deal_order (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, products CLOB DEFAULT NULL, shipping CLOB DEFAULT NULL, promotions CLOB DEFAULT NULL, total_amount INTEGER NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL)');
        $this->addSql('INSERT INTO deal_order (id, products, shipping, promotions, total_amount, created_at, updated_at) SELECT id, products, shipping, promotions, total_amount, created_at, updated_at FROM __temp__deal_order');
        $this->addSql('DROP TABLE __temp__deal_order');
        $this->addSql('CREATE TEMPORARY TABLE __temp__product AS SELECT id, project_id, created_at, updated_at FROM product');
        $this->addSql('DROP TABLE product');
        $this->addSql('CREATE TABLE product (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, project_id INTEGER NOT NULL, created_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        , updated_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        )');
        $this->addSql('INSERT INTO product (id, project_id, created_at, updated_at) SELECT id, project_id, created_at, updated_at FROM __temp__product');
        $this->addSql('DROP TABLE __temp__product');
        $this->addSql('CREATE TEMPORARY TABLE __temp__product_category AS SELECT id, name, project_id, created_at, updated_at FROM product_category');
        $this->addSql('DROP TABLE product_category');
        $this->addSql('CREATE TABLE product_category (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(100) NOT NULL, project_id INTEGER NOT NULL, created_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        , updated_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        )');
        $this->addSql('INSERT INTO product_category (id, name, project_id, created_at, updated_at) SELECT id, name, project_id, created_at, updated_at FROM __temp__product_category');
        $this->addSql('DROP TABLE __temp__product_category');
        $this->addSql('CREATE TEMPORARY TABLE __temp__product_variant AS SELECT id, product_id, name, article, image, price, count, promotion_distributed, percent_discount, active, active_from, active_to, created_at, updated_at FROM product_variant');
        $this->addSql('DROP TABLE product_variant');
        $this->addSql('CREATE TABLE product_variant (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, product_id INTEGER DEFAULT NULL, name VARCHAR(50) NOT NULL, article VARCHAR(50) NOT NULL, image CLOB DEFAULT NULL --(DC2Type:json)
        , price CLOB NOT NULL --(DC2Type:json)
        , count INTEGER NOT NULL, promotion_distributed BOOLEAN NOT NULL, percent_discount INTEGER DEFAULT NULL, active BOOLEAN NOT NULL, active_from DATETIME DEFAULT NULL, active_to DATETIME DEFAULT NULL, created_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        , updated_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        , CONSTRAINT FK_209AA41D4584665A FOREIGN KEY (product_id) REFERENCES product (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO product_variant (id, product_id, name, article, image, price, count, promotion_distributed, percent_discount, active, active_from, active_to, created_at, updated_at) SELECT id, product_id, name, article, image, price, count, promotion_distributed, percent_discount, active, active_from, active_to, created_at, updated_at FROM __temp__product_variant');
        $this->addSql('DROP TABLE __temp__product_variant');
        $this->addSql('CREATE INDEX IDX_209AA41D4584665A ON product_variant (product_id)');
        $this->addSql('CREATE TEMPORARY TABLE __temp__promotion AS SELECT id, name, price, type, project_id, amount, code, discount_form, count, active, active_from, active_to, created_at, updated_at FROM promotion');
        $this->addSql('DROP TABLE promotion');
        $this->addSql('CREATE TABLE promotion (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(100) NOT NULL, price CLOB NOT NULL --(DC2Type:json)
        , type VARCHAR(20) NOT NULL, project_id INTEGER NOT NULL, amount INTEGER NOT NULL, code VARCHAR(100) DEFAULT NULL, discount_form VARCHAR(20) NOT NULL, count INTEGER DEFAULT NULL, active BOOLEAN NOT NULL, active_from DATETIME DEFAULT NULL, active_to DATETIME DEFAULT NULL, created_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        , updated_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        )');
        $this->addSql('INSERT INTO promotion (id, name, price, type, project_id, amount, code, discount_form, count, active, active_from, active_to, created_at, updated_at) SELECT id, name, price, type, project_id, amount, code, discount_form, count, active, active_from, active_to, created_at, updated_at FROM __temp__promotion');
        $this->addSql('DROP TABLE __temp__promotion');
        $this->addSql('CREATE TEMPORARY TABLE __temp__shipping AS SELECT id, title, type, project_id, price, created_at, updated_at FROM shipping');
        $this->addSql('DROP TABLE shipping');
        $this->addSql('CREATE TABLE shipping (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, title VARCHAR(100) NOT NULL, type VARCHAR(20) NOT NULL, project_id INTEGER NOT NULL, price CLOB DEFAULT NULL --(DC2Type:json)
        , created_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        , updated_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        )');
        $this->addSql('INSERT INTO shipping (id, title, type, project_id, price, created_at, updated_at) SELECT id, title, type, project_id, price, created_at, updated_at FROM __temp__shipping');
        $this->addSql('DROP TABLE __temp__shipping');
        $this->addSql('CREATE TEMPORARY TABLE __temp__visitor AS SELECT id, name, channel, channel_visitor_id, created_at, updated_at FROM visitor');
        $this->addSql('DROP TABLE visitor');
        $this->addSql('CREATE TABLE visitor (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(255) DEFAULT NULL, channel VARCHAR(30) NOT NULL, channel_visitor_id INTEGER NOT NULL, created_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        , updated_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        )');
        $this->addSql('INSERT INTO visitor (id, name, channel, channel_visitor_id, created_at, updated_at) SELECT id, name, channel, channel_visitor_id, created_at, updated_at FROM __temp__visitor');
        $this->addSql('DROP TABLE __temp__visitor');
    }
}
