<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230311171245 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE epic_category (epic_id INT NOT NULL, category_id INT NOT NULL, INDEX IDX_E72EF1FA6B71E00E (epic_id), INDEX IDX_E72EF1FA12469DE2 (category_id), PRIMARY KEY(epic_id, category_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE epic_category ADD CONSTRAINT FK_E72EF1FA6B71E00E FOREIGN KEY (epic_id) REFERENCES epic (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE epic_category ADD CONSTRAINT FK_E72EF1FA12469DE2 FOREIGN KEY (category_id) REFERENCES category (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE epic_category DROP FOREIGN KEY FK_E72EF1FA6B71E00E');
        $this->addSql('ALTER TABLE epic_category DROP FOREIGN KEY FK_E72EF1FA12469DE2');
        $this->addSql('DROP TABLE epic_category');
    }
}
