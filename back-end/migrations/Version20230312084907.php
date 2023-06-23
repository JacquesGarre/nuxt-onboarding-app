<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230312084907 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE place_user (place_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_4726A6A5DA6A219 (place_id), INDEX IDX_4726A6A5A76ED395 (user_id), PRIMARY KEY(place_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE place_user ADD CONSTRAINT FK_4726A6A5DA6A219 FOREIGN KEY (place_id) REFERENCES place (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE place_user ADD CONSTRAINT FK_4726A6A5A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE place_user DROP FOREIGN KEY FK_4726A6A5DA6A219');
        $this->addSql('ALTER TABLE place_user DROP FOREIGN KEY FK_4726A6A5A76ED395');
        $this->addSql('DROP TABLE place_user');
    }
}