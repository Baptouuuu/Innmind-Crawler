<?php

namespace Innmind\CrawlerBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;

/**
 * Representation of an image
 */

class Image extends Resource
{
    /**
     * Height
     * @var int
     */

    protected $height;

    /**
     * Width
     * @var int
     */

    protected $width;

    /**
     * File weight
     * @var int
     */

    protected $weight;

    /**
     * MIME type
     * @var string
     */

    protected $mime;

    /**
     * File extension associated to the mime type
     * @var string
     */

    protected $extension;

    /**
     * Exif data
     * @var ArrayCollection
     */

    protected $exif;

    public function __construct()
    {
        parent::__construct();

        $this->exif = new ArrayCollection;
    }

    /**
     * Set the image height
     *
     * @param int $height
     *
     * @return Image self
     */

    public function setHeight($height)
    {
        $this->height = (int) $height;

        return $this;
    }

    /**
     * Return the image height
     *
     * @return int
     */

    public function getHeight()
    {
        return $this->height;
    }

    /**
     * Set the image width
     *
     * @param int $width
     *
     * @return Image self
     */

    public function setWidth($width)
    {
        $this->width = (int) $width;

        return $this;
    }

    /**
     * Return the image width
     *
     * @return int
     */

    public function getWidth()
    {
        return $this->width;
    }

    /**
     * Set the image weight
     *
     * @param int $weight
     *
     * @return Image self
     */

    public function setWeight($weight)
    {
        $this->weight = (int) $weight;

        return $this;
    }

    /**
     * Return the image weight
     *
     * @return int
     */

    public function getWeight()
    {
        return $this->weight;
    }

    /**
     * Set the MIME type
     *
     * @param string $mime
     *
     * @return Image self
     */

    public function setMime($mime)
    {
        $this->mime = (string) $mime;

        return $this;
    }

    /**
     * Return the MIME type
     *
     * @return string
     */

    public function getMime()
    {
        return $this->mime;
    }

    /**
     * Set the extension
     *
     * @param string $extension
     *
     * @return Image self
     */

    public function setExtension($extension)
    {
        $this->extension = (string) $extension;

        return $this;
    }

    /**
     * Return the extension
     *
     * @return string
     */

    public function getExtension()
    {
        return $this->extension;
    }

    /**
     * Add an exif data
     *
     * @param string $key
     * @param string $value
     *
     * @return Image self
     */

    public function addExif($key, $value)
    {
        $this->exif->set((string) $key, (string) $value);

        return $this;
    }

    /**
     * Check if any exif data is set
     *
     * @return bool
     */

    public function hasExif()
    {
        return (bool) $this->exif->count();
    }

    /**
     * Return the exif collection
     *
     * @return ArrayCollection
     */

    public function getExif()
    {
        return $this->exif;
    }
}
