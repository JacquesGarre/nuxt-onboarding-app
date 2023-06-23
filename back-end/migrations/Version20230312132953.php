<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230312132953 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE subscription DROP FOREIGN KEY FK_A3C664D3DEF3D10A');
        $this->addSql('ALTER TABLE partner_pack DROP FOREIGN KEY FK_C7EA6D3BA76ED395');
        $this->addSql('DROP TABLE partner_pack');
        $this->addSql('DROP INDEX IDX_A3C664D3DEF3D10A ON subscription');
        $this->addSql('ALTER TABLE subscription ADD pack_id INT NOT NULL, ADD created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', ADD updated_at DATETIME NOT NULL, DROP partner_pack_id, DROP free, CHANGE ends_at ends_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', CHANGE paid_amount price DOUBLE PRECISION DEFAULT NULL');
        $this->addSql('ALTER TABLE subscription ADD CONSTRAINT FK_A3C664D31919B217 FOREIGN KEY (pack_id) REFERENCES pack (id)');
        $this->addSql('CREATE INDEX IDX_A3C664D31919B217 ON subscription (pack_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE partner_pack (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, name VARCHAR(30) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, price DOUBLE PRECISION NOT NULL, nb_places INT NOT NULL, nb_quests INT NOT NULL, nb_stories INT NOT NULL, nb_epics INT NOT NULL, has_duration TINYINT(1) NOT NULL, starts_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', ends_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', nb_articles INT NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', status VARCHAR(30) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, description LONGTEXT CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, INDEX IDX_C7EA6D3BA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE partner_pack ADD CONSTRAINT FK_C7EA6D3BA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE subscription DROP FOREIGN KEY FK_A3C664D31919B217');
        $this->addSql('DROP INDEX IDX_A3C664D31919B217 ON subscription');
        $this->addSql('ALTER TABLE subscription ADD partner_pack_id INT DEFAULT NULL, ADD free TINYINT(1) NOT NULL, DROP pack_id, DROP created_at, DROP updated_at, CHANGE ends_at ends_at DATETIME DEFAULT NULL, CHANGE price paid_amount DOUBLE PRECISION DEFAULT NULL');
        $this->addSql('ALTER TABLE subscription ADD CONSTRAINT FK_A3C664D3DEF3D10A FOREIGN KEY (partner_pack_id) REFERENCES partner_pack (id)');
        $this->addSql('CREATE INDEX IDX_A3C664D3DEF3D10A ON subscription (partner_pack_id)');
    }
}
