<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230312084141 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE story_user (story_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_1B3EBA67AA5D4036 (story_id), INDEX IDX_1B3EBA67A76ED395 (user_id), PRIMARY KEY(story_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE story_user ADD CONSTRAINT FK_1B3EBA67AA5D4036 FOREIGN KEY (story_id) REFERENCES story (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE story_user ADD CONSTRAINT FK_1B3EBA67A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE story_user DROP FOREIGN KEY FK_1B3EBA67AA5D4036');
        $this->addSql('ALTER TABLE story_user DROP FOREIGN KEY FK_1B3EBA67A76ED395');
        $this->addSql('DROP TABLE story_user');
    }
}
