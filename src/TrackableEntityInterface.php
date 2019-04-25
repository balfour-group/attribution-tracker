<?php

namespace Balfour\AttributionTracker;

interface TrackableEntityInterface
{
    /**
     * @return TrackingMeta
     */
    public function getTrackingMeta();
}
