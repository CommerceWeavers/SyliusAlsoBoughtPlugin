<?php

declare(strict_types=1);

namespace Tests\CommerceWeavers\SyliusAlsoBoughtPlugin\Application\Entity;

use CommerceWeavers\SyliusAlsoBoughtPlugin\Entity\BoughtTogetherConfigurableChannelInterface;
use CommerceWeavers\SyliusAlsoBoughtPlugin\Entity\BoughtTogetherConfigurableChannelTrait;
use Doctrine\ORM\Mapping as ORM;
use Sylius\Component\Core\Model\Channel as BaseChannel;

#[ORM\Entity]
#[ORM\Table(name: 'sylius_channel')]
class Channel extends BaseChannel implements BoughtTogetherConfigurableChannelInterface
{
    use BoughtTogetherConfigurableChannelTrait;
}
