<?php

declare(strict_types=1);

namespace CommerceWeavers\SyliusAlsoBoughtPlugin\Sylius\ChannelBundle\Collector;

use Doctrine\ORM\AbstractQuery;
use Doctrine\ORM\EntityManagerInterface;
use Sylius\Component\Channel\Context\ChannelContextInterface;
use Sylius\Component\Channel\Context\ChannelNotFoundException;
use Sylius\Component\Channel\Model\ChannelInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\DataCollector\DataCollector;
use Webmozart\Assert\Assert;

/**
 * @todo Remove this class when Sylius 1.13 is released.
 *
 * @see https://github.com/Sylius/Sylius/blob/1.13/src/Sylius/Bundle/ChannelBundle/Collector/ChannelCollector.php
 */
final class ChannelCollector extends DataCollector
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private ChannelContextInterface $channelContext,
        private bool $channelChangeSupport = false,
    ) {
    }

    public function getChannel(): ?array
    {
        $channel = $this->data['channel'];
        Assert::nullOrIsArray($channel);

        return $channel;
    }

    /** @return iterable|ChannelInterface[] */
    public function getChannels(): iterable
    {
        $channels = $this->data['channels'];
        Assert::isIterable($channels);

        return $channels;
    }

    public function isChannelChangeSupported(): bool
    {
        return (bool) $this->data['channel_change_support'];
    }

    public function collect(Request $request, Response $response, \Throwable $exception = null): void
    {
        try {
            $channel = $this->pluckChannel($this->channelContext->getChannel());
        } catch (ChannelNotFoundException) {
            $channel = null;
        }

        $this->data = [
            'channel' => $channel,
            'channels' => $this->findAllChannelsWithBasicData(),
            'channel_change_support' => $this->channelChangeSupport,
        ];
    }

    public function reset(): void
    {
        $this->data['channel'] = null;
    }

    public function getName(): string
    {
        return 'sylius.channel_collector';
    }

    private function pluckChannel(ChannelInterface $channel): array
    {
        return [
            'name' => $channel->getName(),
            'hostname' => $channel->getHostname(),
            'code' => $channel->getCode(),
        ];
    }

    private function findAllChannelsWithBasicData(): array
    {
        return (array) $this->entityManager
            ->createQueryBuilder()
            ->select(['o.code', 'o.name', 'o.hostname'])
            ->from(ChannelInterface::class, 'o')
            ->getQuery()
            ->getResult(AbstractQuery::HYDRATE_ARRAY)
        ;
    }
}
