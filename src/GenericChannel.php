<?php

namespace Balfour\AttributionTracker;

class GenericChannel implements ChannelInterface
{
    /**
     * @var string
     */
    protected $category;

    /**
     * @var string
     */
    protected $label;

    /**
     * @param string $category
     * @param string $label
     */
    public function __construct($category, $label)
    {
        $this->category = $category;
        $this->label = $label;
    }

    /**
     * @return string
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * @return string
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * @return string
     */
    public function getFullName()
    {
        return $this->category . ' > ' . $this->label;
    }
}
