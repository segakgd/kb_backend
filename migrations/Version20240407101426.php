<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240407101426 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__product_variant AS SELECT id, product_id, name, article, image, price, count, promotion_distributed, percent_discount, active, active_from, active_to, created_at, updated_at FROM product_variant');
        $this->addSql('DROP TABLE product_variant');
        $this->addSql('CREATE TABLE product_variant (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, product_id INTEGER DEFAULT NULL, name VARCHAR(50) NOT NULL, article VARCHAR(50) NOT NULL, image CLOB DEFAULT NULL --(DC2Type:json)
        , price CLOB NOT NULL --(DC2Type:variant_price_type)
        , count INTEGER DEFAULT NULL, promotion_distributed BOOLEAN DEFAULT NULL, percent_discount INTEGER DEFAULT NULL, active BOOLEAN DEFAULT NULL, active_from DATETIME DEFAULT NULL, active_to DATETIME DEFAULT NULL, created_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        , updated_at DATETIME DEFAULT NULL --(DC2Type:datetime_immutable)
        , is_limitless BOOLEAN NOT NULL, CONSTRAINT FK_209AA41D4584665A FOREIGN KEY (product_id) REFERENCES product (id) ON UPDATE NO ACTION ON DELETE NO ACTION NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO product_variant (id, product_id, name, article, image, price, count, promotion_distributed, percent_discount, active, active_from, active_to, created_at, updated_at) SELECT id, product_id, name, article, image, price, count, promotion_distributed, percent_discount, active, active_from, active_to, created_at, updated_at FROM __temp__product_variant');
        $this->addSql('DROP TABLE __temp__product_variant');
        $this->addSql('CREATE INDEX IDX_209AA41D4584665A ON product_variant (product_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__product_variant AS SELECT id, product_id, name, article, image, price, count, promotion_distributed, percent_discount, active, active_from, active_to, created_at, updated_at FROM product_variant');
        $this->addSql('DROP TABLE product_variant');
        $this->addSql('CREATE TABLE product_variant (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, product_id INTEGER DEFAULT NULL, name VARCHAR(50) NOT NULL, article VARCHAR(50) NOT NULL, image CLOB DEFAULT NULL, price CLOB NOT NULL, count INTEGER DEFAULT NULL, promotion_distributed BOOLEAN DEFAULT NULL, percent_discount INTEGER DEFAULT NULL, active BOOLEAN DEFAULT NULL, active_from DATETIME DEFAULT NULL, active_to DATETIME DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, CONSTRAINT FK_209AA41D4584665A FOREIGN KEY (product_id) REFERENCES product (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO product_variant (id, product_id, name, article, image, price, count, promotion_distributed, percent_discount, active, active_from, active_to, created_at, updated_at) SELECT id, product_id, name, article, image, price, count, promotion_distributed, percent_discount, active, active_from, active_to, created_at, updated_at FROM __temp__product_variant');
        $this->addSql('DROP TABLE __temp__product_variant');
        $this->addSql('CREATE INDEX IDX_209AA41D4584665A ON product_variant (product_id)');
    }
}
