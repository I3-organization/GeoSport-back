<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250123173920 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE rating_place DROP FOREIGN KEY FK_E90B5CF1A32EFC6');
        $this->addSql('ALTER TABLE rating_place DROP FOREIGN KEY FK_E90B5CF1DA6A219');
        $this->addSql('ALTER TABLE rating_user DROP FOREIGN KEY FK_49EB45CCA76ED395');
        $this->addSql('ALTER TABLE rating_user DROP FOREIGN KEY FK_49EB45CCA32EFC6');
        $this->addSql('DROP TABLE rating_place');
        $this->addSql('DROP TABLE rating_user');
        $this->addSql('ALTER TABLE rating ADD user_id INT NOT NULL, ADD place_id INT NOT NULL');
        $this->addSql('ALTER TABLE rating ADD CONSTRAINT FK_D8892622A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE rating ADD CONSTRAINT FK_D8892622DA6A219 FOREIGN KEY (place_id) REFERENCES place (id)');
        $this->addSql('CREATE INDEX IDX_D8892622A76ED395 ON rating (user_id)');
        $this->addSql('CREATE INDEX IDX_D8892622DA6A219 ON rating (place_id)');
        $this->addSql('ALTER TABLE user CHANGE roles roles JSON NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE rating_place (rating_id INT NOT NULL, place_id INT NOT NULL, INDEX IDX_E90B5CF1DA6A219 (place_id), INDEX IDX_E90B5CF1A32EFC6 (rating_id), PRIMARY KEY(rating_id, place_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE rating_user (rating_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_49EB45CCA32EFC6 (rating_id), INDEX IDX_49EB45CCA76ED395 (user_id), PRIMARY KEY(rating_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE rating_place ADD CONSTRAINT FK_E90B5CF1A32EFC6 FOREIGN KEY (rating_id) REFERENCES rating (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE rating_place ADD CONSTRAINT FK_E90B5CF1DA6A219 FOREIGN KEY (place_id) REFERENCES place (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE rating_user ADD CONSTRAINT FK_49EB45CCA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE rating_user ADD CONSTRAINT FK_49EB45CCA32EFC6 FOREIGN KEY (rating_id) REFERENCES rating (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE rating DROP FOREIGN KEY FK_D8892622A76ED395');
        $this->addSql('ALTER TABLE rating DROP FOREIGN KEY FK_D8892622DA6A219');
        $this->addSql('DROP INDEX IDX_D8892622A76ED395 ON rating');
        $this->addSql('DROP INDEX IDX_D8892622DA6A219 ON rating');
        $this->addSql('ALTER TABLE rating DROP user_id, DROP place_id');
        $this->addSql('ALTER TABLE user CHANGE roles roles LONGTEXT NOT NULL COLLATE `utf8mb4_bin`');
    }
}
