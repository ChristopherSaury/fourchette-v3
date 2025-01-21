<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250117154842 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE fvorder DROP FOREIGN KEY FK_9B7A2CF8A76ED395');
        $this->addSql('ALTER TABLE fvorder CHANGE user_id user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE fvorder ADD CONSTRAINT FK_9B7A2CF8A76ED395 FOREIGN KEY (user_id) REFERENCES fvuser (id) ON DELETE SET NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE fvorder DROP FOREIGN KEY FK_9B7A2CF8A76ED395');
        $this->addSql('ALTER TABLE fvorder CHANGE user_id user_id INT NOT NULL');
        $this->addSql('ALTER TABLE fvorder ADD CONSTRAINT FK_9B7A2CF8A76ED395 FOREIGN KEY (user_id) REFERENCES fvuser (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
    }
}
