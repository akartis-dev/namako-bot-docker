<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220401172400 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user_messages ADD all_customer BOOLEAN DEFAULT NULL');
        $this->addSql('ALTER TABLE user_messages_customer DROP CONSTRAINT FK_683E952AC8B96CF');
        $this->addSql('ALTER TABLE user_messages_customer ADD CONSTRAINT FK_683E952AC8B96CF FOREIGN KEY (user_messages_id) REFERENCES user_messages (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA IF NOT EXISTS public');
        $this->addSql('ALTER TABLE user_messages_customer DROP CONSTRAINT fk_683e952ac8b96cf');
        $this->addSql('ALTER TABLE user_messages_customer ADD CONSTRAINT fk_683e952ac8b96cf FOREIGN KEY (user_messages_id) REFERENCES user_messages (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE user_messages DROP all_customer');
    }
}
