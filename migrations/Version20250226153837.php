<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250226153837 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE img ADD book_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE img ADD CONSTRAINT FK_BBC2C8AC16A2B381 FOREIGN KEY (book_id) REFERENCES book (id)');
        $this->addSql('CREATE INDEX IDX_BBC2C8AC16A2B381 ON img (book_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE img DROP FOREIGN KEY FK_BBC2C8AC16A2B381');
        $this->addSql('DROP INDEX IDX_BBC2C8AC16A2B381 ON img');
        $this->addSql('ALTER TABLE img DROP book_id');
    }
}
