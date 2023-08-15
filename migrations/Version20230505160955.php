<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230505160955 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE Users (id INT AUTO_INCREMENT NOT NULL, site_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, last_name VARCHAR(255) NOT NULL, pseudo VARCHAR(50) NOT NULL, telephone VARCHAR(20) DEFAULT NULL, administrateur TINYINT(1) NOT NULL, actif TINYINT(1) NOT NULL, INDEX IDX_D5428AEDF6BD1646 (site_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_sortie (user_id INT NOT NULL, sortie_id INT NOT NULL, INDEX IDX_596DC8CFA76ED395 (user_id), INDEX IDX_596DC8CFCC72D953 (sortie_id), PRIMARY KEY(user_id, sortie_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE Users ADD CONSTRAINT FK_D5428AEDF6BD1646 FOREIGN KEY (site_id) REFERENCES Sites (id)');
        $this->addSql('ALTER TABLE user_sortie ADD CONSTRAINT FK_596DC8CFA76ED395 FOREIGN KEY (user_id) REFERENCES Users (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_sortie ADD CONSTRAINT FK_596DC8CFCC72D953 FOREIGN KEY (sortie_id) REFERENCES Sorties (id) ON DELETE CASCADE');
        $this->addSql('DROP TABLE user');
        $this->addSql('ALTER TABLE sites CHANGE nom nom VARCHAR(50) NOT NULL');
        $this->addSql('ALTER TABLE sorties DROP FOREIGN KEY FK_873C5A749D1C3019');
        $this->addSql('DROP INDEX IDX_873C5A749D1C3019 ON sorties');
        $this->addSql('ALTER TABLE sorties ADD user_id INT NOT NULL, DROP participant_id, CHANGE infosSortie infosSortie VARCHAR(500) DEFAULT NULL, CHANGE photoSortie photoSortie VARCHAR(250) DEFAULT NULL');
        $this->addSql('ALTER TABLE sorties ADD CONSTRAINT FK_873C5A74A76ED395 FOREIGN KEY (user_id) REFERENCES Users (id)');
        $this->addSql('CREATE INDEX IDX_873C5A74A76ED395 ON sorties (user_id)');
        $this->addSql('RENAME TABLE user TO users;');

    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE Sorties DROP FOREIGN KEY FK_873C5A74A76ED395');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, email VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, password VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, last_name VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE Users DROP FOREIGN KEY FK_D5428AEDF6BD1646');
        $this->addSql('ALTER TABLE user_sortie DROP FOREIGN KEY FK_596DC8CFA76ED395');
        $this->addSql('ALTER TABLE user_sortie DROP FOREIGN KEY FK_596DC8CFCC72D953');
        $this->addSql('DROP TABLE Users');
        $this->addSql('DROP TABLE user_sortie');
        $this->addSql('DROP INDEX IDX_873C5A74A76ED395 ON Sorties');
        $this->addSql('ALTER TABLE Sorties ADD participant_id INT UNSIGNED NOT NULL, DROP user_id, CHANGE infosSortie infosSortie VARCHAR(500) NOT NULL, CHANGE photoSortie photoSortie VARCHAR(250) NOT NULL');
        $this->addSql('ALTER TABLE Sorties ADD CONSTRAINT FK_873C5A749D1C3019 FOREIGN KEY (participant_id) REFERENCES participants (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_873C5A749D1C3019 ON Sorties (participant_id)');
        $this->addSql('ALTER TABLE Sites CHANGE nom nom VARCHAR(255) NOT NULL');
    }
}
