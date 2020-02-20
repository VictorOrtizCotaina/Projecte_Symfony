<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200220152905 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE category CHANGE id_user id_user INT DEFAULT NULL, CHANGE image image VARCHAR(255) DEFAULT \'icon-folder.png\'');
        $this->addSql('ALTER TABLE forum CHANGE id_category id_category INT DEFAULT NULL, CHANGE id_user id_user INT DEFAULT NULL, CHANGE image image VARCHAR(255) DEFAULT \'icon-folder.png\'');
        $this->addSql('ALTER TABLE post CHANGE id_user id_user INT DEFAULT NULL, CHANGE id_topic id_topic INT DEFAULT NULL');
        $this->addSql('ALTER TABLE topic CHANGE id_user id_user INT DEFAULT NULL, CHANGE id_forum id_forum INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user CHANGE id_user_group id_user_group INT DEFAULT NULL, CHANGE lang lang VARCHAR(30) DEFAULT \'ES\' NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE category CHANGE id_user id_user INT DEFAULT NULL, CHANGE image image VARCHAR(255) CHARACTER SET utf8 DEFAULT \'\'\'icon-folder.png\'\'\' COLLATE `utf8_general_ci`');
        $this->addSql('ALTER TABLE forum CHANGE id_category id_category INT DEFAULT NULL, CHANGE id_user id_user INT DEFAULT NULL, CHANGE image image VARCHAR(255) CHARACTER SET utf8 DEFAULT \'\'\'icon-folder.png\'\'\' COLLATE `utf8_general_ci`');
        $this->addSql('ALTER TABLE post CHANGE id_topic id_topic INT DEFAULT NULL, CHANGE id_user id_user INT DEFAULT NULL');
        $this->addSql('ALTER TABLE topic CHANGE id_forum id_forum INT DEFAULT NULL, CHANGE id_user id_user INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user CHANGE id_user_group id_user_group INT DEFAULT NULL, CHANGE lang lang VARCHAR(30) CHARACTER SET utf8 DEFAULT \'\'\'ES\'\'\' NOT NULL COLLATE `utf8_general_ci`');
    }
}
