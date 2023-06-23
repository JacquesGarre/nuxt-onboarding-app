<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230312084810 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE epic_user (epic_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_DA7764586B71E00E (epic_id), INDEX IDX_DA776458A76ED395 (user_id), PRIMARY KEY(epic_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE epic_user ADD CONSTRAINT FK_DA7764586B71E00E FOREIGN KEY (epic_id) REFERENCES epic (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE epic_user ADD CONSTRAINT FK_DA776458A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE epic_user DROP FOREIGN KEY FK_DA7764586B71E00E');
        $this->addSql('ALTER TABLE epic_user DROP FOREIGN KEY FK_DA776458A76ED395');
        $this->addSql('DROP TABLE epic_user');
    }
}
