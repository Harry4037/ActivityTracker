<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190516144829 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE entity (entityID INT AUTO_INCREMENT NOT NULL, entity_code VARCHAR(3) DEFAULT NULL, entity_name VARCHAR(45) DEFAULT NULL, PRIMARY KEY(entityID)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE grouprequests (id INT AUTO_INCREMENT NOT NULL, created_at DATETIME DEFAULT NULL, groupID INT DEFAULT NULL, userID INT DEFAULT NULL, INDEX IDX_89CD6320D6EFA878 (groupID), INDEX IDX_89CD63205FD86D04 (userID), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE applicationadmins (applicationAdminID INT AUTO_INCREMENT NOT NULL, created_at DATETIME DEFAULT NULL, applicationID INT DEFAULT NULL, userID INT DEFAULT NULL, INDEX IDX_CB4C1EE93B508F07 (applicationID), INDEX IDX_CB4C1EE95FD86D04 (userID), PRIMARY KEY(applicationAdminID)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE groups (groupID INT AUTO_INCREMENT NOT NULL, groupName VARCHAR(20) DEFAULT \'Personal\', description VARCHAR(80) DEFAULT \'This is your private data collection. You are its administrator\', publicView INT DEFAULT 0, approveToJoin INT DEFAULT 1, rssID VARCHAR(20) DEFAULT NULL, created_at DATETIME DEFAULT NULL, creatorID INT DEFAULT NULL, INDEX IDX_F06D39701FDCEC3E (creatorID), PRIMARY KEY(groupID)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE variablemode (modeID INT AUTO_INCREMENT NOT NULL, description VARCHAR(30) NOT NULL, abbreviation VARCHAR(1) NOT NULL, PRIMARY KEY(modeID)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE archives (archiveID INT AUTO_INCREMENT NOT NULL, error INT DEFAULT NULL, comment VARCHAR(300) DEFAULT NULL, created_at DATETIME DEFAULT NULL, relatedTransactionID INT DEFAULT NULL, groupID INT DEFAULT NULL, applicationID INT DEFAULT NULL, userID INT DEFAULT NULL, entityID INT DEFAULT NULL, commandID INT DEFAULT NULL, INDEX IDX_E262EC39FAA194C7 (relatedTransactionID), INDEX IDX_E262EC39D6EFA878 (groupID), INDEX IDX_E262EC393B508F07 (applicationID), INDEX IDX_E262EC395FD86D04 (userID), INDEX IDX_E262EC39CD460934 (entityID), INDEX IDX_E262EC39B458E68D (commandID), PRIMARY KEY(archiveID)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE recentsimulations (recentsimulationID INT AUTO_INCREMENT NOT NULL, created_at DATETIME DEFAULT NULL, userID INT DEFAULT NULL, groupID INT DEFAULT NULL, applicationID INT DEFAULT NULL, entityID INT DEFAULT NULL, INDEX IDX_58E51365FD86D04 (userID), INDEX IDX_58E5136D6EFA878 (groupID), INDEX IDX_58E51363B508F07 (applicationID), INDEX IDX_58E5136CD460934 (entityID), PRIMARY KEY(recentsimulationID)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE transactiondata (transactiondataID INT AUTO_INCREMENT NOT NULL, userHistEnd VARCHAR(10) DEFAULT NULL, actualHistEnd VARCHAR(10) DEFAULT NULL, inputValues LONGTEXT DEFAULT NULL, outputValues LONGTEXT DEFAULT NULL, transactionID INT DEFAULT NULL, variableID INT DEFAULT NULL, modeID INT DEFAULT NULL, displayTypeID INT DEFAULT NULL, INDEX IDX_1BDC5F50F99A11DC (transactionID), INDEX IDX_1BDC5F50EC225FF2 (variableID), INDEX IDX_1BDC5F50B49845E1 (modeID), INDEX IDX_1BDC5F502C5E4619 (displayTypeID), PRIMARY KEY(transactiondataID)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE transactionqueue (transactionqueueID INT AUTO_INCREMENT NOT NULL, created_at DATETIME DEFAULT NULL, transactionID INT DEFAULT NULL, INDEX IDX_C09B31D9F99A11DC (transactionID), PRIMARY KEY(transactionqueueID)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE users (userID INT AUTO_INCREMENT NOT NULL, username VARCHAR(255) NOT NULL, firstName VARCHAR(255) NOT NULL, lastName VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, affiliation VARCHAR(255) NOT NULL, timezone VARCHAR(255) DEFAULT \'America/New_York\' NOT NULL, displayName VARCHAR(255) DEFAULT \'real\' NOT NULL, invitesRemaining TINYINT DEFAULT 50 NOT NULL, showGetStartedBox TINYINT DEFAULT true NOT NULL, iChartInternalAccess TINYINT DEFAULT 0 NOT NULL, activated TINYINT DEFAULT 0 NOT NULL, activationCode VARCHAR(255) NOT NULL, newPasswordCode VARCHAR(255) NOT NULL, loginAttempts TINYINT DEFAULT 0 NOT NULL, new_password_time DATETIME NOT NULL, created_at DATETIME NOT NULL, last_login_attempt DATETIME NOT NULL, password VARCHAR(255) NOT NULL, roles JSON NOT NULL, reset_password VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_1483A5E9F85E0677 (username), UNIQUE INDEX UNIQ_1483A5E9E7927C74 (email), PRIMARY KEY(userID)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE variable (variableID INT AUTO_INCREMENT NOT NULL, description VARCHAR(200) NOT NULL, PRIMARY KEY(variableID)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE groupmembers (groupMemberID INT AUTO_INCREMENT NOT NULL, admin INT DEFAULT 0, permissionLevel TINYINT DEFAULT 23, created_at DATETIME DEFAULT NULL, groupID INT DEFAULT NULL, userID INT DEFAULT NULL, INDEX IDX_6F15EDACD6EFA878 (groupID), INDEX IDX_6F15EDAC5FD86D04 (userID), PRIMARY KEY(groupMemberID)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE groupapplicationnotifications (groupapplicationnotificationID INT AUTO_INCREMENT NOT NULL, isDeleted TINYINT(1) NULL, approved TINYINT(1) DEFAULT 1 NOT NULL, created_at DATETIME DEFAULT NULL, userID INT DEFAULT NULL, groupID INT DEFAULT NULL, applicationID INT DEFAULT NULL, INDEX IDX_51C5D1555FD86D04 (userID), INDEX IDX_51C5D155D6EFA878 (groupID), INDEX IDX_51C5D1553B508F07 (applicationID), PRIMARY KEY(groupapplicationnotificationID)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE applicationmnemonics (applicationmnemonicID INT AUTO_INCREMENT NOT NULL, mnemonic VARCHAR(255) NOT NULL, applicationID INT DEFAULT NULL, variableID INT DEFAULT NULL, INDEX IDX_721AAD803B508F07 (applicationID), INDEX IDX_721AAD80EC225FF2 (variableID), PRIMARY KEY(applicationmnemonicID)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE applicationrequests (applicationRequestID INT AUTO_INCREMENT NOT NULL, created_at DATETIME DEFAULT NULL, applicationID INT DEFAULT NULL, groupID INT DEFAULT NULL, INDEX IDX_E473321F3B508F07 (applicationID), INDEX IDX_E473321FD6EFA878 (groupID), PRIMARY KEY(applicationRequestID)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE usertransactions (transactionID INT AUTO_INCREMENT NOT NULL, error INT DEFAULT NULL, comment VARCHAR(300) DEFAULT NULL, created_at DATETIME DEFAULT NULL, groupID INT DEFAULT NULL, applicationID INT DEFAULT NULL, userID INT DEFAULT NULL, entityID INT DEFAULT NULL, commandID INT DEFAULT NULL, INDEX IDX_1D0E6127D6EFA878 (groupID), INDEX IDX_1D0E61273B508F07 (applicationID), INDEX IDX_1D0E61275FD86D04 (userID), INDEX IDX_1D0E6127CD460934 (entityID), INDEX IDX_1D0E6127B458E68D (commandID), PRIMARY KEY(transactionID)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE groupmembershipnotifications (groupmembershipnotificationID INT AUTO_INCREMENT NOT NULL, approved TINYINT(1) DEFAULT 1 NOT NULL, created_at DATETIME DEFAULT NULL, userID INT DEFAULT NULL, groupID INT DEFAULT NULL, INDEX IDX_C07B917A5FD86D04 (userID), INDEX IDX_C07B917AD6EFA878 (groupID), PRIMARY KEY(groupmembershipnotificationID)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE groupapplications (groupapplicationID INT AUTO_INCREMENT NOT NULL, created_at DATETIME DEFAULT NULL, groupID INT DEFAULT NULL, applicationID INT DEFAULT NULL, INDEX IDX_8CCCCBCFD6EFA878 (groupID), INDEX IDX_8CCCCBCF3B508F07 (applicationID), PRIMARY KEY(groupapplicationID)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE variabledisplaytype (displayTypeID INT AUTO_INCREMENT NOT NULL, description VARCHAR(30) NOT NULL, abbreviation VARCHAR(5) NOT NULL, PRIMARY KEY(displayTypeID)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE applicationequations (applicationequationID INT AUTO_INCREMENT NOT NULL, equation LONGTEXT NOT NULL, applicationID INT DEFAULT NULL, entityID INT DEFAULT NULL, variableID INT DEFAULT NULL, INDEX IDX_4781EC7F3B508F07 (applicationID), INDEX IDX_4781EC7FCD460934 (entityID), INDEX IDX_4781EC7FEC225FF2 (variableID), PRIMARY KEY(applicationequationID)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE command (commandID INT AUTO_INCREMENT NOT NULL, description VARCHAR(15) NOT NULL, PRIMARY KEY(commandID)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE archivedata (archivedataID INT AUTO_INCREMENT NOT NULL, input_values LONGTEXT DEFAULT NULL, output_values LONGTEXT DEFAULT NULL, archiveID INT DEFAULT NULL, variableID INT DEFAULT NULL, modeID INT DEFAULT NULL, displayTypeID INT DEFAULT NULL, INDEX IDX_70B19876D9FD5E99 (archiveID), INDEX IDX_70B19876EC225FF2 (variableID), INDEX IDX_70B19876B49845E1 (modeID), INDEX IDX_70B198762C5E4619 (displayTypeID), PRIMARY KEY(archivedataID)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE entitiesinapplications (entityapplicationID INT AUTO_INCREMENT NOT NULL, applicationID INT DEFAULT NULL, entityID INT DEFAULT NULL, INDEX IDX_FB5F52FC3B508F07 (applicationID), INDEX IDX_FB5F52FCCD460934 (entityID), PRIMARY KEY(entityapplicationID)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE application (applicationID INT AUTO_INCREMENT NOT NULL, application_name VARCHAR(75) DEFAULT NULL, organization VARCHAR(150) DEFAULT NULL, description VARCHAR(200) DEFAULT NULL, callback_url VARCHAR(75) NOT NULL, secret_key CHAR(40) NOT NULL, nonce CHAR(32) NOT NULL, approve_to_join TINYINT(1) NULL, frequency CHAR(1) NOT NULL, start_data_period VARCHAR(10) NOT NULL, end_data_period VARCHAR(10) NOT NULL, start_simulation_period VARCHAR(10) NOT NULL, end_simulation_period VARCHAR(10) NOT NULL, start_display_period VARCHAR(10) NOT NULL, end_display_period VARCHAR(10) NOT NULL, start_user_solve_period VARCHAR(10) NOT NULL, end_user_solve_period VARCHAR(10) NOT NULL, in_development TINYINT(1) DEFAULT 1 NOT NULL, approved TINYINT(1) DEFAULT 0 NOT NULL, created_at DATETIME DEFAULT NULL, PRIMARY KEY(applicationID)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE isimulateupdates (updateID INT AUTO_INCREMENT NOT NULL, text VARCHAR(255) NOT NULL, created_at DATETIME DEFAULT NULL, PRIMARY KEY(updateID)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE grouprequests ADD CONSTRAINT FK_89CD6320D6EFA878 FOREIGN KEY (groupID) REFERENCES groups (groupID)');
        $this->addSql('ALTER TABLE grouprequests ADD CONSTRAINT FK_89CD63205FD86D04 FOREIGN KEY (userID) REFERENCES users (userID)');
        $this->addSql('ALTER TABLE applicationadmins ADD CONSTRAINT FK_CB4C1EE93B508F07 FOREIGN KEY (applicationID) REFERENCES application (applicationID)');
        $this->addSql('ALTER TABLE applicationadmins ADD CONSTRAINT FK_CB4C1EE95FD86D04 FOREIGN KEY (userID) REFERENCES users (userID)');
        $this->addSql('ALTER TABLE groups ADD CONSTRAINT FK_F06D39701FDCEC3E FOREIGN KEY (creatorID) REFERENCES users (userID)');
        $this->addSql('ALTER TABLE archives ADD CONSTRAINT FK_E262EC39FAA194C7 FOREIGN KEY (relatedTransactionID) REFERENCES usertransactions (transactionID)');
        $this->addSql('ALTER TABLE archives ADD CONSTRAINT FK_E262EC39D6EFA878 FOREIGN KEY (groupID) REFERENCES groups (groupID)');
        $this->addSql('ALTER TABLE archives ADD CONSTRAINT FK_E262EC393B508F07 FOREIGN KEY (applicationID) REFERENCES application (applicationID)');
        $this->addSql('ALTER TABLE archives ADD CONSTRAINT FK_E262EC395FD86D04 FOREIGN KEY (userID) REFERENCES users (userID)');
        $this->addSql('ALTER TABLE archives ADD CONSTRAINT FK_E262EC39CD460934 FOREIGN KEY (entityID) REFERENCES entity (entityID)');
        $this->addSql('ALTER TABLE archives ADD CONSTRAINT FK_E262EC39B458E68D FOREIGN KEY (commandID) REFERENCES command (commandID)');
        $this->addSql('ALTER TABLE recentsimulations ADD CONSTRAINT FK_58E51365FD86D04 FOREIGN KEY (userID) REFERENCES users (userID)');
        $this->addSql('ALTER TABLE recentsimulations ADD CONSTRAINT FK_58E5136D6EFA878 FOREIGN KEY (groupID) REFERENCES groups (groupID)');
        $this->addSql('ALTER TABLE recentsimulations ADD CONSTRAINT FK_58E51363B508F07 FOREIGN KEY (applicationID) REFERENCES application (applicationID)');
        $this->addSql('ALTER TABLE recentsimulations ADD CONSTRAINT FK_58E5136CD460934 FOREIGN KEY (entityID) REFERENCES entity (entityID)');
        $this->addSql('ALTER TABLE transactiondata ADD CONSTRAINT FK_1BDC5F50F99A11DC FOREIGN KEY (transactionID) REFERENCES usertransactions (transactionID)');
        $this->addSql('ALTER TABLE transactiondata ADD CONSTRAINT FK_1BDC5F50EC225FF2 FOREIGN KEY (variableID) REFERENCES variable (variableID)');
        $this->addSql('ALTER TABLE transactiondata ADD CONSTRAINT FK_1BDC5F50B49845E1 FOREIGN KEY (modeID) REFERENCES variablemode (modeID)');
        $this->addSql('ALTER TABLE transactiondata ADD CONSTRAINT FK_1BDC5F502C5E4619 FOREIGN KEY (displayTypeID) REFERENCES variabledisplaytype (displayTypeID)');
        $this->addSql('ALTER TABLE transactionqueue ADD CONSTRAINT FK_C09B31D9F99A11DC FOREIGN KEY (transactionID) REFERENCES usertransactions (transactionID)');
        $this->addSql('ALTER TABLE groupmembers ADD CONSTRAINT FK_6F15EDACD6EFA878 FOREIGN KEY (groupID) REFERENCES groups (groupID)');
        $this->addSql('ALTER TABLE groupmembers ADD CONSTRAINT FK_6F15EDAC5FD86D04 FOREIGN KEY (userID) REFERENCES users (userID)');
        $this->addSql('ALTER TABLE groupapplicationnotifications ADD CONSTRAINT FK_51C5D1555FD86D04 FOREIGN KEY (userID) REFERENCES users (userID)');
        $this->addSql('ALTER TABLE groupapplicationnotifications ADD CONSTRAINT FK_51C5D155D6EFA878 FOREIGN KEY (groupID) REFERENCES groups (groupID)');
        $this->addSql('ALTER TABLE groupapplicationnotifications ADD CONSTRAINT FK_51C5D1553B508F07 FOREIGN KEY (applicationID) REFERENCES application (applicationID)');
        $this->addSql('ALTER TABLE applicationmnemonics ADD CONSTRAINT FK_721AAD803B508F07 FOREIGN KEY (applicationID) REFERENCES application (applicationID)');
        $this->addSql('ALTER TABLE applicationmnemonics ADD CONSTRAINT FK_721AAD80EC225FF2 FOREIGN KEY (variableID) REFERENCES variable (variableID)');
        $this->addSql('ALTER TABLE applicationrequests ADD CONSTRAINT FK_E473321F3B508F07 FOREIGN KEY (applicationID) REFERENCES application (applicationID)');
        $this->addSql('ALTER TABLE applicationrequests ADD CONSTRAINT FK_E473321FD6EFA878 FOREIGN KEY (groupID) REFERENCES groups (groupID)');
        $this->addSql('ALTER TABLE usertransactions ADD CONSTRAINT FK_1D0E6127D6EFA878 FOREIGN KEY (groupID) REFERENCES groups (groupID)');
        $this->addSql('ALTER TABLE usertransactions ADD CONSTRAINT FK_1D0E61273B508F07 FOREIGN KEY (applicationID) REFERENCES application (applicationID)');
        $this->addSql('ALTER TABLE usertransactions ADD CONSTRAINT FK_1D0E61275FD86D04 FOREIGN KEY (userID) REFERENCES users (userID)');
        $this->addSql('ALTER TABLE usertransactions ADD CONSTRAINT FK_1D0E6127CD460934 FOREIGN KEY (entityID) REFERENCES entity (entityID)');
        $this->addSql('ALTER TABLE usertransactions ADD CONSTRAINT FK_1D0E6127B458E68D FOREIGN KEY (commandID) REFERENCES command (commandID)');
        $this->addSql('ALTER TABLE groupmembershipnotifications ADD CONSTRAINT FK_C07B917A5FD86D04 FOREIGN KEY (userID) REFERENCES users (userID)');
        $this->addSql('ALTER TABLE groupmembershipnotifications ADD CONSTRAINT FK_C07B917AD6EFA878 FOREIGN KEY (groupID) REFERENCES groups (groupID)');
        $this->addSql('ALTER TABLE groupapplications ADD CONSTRAINT FK_8CCCCBCFD6EFA878 FOREIGN KEY (groupID) REFERENCES groups (groupID)');
        $this->addSql('ALTER TABLE groupapplications ADD CONSTRAINT FK_8CCCCBCF3B508F07 FOREIGN KEY (applicationID) REFERENCES application (applicationID)');
        $this->addSql('ALTER TABLE applicationequations ADD CONSTRAINT FK_4781EC7F3B508F07 FOREIGN KEY (applicationID) REFERENCES application (applicationID)');
        $this->addSql('ALTER TABLE applicationequations ADD CONSTRAINT FK_4781EC7FCD460934 FOREIGN KEY (entityID) REFERENCES entity (entityID)');
        $this->addSql('ALTER TABLE applicationequations ADD CONSTRAINT FK_4781EC7FEC225FF2 FOREIGN KEY (variableID) REFERENCES variable (variableID)');
        $this->addSql('ALTER TABLE archivedata ADD CONSTRAINT FK_70B19876D9FD5E99 FOREIGN KEY (archiveID) REFERENCES archives (archiveID)');
        $this->addSql('ALTER TABLE archivedata ADD CONSTRAINT FK_70B19876EC225FF2 FOREIGN KEY (variableID) REFERENCES variable (variableID)');
        $this->addSql('ALTER TABLE archivedata ADD CONSTRAINT FK_70B19876B49845E1 FOREIGN KEY (modeID) REFERENCES variablemode (modeID)');
        $this->addSql('ALTER TABLE archivedata ADD CONSTRAINT FK_70B198762C5E4619 FOREIGN KEY (displayTypeID) REFERENCES variabledisplaytype (displayTypeID)');
        $this->addSql('ALTER TABLE entitiesinapplications ADD CONSTRAINT FK_FB5F52FC3B508F07 FOREIGN KEY (applicationID) REFERENCES application (applicationID)');
        $this->addSql('ALTER TABLE entitiesinapplications ADD CONSTRAINT FK_FB5F52FCCD460934 FOREIGN KEY (entityID) REFERENCES entity (entityID)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE archives DROP FOREIGN KEY FK_E262EC39CD460934');
        $this->addSql('ALTER TABLE recentsimulations DROP FOREIGN KEY FK_58E5136CD460934');
        $this->addSql('ALTER TABLE usertransactions DROP FOREIGN KEY FK_1D0E6127CD460934');
        $this->addSql('ALTER TABLE applicationequations DROP FOREIGN KEY FK_4781EC7FCD460934');
        $this->addSql('ALTER TABLE entitiesinapplications DROP FOREIGN KEY FK_FB5F52FCCD460934');
        $this->addSql('ALTER TABLE grouprequests DROP FOREIGN KEY FK_89CD6320D6EFA878');
        $this->addSql('ALTER TABLE archives DROP FOREIGN KEY FK_E262EC39D6EFA878');
        $this->addSql('ALTER TABLE recentsimulations DROP FOREIGN KEY FK_58E5136D6EFA878');
        $this->addSql('ALTER TABLE groupmembers DROP FOREIGN KEY FK_6F15EDACD6EFA878');
        $this->addSql('ALTER TABLE groupapplicationnotifications DROP FOREIGN KEY FK_51C5D155D6EFA878');
        $this->addSql('ALTER TABLE applicationrequests DROP FOREIGN KEY FK_E473321FD6EFA878');
        $this->addSql('ALTER TABLE usertransactions DROP FOREIGN KEY FK_1D0E6127D6EFA878');
        $this->addSql('ALTER TABLE groupmembershipnotifications DROP FOREIGN KEY FK_C07B917AD6EFA878');
        $this->addSql('ALTER TABLE groupapplications DROP FOREIGN KEY FK_8CCCCBCFD6EFA878');
        $this->addSql('ALTER TABLE transactiondata DROP FOREIGN KEY FK_1BDC5F50B49845E1');
        $this->addSql('ALTER TABLE archivedata DROP FOREIGN KEY FK_70B19876B49845E1');
        $this->addSql('ALTER TABLE archivedata DROP FOREIGN KEY FK_70B19876D9FD5E99');
        $this->addSql('ALTER TABLE grouprequests DROP FOREIGN KEY FK_89CD63205FD86D04');
        $this->addSql('ALTER TABLE applicationadmins DROP FOREIGN KEY FK_CB4C1EE95FD86D04');
        $this->addSql('ALTER TABLE groups DROP FOREIGN KEY FK_F06D39701FDCEC3E');
        $this->addSql('ALTER TABLE archives DROP FOREIGN KEY FK_E262EC395FD86D04');
        $this->addSql('ALTER TABLE recentsimulations DROP FOREIGN KEY FK_58E51365FD86D04');
        $this->addSql('ALTER TABLE groupmembers DROP FOREIGN KEY FK_6F15EDAC5FD86D04');
        $this->addSql('ALTER TABLE groupapplicationnotifications DROP FOREIGN KEY FK_51C5D1555FD86D04');
        $this->addSql('ALTER TABLE usertransactions DROP FOREIGN KEY FK_1D0E61275FD86D04');
        $this->addSql('ALTER TABLE groupmembershipnotifications DROP FOREIGN KEY FK_C07B917A5FD86D04');
        $this->addSql('ALTER TABLE transactiondata DROP FOREIGN KEY FK_1BDC5F50EC225FF2');
        $this->addSql('ALTER TABLE applicationmnemonics DROP FOREIGN KEY FK_721AAD80EC225FF2');
        $this->addSql('ALTER TABLE applicationequations DROP FOREIGN KEY FK_4781EC7FEC225FF2');
        $this->addSql('ALTER TABLE archivedata DROP FOREIGN KEY FK_70B19876EC225FF2');
        $this->addSql('ALTER TABLE archives DROP FOREIGN KEY FK_E262EC39FAA194C7');
        $this->addSql('ALTER TABLE transactiondata DROP FOREIGN KEY FK_1BDC5F50F99A11DC');
        $this->addSql('ALTER TABLE transactionqueue DROP FOREIGN KEY FK_C09B31D9F99A11DC');
        $this->addSql('ALTER TABLE transactiondata DROP FOREIGN KEY FK_1BDC5F502C5E4619');
        $this->addSql('ALTER TABLE archivedata DROP FOREIGN KEY FK_70B198762C5E4619');
        $this->addSql('ALTER TABLE archives DROP FOREIGN KEY FK_E262EC39B458E68D');
        $this->addSql('ALTER TABLE usertransactions DROP FOREIGN KEY FK_1D0E6127B458E68D');
        $this->addSql('ALTER TABLE applicationadmins DROP FOREIGN KEY FK_CB4C1EE93B508F07');
        $this->addSql('ALTER TABLE archives DROP FOREIGN KEY FK_E262EC393B508F07');
        $this->addSql('ALTER TABLE recentsimulations DROP FOREIGN KEY FK_58E51363B508F07');
        $this->addSql('ALTER TABLE groupapplicationnotifications DROP FOREIGN KEY FK_51C5D1553B508F07');
        $this->addSql('ALTER TABLE applicationmnemonics DROP FOREIGN KEY FK_721AAD803B508F07');
        $this->addSql('ALTER TABLE applicationrequests DROP FOREIGN KEY FK_E473321F3B508F07');
        $this->addSql('ALTER TABLE usertransactions DROP FOREIGN KEY FK_1D0E61273B508F07');
        $this->addSql('ALTER TABLE groupapplications DROP FOREIGN KEY FK_8CCCCBCF3B508F07');
        $this->addSql('ALTER TABLE applicationequations DROP FOREIGN KEY FK_4781EC7F3B508F07');
        $this->addSql('ALTER TABLE entitiesinapplications DROP FOREIGN KEY FK_FB5F52FC3B508F07');
        $this->addSql('DROP TABLE entity');
        $this->addSql('DROP TABLE grouprequests');
        $this->addSql('DROP TABLE applicationadmins');
        $this->addSql('DROP TABLE groups');
        $this->addSql('DROP TABLE variablemode');
        $this->addSql('DROP TABLE archives');
        $this->addSql('DROP TABLE recentsimulations');
        $this->addSql('DROP TABLE transactiondata');
        $this->addSql('DROP TABLE transactionqueue');
        $this->addSql('DROP TABLE users');
        $this->addSql('DROP TABLE variable');
        $this->addSql('DROP TABLE groupmembers');
        $this->addSql('DROP TABLE groupapplicationnotifications');
        $this->addSql('DROP TABLE applicationmnemonics');
        $this->addSql('DROP TABLE applicationrequests');
        $this->addSql('DROP TABLE usertransactions');
        $this->addSql('DROP TABLE groupmembershipnotifications');
        $this->addSql('DROP TABLE groupapplications');
        $this->addSql('DROP TABLE variabledisplaytype');
        $this->addSql('DROP TABLE applicationequations');
        $this->addSql('DROP TABLE command');
        $this->addSql('DROP TABLE archivedata');
        $this->addSql('DROP TABLE entitiesinapplications');
        $this->addSql('DROP TABLE application');
        $this->addSql('DROP TABLE isimulateupdates');
    }
}
