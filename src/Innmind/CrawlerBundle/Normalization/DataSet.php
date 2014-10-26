<?php

namespace Innmind\CrawlerBundle\Normalization;

/**
 * Small abstraction layer to set raw data
 */

class DataSet
{
    protected $data = [];

    /**
     * Set a new value
     *
     * @param string $key
     * @param mixed $value
     *
     * @return DataSet self
     */

    public function set($key, $value)
    {
        $this->data[$key] = $value;

        return $this;
    }

    /**
     * Return the data array
     *
     * @return array
     */

    public function getArray()
    {
        return $this->data;
    }
}
