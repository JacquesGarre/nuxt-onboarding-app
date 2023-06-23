<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230312134714 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user ADD nb_private_articles_left INT NOT NULL, ADD nb_sponsored_articles_left INT NOT NULL, ADD nb_private_achievements_left INT NOT NULL, ADD nb_sponsored_achievements_left INT NOT NULL, ADD nb_private_stories_left INT NOT NULL, ADD nb_sponsored_stories_left INT NOT NULL, ADD nb_private_quests_left INT NOT NULL, ADD nb_sponsored_quests_left INT NOT NULL, ADD nb_private_epics_left INT NOT NULL, ADD nb_sponsored_epics_left INT NOT NULL, ADD nb_private_places_left INT NOT NULL, ADD nb_sponsored_places_left INT NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user DROP nb_private_articles_left, DROP nb_sponsored_articles_left, DROP nb_private_achievements_left, DROP nb_sponsored_achievements_left, DROP nb_private_stories_left, DROP nb_sponsored_stories_left, DROP nb_private_quests_left, DROP nb_sponsored_quests_left, DROP nb_private_epics_left, DROP nb_sponsored_epics_left, DROP nb_private_places_left, DROP nb_sponsored_places_left');
    }
}
