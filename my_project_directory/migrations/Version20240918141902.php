<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240918141902 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE actors (id INT AUTO_INCREMENT NOT NULL, lastname VARCHAR(255) NOT NULL, firstname VARCHAR(255) DEFAULT NULL, dob DATE NOT NULL, nationality VARCHAR(255) NOT NULL, media VARCHAR(255) DEFAULT NULL, gender VARCHAR(255) NOT NULL, awards INT DEFAULT NULL, bio LONGTEXT DEFAULT NULL, death_date DATE DEFAULT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE actors_movie (actors_id INT NOT NULL, movie_id INT NOT NULL, INDEX IDX_683D9EAD7168CF59 (actors_id), INDEX IDX_683D9EAD8F93B6FC (movie_id), PRIMARY KEY(actors_id, movie_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE movie (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, release_date DATE DEFAULT NULL, director VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, media VARCHAR(255) DEFAULT NULL, entries INT DEFAULT NULL, rating DOUBLE PRECISION DEFAULT NULL, duration INT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', available_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', delivered_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE actors_movie ADD CONSTRAINT FK_683D9EAD7168CF59 FOREIGN KEY (actors_id) REFERENCES actors (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE actors_movie ADD CONSTRAINT FK_683D9EAD8F93B6FC FOREIGN KEY (movie_id) REFERENCES movie (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE actors_movie DROP FOREIGN KEY FK_683D9EAD7168CF59');
        $this->addSql('ALTER TABLE actors_movie DROP FOREIGN KEY FK_683D9EAD8F93B6FC');
        $this->addSql('DROP TABLE actors');
        $this->addSql('DROP TABLE actors_movie');
        $this->addSql('DROP TABLE movie');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
