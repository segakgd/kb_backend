<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240507104111 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__promotion AS SELECT id, name, type, project_id, amount, code, count, active, active_from, active_to, created_at, updated_at, discount_type, discount_from, usage_with_any_discount, deleted_at FROM promotion');
        $this->addSql('DROP TABLE promotion');
        $this->addSql('CREATE TABLE promotion (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(100) NOT NULL, type VARCHAR(20) NOT NULL, project_id INTEGER NOT NULL, amount INTEGER NOT NULL, code VARCHAR(100) NOT NULL, count INTEGER DEFAULT NULL, active BOOLEAN NOT NULL, active_from DATETIME DEFAULT NULL, active_to DATETIME DEFAULT NULL, created_at DATE DEFAULT NULL --(DC2Type:date_immutable)
        , updated_at DATETIME DEFAULT NULL, discount_type VARCHAR(255) NOT NULL, discount_from INTEGER DEFAULT NULL, usage_with_any_discount BOOLEAN NOT NULL, deleted_at DATETIME DEFAULT NULL)');
        $this->addSql('INSERT INTO promotion (id, name, type, project_id, amount, code, count, active, active_from, active_to, created_at, updated_at, discount_type, discount_from, usage_with_any_discount, deleted_at) SELECT id, name, type, project_id, amount, code, count, active, active_from, active_to, created_at, updated_at, discount_type, discount_from, usage_with_any_discount, deleted_at FROM __temp__promotion');
        $this->addSql('DROP TABLE __temp__promotion');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__promotion AS SELECT id, name, type, discount_type, project_id, amount, code, discount_from, count, active, usage_with_any_discount, active_from, active_to, created_at, updated_at, deleted_at FROM promotion');
        $this->addSql('DROP TABLE promotion');
        $this->addSql('CREATE TABLE promotion (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, name VARCHAR(100) NOT NULL, type VARCHAR(20) NOT NULL, discount_type VARCHAR(255) NOT NULL, project_id INTEGER NOT NULL, amount INTEGER NOT NULL, code VARCHAR(100) NOT NULL, discount_from INTEGER DEFAULT NULL, count INTEGER DEFAULT NULL, active BOOLEAN NOT NULL, usage_with_any_discount BOOLEAN NOT NULL, active_from DATETIME DEFAULT NULL, active_to DATETIME DEFAULT NULL, created_at DATETIME DEFAULT NULL, updated_at DATETIME DEFAULT NULL, deleted_at DATETIME DEFAULT NULL)');
        $this->addSql('INSERT INTO promotion (id, name, type, discount_type, project_id, amount, code, discount_from, count, active, usage_with_any_discount, active_from, active_to, created_at, updated_at, deleted_at) SELECT id, name, type, discount_type, project_id, amount, code, discount_from, count, active, usage_with_any_discount, active_from, active_to, created_at, updated_at, deleted_at FROM __temp__promotion');
        $this->addSql('DROP TABLE __temp__promotion');
    }
}
