<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20180630001639 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'sqlite', 'Migration can only be executed safely on \'sqlite\'.');

        $this->addSql('CREATE TEMPORARY TABLE __temp__newsletter_subscription AS SELECT id, email, username FROM newsletter_subscription');
        $this->addSql('DROP TABLE newsletter_subscription');
        $this->addSql('CREATE TABLE newsletter_subscription (id INTEGER NOT NULL, newsletter_id INTEGER DEFAULT NULL, email VARCHAR(255) NOT NULL COLLATE BINARY, username VARCHAR(255) DEFAULT NULL COLLATE BINARY, PRIMARY KEY(id), CONSTRAINT FK_A82B55AD22DB1917 FOREIGN KEY (newsletter_id) REFERENCES newsletter (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO newsletter_subscription (id, email, username) SELECT id, email, username FROM __temp__newsletter_subscription');
        $this->addSql('DROP TABLE __temp__newsletter_subscription');
        $this->addSql('CREATE INDEX IDX_A82B55AD22DB1917 ON newsletter_subscription (newsletter_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'sqlite', 'Migration can only be executed safely on \'sqlite\'.');

        $this->addSql('DROP INDEX IDX_A82B55AD22DB1917');
        $this->addSql('CREATE TEMPORARY TABLE __temp__newsletter_subscription AS SELECT id, email, username FROM newsletter_subscription');
        $this->addSql('DROP TABLE newsletter_subscription');
        $this->addSql('CREATE TABLE newsletter_subscription (id INTEGER NOT NULL, email VARCHAR(255) NOT NULL, username VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('INSERT INTO newsletter_subscription (id, email, username) SELECT id, email, username FROM __temp__newsletter_subscription');
        $this->addSql('DROP TABLE __temp__newsletter_subscription');
    }
}
