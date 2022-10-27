<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220401152258 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE user_messages_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE user_messages (id INT NOT NULL, content TEXT NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE user_messages_customer (user_messages_id INT NOT NULL, customer_id INT NOT NULL, PRIMARY KEY(user_messages_id, customer_id))');
        $this->addSql('CREATE INDEX IDX_683E952AC8B96CF ON user_messages_customer (user_messages_id)');
        $this->addSql('CREATE INDEX IDX_683E952A9395C3F3 ON user_messages_customer (customer_id)');
        $this->addSql('ALTER TABLE user_messages_customer ADD CONSTRAINT FK_683E952AC8B96CF FOREIGN KEY (user_messages_id) REFERENCES user_messages (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE user_messages_customer ADD CONSTRAINT FK_683E952A9395C3F3 FOREIGN KEY (customer_id) REFERENCES customer (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA IF NOT EXISTS public');
        $this->addSql('ALTER TABLE user_messages_customer DROP CONSTRAINT FK_683E952AC8B96CF');
        $this->addSql('DROP SEQUENCE user_messages_id_seq CASCADE');
        $this->addSql('DROP TABLE user_messages');
        $this->addSql('DROP TABLE user_messages_customer');
    }
}
