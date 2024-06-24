<?php

declare(strict_types=1);

namespace CommerceWeaversSyliusAlsoBoughtMigrations;

use Doctrine\DBAL\Platforms\PostgreSQLPlatform;
use Doctrine\DBAL\Schema\Schema;
use Sylius\Bundle\CoreBundle\Doctrine\Migrations\AbstractMigration;

final class Version20230630102308 extends AbstractMigration
{
    public function preUp(Schema $schema): void
    {
        // Intentionally left empty to override the parent method
    }

    public function preDown(Schema $schema): void
    {
        // Intentionally left empty to override the parent method
    }

    public function getDescription(): string
    {
        return 'Setup the database for the plugin';
    }

    public function up(Schema $schema): void
    {
        $this->abortIf(
            !$this->isMySql() && !$this->isPostgreSQL(),
            'This migration can only be executed on \'MySQL\' or \'PostgreSQL\'.',
        );

        if ($this->isMySql()) {
            $this->addSql('CREATE TABLE sylius_also_bought_product_synchronization (id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', start_date DATETIME NOT NULL, end_date DATETIME DEFAULT NULL, number_of_orders INT NOT NULL, affected_products JSON NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET UTF8 COLLATE `UTF8_unicode_ci` ENGINE = InnoDB');
            $this->addSql('ALTER TABLE sylius_product ADD bought_together_products JSON NOT NULL');
            $this->addSql('UPDATE sylius_product SET bought_together_products = JSON_ARRAY()');

            return;
        }

        $this->addSql('CREATE TABLE sylius_also_bought_product_synchronization (id UUID NOT NULL, start_date TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, end_date TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, number_of_orders INT NOT NULL, affected_products JSON NOT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN sylius_also_bought_product_synchronization.id IS \'(DC2Type:uuid)\'');
        $this->addSql('ALTER TABLE sylius_product ADD bought_together_products JSON NOT NULL');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE sylius_also_bought_product_synchronization');
        $this->addSql('ALTER TABLE sylius_product DROP bought_together_products');
    }

    protected function isPostgreSQL(): bool
    {
        return
            class_exists(PostgreSQLPlatform::class) &&
            is_a($this->connection->getDatabasePlatform(), PostgreSQLPlatform::class, true);
    }
}
