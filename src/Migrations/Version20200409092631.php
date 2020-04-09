<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200409092631 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

 //       $this->addSql('ALTER TABLE ticket CHANGE description description VARCHAR(255) NOT NULL');
 //       $this->addSql('ALTER TABLE ticket ADD CONSTRAINT FK_97A0ADA361220EA6 FOREIGN KEY (creator_id) REFERENCES user (id)');
 //       $this->addSql('ALTER TABLE ticket ADD CONSTRAINT FK_97A0ADA32261B4C3 FOREIGN KEY (addressee_id) REFERENCES user (id)');
 //       $this->addSql('ALTER TABLE ticket ADD CONSTRAINT FK_97A0ADA3166D1F9C FOREIGN KEY (project_id) REFERENCES projects (id)');
        $this->addSql('ALTER TABLE comment CHANGE creator_id creator_id INT NOT NULL');
 //       $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526C700047D2 FOREIGN KEY (ticket_id) REFERENCES ticket (id)');
 //       $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526C61220EA6 FOREIGN KEY (creator_id) REFERENCES user (id)');
 //       $this->addSql('CREATE INDEX IDX_9474526C700047D2 ON comment (ticket_id)');
 //       $this->addSql('CREATE INDEX IDX_9474526C61220EA6 ON comment (creator_id)');
 //       $this->addSql('ALTER TABLE projects ADD CONSTRAINT FK_5C93B3A4F675F31B FOREIGN KEY (author_id) REFERENCES user (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE comment DROP FOREIGN KEY FK_9474526C700047D2');
        $this->addSql('ALTER TABLE comment DROP FOREIGN KEY FK_9474526C61220EA6');
        $this->addSql('DROP INDEX IDX_9474526C700047D2 ON comment');
        $this->addSql('DROP INDEX IDX_9474526C61220EA6 ON comment');
        $this->addSql('ALTER TABLE comment CHANGE creator_id user_id INT NOT NULL');
        $this->addSql('ALTER TABLE projects DROP FOREIGN KEY FK_5C93B3A4F675F31B');
        $this->addSql('ALTER TABLE ticket DROP FOREIGN KEY FK_97A0ADA361220EA6');
        $this->addSql('ALTER TABLE ticket DROP FOREIGN KEY FK_97A0ADA32261B4C3');
        $this->addSql('ALTER TABLE ticket DROP FOREIGN KEY FK_97A0ADA3166D1F9C');
        $this->addSql('ALTER TABLE ticket CHANGE description description VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`');
    }
}
