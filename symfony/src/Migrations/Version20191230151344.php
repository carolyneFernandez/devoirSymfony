<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191230151344 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE projet (id INT AUTO_INCREMENT NOT NULL, matiere_id INT DEFAULT NULL, nom VARCHAR(255) NOT NULL, nombre_etudiant INT NOT NULL, note DOUBLE PRECISION DEFAULT NULL, INDEX IDX_50159CA9F46CD258 (matiere_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE projet_etudiant (projet_id INT NOT NULL, etudiant_id INT NOT NULL, INDEX IDX_56FE4914C18272 (projet_id), INDEX IDX_56FE4914DDEAB1A3 (etudiant_id), PRIMARY KEY(projet_id, etudiant_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE etudiant (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, prenom VARCHAR(255) NOT NULL, age INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE matiere (id INT AUTO_INCREMENT NOT NULL, intervenant_id INT DEFAULT NULL, nom VARCHAR(255) NOT NULL, INDEX IDX_9014574AAB9A1716 (intervenant_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE intervenant (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, prenom VARCHAR(255) NOT NULL, age INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE projet ADD CONSTRAINT FK_50159CA9F46CD258 FOREIGN KEY (matiere_id) REFERENCES matiere (id)');
        $this->addSql('ALTER TABLE projet_etudiant ADD CONSTRAINT FK_56FE4914C18272 FOREIGN KEY (projet_id) REFERENCES projet (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE projet_etudiant ADD CONSTRAINT FK_56FE4914DDEAB1A3 FOREIGN KEY (etudiant_id) REFERENCES etudiant (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE matiere ADD CONSTRAINT FK_9014574AAB9A1716 FOREIGN KEY (intervenant_id) REFERENCES intervenant (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE projet_etudiant DROP FOREIGN KEY FK_56FE4914C18272');
        $this->addSql('ALTER TABLE projet_etudiant DROP FOREIGN KEY FK_56FE4914DDEAB1A3');
        $this->addSql('ALTER TABLE projet DROP FOREIGN KEY FK_50159CA9F46CD258');
        $this->addSql('ALTER TABLE matiere DROP FOREIGN KEY FK_9014574AAB9A1716');
        $this->addSql('DROP TABLE projet');
        $this->addSql('DROP TABLE projet_etudiant');
        $this->addSql('DROP TABLE etudiant');
        $this->addSql('DROP TABLE matiere');
        $this->addSql('DROP TABLE intervenant');
    }
}
