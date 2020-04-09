<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200406193228 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE ticket CHANGE description description VARCHAR(255) , CHANGE addressee_id addressee_id INT NOT NULL');
  //      $this->addSql('ALTER TABLE ticket ADD CONSTRAINT FK_97A0ADA361220EA6 FOREIGN KEY (creator_id) REFERENCES user (id)');
  //      $this->addSql('ALTER TABLE ticket ADD CONSTRAINT FK_97A0ADA32261B4C3 FOREIGN KEY (addressee_id) REFERENCES user (id)');
  //      $this->addSql('ALTER TABLE ticket ADD CONSTRAINT FK_97A0ADA3166D1F9C FOREIGN KEY (project_id) REFERENCES projects (id)');
  //      $this->addSql('CREATE INDEX IDX_97A0ADA361220EA6 ON ticket (creator_id)');
  //      $this->addSql('CREATE INDEX IDX_97A0ADA32261B4C3 ON ticket (addressee_id)');
  //      $this->addSql('CREATE INDEX IDX_97A0ADA3166D1F9C ON ticket (project_id)');
  //      $this->addSql('ALTER TABLE projects ADD CONSTRAINT FK_5C93B3A4F675F31B FOREIGN KEY (author_id) REFERENCES user (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE projects DROP FOREIGN KEY FK_5C93B3A4F675F31B');
        $this->addSql('ALTER TABLE ticket DROP FOREIGN KEY FK_97A0ADA361220EA6');
        $this->addSql('ALTER TABLE ticket DROP FOREIGN KEY FK_97A0ADA32261B4C3');
        $this->addSql('ALTER TABLE ticket DROP FOREIGN KEY FK_97A0ADA3166D1F9C');
        $this->addSql('DROP INDEX IDX_97A0ADA361220EA6 ON ticket');
        $this->addSql('DROP INDEX IDX_97A0ADA32261B4C3 ON ticket');
        $this->addSql('DROP INDEX IDX_97A0ADA3166D1F9C ON ticket');
        $this->addSql('ALTER TABLE ticket CHANGE description description VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE addressee_id addressee_id INT NOT NULL');
    }
}
