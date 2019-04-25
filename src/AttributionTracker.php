<?php

namespace Balfour\AttributionTracker;

use Balfour\AttributionTracker\Rules\RuleInterface;

class AttributionTracker
{
    /**
     * @var ChannelInterface
     */
    protected $defaultChannel;

    /**
     * @var array
     */
    protected $rules;

    /**
     * @param ChannelInterface $defaultChannel
     * @param array $rules
     */
    public function __construct(ChannelInterface $defaultChannel = null, array $rules = [])
    {
        $this->defaultChannel = $defaultChannel;
        $this->rules = $rules;
    }

    /**
     * @param ChannelInterface $channel
     */
    public function setDefaultChannel(ChannelInterface $channel)
    {
        $this->defaultChannel = $channel;
    }

    /**
     * @return ChannelInterface
     */
    public function getDefaultChannel()
    {
        return $this->defaultChannel;
    }

    /**
     * @param RuleInterface $rule
     */
    public function addRule(RuleInterface $rule)
    {
        $this->rules[] = $rule;
    }

    /**
     * @param array $rules
     */
    public function addRules(array $rules)
    {
        foreach ($rules as $rule) {
            /** @var RuleInterface $rule */
            $this->addRule($rule);
        }
    }

    /**
     * @return array
     */
    public function getRules()
    {
        return $this->rules;
    }

    /**
     * @return array
     */
    public function sortAndGetRules()
    {
        $this->sort();
        return $this->rules;
    }

    public function sort()
    {
        usort($this->rules, function (RuleInterface $a, RuleInterface $b) {
            $p1 = $a->getPrecedence();
            $p2 = $b->getPrecedence();

            if ($p1 == $p2) {
                return 0;
            }

            return $p1 < $p2 ? 1 : -1;
        });
    }

    /**
     * @param TrackableEntityInterface $entity
     * @return ChannelInterface
     */
    public function getChannel(TrackableEntityInterface $entity)
    {
        $this->sort();

        foreach ($this->rules as $rule) {
            /** @var RuleInterface $rule */
            if ($rule->matches($entity)) {
                return $rule->getChannel();
            }
        }

        return $this->defaultChannel;
    }
}
