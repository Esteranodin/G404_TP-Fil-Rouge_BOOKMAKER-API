<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250226142911 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE book_categorie (book_id INT NOT NULL, categorie_id INT NOT NULL, INDEX IDX_5BD3C07216A2B381 (book_id), INDEX IDX_5BD3C072BCF5E72D (categorie_id), PRIMARY KEY(book_id, categorie_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE categorie (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE book_categorie ADD CONSTRAINT FK_5BD3C07216A2B381 FOREIGN KEY (book_id) REFERENCES book (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE book_categorie ADD CONSTRAINT FK_5BD3C072BCF5E72D FOREIGN KEY (categorie_id) REFERENCES categorie (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE book CHANGE isbn isbn VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE book_categorie DROP FOREIGN KEY FK_5BD3C07216A2B381');
        $this->addSql('ALTER TABLE book_categorie DROP FOREIGN KEY FK_5BD3C072BCF5E72D');
        $this->addSql('DROP TABLE book_categorie');
        $this->addSql('DROP TABLE categorie');
        $this->addSql('ALTER TABLE book CHANGE isbn isbn VARCHAR(255) DEFAULT NULL');
    }
}
