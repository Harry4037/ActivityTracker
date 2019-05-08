<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190508155704 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE groupmembershipnotifications (groupmembershipnotificationID INT AUTO_INCREMENT NOT NULL, approved TINYINT(1) DEFAULT 1 NOT NULL, created_at DATETIME DEFAULT NULL, userID INT DEFAULT NULL, groupID INT DEFAULT NULL, INDEX IDX_C07B917A5FD86D04 (userID), INDEX IDX_C07B917AD6EFA878 (groupID), PRIMARY KEY(groupmembershipnotificationID)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE groupmembershipnotifications ADD CONSTRAINT FK_C07B917A5FD86D04 FOREIGN KEY (userID) REFERENCES users (userID)');
        $this->addSql('ALTER TABLE groupmembershipnotifications ADD CONSTRAINT FK_C07B917AD6EFA878 FOREIGN KEY (groupID) REFERENCES groups (groupID)');
        $this->addSql('ALTER TABLE users CHANGE invitesRemaining invitesRemaining TINYINT DEFAULT 50 NOT NULL, CHANGE showGetStartedBox showGetStartedBox TINYINT DEFAULT true NOT NULL, CHANGE iChartInternalAccess iChartInternalAccess TINYINT DEFAULT 0 NOT NULL, CHANGE activated activated TINYINT DEFAULT 0 NOT NULL, CHANGE loginAttempts loginAttempts TINYINT DEFAULT 0 NOT NULL');
        $this->addSql('ALTER TABLE groupmembers CHANGE permissionLevel permissionLevel TINYINT DEFAULT 23');
        $this->addSql('ALTER TABLE application CHANGE secret_key secret_key CHAR(40) NOT NULL, CHANGE nonce nonce CHAR(32) NOT NULL, CHANGE approve_to_join approve_to_join TINYINT(1) NULL, CHANGE frequency frequency CHAR(1) NOT NULL, CHANGE in_development in_development TINYINT(1) DEFAULT 1 NOT NULL, CHANGE approved approved TINYINT(1) DEFAULT 0 NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE groupmembershipnotifications');
        $this->addSql('ALTER TABLE application CHANGE secret_key secret_key CHAR(40) NOT NULL COLLATE utf8mb4_unicode_ci, CHANGE nonce nonce CHAR(32) NOT NULL COLLATE utf8mb4_unicode_ci, CHANGE approve_to_join approve_to_join TINYINT(1) DEFAULT NULL, CHANGE frequency frequency CHAR(1) NOT NULL COLLATE utf8mb4_unicode_ci, CHANGE in_development in_development TINYINT(1) DEFAULT \'1\' NOT NULL, CHANGE approved approved TINYINT(1) DEFAULT \'0\' NOT NULL');
        $this->addSql('ALTER TABLE groupmembers CHANGE permissionLevel permissionLevel TINYINT(1) DEFAULT \'23\'');
        $this->addSql('ALTER TABLE users CHANGE invitesRemaining invitesRemaining TINYINT(1) DEFAULT \'50\' NOT NULL, CHANGE showGetStartedBox showGetStartedBox TINYINT(1) DEFAULT \'1\' NOT NULL, CHANGE iChartInternalAccess iChartInternalAccess TINYINT(1) DEFAULT \'0\' NOT NULL, CHANGE activated activated TINYINT(1) DEFAULT \'0\' NOT NULL, CHANGE loginAttempts loginAttempts TINYINT(1) DEFAULT \'0\' NOT NULL');
    }
}
