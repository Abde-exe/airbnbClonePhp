<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220721115153 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE option_propriete DROP FOREIGN KEY FK_A2334B13A7C41D6F');
        $this->addSql('DROP TABLE `option`');
        $this->addSql('DROP TABLE option_propriete');
        $this->addSql('ALTER TABLE propriete ADD ville VARCHAR(50) DEFAULT NULL, ADD pays VARCHAR(50) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE `option` (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE option_propriete (option_id INT NOT NULL, propriete_id INT NOT NULL, INDEX IDX_A2334B13A7C41D6F (option_id), INDEX IDX_A2334B1318566CAF (propriete_id), PRIMARY KEY(option_id, propriete_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE option_propriete ADD CONSTRAINT FK_A2334B1318566CAF FOREIGN KEY (propriete_id) REFERENCES propriete (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE option_propriete ADD CONSTRAINT FK_A2334B13A7C41D6F FOREIGN KEY (option_id) REFERENCES `option` (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE propriete DROP ville, DROP pays');
    }
}
