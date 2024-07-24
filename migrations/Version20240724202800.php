<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240724202800 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE historique_t DROP FOREIGN KEY FK_2FE09145F2C56620');
        $this->addSql('DROP INDEX IDX_2FE09145F2C56620 ON historique_t');
        $this->addSql('ALTER TABLE historique_t ADD solde BIGINT NOT NULL, ADD numero_compte VARCHAR(255) NOT NULL, ADD titulaire VARCHAR(255) NOT NULL, DROP compte_id, CHANGE nom_transaction nom VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE historique_t ADD compte_id INT NOT NULL, ADD nom_transaction VARCHAR(255) NOT NULL, DROP nom, DROP solde, DROP numero_compte, DROP titulaire');
        $this->addSql('ALTER TABLE historique_t ADD CONSTRAINT FK_2FE09145F2C56620 FOREIGN KEY (compte_id) REFERENCES compte (id)');
        $this->addSql('CREATE INDEX IDX_2FE09145F2C56620 ON historique_t (compte_id)');
    }
}
