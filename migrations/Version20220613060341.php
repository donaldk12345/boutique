<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220613060341 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE purchase_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE purchase_cart_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE purchase (id INT NOT NULL, user_purchase_id INT NOT NULL, name VARCHAR(255) NOT NULL, address VARCHAR(255) NOT NULL, codepostal VARCHAR(255) NOT NULL, city VARCHAR(255) NOT NULL, total INT NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_6117D13BFC94772C ON purchase (user_purchase_id)');
        $this->addSql('COMMENT ON COLUMN purchase.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE purchase_cart (id INT NOT NULL, produit_id INT NOT NULL, purchase_id INT NOT NULL, nom_produit VARCHAR(255) NOT NULL, prix_produit INT NOT NULL, quantite_produit INT NOT NULL, total INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_7B1040D4F347EFB ON purchase_cart (produit_id)');
        $this->addSql('CREATE INDEX IDX_7B1040D4558FBEB9 ON purchase_cart (purchase_id)');
        $this->addSql('ALTER TABLE purchase ADD CONSTRAINT FK_6117D13BFC94772C FOREIGN KEY (user_purchase_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE purchase_cart ADD CONSTRAINT FK_7B1040D4F347EFB FOREIGN KEY (produit_id) REFERENCES produit (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE purchase_cart ADD CONSTRAINT FK_7B1040D4558FBEB9 FOREIGN KEY (purchase_id) REFERENCES purchase (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE produit ADD quantite INT DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE purchase_cart DROP CONSTRAINT FK_7B1040D4558FBEB9');
        $this->addSql('DROP SEQUENCE purchase_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE purchase_cart_id_seq CASCADE');
        $this->addSql('DROP TABLE purchase');
        $this->addSql('DROP TABLE purchase_cart');
        $this->addSql('ALTER TABLE produit DROP quantite');
    }
}
