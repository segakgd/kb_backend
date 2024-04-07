<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240407113143 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE deal_order_product_variant (deal_order_id INTEGER NOT NULL, product_variant_id INTEGER NOT NULL, PRIMARY KEY(deal_order_id, product_variant_id), CONSTRAINT FK_D013B5576E187CA2 FOREIGN KEY (deal_order_id) REFERENCES deal_order (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_D013B557A80EF684 FOREIGN KEY (product_variant_id) REFERENCES product_variant (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_D013B5576E187CA2 ON deal_order_product_variant (deal_order_id)');
        $this->addSql('CREATE INDEX IDX_D013B557A80EF684 ON deal_order_product_variant (product_variant_id)');
        $this->addSql('CREATE TABLE deal_order_shipping (deal_order_id INTEGER NOT NULL, shipping_id INTEGER NOT NULL, PRIMARY KEY(deal_order_id, shipping_id), CONSTRAINT FK_F6B39EF96E187CA2 FOREIGN KEY (deal_order_id) REFERENCES deal_order (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_F6B39EF94887F3F8 FOREIGN KEY (shipping_id) REFERENCES shipping (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_F6B39EF96E187CA2 ON deal_order_shipping (deal_order_id)');
        $this->addSql('CREATE INDEX IDX_F6B39EF94887F3F8 ON deal_order_shipping (shipping_id)');
        $this->addSql('CREATE TABLE deal_order_promotion (deal_order_id INTEGER NOT NULL, promotion_id INTEGER NOT NULL, PRIMARY KEY(deal_order_id, promotion_id), CONSTRAINT FK_39A47C316E187CA2 FOREIGN KEY (deal_order_id) REFERENCES deal_order (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_39A47C31139DF194 FOREIGN KEY (promotion_id) REFERENCES promotion (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_39A47C316E187CA2 ON deal_order_promotion (deal_order_id)');
        $this->addSql('CREATE INDEX IDX_39A47C31139DF194 ON deal_order_promotion (promotion_id)');
        $this->addSql('CREATE TEMPORARY TABLE __temp__deal_order AS SELECT id, total_amount, created_at, updated_at FROM deal_order');
        $this->addSql('DROP TABLE deal_order');
        $this->addSql('CREATE TABLE deal_order (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, total_amount INTEGER NOT NULL, created_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        , updated_at DATETIME DEFAULT NULL --(DC2Type:datetime_immutable)
        )');
        $this->addSql('INSERT INTO deal_order (id, total_amount, created_at, updated_at) SELECT id, total_amount, created_at, updated_at FROM __temp__deal_order');
        $this->addSql('DROP TABLE __temp__deal_order');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE deal_order_product_variant');
        $this->addSql('DROP TABLE deal_order_shipping');
        $this->addSql('DROP TABLE deal_order_promotion');
        $this->addSql('ALTER TABLE deal_order ADD COLUMN products CLOB DEFAULT NULL');
        $this->addSql('ALTER TABLE deal_order ADD COLUMN shipping CLOB DEFAULT NULL');
        $this->addSql('ALTER TABLE deal_order ADD COLUMN promotions CLOB DEFAULT NULL');
    }
}
