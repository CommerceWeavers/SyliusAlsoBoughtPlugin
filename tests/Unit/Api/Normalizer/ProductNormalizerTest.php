<?php

declare(strict_types=1);

namespace Tests\CommerceWeavers\SyliusAlsoBoughtPlugin\Unit\Api\Normalizer;

use ApiPlatform\Core\DataProvider\ItemDataProviderInterface;
use CommerceWeavers\SyliusAlsoBoughtPlugin\Api\Normalizer\ProductNormalizer;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;
use Sylius\Bundle\ApiBundle\Converter\IriToIdentifierConverterInterface;
use Sylius\Component\Core\Model\Product;
use Sylius\Component\Product\Model\ProductAssociation;
use Sylius\Component\Product\Model\ProductAssociationInterface;
use Sylius\Component\Product\Model\ProductAssociationTypeInterface;
use Symfony\Component\Serializer\Normalizer\ContextAwareNormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerAwareInterface;

final class ProductNormalizerTest extends TestCase
{
    use ProphecyTrait;

    private string $productAssociationClass = ProductAssociation::class;

    public function testItThrowsInvalidArgumentExceptionWhenProductAssociationClassDoesNotImplementProductAssociationInterface(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('The class "stdClass" must implement "Sylius\Component\Product\Model\ProductAssociationInterface".');

        new ProductNormalizer(
            $this->prophesize(ContextAwareNormalizerInterface::class)->reveal(),
            $this->prophesize(ItemDataProviderInterface::class)->reveal(),
            $this->prophesize(IriToIdentifierConverterInterface::class)->reveal(),
            \stdClass::class,
        );
    }

    public function testItAddsAssociationsTypesToProductResponse(): void
    {
        $baseNormalizer = $this->prophesize(ProductNormalizerInterface::class);
        $itemDataProvider = $this->prophesize(ItemDataProviderInterface::class);
        $iriToIdentifierConverter = $this->prophesize(IriToIdentifierConverterInterface::class);

        $normalizer = new ProductNormalizer(
            $baseNormalizer->reveal(),
            $itemDataProvider->reveal(),
            $iriToIdentifierConverter->reveal(),
            $this->productAssociationClass,
        );

        $product = $this->prophesize(Product::class);
        $baseNormalizer->normalize($product->reveal(), null, [])->willReturn([
            'code' => 'product_code',
            'associations' => [
                '/api/v2/product-associations/1',
                '/api/v2/product-associations/2',
            ],
        ]);

        $firstAssociation = $this->prophesize(ProductAssociationInterface::class);
        $firstAssociationType = $this->prophesize(ProductAssociationTypeInterface::class);
        $firstAssociation->getType()->willReturn($firstAssociationType);
        $firstAssociationType->getCode()->willReturn('first_association_type');

        $secondAssociation = $this->prophesize(ProductAssociationInterface::class);
        $secondAssociationType = $this->prophesize(ProductAssociationTypeInterface::class);
        $secondAssociation->getType()->willReturn($secondAssociationType);
        $secondAssociationType->getCode()->willReturn('second_association_type');

        $iriToIdentifierConverter->getIdentifier('/api/v2/product-associations/1')->willReturn('1');
        $itemDataProvider->getItem($this->productAssociationClass, '1')->willReturn($firstAssociation);
        $iriToIdentifierConverter->getIdentifier('/api/v2/product-associations/2')->willReturn('2');
        $itemDataProvider->getItem($this->productAssociationClass, '2')->willReturn($secondAssociation);

        self::assertSame(
            [
                'code' => 'product_code',
                'associations' => [
                    'first_association_type' => '/api/v2/product-associations/1',
                    'second_association_type' => '/api/v2/product-associations/2',
                ],
            ],
            $normalizer->normalize($product->reveal())
        );
    }
}

interface ProductNormalizerInterface extends ContextAwareNormalizerInterface, NormalizerAwareInterface
{
}
