<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221015125221 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(
            'CREATE TABLE domet_part (id INT AUTO_INCREMENT NOT NULL, domet_id INT NOT NULL, begin_date DATETIME NOT NULL, end_date DATETIME NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', modified_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', active TINYINT(1) NOT NULL, deleted TINYINT(1) NOT NULL, INDEX IDX_6887EC36D4988274 (domet_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB'
        );
        $this->addSql(
            'ALTER TABLE domet_part ADD CONSTRAINT FK_6887EC36D4988274 FOREIGN KEY (domet_id) REFERENCES domet (id)'
        );
        $this->addSql(
            'ALTER TABLE domet ADD duration INT NOT NULL, ADD duration_left INT NOT NULL, DROP begin_date, DROP end_date'
        );
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE domet_part DROP FOREIGN KEY FK_6887EC36D4988274');
        $this->addSql('DROP TABLE domet_part');
        $this->addSql(
            'ALTER TABLE domet ADD begin_date DATETIME NOT NULL, ADD end_date DATETIME NOT NULL, DROP duration, DROP duration_left'
        );
    }
}