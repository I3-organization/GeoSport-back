<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250123134015 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE rating_user (rating_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_49EB45CCA32EFC6 (rating_id), INDEX IDX_49EB45CCA76ED395 (user_id), PRIMARY KEY(rating_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE rating_user ADD CONSTRAINT FK_49EB45CCA32EFC6 FOREIGN KEY (rating_id) REFERENCES rating (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE rating_user ADD CONSTRAINT FK_49EB45CCA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE rating DROP FOREIGN KEY FK_D88926229D86650F');
        $this->addSql('DROP INDEX IDX_D88926229D86650F ON rating');
        $this->addSql('ALTER TABLE rating DROP user_id_id');
        $this->addSql('ALTER TABLE user CHANGE roles roles JSON NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE rating_user DROP FOREIGN KEY FK_49EB45CCA32EFC6');
        $this->addSql('ALTER TABLE rating_user DROP FOREIGN KEY FK_49EB45CCA76ED395');
        $this->addSql('DROP TABLE rating_user');
        $this->addSql('ALTER TABLE rating ADD user_id_id INT NOT NULL');
        $this->addSql('ALTER TABLE rating ADD CONSTRAINT FK_D88926229D86650F FOREIGN KEY (user_id_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_D88926229D86650F ON rating (user_id_id)');
        $this->addSql('ALTER TABLE user CHANGE roles roles LONGTEXT NOT NULL COLLATE `utf8mb4_bin`');
    }
}
