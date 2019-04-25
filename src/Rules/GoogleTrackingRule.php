<?php

namespace Balfour\AttributionTracker\Rules;

use Balfour\AttributionTracker\ChannelInterface;
use Balfour\AttributionTracker\TrackableEntityInterface;

class GoogleTrackingRule extends BaseRule
{
    /**
     * @var string
     */
    protected $utmSource;

    /**
     * @var string
     */
    protected $utmMedium;

    /**
     * @var string
     */
    protected $utmCampaign;

    /**
     * @var string
     */
    protected $utmTerm;

    /**
     * @var string
     */
    protected $utmContent;

    /**
     * @param ChannelInterface $channel
     * @param string $utmSource
     * @param string $utmMedium
     * @param string $utmCampaign
     * @param string $utmTerm
     * @param string $utmContent
     */
    public function __construct(
        ChannelInterface $channel,
        $utmSource,
        $utmMedium = null,
        $utmCampaign = null,
        $utmTerm = null,
        $utmContent = null
    ) {
        $this->utmSource = $utmSource;
        $this->utmMedium = $utmMedium;
        $this->utmCampaign = $utmCampaign;
        $this->utmTerm = $utmTerm;
        $this->utmContent = $utmContent;

        parent::__construct($channel);
    }

    /**
     * @return array
     */
    protected function getParams()
    {
        return [
            $this->utmSource,
            $this->utmMedium,
            $this->utmCampaign,
            $this->utmTerm,
            $this->utmContent,
        ];
    }

    /**
     * @param TrackableEntityInterface $entity
     * @return bool
     */
    public function matches(TrackableEntityInterface $entity)
    {
        // compare expected to actual utm params
        $expected = $this->getParams();

        $meta = $entity->getTrackingMeta();
        $actual = [
            $meta->getUTMSource(),
            $meta->getUTMMedium(),
            $meta->getUTMCampaign(),
            $meta->getUTMTerm(),
            $meta->getUTMContent(),
        ];

        foreach ($expected as $k => $v) {
            if ($v === null) {
                continue; // doesn't matter what actual value is
            } elseif (substr($v, 0, 3) === 're:') {
                // we're matching actual against a regular expression
                $regex = substr($v, 3);
                if (!preg_match($regex, $actual[$k])) {
                    return false;
                }
            } elseif (strcasecmp($v, $actual[$k]) !== 0) {
                return false;
            }
        }

        return true;
    }

    /**
     * @return int
     */
    public function getPrecedence()
    {
        $score = 8000;
        $params = $this->getParams();
        $total = count($params);

        foreach ($params as $n => $v) {
            if ($v) {
                $score += pow($total - $n + 1, $total);
            }
        }

        return $score;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        $params = [
            'utm_source' => $this->utmSource,
            'utm_medium' => $this->utmMedium,
            'utm_campaign' => $this->utmCampaign,
            'utm_term' => $this->utmTerm,
            'utm_content' => $this->utmContent,
        ];

        $parts = [];
        foreach ($params as $label => $value) {
            if ($value === null) {
                $parts[] = sprintf('%s is anything', $label);
            } elseif (substr($value, 0, 3) === 're:') {
                $parts[] = sprintf('%s matches the expression "%s"', $label, $value);
            } else {
                $parts[] = sprintf('%s matches "%s"', $label, $value);
            }
        }

        $str = '';
        for ($x = 0; $x < count($parts); $x++) {
            if ($x === 0) {
                // first
                $str .= $parts[$x];
            } elseif ($x === count($parts) - 1) {
                // last
                $str .= ' & ' . $parts[$x];
            } else {
                $str .= ', ' . $parts[$x];
            }
        }

        return $str;
    }

    /**
     * @return string
     */
    public function getHTMLDescription()
    {
        $params = [
            'utm_source' => $this->utmSource,
            'utm_medium' => $this->utmMedium,
            'utm_campaign' => $this->utmCampaign,
            'utm_term' => $this->utmTerm,
            'utm_content' => $this->utmContent,
        ];

        $parts = [];
        foreach ($params as $label => $value) {
            if ($value === null) {
                $parts[] = sprintf(
                    '<code>%s</code> is <code>anything</code>',
                    htmlspecialchars($label, ENT_QUOTES, 'UTF-8', true)
                );
            } elseif (substr($value, 0, 3) === 're:') {
                $parts[] = sprintf(
                    '<code>%s</code> matches the expression <code>%s</code>',
                    htmlspecialchars($label, ENT_QUOTES, 'UTF-8', true),
                    htmlspecialchars($value, ENT_QUOTES, 'UTF-8', true)
                );
            } else {
                $parts[] = sprintf(
                    '<code>%s</code> matches <code>%s</code>',
                    htmlspecialchars($label, ENT_QUOTES, 'UTF-8', true),
                    htmlspecialchars($value, ENT_QUOTES, 'UTF-8', true)
                );
            }
        }

        $parts = array_map(function ($part) {
            return sprintf('<div>%s</div>', $part);
        }, $parts);

        return implode('', $parts);
    }
}
