<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250228162200 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE user_pro (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, is_validated TINYINT(1) NOT NULL, phone VARCHAR(255) DEFAULT NULL, company_name VARCHAR(255) DEFAULT NULL, company_adress VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_80A2B774A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE user_pro ADD CONSTRAINT FK_80A2B774A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user_pro DROP FOREIGN KEY FK_80A2B774A76ED395');
        $this->addSql('DROP TABLE user_pro');
    }
}
