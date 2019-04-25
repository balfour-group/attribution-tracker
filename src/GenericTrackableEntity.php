<?php

namespace Balfour\AttributionTracker;

class GenericTrackableEntity implements TrackableEntityInterface
{
    /**
     * @var TrackingMeta
     */
    protected $tracking;

    /**
     * @param TrackingMeta $tracking
     */
    public function __construct(TrackingMeta $tracking)
    {
        $this->tracking = $tracking;
    }

    /**
     * @return TrackingMeta
     */
    public function getTrackingMeta()
    {
        return $this->tracking;
    }
}
