<?php

namespace Balfour\AttributionTracker\Rules;

use Balfour\AttributionTracker\ChannelInterface;

abstract class BaseRule implements RuleInterface
{
    /**
     * @var ChannelInterface
     */
    protected $channel;

    /**
     * @param ChannelInterface $channel
     */
    public function __construct(ChannelInterface $channel)
    {
        $this->channel = $channel;
    }

    /**
     * @return ChannelInterface
     */
    public function getChannel()
    {
        return $this->channel;
    }
}
