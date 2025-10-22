<?php

declare(strict_types=1);

namespace Tests\CommerceWeavers\SyliusAlsoBoughtPlugin\Unit\Api\Normalizer;

use CommerceWeavers\SyliusAlsoBoughtPlugin\Api\Normalizer\ProductNormalizer;
use CommerceWeavers\SyliusAlsoBoughtPlugin\Doctrine\Query\GetAssociationTypeCodeByAssociationIdQueryInterface;
use CommerceWeavers\SyliusAlsoBoughtPlugin\Exception\ProductAssociationTypeNotFoundException;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;
use Sylius\Bundle\ApiBundle\Converter\IriToIdentifierConverterInterface;
use Sylius\Component\Core\Model\Product;
use Symfony\Component\Serializer\Normalizer\ContextAwareNormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerAwareInterface;

final class ProductNormalizerTest extends TestCase
{
    use ProphecyTrait;

    public function testItAddsAssociationsTypesToProductResponse(): void
    {
        $baseNormalizer = $this->prophesize(ProductNormalizerInterface::class);
        $query = $this->prophesize(GetAssociationTypeCodeByAssociationIdQueryInterface::class);
        $iriToIdentifierConverter = $this->prophesize(IriToIdentifierConverterInterface::class);

        $normalizer = new ProductNormalizer(
            $baseNormalizer->reveal(),
            $query->reveal(),
            $iriToIdentifierConverter->reveal(),
        );

        $product = $this->prophesize(Product::class);
        $baseNormalizer->normalize($product->reveal(), null, [])->willReturn([
            'code' => 'product_code',
            'associations' => [
                '/api/v2/product-associations/1',
                '/api/v2/product-associations/2',
            ],
        ]);

        $iriToIdentifierConverter->getIdentifier('/api/v2/product-associations/1')->willReturn('1');
        $query->get(1)->willReturn('first_association_type');
        $iriToIdentifierConverter->getIdentifier('/api/v2/product-associations/2')->willReturn('2');
        $query->get(2)->willReturn('second_association_type');

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

    public function testItThrowsExceptionWhenAssociationTypeCodeIsNull(): void
    {
        $baseNormalizer = $this->prophesize(ProductNormalizerInterface::class);
        $query = $this->prophesize(GetAssociationTypeCodeByAssociationIdQueryInterface::class);
        $iriToIdentifierConverter = $this->prophesize(IriToIdentifierConverterInterface::class);

        $normalizer = new ProductNormalizer(
            $baseNormalizer->reveal(),
            $query->reveal(),
            $iriToIdentifierConverter->reveal(),
        );

        $product = $this->prophesize(Product::class);
        $baseNormalizer->normalize($product->reveal(), null, [])->willReturn([
            'code' => 'product_code',
            'associations' => [
                '/api/v2/product-associations/1',
            ],
        ]);

        $iriToIdentifierConverter->getIdentifier('/api/v2/product-associations/1')->willReturn('1');
        $query->get(1)->willReturn(null);

        $this->expectException(ProductAssociationTypeNotFoundException::class);
        $this->expectExceptionMessage('Product association type not found for association with id "1".');

        $normalizer->normalize($product->reveal());
    }
}

interface ProductNormalizerInterface extends ContextAwareNormalizerInterface, NormalizerAwareInterface
{
}
