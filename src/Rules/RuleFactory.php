<?php

namespace Balfour\AttributionTracker\Rules;

use Balfour\AttributionTracker\ChannelInterface;
use stdClass;
use UnexpectedValueException;

class RuleFactory
{
    /**
     * @param ChannelInterface $channel
     * @param string $rule
     * @param stdClass|null $params
     * @return RuleInterface
     */
    public static function make(ChannelInterface $channel, $rule, stdClass $params = null)
    {
        switch ($rule) {
            case 'GOOGLE ADS':
                return new GoogleAdsRule($channel);
            case 'GOOGLE TRACKING':
                return new GoogleTrackingRule(
                    $channel,
                    $params['utm_source'] ?? null,
                    $params['utm_medium'] ?? null,
                    $params['utm_campaign'] ?? null,
                    $params['utm_term'] ?? null,
                    $params['utm_content'] ?? null
                );
            case 'HTTP REFERER':
                return new HttpRefererRule($channel, $params->domain, $params['path'] ?? null);
        }

        throw new UnexpectedValueException(sprintf('The rule %s is not supported.', $rule));
    }
}
