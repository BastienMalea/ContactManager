<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231230173652 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE contact_custom_field (contact_id INT NOT NULL, custom_field_id INT NOT NULL, INDEX IDX_B44F8F05E7A1254A (contact_id), INDEX IDX_B44F8F05A1E5E0D4 (custom_field_id), PRIMARY KEY(contact_id, custom_field_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE contact_custom_field ADD CONSTRAINT FK_B44F8F05E7A1254A FOREIGN KEY (contact_id) REFERENCES contact (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE contact_custom_field ADD CONSTRAINT FK_B44F8F05A1E5E0D4 FOREIGN KEY (custom_field_id) REFERENCES custom_field (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE contact CHANGE name name VARCHAR(50) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE contact_custom_field DROP FOREIGN KEY FK_B44F8F05E7A1254A');
        $this->addSql('ALTER TABLE contact_custom_field DROP FOREIGN KEY FK_B44F8F05A1E5E0D4');
        $this->addSql('DROP TABLE contact_custom_field');
        $this->addSql('ALTER TABLE contact CHANGE name name VARCHAR(255) NOT NULL');
    }
}
