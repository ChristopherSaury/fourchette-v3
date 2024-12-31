<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241227081245 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE fvorder (id INT AUTO_INCREMENT NOT NULL, created_at DATETIME NOT NULL, state INT NOT NULL, delivery LONGTEXT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE fvorder_detail (id INT AUTO_INCREMENT NOT NULL, my_order_id INT DEFAULT NULL, dish_name VARCHAR(255) NOT NULL, dish_image VARCHAR(255) NOT NULL, dish_quantity INT NOT NULL, dish_price DOUBLE PRECISION NOT NULL, dish_tva DOUBLE PRECISION NOT NULL, INDEX IDX_29E9B728BFCDF877 (my_order_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE fvorder_detail ADD CONSTRAINT FK_29E9B728BFCDF877 FOREIGN KEY (my_order_id) REFERENCES fvorder (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE fvorder_detail DROP FOREIGN KEY FK_29E9B728BFCDF877');
        $this->addSql('DROP TABLE fvorder');
        $this->addSql('DROP TABLE fvorder_detail');
    }
}
