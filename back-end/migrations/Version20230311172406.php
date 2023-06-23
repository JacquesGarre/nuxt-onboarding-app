<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230311172406 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE subscription (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, partner_pack_id INT DEFAULT NULL, status VARCHAR(255) NOT NULL, paid_amount DOUBLE PRECISION DEFAULT NULL, free TINYINT(1) NOT NULL, starts_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', ends_at DATETIME DEFAULT NULL, INDEX IDX_A3C664D3A76ED395 (user_id), INDEX IDX_A3C664D3DEF3D10A (partner_pack_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE subscription ADD CONSTRAINT FK_A3C664D3A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE subscription ADD CONSTRAINT FK_A3C664D3DEF3D10A FOREIGN KEY (partner_pack_id) REFERENCES partner_pack (id)');
        $this->addSql('ALTER TABLE achievement_category DROP FOREIGN KEY FK_CACE37ACB3EC99FE');
        $this->addSql('ALTER TABLE achievement_category DROP FOREIGN KEY FK_CACE37AC12469DE2');
        $this->addSql('ALTER TABLE category DROP FOREIGN KEY FK_64C19C1727ACA70');
        $this->addSql('ALTER TABLE epic_category DROP FOREIGN KEY FK_E72EF1FA12469DE2');
        $this->addSql('ALTER TABLE epic_category DROP FOREIGN KEY FK_E72EF1FA6B71E00E');
        $this->addSql('ALTER TABLE quest_category DROP FOREIGN KEY FK_E716A66412469DE2');
        $this->addSql('ALTER TABLE quest_category DROP FOREIGN KEY FK_E716A664209E9EF4');
        $this->addSql('ALTER TABLE story_category DROP FOREIGN KEY FK_5A9075D12469DE2');
        $this->addSql('ALTER TABLE story_category DROP FOREIGN KEY FK_5A9075DAA5D4036');
        $this->addSql('DROP TABLE achievement_category');
        $this->addSql('DROP TABLE category');
        $this->addSql('DROP TABLE epic_category');
        $this->addSql('DROP TABLE quest_category');
        $this->addSql('DROP TABLE story_category');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE achievement_category (achievement_id INT NOT NULL, category_id INT NOT NULL, INDEX IDX_CACE37ACB3EC99FE (achievement_id), INDEX IDX_CACE37AC12469DE2 (category_id), PRIMARY KEY(achievement_id, category_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE category (id INT AUTO_INCREMENT NOT NULL, parent_id INT DEFAULT NULL, name VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, latitude VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, longitude VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, radius INT DEFAULT NULL, INDEX IDX_64C19C1727ACA70 (parent_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE epic_category (epic_id INT NOT NULL, category_id INT NOT NULL, INDEX IDX_E72EF1FA6B71E00E (epic_id), INDEX IDX_E72EF1FA12469DE2 (category_id), PRIMARY KEY(epic_id, category_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE quest_category (quest_id INT NOT NULL, category_id INT NOT NULL, INDEX IDX_E716A664209E9EF4 (quest_id), INDEX IDX_E716A66412469DE2 (category_id), PRIMARY KEY(quest_id, category_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE story_category (story_id INT NOT NULL, category_id INT NOT NULL, INDEX IDX_5A9075D12469DE2 (category_id), INDEX IDX_5A9075DAA5D4036 (story_id), PRIMARY KEY(story_id, category_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE achievement_category ADD CONSTRAINT FK_CACE37ACB3EC99FE FOREIGN KEY (achievement_id) REFERENCES achievement (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE achievement_category ADD CONSTRAINT FK_CACE37AC12469DE2 FOREIGN KEY (category_id) REFERENCES category (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE category ADD CONSTRAINT FK_64C19C1727ACA70 FOREIGN KEY (parent_id) REFERENCES category (id)');
        $this->addSql('ALTER TABLE epic_category ADD CONSTRAINT FK_E72EF1FA12469DE2 FOREIGN KEY (category_id) REFERENCES category (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE epic_category ADD CONSTRAINT FK_E72EF1FA6B71E00E FOREIGN KEY (epic_id) REFERENCES epic (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE quest_category ADD CONSTRAINT FK_E716A66412469DE2 FOREIGN KEY (category_id) REFERENCES category (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE quest_category ADD CONSTRAINT FK_E716A664209E9EF4 FOREIGN KEY (quest_id) REFERENCES quest (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE story_category ADD CONSTRAINT FK_5A9075D12469DE2 FOREIGN KEY (category_id) REFERENCES category (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE story_category ADD CONSTRAINT FK_5A9075DAA5D4036 FOREIGN KEY (story_id) REFERENCES story (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE subscription DROP FOREIGN KEY FK_A3C664D3A76ED395');
        $this->addSql('ALTER TABLE subscription DROP FOREIGN KEY FK_A3C664D3DEF3D10A');
        $this->addSql('DROP TABLE subscription');
    }
}