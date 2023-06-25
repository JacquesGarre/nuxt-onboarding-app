<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230625174155 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE api_token DROP get_mobile_app_settings, DROP post_mobile_app_settings, DROP patch_mobile_app_settings, DROP put_mobile_app_settings, DROP delete_mobile_app_settings, DROP get_articles, DROP post_articles, DROP patch_articles, DROP put_articles, DROP delete_articles');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE api_token ADD get_mobile_app_settings TINYINT(1) NOT NULL, ADD post_mobile_app_settings TINYINT(1) NOT NULL, ADD patch_mobile_app_settings TINYINT(1) NOT NULL, ADD put_mobile_app_settings TINYINT(1) NOT NULL, ADD delete_mobile_app_settings TINYINT(1) NOT NULL, ADD get_articles TINYINT(1) NOT NULL, ADD post_articles TINYINT(1) NOT NULL, ADD patch_articles TINYINT(1) NOT NULL, ADD put_articles TINYINT(1) NOT NULL, ADD delete_articles TINYINT(1) NOT NULL');
    }
}
