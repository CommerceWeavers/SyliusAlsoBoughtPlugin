<?php

declare(strict_types=1);

namespace CommerceWeavers\SyliusAlsoBoughtPlugin\Form\Extension;

use Sylius\Bundle\ChannelBundle\Form\Type\ChannelType;
use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;

final class ChannelTypeExtension extends AbstractTypeExtension
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('numberOfSynchronisedProducts', NumberType::class, [
                'label' => 'commerce_weavers_sylius_also_bought.form.channel.number_of_synchronised_products',
                'required' => true,
            ])
        ;
    }

    public static function getExtendedTypes(): iterable
    {
        yield ChannelType::class;
    }
}
