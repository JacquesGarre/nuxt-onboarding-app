<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230311165752 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE achievement_category (achievement_id INT NOT NULL, category_id INT NOT NULL, INDEX IDX_CACE37ACB3EC99FE (achievement_id), INDEX IDX_CACE37AC12469DE2 (category_id), PRIMARY KEY(achievement_id, category_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE achievement_category ADD CONSTRAINT FK_CACE37ACB3EC99FE FOREIGN KEY (achievement_id) REFERENCES achievement (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE achievement_category ADD CONSTRAINT FK_CACE37AC12469DE2 FOREIGN KEY (category_id) REFERENCES category (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE achievement_category DROP FOREIGN KEY FK_CACE37ACB3EC99FE');
        $this->addSql('ALTER TABLE achievement_category DROP FOREIGN KEY FK_CACE37AC12469DE2');
        $this->addSql('DROP TABLE achievement_category');
    }
}
