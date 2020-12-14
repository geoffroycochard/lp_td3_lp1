<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201214103949 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'sqlite', 'Migration can only be executed safely on \'sqlite\'.');

        $this->addSql('CREATE TABLE ticket_user (ticket_id INTEGER NOT NULL, user_id INTEGER NOT NULL, PRIMARY KEY(ticket_id, user_id))');
        $this->addSql('CREATE INDEX IDX_BF48C371700047D2 ON ticket_user (ticket_id)');
        $this->addSql('CREATE INDEX IDX_BF48C371A76ED395 ON ticket_user (user_id)');
        $this->addSql('CREATE TABLE user (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, username VARCHAR(255) NOT NULL)');
        $this->addSql('DROP INDEX IDX_97A0ADA312469DE2');
        $this->addSql('CREATE TEMPORARY TABLE __temp__ticket AS SELECT id, category_id, title, description FROM ticket');
        $this->addSql('DROP TABLE ticket');
        $this->addSql('CREATE TABLE ticket (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, category_id INTEGER NOT NULL, title VARCHAR(255) NOT NULL COLLATE BINARY, description CLOB NOT NULL COLLATE BINARY, CONSTRAINT FK_97A0ADA312469DE2 FOREIGN KEY (category_id) REFERENCES category (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO ticket (id, category_id, title, description) SELECT id, category_id, title, description FROM __temp__ticket');
        $this->addSql('DROP TABLE __temp__ticket');
        $this->addSql('CREATE INDEX IDX_97A0ADA312469DE2 ON ticket (category_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'sqlite', 'Migration can only be executed safely on \'sqlite\'.');

        $this->addSql('DROP TABLE ticket_user');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP INDEX IDX_97A0ADA312469DE2');
        $this->addSql('CREATE TEMPORARY TABLE __temp__ticket AS SELECT id, category_id, title, description FROM ticket');
        $this->addSql('DROP TABLE ticket');
        $this->addSql('CREATE TABLE ticket (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, category_id INTEGER NOT NULL, title VARCHAR(255) NOT NULL, description CLOB NOT NULL)');
        $this->addSql('INSERT INTO ticket (id, category_id, title, description) SELECT id, category_id, title, description FROM __temp__ticket');
        $this->addSql('DROP TABLE __temp__ticket');
        $this->addSql('CREATE INDEX IDX_97A0ADA312469DE2 ON ticket (category_id)');
    }
}
