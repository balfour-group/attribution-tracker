<?php

namespace Balfour\AttributionTracker;

use stdClass;

class TrackingMeta
{
    /**
     * @var $data;
     */
    protected $data;

    /**
     * @param stdClass $data
     */
    public function __construct(stdClass $data = null)
    {
        $this->data = $data === null ? new stdClass() : $data;
    }

    /**
     * @param string $prop
     * @param mixed $default
     * @return mixed
     */
    protected function getTrackingMetaProp($prop, $default = null)
    {
        return isset($this->data->{$prop}) ? $this->data->{$prop} : $default;
    }

    /**
     * @return string
     */
    public function getHTTPReferer()
    {
        return $this->getTrackingMetaProp('http_referer');
    }

    /**
     * @return string
     */
    public function getUTMSource()
    {
        return $this->getTrackingMetaProp('utm_source');
    }

    /**
     * @return string
     */
    public function getUTMMedium()
    {
        return $this->getTrackingMetaProp('utm_medium');
    }

    /**
     * @return string
     */
    public function getUTMCampaign()
    {
        return $this->getTrackingMetaProp('utm_campaign');
    }

    /**
     * @return string
     */
    public function getUTMTerm()
    {
        return $this->getTrackingMetaProp('utm_term');
    }

    /**
     * @return string
     */
    public function getUTMContent()
    {
        return $this->getTrackingMetaProp('utm_content');
    }

    /**
     * @return string
     */
    public function getGclid()
    {
        return $this->getTrackingMetaProp('gclid');
    }

    /**
     * @return bool
     */
    public function isGoogleAdsCampaign()
    {
        return (bool) $this->getTrackingMetaProp('is_google_ads_campaign', false);
    }

    /**
     * @return string
     */
    public function getKeyword()
    {
        return $this->getTrackingMetaProp('keyword');
    }

    /**
     * @return string
     */
    public function getMatchType()
    {
        return $this->getTrackingMetaProp('matchtype');
    }
}
