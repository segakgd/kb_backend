<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240407204930 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE deal_order_product_variant');
        $this->addSql('DROP TABLE deal_order_promotion');
        $this->addSql('DROP TABLE deal_order_shipping');
        $this->addSql('ALTER TABLE deal_order ADD COLUMN product_variants CLOB DEFAULT NULL');
        $this->addSql('ALTER TABLE deal_order ADD COLUMN shipping CLOB DEFAULT NULL');
        $this->addSql('ALTER TABLE deal_order ADD COLUMN promotions CLOB DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE deal_order_product_variant (id INTEGER DEFAULT NULL)');
        $this->addSql('CREATE TABLE deal_order_promotion (id INTEGER DEFAULT NULL)');
        $this->addSql('CREATE TABLE deal_order_shipping (id INTEGER DEFAULT NULL)');
        $this->addSql('CREATE TEMPORARY TABLE __temp__deal_order AS SELECT id, total_amount, created_at, updated_at FROM deal_order');
        $this->addSql('DROP TABLE deal_order');
        $this->addSql('CREATE TABLE deal_order (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, total_amount INTEGER NOT NULL, created_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        , updated_at DATETIME DEFAULT NULL --(DC2Type:datetime_immutable)
        )');
        $this->addSql('INSERT INTO deal_order (id, total_amount, created_at, updated_at) SELECT id, total_amount, created_at, updated_at FROM __temp__deal_order');
        $this->addSql('DROP TABLE __temp__deal_order');
    }
}
