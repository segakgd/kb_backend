<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240815234836 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__shipping AS SELECT id, title, type, project_id, price, created_at, updated_at, description, calculation_type, is_active, apply_from_amount, apply_to_amount, free_from, is_not_fixed, fields FROM shipping');
        $this->addSql('DROP TABLE shipping');
        $this->addSql('CREATE TABLE shipping (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, title VARCHAR(100) NOT NULL, type VARCHAR(20) NOT NULL, project_id INTEGER NOT NULL, price CLOB DEFAULT NULL --(DC2Type:shipping_price_type)
        , created_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        , updated_at DATETIME DEFAULT NULL --(DC2Type:datetime_immutable)
        , description VARCHAR(255) NOT NULL, calculation_type VARCHAR(50) NOT NULL, active BOOLEAN NOT NULL, apply_from_amount INTEGER DEFAULT NULL, apply_to_amount INTEGER DEFAULT NULL, free_from INTEGER DEFAULT NULL, is_not_fixed BOOLEAN NOT NULL, fields CLOB NOT NULL --(DC2Type:shipping_field_req_dto_array)
        )');
        $this->addSql('INSERT INTO shipping (id, title, type, project_id, price, created_at, updated_at, description, calculation_type, active, apply_from_amount, apply_to_amount, free_from, is_not_fixed, fields) SELECT id, title, type, project_id, price, created_at, updated_at, description, calculation_type, is_active, apply_from_amount, apply_to_amount, free_from, is_not_fixed, fields FROM __temp__shipping');
        $this->addSql('DROP TABLE __temp__shipping');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__shipping AS SELECT id, title, description, type, calculation_type, project_id, price, active, apply_from_amount, apply_to_amount, free_from, is_not_fixed, fields, created_at, updated_at FROM shipping');
        $this->addSql('DROP TABLE shipping');
        $this->addSql('CREATE TABLE shipping (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, title VARCHAR(100) NOT NULL, description VARCHAR(255) NOT NULL, type VARCHAR(20) NOT NULL, calculation_type VARCHAR(50) NOT NULL, project_id INTEGER NOT NULL, price CLOB DEFAULT NULL --(DC2Type:shipping_price_type)
        , is_active BOOLEAN NOT NULL, apply_from_amount INTEGER DEFAULT NULL, apply_to_amount INTEGER DEFAULT NULL, free_from INTEGER DEFAULT NULL, is_not_fixed BOOLEAN NOT NULL, fields CLOB NOT NULL --(DC2Type:shipping_field_req_dto_array)
        , created_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        , updated_at DATETIME DEFAULT NULL --(DC2Type:datetime_immutable)
        )');
        $this->addSql('INSERT INTO shipping (id, title, description, type, calculation_type, project_id, price, is_active, apply_from_amount, apply_to_amount, free_from, is_not_fixed, fields, created_at, updated_at) SELECT id, title, description, type, calculation_type, project_id, price, active, apply_from_amount, apply_to_amount, free_from, is_not_fixed, fields, created_at, updated_at FROM __temp__shipping');
        $this->addSql('DROP TABLE __temp__shipping');
    }
}
