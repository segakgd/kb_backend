<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240507092328 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__promotion AS SELECT id, name, type, project_id, amount, code, count, active, active_from, active_to, created_at, updated_at FROM promotion');
        $this->addSql('DROP TABLE promotion');
        $this->addSql('CREATE TABLE promotion (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(100) NOT NULL, type VARCHAR(20) NOT NULL, project_id INTEGER NOT NULL, amount INTEGER NOT NULL, code VARCHAR(100) NOT NULL, count INTEGER DEFAULT NULL, active BOOLEAN NOT NULL, active_from DATETIME DEFAULT NULL, active_to DATETIME DEFAULT NULL, created_at DATETIME DEFAULT NULL --(DC2Type:datetime_immutable)
        , updated_at VARCHAR(255) DEFAULT NULL, discount_type VARCHAR(255) NOT NULL, discount_from INTEGER NOT NULL, usage_with_any_discount BOOLEAN NOT NULL, deleted_at DATETIME DEFAULT NULL)');
        $this->addSql('INSERT INTO promotion (id, name, type, project_id, amount, code, count, active, active_from, active_to, created_at, updated_at) SELECT id, name, type, project_id, amount, code, count, active, active_from, active_to, created_at, updated_at FROM __temp__promotion');
        $this->addSql('DROP TABLE __temp__promotion');
        $this->addSql('CREATE TEMPORARY TABLE __temp__shipping AS SELECT id, title, type, project_id, price, created_at, updated_at FROM shipping');
        $this->addSql('DROP TABLE shipping');
        $this->addSql('CREATE TABLE shipping (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, title VARCHAR(100) NOT NULL, type VARCHAR(20) NOT NULL, project_id INTEGER NOT NULL, price CLOB DEFAULT NULL --(DC2Type:shipping_price_type)
        , created_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        , updated_at DATETIME DEFAULT NULL --(DC2Type:datetime_immutable)
        , description VARCHAR(255) NOT NULL, calculation_type VARCHAR(50) NOT NULL, is_active BOOLEAN NOT NULL, apply_from_amount INTEGER DEFAULT NULL, apply_to_amount INTEGER DEFAULT NULL, free_from INTEGER DEFAULT NULL, is_not_fixed BOOLEAN NOT NULL, fields CLOB NOT NULL --(DC2Type:shipping_field_req_dto_array)
        )');
        $this->addSql('INSERT INTO shipping (id, title, type, project_id, price, created_at, updated_at) SELECT id, title, type, project_id, price, created_at, updated_at FROM __temp__shipping');
        $this->addSql('DROP TABLE __temp__shipping');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__promotion AS SELECT id, name, type, project_id, amount, code, count, active, active_from, active_to, created_at, updated_at FROM promotion');
        $this->addSql('DROP TABLE promotion');
        $this->addSql('CREATE TABLE promotion (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(100) NOT NULL, type VARCHAR(20) NOT NULL, project_id INTEGER NOT NULL, amount INTEGER NOT NULL, code VARCHAR(100) DEFAULT NULL, count INTEGER DEFAULT NULL, active BOOLEAN NOT NULL, active_from DATETIME DEFAULT NULL, active_to DATETIME DEFAULT NULL, created_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        , updated_at DATETIME DEFAULT NULL --(DC2Type:datetime_immutable)
        , price CLOB NOT NULL --(DC2Type:json)
        , discount_form VARCHAR(20) NOT NULL)');
        $this->addSql('INSERT INTO promotion (id, name, type, project_id, amount, code, count, active, active_from, active_to, created_at, updated_at) SELECT id, name, type, project_id, amount, code, count, active, active_from, active_to, created_at, updated_at FROM __temp__promotion');
        $this->addSql('DROP TABLE __temp__promotion');
        $this->addSql('CREATE TEMPORARY TABLE __temp__shipping AS SELECT id, title, type, project_id, price, created_at, updated_at FROM shipping');
        $this->addSql('DROP TABLE shipping');
        $this->addSql('CREATE TABLE shipping (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, title VARCHAR(100) NOT NULL, type VARCHAR(20) NOT NULL, project_id INTEGER NOT NULL, price CLOB DEFAULT NULL --(DC2Type:json)
        , created_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        , updated_at DATETIME DEFAULT NULL --(DC2Type:datetime_immutable)
        )');
        $this->addSql('INSERT INTO shipping (id, title, type, project_id, price, created_at, updated_at) SELECT id, title, type, project_id, price, created_at, updated_at FROM __temp__shipping');
        $this->addSql('DROP TABLE __temp__shipping');
    }
}
