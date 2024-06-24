<?php

declare(strict_types=1);

namespace CommerceWeaversSyliusAlsoBoughtMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20240408131819 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Add synchronised products number for channel';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE sylius_channel ADD number_of_synchronised_products INT DEFAULT 10 NOT NULL');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE sylius_channel DROP number_of_synchronised_products');
    }
}
