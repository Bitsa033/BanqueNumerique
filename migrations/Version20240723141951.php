<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240723141951 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE UNIQUE INDEX UNIQ_CFF65260F55AE19E ON compte (numero)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_CFF65260BFB7B5B6 ON compte (rib)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX UNIQ_CFF65260F55AE19E ON compte');
        $this->addSql('DROP INDEX UNIQ_CFF65260BFB7B5B6 ON compte');
    }
}
