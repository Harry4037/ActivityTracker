<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190506115029 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE grouprequests (id INT AUTO_INCREMENT NOT NULL, created_at DATETIME DEFAULT NULL, groupID INT DEFAULT NULL, userID INT DEFAULT NULL, INDEX IDX_89CD6320D6EFA878 (groupID), INDEX IDX_89CD63205FD86D04 (userID), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE applicationadmins (applicationAdminID INT AUTO_INCREMENT NOT NULL, created_at DATETIME DEFAULT NULL, applicationID INT DEFAULT NULL, userID INT DEFAULT NULL, INDEX IDX_CB4C1EE93B508F07 (applicationID), INDEX IDX_CB4C1EE95FD86D04 (userID), PRIMARY KEY(applicationAdminID)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE applicationrequests (applicationRequestID INT AUTO_INCREMENT NOT NULL, created_at DATETIME DEFAULT NULL, applicationID INT DEFAULT NULL, groupID INT DEFAULT NULL, INDEX IDX_E473321F3B508F07 (applicationID), INDEX IDX_E473321FD6EFA878 (groupID), PRIMARY KEY(applicationRequestID)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE application (applicationID INT AUTO_INCREMENT NOT NULL, application_name VARCHAR(75) DEFAULT NULL, organization VARCHAR(150) DEFAULT NULL, description VARCHAR(200) DEFAULT NULL, callback_url VARCHAR(75) NOT NULL, secret_key CHAR(40) NOT NULL, nonce CHAR(32) NOT NULL, approve_to_join TINYINT(1) NULL, frequency CHAR(1) NOT NULL, start_data_period VARCHAR(10) NOT NULL, end_data_period VARCHAR(10) NOT NULL, start_simulation_period VARCHAR(10) NOT NULL, end_simulation_period VARCHAR(10) NOT NULL, start_display_period VARCHAR(10) NOT NULL, end_display_period VARCHAR(10) NOT NULL, start_user_solve_period VARCHAR(10) NOT NULL, end_user_solve_period VARCHAR(10) NOT NULL, in_development TINYINT(1) DEFAULT 1 NOT NULL, approved TINYINT(1) DEFAULT 0 NOT NULL, created_at DATETIME DEFAULT NULL, PRIMARY KEY(applicationID)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE grouprequests ADD CONSTRAINT FK_89CD6320D6EFA878 FOREIGN KEY (groupID) REFERENCES groups (groupID)');
        $this->addSql('ALTER TABLE grouprequests ADD CONSTRAINT FK_89CD63205FD86D04 FOREIGN KEY (userID) REFERENCES users (userID)');
        $this->addSql('ALTER TABLE applicationadmins ADD CONSTRAINT FK_CB4C1EE93B508F07 FOREIGN KEY (applicationID) REFERENCES application (applicationID)');
        $this->addSql('ALTER TABLE applicationadmins ADD CONSTRAINT FK_CB4C1EE95FD86D04 FOREIGN KEY (userID) REFERENCES users (userID)');
        $this->addSql('ALTER TABLE applicationrequests ADD CONSTRAINT FK_E473321F3B508F07 FOREIGN KEY (applicationID) REFERENCES application (applicationID)');
        $this->addSql('ALTER TABLE applicationrequests ADD CONSTRAINT FK_E473321FD6EFA878 FOREIGN KEY (groupID) REFERENCES groups (groupID)');
        $this->addSql('ALTER TABLE groups ADD CONSTRAINT FK_F06D39701FDCEC3E FOREIGN KEY (creatorID) REFERENCES users (userID)');
        $this->addSql('CREATE INDEX IDX_F06D39701FDCEC3E ON groups (creatorID)');
        $this->addSql('ALTER TABLE users CHANGE invitesRemaining invitesRemaining TINYINT DEFAULT 50 NOT NULL, CHANGE showGetStartedBox showGetStartedBox TINYINT DEFAULT true NOT NULL, CHANGE iChartInternalAccess iChartInternalAccess TINYINT DEFAULT 0 NOT NULL, CHANGE activated activated TINYINT DEFAULT 0 NOT NULL, CHANGE loginAttempts loginAttempts TINYINT DEFAULT 0 NOT NULL');
        $this->addSql('ALTER TABLE groupmembers CHANGE groupID groupID INT DEFAULT NULL, CHANGE userID userID INT DEFAULT NULL, CHANGE permissionLevel permissionLevel TINYINT DEFAULT 23');
        $this->addSql('ALTER TABLE groupmembers ADD CONSTRAINT FK_6F15EDACD6EFA878 FOREIGN KEY (groupID) REFERENCES groups (groupID)');
        $this->addSql('ALTER TABLE groupmembers ADD CONSTRAINT FK_6F15EDAC5FD86D04 FOREIGN KEY (userID) REFERENCES users (userID)');
        $this->addSql('CREATE INDEX IDX_6F15EDACD6EFA878 ON groupmembers (groupID)');
        $this->addSql('CREATE INDEX IDX_6F15EDAC5FD86D04 ON groupmembers (userID)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE applicationadmins DROP FOREIGN KEY FK_CB4C1EE93B508F07');
        $this->addSql('ALTER TABLE applicationrequests DROP FOREIGN KEY FK_E473321F3B508F07');
        $this->addSql('DROP TABLE grouprequests');
        $this->addSql('DROP TABLE applicationadmins');
        $this->addSql('DROP TABLE applicationrequests');
        $this->addSql('DROP TABLE application');
        $this->addSql('ALTER TABLE groupmembers DROP FOREIGN KEY FK_6F15EDACD6EFA878');
        $this->addSql('ALTER TABLE groupmembers DROP FOREIGN KEY FK_6F15EDAC5FD86D04');
        $this->addSql('DROP INDEX IDX_6F15EDACD6EFA878 ON groupmembers');
        $this->addSql('DROP INDEX IDX_6F15EDAC5FD86D04 ON groupmembers');
        $this->addSql('ALTER TABLE groupmembers CHANGE permissionLevel permissionLevel TINYINT(1) DEFAULT \'23\', CHANGE groupID groupID VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, CHANGE userID userID VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci');
        $this->addSql('ALTER TABLE groups DROP FOREIGN KEY FK_F06D39701FDCEC3E');
        $this->addSql('DROP INDEX IDX_F06D39701FDCEC3E ON groups');
        $this->addSql('ALTER TABLE users CHANGE invitesRemaining invitesRemaining TINYINT(1) DEFAULT \'50\' NOT NULL, CHANGE showGetStartedBox showGetStartedBox TINYINT(1) DEFAULT \'1\' NOT NULL, CHANGE iChartInternalAccess iChartInternalAccess TINYINT(1) DEFAULT \'0\' NOT NULL, CHANGE activated activated TINYINT(1) DEFAULT \'0\' NOT NULL, CHANGE loginAttempts loginAttempts TINYINT(1) DEFAULT \'0\' NOT NULL');
    }
}
