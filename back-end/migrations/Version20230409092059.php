<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230409092059 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE continent DROP wkt_bk, CHANGE wkt wkt MULTIPOLYGON DEFAULT NULL COMMENT \'(DC2Type:multipolygon)\'');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE continent ADD wkt_bk MULTIPOLYGON NOT NULL COMMENT \'(DC2Type:multipolygon)\', CHANGE wkt wkt VARCHAR(255) DEFAULT NULL');
    }
}
