<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220722143609 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE category (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE panier (id INT AUTO_INCREMENT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE propriete (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, category_id INT NOT NULL, titre VARCHAR(100) NOT NULL, description VARCHAR(255) NOT NULL, photos VARCHAR(255) NOT NULL, prix_journalier INT NOT NULL, date_enregistrement DATETIME NOT NULL, lits INT DEFAULT NULL, chambres INT DEFAULT NULL, sdb INT DEFAULT NULL, ville VARCHAR(50) DEFAULT NULL, pays VARCHAR(50) DEFAULT NULL, INDEX IDX_73A85B93A76ED395 (user_id), INDEX IDX_73A85B9312469DE2 (category_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reservation (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, date DATETIME NOT NULL, montant DOUBLE PRECISION NOT NULL, INDEX IDX_42C84955A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reservation_detail (id INT AUTO_INCREMENT NOT NULL, reservation_id INT DEFAULT NULL, propriete_id INT NOT NULL, nb_pers INT NOT NULL, prix DOUBLE PRECISION NOT NULL, INDEX IDX_66F73608B83297E7 (reservation_id), INDEX IDX_66F7360818566CAF (propriete_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, nom VARCHAR(20) NOT NULL, prenom VARCHAR(20) NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE propriete ADD CONSTRAINT FK_73A85B93A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE propriete ADD CONSTRAINT FK_73A85B9312469DE2 FOREIGN KEY (category_id) REFERENCES category (id)');
        $this->addSql('ALTER TABLE reservation ADD CONSTRAINT FK_42C84955A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE reservation_detail ADD CONSTRAINT FK_66F73608B83297E7 FOREIGN KEY (reservation_id) REFERENCES reservation (id)');
        $this->addSql('ALTER TABLE reservation_detail ADD CONSTRAINT FK_66F7360818566CAF FOREIGN KEY (propriete_id) REFERENCES propriete (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE propriete DROP FOREIGN KEY FK_73A85B9312469DE2');
        $this->addSql('ALTER TABLE reservation_detail DROP FOREIGN KEY FK_66F7360818566CAF');
        $this->addSql('ALTER TABLE reservation_detail DROP FOREIGN KEY FK_66F73608B83297E7');
        $this->addSql('ALTER TABLE propriete DROP FOREIGN KEY FK_73A85B93A76ED395');
        $this->addSql('ALTER TABLE reservation DROP FOREIGN KEY FK_42C84955A76ED395');
    }
}
