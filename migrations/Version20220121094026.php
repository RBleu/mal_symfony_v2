<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220121094026 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE ms_list_type (lt_id INT AUTO_INCREMENT NOT NULL, lt_name VARCHAR(255) NOT NULL, lt_list_key VARCHAR(255) NOT NULL, PRIMARY KEY(lt_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ms_priority (p_id INT AUTO_INCREMENT NOT NULL, p_name VARCHAR(255) NOT NULL, PRIMARY KEY(p_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ms_theme (th_id INT AUTO_INCREMENT NOT NULL, th_anime_id INT NOT NULL, th_name VARCHAR(255) NOT NULL, th_type VARCHAR(255) NOT NULL, INDEX IDX_9775E708794BBE89 (th_anime_id), PRIMARY KEY(th_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE ms_theme ADD CONSTRAINT FK_9775E708794BBE89 FOREIGN KEY (th_anime_id) REFERENCES ms_anime (a_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE anime_genre DROP FOREIGN KEY FK_EFF953C7794BBE89');
        $this->addSql('ALTER TABLE anime_anime DROP FOREIGN KEY FK_7FAD397DE980FB2E');
        $this->addSql('ALTER TABLE anime_anime DROP FOREIGN KEY FK_7FAD397DF065ABA1');
        $this->addSql('ALTER TABLE theme DROP FOREIGN KEY FK_9775E708794BBE89');
        $this->addSql('ALTER TABLE anime_genre DROP FOREIGN KEY FK_EFF953C74296D31F');
        $this->addSql('ALTER TABLE anime DROP FOREIGN KEY FK_13045942C54C8C93');
        $this->addSql('CREATE TABLE ms_anime (a_id INT AUTO_INCREMENT NOT NULL, a_type_id INT NOT NULL, a_title VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, a_episodes INT DEFAULT NULL, a_airing SMALLINT NOT NULL, a_status VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, a_aired_from DATE DEFAULT NULL, a_aired_to DATE DEFAULT NULL, a_aired VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, a_duration VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, a_score DOUBLE PRECISION DEFAULT NULL, a_scored_by INT DEFAULT NULL, a_rank INT DEFAULT NULL, a_synopsis LONGTEXT CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, a_premiered VARCHAR(11) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, a_cover VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, a_members INT DEFAULT NULL, INDEX IDX_13045942C54C8C93 (a_type_id), PRIMARY KEY(a_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE ms_anime_genre (ag_anime_id INT NOT NULL, ag_genre_id INT NOT NULL, INDEX IDX_EFF953C74296D31F (ag_genre_id), INDEX IDX_EFF953C7794BBE89 (ag_anime_id), PRIMARY KEY(ag_anime_id, ag_genre_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE ms_anime_relation (ar_prequel_id INT NOT NULL, ar_sequel_id INT NOT NULL, INDEX IDX_7FAD397DF065ABA1 (ar_sequel_id), INDEX IDX_7FAD397DE980FB2E (ar_prequel_id), PRIMARY KEY(ar_prequel_id, ar_sequel_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE ms_genre (g_id INT AUTO_INCREMENT NOT NULL, g_name VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, PRIMARY KEY(g_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE ms_type (ty_id INT AUTO_INCREMENT NOT NULL, ty_name VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, PRIMARY KEY(ty_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE ms_user (u_id INT AUTO_INCREMENT NOT NULL, u_username VARCHAR(180) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, u_roles LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci` COMMENT \'(DC2Type:json)\', u_password VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, u_email VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, u_image VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, u_signup_date DATE NOT NULL, UNIQUE INDEX UNIQ_8D93D649F85E0677 (u_username), PRIMARY KEY(u_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE ms_anime ADD CONSTRAINT FK_13045942C54C8C93 FOREIGN KEY (a_type_id) REFERENCES ms_type (ty_id)');
        $this->addSql('ALTER TABLE ms_anime_genre ADD CONSTRAINT FK_EFF953C7794BBE89 FOREIGN KEY (ag_anime_id) REFERENCES ms_anime (a_id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE ms_anime_genre ADD CONSTRAINT FK_EFF953C74296D31F FOREIGN KEY (ag_genre_id) REFERENCES ms_genre (g_id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE ms_anime_relation ADD CONSTRAINT FK_7FAD397DF065ABA1 FOREIGN KEY (ar_sequel_id) REFERENCES ms_anime (a_id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE ms_anime_relation ADD CONSTRAINT FK_7FAD397DE980FB2E FOREIGN KEY (ar_prequel_id) REFERENCES ms_anime (a_id) ON DELETE CASCADE');
        $this->addSql('DROP TABLE anime');
        $this->addSql('DROP TABLE anime_genre');
        $this->addSql('DROP TABLE anime_anime');
        $this->addSql('DROP TABLE genre');
        $this->addSql('DROP TABLE list_type');
        $this->addSql('DROP TABLE priority');
        $this->addSql('DROP TABLE theme');
        $this->addSql('DROP TABLE type');
        $this->addSql('DROP TABLE `user`');
    }
}
