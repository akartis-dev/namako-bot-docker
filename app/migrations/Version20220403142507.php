<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220403142507 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user_messages ADD sender_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user_messages ADD CONSTRAINT FK_3B8FFA96F624B39D FOREIGN KEY (sender_id) REFERENCES customer (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_3B8FFA96F624B39D ON user_messages (sender_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE user_messages DROP CONSTRAINT FK_3B8FFA96F624B39D');
        $this->addSql('DROP INDEX IDX_3B8FFA96F624B39D');
        $this->addSql('ALTER TABLE user_messages DROP sender_id');
    }
}
