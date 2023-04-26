<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230426180103 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE client (id INT AUTO_INCREMENT NOT NULL, employe_id INT NOT NULL, surname VARCHAR(50) NOT NULL, name VARCHAR(50) NOT NULL, address VARCHAR(50) NOT NULL, city VARCHAR(50) NOT NULL, postal_code VARCHAR(10) NOT NULL, country VARCHAR(50) NOT NULL, phone VARCHAR(10) DEFAULT NULL, mail VARCHAR(50) NOT NULL, pro TINYINT(1) NOT NULL, coef NUMERIC(4, 2) NOT NULL, INDEX IDX_C74404551B65292 (employe_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE employe (id INT AUTO_INCREMENT NOT NULL, surname VARCHAR(50) NOT NULL, name VARCHAR(50) NOT NULL, address VARCHAR(50) NOT NULL, city VARCHAR(50) NOT NULL, postal_code VARCHAR(10) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE fournisseur (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(50) NOT NULL, address VARCHAR(50) NOT NULL, city VARCHAR(50) NOT NULL, postal_code VARCHAR(10) NOT NULL, country VARCHAR(50) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE produit (id INT AUTO_INCREMENT NOT NULL, fournisseur_id INT NOT NULL, name VARCHAR(50) NOT NULL, description VARCHAR(255) NOT NULL, price NUMERIC(5, 2) NOT NULL, picture VARCHAR(255) NOT NULL, stock INT NOT NULL, active TINYINT(1) NOT NULL, INDEX IDX_29A5EC27670C757F (fournisseur_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE produit_sous_rubrique (produit_id INT NOT NULL, sous_rubrique_id INT NOT NULL, INDEX IDX_A9B49BF7F347EFB (produit_id), INDEX IDX_A9B49BF77BEAFB00 (sous_rubrique_id), PRIMARY KEY(produit_id, sous_rubrique_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE sous_rubrique (id INT AUTO_INCREMENT NOT NULL, rubrique_id INT NOT NULL, name VARCHAR(50) NOT NULL, picture VARCHAR(255) DEFAULT NULL, INDEX IDX_87EA3D293BD38833 (rubrique_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE client ADD CONSTRAINT FK_C74404551B65292 FOREIGN KEY (employe_id) REFERENCES employe (id)');
        $this->addSql('ALTER TABLE produit ADD CONSTRAINT FK_29A5EC27670C757F FOREIGN KEY (fournisseur_id) REFERENCES fournisseur (id)');
        $this->addSql('ALTER TABLE produit_sous_rubrique ADD CONSTRAINT FK_A9B49BF7F347EFB FOREIGN KEY (produit_id) REFERENCES produit (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE produit_sous_rubrique ADD CONSTRAINT FK_A9B49BF77BEAFB00 FOREIGN KEY (sous_rubrique_id) REFERENCES sous_rubrique (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE sous_rubrique ADD CONSTRAINT FK_87EA3D293BD38833 FOREIGN KEY (rubrique_id) REFERENCES rubrique (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE client DROP FOREIGN KEY FK_C74404551B65292');
        $this->addSql('ALTER TABLE produit DROP FOREIGN KEY FK_29A5EC27670C757F');
        $this->addSql('ALTER TABLE produit_sous_rubrique DROP FOREIGN KEY FK_A9B49BF7F347EFB');
        $this->addSql('ALTER TABLE produit_sous_rubrique DROP FOREIGN KEY FK_A9B49BF77BEAFB00');
        $this->addSql('ALTER TABLE sous_rubrique DROP FOREIGN KEY FK_87EA3D293BD38833');
        $this->addSql('DROP TABLE client');
        $this->addSql('DROP TABLE employe');
        $this->addSql('DROP TABLE fournisseur');
        $this->addSql('DROP TABLE produit');
        $this->addSql('DROP TABLE produit_sous_rubrique');
        $this->addSql('DROP TABLE sous_rubrique');
    }
}
