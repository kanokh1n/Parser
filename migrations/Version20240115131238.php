<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240115131238 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE product_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE seller_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE product (id INT NOT NULL, seller_id_id INT NOT NULL, product_id INT NOT NULL, sku INT NOT NULL, price DOUBLE PRECISION NOT NULL, name VARCHAR(255) NOT NULL, reviews_count INT NOT NULL, created_date DATE NOT NULL, updated_date DATE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_D34A04ADDF4C85EA ON product (seller_id_id)');
        $this->addSql('COMMENT ON COLUMN product.created_date IS \'(DC2Type:date_immutable)\'');
        $this->addSql('COMMENT ON COLUMN product.updated_date IS \'(DC2Type:date_immutable)\'');
        $this->addSql('CREATE TABLE seller (id INT NOT NULL, seller_id INT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('ALTER TABLE product ADD CONSTRAINT FK_D34A04ADDF4C85EA FOREIGN KEY (seller_id_id) REFERENCES seller (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE product_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE seller_id_seq CASCADE');
        $this->addSql('ALTER TABLE product DROP CONSTRAINT FK_D34A04ADDF4C85EA');
        $this->addSql('DROP TABLE product');
        $this->addSql('DROP TABLE seller');
    }
}
