<?php

declare(strict_types=1);

namespace Tests\CommerceWeavers\SyliusAlsoBoughtPlugin\Behat\Context\Cli;

use Behat\Behat\Context\Context;
use CommerceWeavers\SyliusAlsoBoughtPlugin\Entity\BroughtTogetherProductsAwareInterface;
use Sylius\Component\Core\Formatter\StringInflector;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Tester\CommandTester;
use Symfony\Component\HttpKernel\KernelInterface;
use Webmozart\Assert\Assert;

final class SynchronizeBoughtTogetherProductsContext implements Context
{
    private const COMMAND_NAME = 'sylius:also-bought:synchronize';

    private Application $application;

    private ?CommandTester $commandTester = null;

    public function __construct(KernelInterface $kernel)
    {
        $this->application = new Application($kernel);
    }

    /**
     * @When I synchronize bought together products by running command
     */
    public function runSynchronizeBoughtTogetherProductsCommand(): void
    {
        $this->commandTester = new CommandTester($this->application->find(self::COMMAND_NAME));
        $this->commandTester->execute(['command' => self::COMMAND_NAME]);
    }

    /**
     * @Then I should be informed that the bought together products are synchronized
     */
    public function shouldBeInformedThatBoughtTogetherProductsAreSynchronized(): void
    {
        Assert::endsWith(
            $this->commandTester->getDisplay(),
            "Synchronization of bought together products finished successfully.\n",
        );

        Assert::same($this->commandTester->getStatusCode(), Command::SUCCESS);
    }

    /**
     * @Then I should be informed that bought together association type not found
     */
    public function shouldBeInformedThatBoughtTogetherAssociationTypeNotFound(): void
    {
        Assert::endsWith(
            $this->commandTester->getDisplay(),
            "Bought together association type not found.\n",
        );

        Assert::same($this->commandTester->getStatusCode(), Command::FAILURE);
    }

    /**
     * @Then the :product product should have usually been bought with :productName1 and :productName2
     * @Then the :product product should have usually been bought with :productName1, :productName2, :productName3 and :productName4
     * @Then the :product product should have usually been bought with :productName1, :productName2, :productName3, :productName4 and :productName5
     */
    public function productShouldHaveUsuallyBeenBoughtWith(BroughtTogetherProductsAwareInterface $product, string ...$productNames): void
    {
        $expectedProductCodes = array_map(
            fn (string $productName) => StringInflector::nameToUppercaseCode($productName),
            $productNames,
        );

        $groupedBoughtTogetherProducts = [];

        foreach ($product->getBoughtTogetherProducts() as $productCode => $weight) {
            $groupedBoughtTogetherProducts[$weight][] = $productCode;
        }

        krsort($groupedBoughtTogetherProducts);

        foreach ($groupedBoughtTogetherProducts as $productCodesWithSameWeight) {
            $expectedProductCodesWithSameWeight = array_slice($expectedProductCodes, 0, count($productCodesWithSameWeight));
            $expectedProductCodes = array_slice($expectedProductCodes, count($productCodesWithSameWeight));

            Assert::count(
                array_intersect($productCodesWithSameWeight, $expectedProductCodesWithSameWeight),
                count($expectedProductCodesWithSameWeight)
            );
        }
    }
}
