<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240507100747 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__product_variant AS SELECT id, product_id, name, article, image, price, count, promotion_distributed, percent_discount, active, active_from, active_to, created_at, updated_at, is_limitless FROM product_variant');
        $this->addSql('DROP TABLE product_variant');
        $this->addSql('CREATE TABLE product_variant (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, product_id INTEGER DEFAULT NULL, name VARCHAR(50) NOT NULL, article VARCHAR(50) NOT NULL, image CLOB DEFAULT NULL --(DC2Type:json)
        , price CLOB NOT NULL --(DC2Type:variant_price_type)
        , count INTEGER DEFAULT NULL, promotion_distributed BOOLEAN DEFAULT NULL, percent_discount INTEGER DEFAULT NULL, active BOOLEAN DEFAULT NULL, active_from DATETIME DEFAULT NULL, active_to DATETIME DEFAULT NULL, created_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        , updated_at DATETIME DEFAULT NULL --(DC2Type:datetime_immutable)
        , is_limitless BOOLEAN NOT NULL, CONSTRAINT FK_209AA41D4584665A FOREIGN KEY (product_id) REFERENCES product (id) ON UPDATE NO ACTION ON DELETE NO ACTION NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO product_variant (id, product_id, name, article, image, price, count, promotion_distributed, percent_discount, active, active_from, active_to, created_at, updated_at, is_limitless) SELECT id, product_id, name, article, image, price, count, promotion_distributed, percent_discount, active, active_from, active_to, created_at, updated_at, is_limitless FROM __temp__product_variant');
        $this->addSql('DROP TABLE __temp__product_variant');
        $this->addSql('CREATE INDEX IDX_209AA41D4584665A ON product_variant (product_id)');
        $this->addSql('CREATE TEMPORARY TABLE __temp__promotion AS SELECT id, name, type, project_id, amount, code, count, active, active_from, active_to, created_at, updated_at, discount_type, discount_from, usage_with_any_discount, deleted_at FROM promotion');
        $this->addSql('DROP TABLE promotion');
        $this->addSql('CREATE TABLE promotion (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(100) NOT NULL, type VARCHAR(20) NOT NULL, project_id INTEGER NOT NULL, amount INTEGER NOT NULL, code VARCHAR(100) NOT NULL, count INTEGER DEFAULT NULL, active BOOLEAN NOT NULL, active_from DATETIME DEFAULT NULL, active_to DATETIME DEFAULT NULL, created_at DATETIME DEFAULT NULL, updated_at DATETIME DEFAULT NULL, discount_type VARCHAR(255) NOT NULL, discount_from INTEGER DEFAULT NULL, usage_with_any_discount BOOLEAN NOT NULL, deleted_at DATETIME DEFAULT NULL)');
        $this->addSql('INSERT INTO promotion (id, name, type, project_id, amount, code, count, active, active_from, active_to, created_at, updated_at, discount_type, discount_from, usage_with_any_discount, deleted_at) SELECT id, name, type, project_id, amount, code, count, active, active_from, active_to, created_at, updated_at, discount_type, discount_from, usage_with_any_discount, deleted_at FROM __temp__promotion');
        $this->addSql('DROP TABLE __temp__promotion');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__product_variant AS SELECT id, product_id, name, article, image, price, count, promotion_distributed, percent_discount, active, is_limitless, active_from, active_to, created_at, updated_at FROM product_variant');
        $this->addSql('DROP TABLE product_variant');
        $this->addSql('CREATE TABLE product_variant (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, product_id INTEGER DEFAULT NULL, name VARCHAR(50) NOT NULL, article VARCHAR(50) NOT NULL, image CLOB DEFAULT NULL --(DC2Type:json)
        , price CLOB NOT NULL --(DC2Type:variant_price_type)
        , count INTEGER DEFAULT NULL, promotion_distributed BOOLEAN DEFAULT NULL, percent_discount INTEGER DEFAULT NULL, active BOOLEAN DEFAULT NULL, is_limitless BOOLEAN DEFAULT 0 NOT NULL, active_from DATETIME DEFAULT NULL, active_to DATETIME DEFAULT NULL, created_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        , updated_at DATETIME DEFAULT NULL --(DC2Type:datetime_immutable)
        , CONSTRAINT FK_209AA41D4584665A FOREIGN KEY (product_id) REFERENCES product (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO product_variant (id, product_id, name, article, image, price, count, promotion_distributed, percent_discount, active, is_limitless, active_from, active_to, created_at, updated_at) SELECT id, product_id, name, article, image, price, count, promotion_distributed, percent_discount, active, is_limitless, active_from, active_to, created_at, updated_at FROM __temp__product_variant');
        $this->addSql('DROP TABLE __temp__product_variant');
        $this->addSql('CREATE INDEX IDX_209AA41D4584665A ON product_variant (product_id)');
        $this->addSql('CREATE TEMPORARY TABLE __temp__promotion AS SELECT id, name, type, discount_type, project_id, amount, code, discount_from, count, active, usage_with_any_discount, active_from, active_to, created_at, updated_at, deleted_at FROM promotion');
        $this->addSql('DROP TABLE promotion');
        $this->addSql('CREATE TABLE promotion (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(100) NOT NULL, type VARCHAR(20) NOT NULL, discount_type VARCHAR(255) NOT NULL, project_id INTEGER NOT NULL, amount INTEGER NOT NULL, code VARCHAR(100) NOT NULL, discount_from INTEGER NOT NULL, count INTEGER DEFAULT NULL, active BOOLEAN NOT NULL, usage_with_any_discount BOOLEAN NOT NULL, active_from DATETIME DEFAULT NULL, active_to DATETIME DEFAULT NULL, created_at DATETIME DEFAULT NULL --(DC2Type:datetime_immutable)
        , updated_at VARCHAR(255) DEFAULT NULL, deleted_at DATETIME DEFAULT NULL)');
        $this->addSql('INSERT INTO promotion (id, name, type, discount_type, project_id, amount, code, discount_from, count, active, usage_with_any_discount, active_from, active_to, created_at, updated_at, deleted_at) SELECT id, name, type, discount_type, project_id, amount, code, discount_from, count, active, usage_with_any_discount, active_from, active_to, created_at, updated_at, deleted_at FROM __temp__promotion');
        $this->addSql('DROP TABLE __temp__promotion');
    }
}
