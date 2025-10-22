<?php

declare(strict_types=1);

namespace CommerceWeavers\SyliusAlsoBoughtPlugin\Api\Normalizer;

use CommerceWeavers\SyliusAlsoBoughtPlugin\Doctrine\Query\GetAssociationTypeCodeByAssociationIdQueryInterface;
use CommerceWeavers\SyliusAlsoBoughtPlugin\Exception\ProductAssociationTypeNotFoundException;
use Sylius\Bundle\ApiBundle\Converter\IriToIdentifierConverterInterface;
use Symfony\Component\Serializer\Normalizer\ContextAwareNormalizerInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerAwareInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

final class ProductNormalizer implements ContextAwareNormalizerInterface, NormalizerAwareInterface
{
    /** @param ContextAwareNormalizerInterface&NormalizerAwareInterface $decoratedNormalizer */
    public function __construct(
        private NormalizerInterface $decoratedNormalizer,
        private GetAssociationTypeCodeByAssociationIdQueryInterface $getAssociationTypeCodeByAssociationIdQuery,
        private IriToIdentifierConverterInterface $iriToIdentifierConverter,
    ) {
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
            $associationTypeCode = $this->getAssociationTypeCodeByAssociationIdQuery->get((int) $id);

            if (null === $associationTypeCode) {
                throw new ProductAssociationTypeNotFoundException((int) $id);
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
