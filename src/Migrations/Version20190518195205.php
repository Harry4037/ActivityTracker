<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190518195205 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE externaldata (externaldataID INT AUTO_INCREMENT NOT NULL, value LONGTEXT NOT NULL, sourceID INT DEFAULT NULL, entityID INT DEFAULT NULL, variableID INT DEFAULT NULL, INDEX IDX_40B24451D57B7A28 (sourceID), INDEX IDX_40B24451CD460934 (entityID), INDEX IDX_40B24451EC225FF2 (variableID), PRIMARY KEY(externaldataID)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE externalsource (sourceID INT AUTO_INCREMENT NOT NULL, sourceName VARCHAR(50) DEFAULT NULL, description VARCHAR(200) DEFAULT NULL, PRIMARY KEY(sourceID)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE disabledtransactionnotifications (disabledtransactionID INT AUTO_INCREMENT NOT NULL, userID INT DEFAULT NULL, transactionID INT DEFAULT NULL, INDEX IDX_17FA24715FD86D04 (userID), INDEX IDX_17FA2471F99A11DC (transactionID), PRIMARY KEY(disabledtransactionID)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE baseapplicationdata (baseapplicationdataID INT AUTO_INCREMENT NOT NULL, userHisEnd VARCHAR(10) DEFAULT NULL, actualHisEnd VARCHAR(10) DEFAULT NULL, data VARCHAR(1000) DEFAULT NULL, applicationID INT DEFAULT NULL, entityID INT DEFAULT NULL, variableID INT DEFAULT NULL, modeID INT DEFAULT NULL, displayTypeID INT DEFAULT NULL, INDEX IDX_EBD29F3B508F07 (applicationID), INDEX IDX_EBD29FCD460934 (entityID), INDEX IDX_EBD29FEC225FF2 (variableID), INDEX IDX_EBD29FB49845E1 (modeID), INDEX IDX_EBD29F2C5E4619 (displayTypeID), PRIMARY KEY(baseapplicationdataID)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE currenttransactions (currenttransactionID INT AUTO_INCREMENT NOT NULL, groupID INT DEFAULT NULL, applicationID INT DEFAULT NULL, entityID INT DEFAULT NULL, transactionID INT DEFAULT NULL, previousTransactionID INT DEFAULT NULL, INDEX IDX_AC1036BD6EFA878 (groupID), INDEX IDX_AC1036B3B508F07 (applicationID), INDEX IDX_AC1036BCD460934 (entityID), INDEX IDX_AC1036BF99A11DC (transactionID), INDEX IDX_AC1036BBD6A7ACD (previousTransactionID), PRIMARY KEY(currenttransactionID)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE entityinaggregates (entityinaggregateID INT AUTO_INCREMENT NOT NULL, applicationID INT DEFAULT NULL, aggregateID INT DEFAULT NULL, entityID INT DEFAULT NULL, INDEX IDX_C53AF45C3B508F07 (applicationID), INDEX IDX_C53AF45C8DF7D005 (aggregateID), INDEX IDX_C53AF45CCD460934 (entityID), PRIMARY KEY(entityinaggregateID)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE Solutionengine (engineID INT AUTO_INCREMENT NOT NULL, engineName VARCHAR(75) DEFAULT NULL, PRIMARY KEY(engineID)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE securityquestion (questionID INT AUTO_INCREMENT NOT NULL, question VARCHAR(100) NOT NULL, PRIMARY KEY(questionID)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE externaldata ADD CONSTRAINT FK_40B24451D57B7A28 FOREIGN KEY (sourceID) REFERENCES externalsource (sourceID)');
        $this->addSql('ALTER TABLE externaldata ADD CONSTRAINT FK_40B24451CD460934 FOREIGN KEY (entityID) REFERENCES entity (entityID)');
        $this->addSql('ALTER TABLE externaldata ADD CONSTRAINT FK_40B24451EC225FF2 FOREIGN KEY (variableID) REFERENCES variable (variableID)');
        $this->addSql('ALTER TABLE disabledtransactionnotifications ADD CONSTRAINT FK_17FA24715FD86D04 FOREIGN KEY (userID) REFERENCES users (userID)');
        $this->addSql('ALTER TABLE disabledtransactionnotifications ADD CONSTRAINT FK_17FA2471F99A11DC FOREIGN KEY (transactionID) REFERENCES usertransactions (transactionID)');
        $this->addSql('ALTER TABLE baseapplicationdata ADD CONSTRAINT FK_EBD29F3B508F07 FOREIGN KEY (applicationID) REFERENCES application (applicationID)');
        $this->addSql('ALTER TABLE baseapplicationdata ADD CONSTRAINT FK_EBD29FCD460934 FOREIGN KEY (entityID) REFERENCES entity (entityID)');
        $this->addSql('ALTER TABLE baseapplicationdata ADD CONSTRAINT FK_EBD29FEC225FF2 FOREIGN KEY (variableID) REFERENCES variable (variableID)');
        $this->addSql('ALTER TABLE baseapplicationdata ADD CONSTRAINT FK_EBD29FB49845E1 FOREIGN KEY (modeID) REFERENCES variablemode (modeID)');
        $this->addSql('ALTER TABLE baseapplicationdata ADD CONSTRAINT FK_EBD29F2C5E4619 FOREIGN KEY (displayTypeID) REFERENCES variabledisplaytype (displayTypeID)');
        $this->addSql('ALTER TABLE currenttransactions ADD CONSTRAINT FK_AC1036BD6EFA878 FOREIGN KEY (groupID) REFERENCES groups (groupID)');
        $this->addSql('ALTER TABLE currenttransactions ADD CONSTRAINT FK_AC1036B3B508F07 FOREIGN KEY (applicationID) REFERENCES application (applicationID)');
        $this->addSql('ALTER TABLE currenttransactions ADD CONSTRAINT FK_AC1036BCD460934 FOREIGN KEY (entityID) REFERENCES entity (entityID)');
        $this->addSql('ALTER TABLE currenttransactions ADD CONSTRAINT FK_AC1036BF99A11DC FOREIGN KEY (transactionID) REFERENCES usertransactions (transactionID)');
        $this->addSql('ALTER TABLE currenttransactions ADD CONSTRAINT FK_AC1036BBD6A7ACD FOREIGN KEY (previousTransactionID) REFERENCES usertransactions (transactionID)');
        $this->addSql('ALTER TABLE entityinaggregates ADD CONSTRAINT FK_C53AF45C3B508F07 FOREIGN KEY (applicationID) REFERENCES application (applicationID)');
        $this->addSql('ALTER TABLE entityinaggregates ADD CONSTRAINT FK_C53AF45C8DF7D005 FOREIGN KEY (aggregateID) REFERENCES entity (entityID)');
        $this->addSql('ALTER TABLE entityinaggregates ADD CONSTRAINT FK_C53AF45CCD460934 FOREIGN KEY (entityID) REFERENCES entity (entityID)');
        $this->addSql('ALTER TABLE users CHANGE invitesRemaining invitesRemaining TINYINT DEFAULT 50 NOT NULL, CHANGE showGetStartedBox showGetStartedBox TINYINT DEFAULT true NOT NULL, CHANGE iChartInternalAccess iChartInternalAccess TINYINT DEFAULT 0 NOT NULL, CHANGE activated activated TINYINT DEFAULT 0 NOT NULL, CHANGE loginAttempts loginAttempts TINYINT DEFAULT 0 NOT NULL');
        $this->addSql('ALTER TABLE groupmembers CHANGE permissionLevel permissionLevel TINYINT DEFAULT 23');
        $this->addSql('ALTER TABLE groupapplicationnotifications CHANGE isDeleted isDeleted TINYINT(1) NULL, CHANGE approved approved TINYINT(1) DEFAULT 1 NOT NULL');
        $this->addSql('ALTER TABLE groupmembershipnotifications CHANGE approved approved TINYINT(1) DEFAULT 1 NOT NULL');
        $this->addSql('ALTER TABLE application CHANGE secret_key secret_key CHAR(40) NOT NULL, CHANGE nonce nonce CHAR(32) NOT NULL, CHANGE approve_to_join approve_to_join TINYINT(1) NULL, CHANGE frequency frequency CHAR(1) NOT NULL, CHANGE in_development in_development TINYINT(1) DEFAULT 1 NOT NULL, CHANGE approved approved TINYINT(1) DEFAULT 0 NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE externaldata DROP FOREIGN KEY FK_40B24451D57B7A28');
        $this->addSql('DROP TABLE externaldata');
        $this->addSql('DROP TABLE externalsource');
        $this->addSql('DROP TABLE disabledtransactionnotifications');
        $this->addSql('DROP TABLE baseapplicationdata');
        $this->addSql('DROP TABLE currenttransactions');
        $this->addSql('DROP TABLE entityinaggregates');
        $this->addSql('DROP TABLE Solutionengine');
        $this->addSql('DROP TABLE securityquestion');
        $this->addSql('ALTER TABLE application CHANGE secret_key secret_key CHAR(40) NOT NULL COLLATE utf8mb4_unicode_ci, CHANGE nonce nonce CHAR(32) NOT NULL COLLATE utf8mb4_unicode_ci, CHANGE approve_to_join approve_to_join TINYINT(1) DEFAULT NULL, CHANGE frequency frequency CHAR(1) NOT NULL COLLATE utf8mb4_unicode_ci, CHANGE in_development in_development TINYINT(1) DEFAULT \'1\' NOT NULL, CHANGE approved approved TINYINT(1) DEFAULT \'0\' NOT NULL');
        $this->addSql('ALTER TABLE groupapplicationnotifications CHANGE isDeleted isDeleted TINYINT(1) DEFAULT NULL, CHANGE approved approved TINYINT(1) DEFAULT \'1\' NOT NULL');
        $this->addSql('ALTER TABLE groupmembers CHANGE permissionLevel permissionLevel TINYINT(1) DEFAULT \'23\'');
        $this->addSql('ALTER TABLE groupmembershipnotifications CHANGE approved approved TINYINT(1) DEFAULT \'1\' NOT NULL');
        $this->addSql('ALTER TABLE users CHANGE invitesRemaining invitesRemaining TINYINT(1) DEFAULT \'50\' NOT NULL, CHANGE showGetStartedBox showGetStartedBox TINYINT(1) DEFAULT \'1\' NOT NULL, CHANGE iChartInternalAccess iChartInternalAccess TINYINT(1) DEFAULT \'0\' NOT NULL, CHANGE activated activated TINYINT(1) DEFAULT \'0\' NOT NULL, CHANGE loginAttempts loginAttempts TINYINT(1) DEFAULT \'0\' NOT NULL');
    }
}
