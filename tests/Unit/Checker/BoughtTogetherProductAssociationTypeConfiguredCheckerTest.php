<?php

declare(strict_types=1);

namespace Tests\CommerceWeavers\SyliusAlsoBoughtPlugin\Unit\Checker;

use CommerceWeavers\SyliusAlsoBoughtPlugin\Checker\BoughtTogetherProductAssociationTypeConfiguredChecker;
use CommerceWeavers\SyliusAlsoBoughtPlugin\Entity\BoughtTogetherProductsAwareInterface;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;
use Sylius\Component\Product\Model\ProductAssociationTypeInterface;
use Sylius\Component\Product\Repository\ProductAssociationTypeRepositoryInterface;

final class BoughtTogetherProductAssociationTypeConfiguredCheckerTest extends TestCase
{
    use ProphecyTrait;

    /** @dataProvider isConfiguredDataProvider */
    public function testItChecksIfBoughtTogetherProductAssociationTypeIsConfigured(bool $isConfigured): void
    {
        $productAssociationTypeRepository = $this->prophesize(ProductAssociationTypeRepositoryInterface::class);
        $productAssociationTypeRepository
            ->findOneBy(['code' => BoughtTogetherProductsAwareInterface::BOUGHT_TOGETHER_ASSOCIATION_TYPE_CODE])
            ->willReturn(
                $isConfigured
                    ? $this->prophesize(ProductAssociationTypeInterface::class)->reveal()
                    : null
            )
            ->shouldBeCalledOnce();

        $checker = new BoughtTogetherProductAssociationTypeConfiguredChecker($productAssociationTypeRepository->reveal());

        self::assertSame($isConfigured, $checker->isConfigured());
    }

    public static function isConfiguredDataProvider(): array
    {
        return [
            'association type is configured' => [
                'isConfigured' => true,
            ],
            'association type is not configured' => [
                'isConfigured' => false,
            ],
        ];
    }
}
