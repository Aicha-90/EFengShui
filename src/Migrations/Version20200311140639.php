<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200312140639 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE criteres (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, nb_m_carre VARCHAR(10) NOT NULL, lieu TINYINT(1) NOT NULL, annee_constr INT NOT NULL, plan_lieu VARCHAR(255) DEFAULT NULL, photo_lieu VARCHAR(255) DEFAULT NULL, orientation TINYINT(1) NOT NULL, titre_diagnostic VARCHAR(20) NOT NULL, INDEX IDX_E913A5C5A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE diagnostic (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, criteres_id INT NOT NULL, date DATE NOT NULL, statut_paiement TINYINT(1) NOT NULL, prix INT DEFAULT NULL, expertise VARCHAR(255) DEFAULT NULL, statut_expertise TINYINT(1) NOT NULL, INDEX IDX_FA7C8889A76ED395 (user_id), UNIQUE INDEX UNIQ_FA7C8889A6EB9800 (criteres_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE estimation (id INT AUTO_INCREMENT NOT NULL, nb_m_carre VARCHAR(10) NOT NULL, prix VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(20) NOT NULL, prenom VARCHAR(20) NOT NULL, email VARCHAR(50) NOT NULL, mdp VARCHAR(20) NOT NULL, statut_user TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE criteres ADD CONSTRAINT FK_E913A5C5A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE diagnostic ADD CONSTRAINT FK_FA7C8889A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE diagnostic ADD CONSTRAINT FK_FA7C8889A6EB9800 FOREIGN KEY (criteres_id) REFERENCES criteres (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE diagnostic DROP FOREIGN KEY FK_FA7C8889A6EB9800');
        $this->addSql('ALTER TABLE criteres DROP FOREIGN KEY FK_E913A5C5A76ED395');
        $this->addSql('ALTER TABLE diagnostic DROP FOREIGN KEY FK_FA7C8889A76ED395');
        $this->addSql('DROP TABLE criteres');
        $this->addSql('DROP TABLE diagnostic');
        $this->addSql('DROP TABLE estimation');
        $this->addSql('DROP TABLE user');
    }
}
