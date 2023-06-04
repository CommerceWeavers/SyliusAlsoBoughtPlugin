<?php

declare(strict_types=1);

namespace CommerceWeaversSyliusAlsoBoughtMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20230604164559 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Setup the database for the plugin';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE sylius_also_bought_product_synchronization (id INT AUTO_INCREMENT NOT NULL, start_date DATETIME NOT NULL, end_date DATETIME DEFAULT NULL, number_of_orders INT NOT NULL, affected_products JSON NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET UTF8 COLLATE `UTF8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE sylius_product ADD bought_together_products JSON NOT NULL DEFAULT (JSON_OBJECT())');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE sylius_also_bought_product_synchronization');
        $this->addSql('ALTER TABLE sylius_product DROP bought_together_products');
    }
}
