<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190503055216 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE groups (groupID INT AUTO_INCREMENT NOT NULL, groupName VARCHAR(20) DEFAULT \'Personal\', description VARCHAR(80) DEFAULT \'This is your private data collection. You are its administrator\', creatorID INT DEFAULT NULL, publicView INT DEFAULT 0, approveToJoin INT DEFAULT 1, rssID VARCHAR(20) DEFAULT NULL, created_at DATETIME DEFAULT NULL, PRIMARY KEY(groupID)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE users CHANGE invitesRemaining invitesRemaining TINYINT DEFAULT 50 NOT NULL, CHANGE showGetStartedBox showGetStartedBox TINYINT DEFAULT true NOT NULL, CHANGE iChartInternalAccess iChartInternalAccess TINYINT DEFAULT 0 NOT NULL, CHANGE activated activated TINYINT DEFAULT 0 NOT NULL, CHANGE loginAttempts loginAttempts TINYINT DEFAULT 0 NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE groups');
        $this->addSql('ALTER TABLE users CHANGE invitesRemaining invitesRemaining TINYINT(1) DEFAULT \'50\' NOT NULL, CHANGE showGetStartedBox showGetStartedBox TINYINT(1) DEFAULT \'1\' NOT NULL, CHANGE iChartInternalAccess iChartInternalAccess TINYINT(1) DEFAULT \'0\' NOT NULL, CHANGE activated activated TINYINT(1) DEFAULT \'0\' NOT NULL, CHANGE loginAttempts loginAttempts TINYINT(1) DEFAULT \'0\' NOT NULL');
    }
}
