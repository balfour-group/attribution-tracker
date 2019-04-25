<?php

namespace Balfour\AttributionTracker;

interface ChannelInterface
{
    /**
     * @return string
     */
    public function getCategory();

    /**
     * @return string
     */
    public function getLabel();

    /**
     * @return string
     */
    public function getFullName();
}
