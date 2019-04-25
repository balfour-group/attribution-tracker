<?php

namespace Balfour\AttributionTracker\Rules;

use Balfour\AttributionTracker\TrackableEntityInterface;

class GoogleAdsRule extends BaseRule
{
    /**
     * @param TrackableEntityInterface $entity
     * @return bool
     */
    public function matches(TrackableEntityInterface $entity)
    {
        return $entity->getTrackingMeta()->isGoogleAdsCampaign();
    }

    /**
     * @return int
     */
    public function getPrecedence()
    {
        return 9000;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return 'url contains a "gclid" query string parameter';
    }

    /**
     * @return string
     */
    public function getHTMLDescription()
    {
        return 'url contains a <code>gclid</code> query string parameter';
    }
}
