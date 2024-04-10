<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240407205223 extends AbstractMigration
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
        , product_variants CLOB DEFAULT NULL --(DC2Type:json)
        , shipping CLOB DEFAULT NULL --(DC2Type:json)
        , promotions CLOB DEFAULT NULL --(DC2Type:json)
        )');
        $this->addSql('INSERT INTO deal_order (id, total_amount, created_at, updated_at, product_variants, shipping, promotions) SELECT id, total_amount, created_at, updated_at, product_variants, shipping, promotions FROM __temp__deal_order');
        $this->addSql('DROP TABLE __temp__deal_order');
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
    }
}
