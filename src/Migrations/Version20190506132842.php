<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190506132842 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE entity (entityID INT AUTO_INCREMENT NOT NULL, entity_code VARCHAR(3) DEFAULT NULL, entity_name VARCHAR(45) DEFAULT NULL, PRIMARY KEY(entityID)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE usertransactions (usertransactionID INT AUTO_INCREMENT NOT NULL, error INT DEFAULT NULL, comment VARCHAR(300) DEFAULT NULL, created_at DATETIME DEFAULT NULL, groupID INT DEFAULT NULL, applicationID INT DEFAULT NULL, userID INT DEFAULT NULL, entityID INT DEFAULT NULL, commandID INT DEFAULT NULL, INDEX IDX_1D0E6127D6EFA878 (groupID), INDEX IDX_1D0E61273B508F07 (applicationID), INDEX IDX_1D0E61275FD86D04 (userID), INDEX IDX_1D0E6127CD460934 (entityID), INDEX IDX_1D0E6127B458E68D (commandID), PRIMARY KEY(usertransactionID)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE command (commandID INT AUTO_INCREMENT NOT NULL, description VARCHAR(15) NOT NULL, PRIMARY KEY(commandID)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE usertransactions ADD CONSTRAINT FK_1D0E6127D6EFA878 FOREIGN KEY (groupID) REFERENCES groups (groupID)');
        $this->addSql('ALTER TABLE usertransactions ADD CONSTRAINT FK_1D0E61273B508F07 FOREIGN KEY (applicationID) REFERENCES application (applicationID)');
        $this->addSql('ALTER TABLE usertransactions ADD CONSTRAINT FK_1D0E61275FD86D04 FOREIGN KEY (userID) REFERENCES users (userID)');
        $this->addSql('ALTER TABLE usertransactions ADD CONSTRAINT FK_1D0E6127CD460934 FOREIGN KEY (entityID) REFERENCES entity (entityID)');
        $this->addSql('ALTER TABLE usertransactions ADD CONSTRAINT FK_1D0E6127B458E68D FOREIGN KEY (commandID) REFERENCES command (commandID)');
        $this->addSql('ALTER TABLE users CHANGE invitesRemaining invitesRemaining TINYINT DEFAULT 50 NOT NULL, CHANGE showGetStartedBox showGetStartedBox TINYINT DEFAULT true NOT NULL, CHANGE iChartInternalAccess iChartInternalAccess TINYINT DEFAULT 0 NOT NULL, CHANGE activated activated TINYINT DEFAULT 0 NOT NULL, CHANGE loginAttempts loginAttempts TINYINT DEFAULT 0 NOT NULL');
        $this->addSql('ALTER TABLE groupmembers CHANGE permissionLevel permissionLevel TINYINT DEFAULT 23');
        $this->addSql('ALTER TABLE application CHANGE secret_key secret_key CHAR(40) NOT NULL, CHANGE nonce nonce CHAR(32) NOT NULL, CHANGE approve_to_join approve_to_join TINYINT(1) NULL, CHANGE frequency frequency CHAR(1) NOT NULL, CHANGE in_development in_development TINYINT(1) DEFAULT 1 NOT NULL, CHANGE approved approved TINYINT(1) DEFAULT 0 NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE usertransactions DROP FOREIGN KEY FK_1D0E6127CD460934');
        $this->addSql('ALTER TABLE usertransactions DROP FOREIGN KEY FK_1D0E6127B458E68D');
        $this->addSql('DROP TABLE entity');
        $this->addSql('DROP TABLE usertransactions');
        $this->addSql('DROP TABLE command');
        $this->addSql('ALTER TABLE application CHANGE secret_key secret_key CHAR(40) NOT NULL COLLATE utf8mb4_unicode_ci, CHANGE nonce nonce CHAR(32) NOT NULL COLLATE utf8mb4_unicode_ci, CHANGE approve_to_join approve_to_join TINYINT(1) DEFAULT NULL, CHANGE frequency frequency CHAR(1) NOT NULL COLLATE utf8mb4_unicode_ci, CHANGE in_development in_development TINYINT(1) DEFAULT \'1\' NOT NULL, CHANGE approved approved TINYINT(1) DEFAULT \'0\' NOT NULL');
        $this->addSql('ALTER TABLE groupmembers CHANGE permissionLevel permissionLevel TINYINT(1) DEFAULT \'23\'');
        $this->addSql('ALTER TABLE users CHANGE invitesRemaining invitesRemaining TINYINT(1) DEFAULT \'50\' NOT NULL, CHANGE showGetStartedBox showGetStartedBox TINYINT(1) DEFAULT \'1\' NOT NULL, CHANGE iChartInternalAccess iChartInternalAccess TINYINT(1) DEFAULT \'0\' NOT NULL, CHANGE activated activated TINYINT(1) DEFAULT \'0\' NOT NULL, CHANGE loginAttempts loginAttempts TINYINT(1) DEFAULT \'0\' NOT NULL');
    }
}
