<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230311132215 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE partner_pack ADD user_id INT NOT NULL');
        $this->addSql('ALTER TABLE partner_pack ADD CONSTRAINT FK_C7EA6D3BA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_C7EA6D3BA76ED395 ON partner_pack (user_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE partner_pack DROP FOREIGN KEY FK_C7EA6D3BA76ED395');
        $this->addSql('DROP INDEX IDX_C7EA6D3BA76ED395 ON partner_pack');
        $this->addSql('ALTER TABLE partner_pack DROP user_id');
    }
}
