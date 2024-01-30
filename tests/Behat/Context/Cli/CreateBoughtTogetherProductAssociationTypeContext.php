<?php

declare(strict_types=1);

namespace Tests\CommerceWeavers\SyliusAlsoBoughtPlugin\Behat\Context\Cli;

use Behat\Behat\Context\Context;
use CommerceWeavers\SyliusAlsoBoughtPlugin\Entity\BoughtTogetherProductsAwareInterface;
use Sylius\Component\Core\Formatter\StringInflector;
use Symfony\Bundle\FrameworkBundle\Console\Application;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Tester\CommandTester;
use Symfony\Component\HttpKernel\KernelInterface;
use Webmozart\Assert\Assert;

final class CreateBoughtTogetherProductAssociationTypeContext implements Context
{
    private const COMMAND_NAME = 'sylius:also-bought:setup';

    private Application $application;

    private ?CommandTester $commandTester = null;

    public function __construct(KernelInterface $kernel)
    {
        $this->application = new Application($kernel);
    }

    /**
     * @When I create a bought together product association type
     */
    public function runCreateBoughtTogetherProductAssociationTypeCommand(): void
    {
        $this->commandTester = new CommandTester($this->application->find(self::COMMAND_NAME));
        $this->commandTester->execute(['command' => self::COMMAND_NAME]);
    }

    /**
     * @Then I should be informed that the bought together product association type is created
     */
    public function shouldBeInformedThatBoughtTogetherProductAssociationTypeIsCreated(): void
    {
        Assert::contains(
            $this->commandTester->getDisplay(),
            "The \"bought together\" product association type created.\n",
        );

        Assert::same($this->commandTester->getStatusCode(), Command::SUCCESS);
    }

    /**
     * @Then I should be informed that the bought together product association type already exists
     */
    public function shouldBeInformedThatBoughtTogetherAssociationTypeNotFound(): void
    {
        Assert::contains(
            $this->commandTester->getDisplay(),
            "The \"bought together\" product association type already exists.\n",
        );

        Assert::same($this->commandTester->getStatusCode(), Command::SUCCESS);
    }
}
