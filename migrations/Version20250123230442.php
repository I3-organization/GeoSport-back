<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250123230442 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE place_tag_label (place_id INT NOT NULL, tag_label_id INT NOT NULL, INDEX IDX_58D0C093DA6A219 (place_id), INDEX IDX_58D0C09358854E2 (tag_label_id), PRIMARY KEY(place_id, tag_label_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE review (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, place_id INT NOT NULL, title VARCHAR(255) NOT NULL, comment LONGTEXT NOT NULL, INDEX IDX_794381C6A76ED395 (user_id), INDEX IDX_794381C6DA6A219 (place_id), UNIQUE INDEX UNIQ_794381C6A76ED395DA6A219 (user_id, place_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE place_tag_label ADD CONSTRAINT FK_58D0C093DA6A219 FOREIGN KEY (place_id) REFERENCES place (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE place_tag_label ADD CONSTRAINT FK_58D0C09358854E2 FOREIGN KEY (tag_label_id) REFERENCES tag_label (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE review ADD CONSTRAINT FK_794381C6A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE review ADD CONSTRAINT FK_794381C6DA6A219 FOREIGN KEY (place_id) REFERENCES place (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_D8892622A76ED395DA6A219 ON rating (user_id, place_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE place_tag_label DROP FOREIGN KEY FK_58D0C093DA6A219');
        $this->addSql('ALTER TABLE place_tag_label DROP FOREIGN KEY FK_58D0C09358854E2');
        $this->addSql('ALTER TABLE review DROP FOREIGN KEY FK_794381C6A76ED395');
        $this->addSql('ALTER TABLE review DROP FOREIGN KEY FK_794381C6DA6A219');
        $this->addSql('DROP TABLE place_tag_label');
        $this->addSql('DROP TABLE review');
        $this->addSql('DROP INDEX UNIQ_D8892622A76ED395DA6A219 ON rating');
    }
}
