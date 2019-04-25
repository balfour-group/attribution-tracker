<?php

namespace Balfour\AttributionTracker\Rules;

use Balfour\AttributionTracker\ChannelInterface;
use Balfour\AttributionTracker\TrackableEntityInterface;

interface RuleInterface
{
    /**
     * @param TrackableEntityInterface $entity
     * @return bool
     */
    public function matches(TrackableEntityInterface $entity);

    /**
     * @return ChannelInterface
     */
    public function getChannel();

    /**
     * @return int
     */
    public function getPrecedence();

    /**
     * @return string
     */
    public function getDescription();

    /**
     * @return string
     */
    public function getHTMLDescription();
}
