<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200319181700 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE diagnostic DROP FOREIGN KEY FK_FA7C8889A6EB9800');
        $this->addSql('CREATE TABLE critere (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, nb_m_carre VARCHAR(10) NOT NULL, lieu enum(\'bureau\', \'domicile\'), annee_constr INT NOT NULL, plan_lieu VARCHAR(255) DEFAULT NULL, photo_lieu VARCHAR(255) DEFAULT NULL, orientation TINYINT(1) NOT NULL, titre_diagnostic VARCHAR(20) NOT NULL, INDEX IDX_7F6A8053A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE critere ADD CONSTRAINT FK_7F6A8053A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('DROP TABLE criteres');
        $this->addSql('DROP INDEX UNIQ_FA7C8889A6EB9800 ON diagnostic');
        $this->addSql('ALTER TABLE diagnostic CHANGE prix prix INT DEFAULT NULL, CHANGE expertise expertise VARCHAR(255) DEFAULT NULL, CHANGE criteres_id critere_id INT NOT NULL');
        $this->addSql('ALTER TABLE diagnostic ADD CONSTRAINT FK_FA7C88899E5F45AB FOREIGN KEY (critere_id) REFERENCES critere (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_FA7C88899E5F45AB ON diagnostic (critere_id)');
        $this->addSql('ALTER TABLE user CHANGE roles roles JSON NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE diagnostic DROP FOREIGN KEY FK_FA7C88899E5F45AB');
        $this->addSql('CREATE TABLE criteres (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, nb_m_carre VARCHAR(10) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, lieu VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, annee_constr INT NOT NULL, plan_lieu VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, photo_lieu VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, orientation TINYINT(1) NOT NULL, titre_diagnostic VARCHAR(20) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, INDEX IDX_E913A5C5A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE criteres ADD CONSTRAINT FK_E913A5C5A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('DROP TABLE critere');
        $this->addSql('DROP INDEX UNIQ_FA7C88899E5F45AB ON diagnostic');
        $this->addSql('ALTER TABLE diagnostic CHANGE prix prix INT DEFAULT NULL, CHANGE expertise expertise VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE critere_id criteres_id INT NOT NULL');
        $this->addSql('ALTER TABLE diagnostic ADD CONSTRAINT FK_FA7C8889A6EB9800 FOREIGN KEY (criteres_id) REFERENCES criteres (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_FA7C8889A6EB9800 ON diagnostic (criteres_id)');
        $this->addSql('ALTER TABLE user CHANGE roles roles LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_bin`');
    }
}
