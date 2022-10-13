<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221013092813 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE domet (id INT AUTO_INCREMENT NOT NULL, task_id INT DEFAULT NULL, user_id INT NOT NULL, domet_type VARCHAR(50) NOT NULL, begin_date DATETIME NOT NULL, end_date DATETIME NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', modified_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_C638B3718DB60186 (task_id), INDEX IDX_C638B371A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE domet ADD CONSTRAINT FK_C638B3718DB60186 FOREIGN KEY (task_id) REFERENCES task (id)');
        $this->addSql('ALTER TABLE domet ADD CONSTRAINT FK_C638B371A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE domet DROP FOREIGN KEY FK_C638B3718DB60186');
        $this->addSql('ALTER TABLE domet DROP FOREIGN KEY FK_C638B371A76ED395');
        $this->addSql('DROP TABLE domet');
    }
}
