<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230311170440 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE quest_category (quest_id INT NOT NULL, category_id INT NOT NULL, INDEX IDX_E716A664209E9EF4 (quest_id), INDEX IDX_E716A66412469DE2 (category_id), PRIMARY KEY(quest_id, category_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE quest_category ADD CONSTRAINT FK_E716A664209E9EF4 FOREIGN KEY (quest_id) REFERENCES quest (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE quest_category ADD CONSTRAINT FK_E716A66412469DE2 FOREIGN KEY (category_id) REFERENCES category (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE quest_category DROP FOREIGN KEY FK_E716A664209E9EF4');
        $this->addSql('ALTER TABLE quest_category DROP FOREIGN KEY FK_E716A66412469DE2');
        $this->addSql('DROP TABLE quest_category');
    }
}
