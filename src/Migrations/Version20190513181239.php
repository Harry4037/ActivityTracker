<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190513181239 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE variablemode (modeID INT AUTO_INCREMENT NOT NULL, description VARCHAR(30) NOT NULL, abbreviation VARCHAR(1) NOT NULL, PRIMARY KEY(modeID)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE variable (variableID INT AUTO_INCREMENT NOT NULL, description VARCHAR(200) NOT NULL, PRIMARY KEY(variableID)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE applicationmnemonics (applicationmnemonicID INT AUTO_INCREMENT NOT NULL, mnemonic VARCHAR(255) NOT NULL, applicationID INT DEFAULT NULL, variableID INT DEFAULT NULL, INDEX IDX_721AAD803B508F07 (applicationID), INDEX IDX_721AAD80EC225FF2 (variableID), PRIMARY KEY(applicationmnemonicID)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE applicationequations (applicationequationID INT AUTO_INCREMENT NOT NULL, equation LONGTEXT NOT NULL, applicationID INT DEFAULT NULL, entityID INT DEFAULT NULL, variableID INT DEFAULT NULL, INDEX IDX_4781EC7F3B508F07 (applicationID), INDEX IDX_4781EC7FCD460934 (entityID), INDEX IDX_4781EC7FEC225FF2 (variableID), PRIMARY KEY(applicationequationID)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE applicationmnemonics ADD CONSTRAINT FK_721AAD803B508F07 FOREIGN KEY (applicationID) REFERENCES application (applicationID)');
        $this->addSql('ALTER TABLE applicationmnemonics ADD CONSTRAINT FK_721AAD80EC225FF2 FOREIGN KEY (variableID) REFERENCES variable (variableID)');
        $this->addSql('ALTER TABLE applicationequations ADD CONSTRAINT FK_4781EC7F3B508F07 FOREIGN KEY (applicationID) REFERENCES application (applicationID)');
        $this->addSql('ALTER TABLE applicationequations ADD CONSTRAINT FK_4781EC7FCD460934 FOREIGN KEY (entityID) REFERENCES entity (entityID)');
        $this->addSql('ALTER TABLE applicationequations ADD CONSTRAINT FK_4781EC7FEC225FF2 FOREIGN KEY (variableID) REFERENCES variable (variableID)');
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

        $this->addSql('ALTER TABLE applicationmnemonics DROP FOREIGN KEY FK_721AAD80EC225FF2');
        $this->addSql('ALTER TABLE applicationequations DROP FOREIGN KEY FK_4781EC7FEC225FF2');
        $this->addSql('DROP TABLE variablemode');
        $this->addSql('DROP TABLE variable');
        $this->addSql('DROP TABLE applicationmnemonics');
        $this->addSql('DROP TABLE applicationequations');
        $this->addSql('ALTER TABLE application CHANGE secret_key secret_key CHAR(40) NOT NULL COLLATE utf8mb4_unicode_ci, CHANGE nonce nonce CHAR(32) NOT NULL COLLATE utf8mb4_unicode_ci, CHANGE approve_to_join approve_to_join TINYINT(1) DEFAULT NULL, CHANGE frequency frequency CHAR(1) NOT NULL COLLATE utf8mb4_unicode_ci, CHANGE in_development in_development TINYINT(1) DEFAULT \'1\' NOT NULL, CHANGE approved approved TINYINT(1) DEFAULT \'0\' NOT NULL');
        $this->addSql('ALTER TABLE groupapplicationnotifications CHANGE isDeleted isDeleted TINYINT(1) DEFAULT NULL, CHANGE approved approved TINYINT(1) DEFAULT \'1\' NOT NULL');
        $this->addSql('ALTER TABLE groupmembers CHANGE permissionLevel permissionLevel TINYINT(1) DEFAULT \'23\'');
        $this->addSql('ALTER TABLE groupmembershipnotifications CHANGE approved approved TINYINT(1) DEFAULT \'1\' NOT NULL');
        $this->addSql('ALTER TABLE users CHANGE invitesRemaining invitesRemaining TINYINT(1) DEFAULT \'50\' NOT NULL, CHANGE showGetStartedBox showGetStartedBox TINYINT(1) DEFAULT \'1\' NOT NULL, CHANGE iChartInternalAccess iChartInternalAccess TINYINT(1) DEFAULT \'0\' NOT NULL, CHANGE activated activated TINYINT(1) DEFAULT \'0\' NOT NULL, CHANGE loginAttempts loginAttempts TINYINT(1) DEFAULT \'0\' NOT NULL');
    }
}
