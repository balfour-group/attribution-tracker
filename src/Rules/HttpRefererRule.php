<?php

namespace Balfour\AttributionTracker\Rules;

use Balfour\AttributionTracker\ChannelInterface;
use Balfour\AttributionTracker\TrackableEntityInterface;

class HttpRefererRule extends BaseRule
{
    /**
     * @var string
     */
    protected $domain;

    /**
     * @var string
     */
    protected $path;

    /**
     * @param ChannelInterface $channel
     * @param string $domain
     * @param string $path
     */
    public function __construct(ChannelInterface $channel, $domain, $path = null)
    {
        $this->domain = $domain;

        // normalise path
        if ($path) {
            if ($path[0] !== '/') {
                $path = '/' . $path;
            }
            $this->path = rtrim($path, '/');
        } else {
            $this->path = null;
        }

        parent::__construct($channel);
    }

    /**
     * @param TrackableEntityInterface $entity
     * @return bool
     */
    public function matches(TrackableEntityInterface $entity)
    {
        $parsed = parse_url($entity->getTrackingMeta()->getHTTPReferer());
        $host = $parsed['host'] ?? null;

        if (strcasecmp($this->domain, $host) !== 0) {
            return false;
        }

        if ($this->path) {
            $path = $parsed['path'] ?? null;
            $path = '/' . trim($path, '/');

            if (strcasecmp($this->path, $path) !== 0) {
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
        $score = 7000;

        if ($this->path) {
            $score += 1;
        }

        return $score;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        $str = sprintf('domain matches "%s"', $this->domain);
        if ($this->path) {
            $str .= sprintf(' and path matches "%s"', $this->path);
        }
        return $str;
    }

    /**
     * @return string
     */
    public function getHTMLDescription()
    {
        $str = sprintf(
            '<div><code>domain</code> matches <code>%s</code></div>',
            htmlspecialchars($this->domain, ENT_QUOTES, 'UTF-8', true)
        );
        if ($this->path) {
            $str .= sprintf(
                '<div><code>path</code> matches <code>%s</code></div>',
                htmlspecialchars($this->path, ENT_QUOTES, 'UTF-8', true)
            );
        }
        return $str;
    }
}
