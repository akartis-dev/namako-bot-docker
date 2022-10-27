<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220320193322 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE error_detail ADD search_term TEXT DEFAULT NULL');
        $this->addSql('ALTER TABLE error_detail ALTER detail DROP NOT NULL');
        $this->addSql('ALTER TABLE error_detail ALTER origin DROP NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE error_detail DROP search_term');
        $this->addSql('ALTER TABLE error_detail ALTER detail SET NOT NULL');
        $this->addSql('ALTER TABLE error_detail ALTER origin SET NOT NULL');
    }
}
