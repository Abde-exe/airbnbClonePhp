<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220719085434 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE propriete ADD user_id INT NOT NULL, CHANGE prix_journalier prix_journalier INT NOT NULL');
        $this->addSql('ALTER TABLE propriete ADD CONSTRAINT FK_73A85B93A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_73A85B93A76ED395 ON propriete (user_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE propriete DROP FOREIGN KEY FK_73A85B93A76ED395');
        $this->addSql('DROP INDEX IDX_73A85B93A76ED395 ON propriete');
        $this->addSql('ALTER TABLE propriete DROP user_id, CHANGE prix_journalier prix_journalier DOUBLE PRECISION NOT NULL');
    }
}
