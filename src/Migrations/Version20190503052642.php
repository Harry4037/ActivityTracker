<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190503052642 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE users (userID INT AUTO_INCREMENT NOT NULL, username VARCHAR(255) NOT NULL, firstName VARCHAR(255) NOT NULL, lastName VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, affiliation VARCHAR(255) NOT NULL, timezone VARCHAR(255) DEFAULT \'America/New_York\' NOT NULL, displayName VARCHAR(255) DEFAULT \'real\' NOT NULL, invitesRemaining TINYINT DEFAULT 50 NOT NULL, showGetStartedBox TINYINT DEFAULT true NOT NULL, iChartInternalAccess TINYINT DEFAULT 0 NOT NULL, activated TINYINT DEFAULT 0 NOT NULL, activationCode VARCHAR(255) NOT NULL, newPasswordCode VARCHAR(255) NOT NULL, loginAttempts TINYINT DEFAULT 0 NOT NULL, new_password_time DATETIME NOT NULL, created_at DATETIME NOT NULL, last_login_attempt DATETIME NOT NULL, password VARCHAR(255) NOT NULL, roles JSON NOT NULL, reset_password VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_1483A5E9F85E0677 (username), UNIQUE INDEX UNIQ_1483A5E9E7927C74 (email), PRIMARY KEY(userID)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE users');
    }
}
