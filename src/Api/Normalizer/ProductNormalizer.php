<?php

declare(strict_types=1);

namespace CommerceWeavers\SyliusAlsoBoughtPlugin\Api\Normalizer;

use ApiPlatform\Core\DataProvider\ItemDataProviderInterface;
use Sylius\Bundle\ApiBundle\Converter\IriToIdentifierConverterInterface;
use Sylius\Component\Product\Model\ProductAssociationInterface;
use Symfony\Component\Serializer\Normalizer\ContextAwareNormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerAwareInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

final class ProductNormalizer implements ContextAwareNormalizerInterface, NormalizerAwareInterface
{
    /** @param ContextAwareNormalizerInterface&NormalizerAwareInterface $decoratedNormalizer */
    public function __construct(
        private NormalizerInterface $decoratedNormalizer,
        private ItemDataProviderInterface $itemDataProvider,
        private IriToIdentifierConverterInterface $iriToIdentifierConverter,
        private string $productAssociationClass,
    ) {
        if (!is_a($productAssociationClass, ProductAssociationInterface::class, true)) {
            throw new \InvalidArgumentException(sprintf(
                'The class "%s" must implement "%s".',
                $productAssociationClass,
                ProductAssociationInterface::class,
            ));
        }
    }

    public function supportsNormalization(mixed $data, string $format = null, array $context = []): bool
    {
        return $this->decoratedNormalizer->supportsNormalization($data, $format, $context);
    }

    public function normalize($object, string $format = null, array $context = []): array
    {
        /** @var array $object */
        $object = $this->decoratedNormalizer->normalize($object, $format, $context);

        /** @var string[] $associations */
        $associations = $object['associations'];
        $object['associations'] = [];

        foreach ($associations as $association) {
            $id = $this->iriToIdentifierConverter->getIdentifier($association);
            /** @var ProductAssociationInterface $associationObject */
            $associationObject = $this->itemDataProvider->getItem($this->productAssociationClass, (string) $id);
            $associationTypeCode = $associationObject->getType()?->getCode();

            if (null === $associationTypeCode) {
                continue;
            }

            $object['associations'][$associationTypeCode] = $association;
        }

        return $object;
    }

    public function setNormalizer(NormalizerInterface $normalizer): void
    {
        $this->decoratedNormalizer->setNormalizer($normalizer);
    }
}
