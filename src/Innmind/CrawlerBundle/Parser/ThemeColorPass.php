<?php

namespace Innmind\CrawlerBundle\Parser;

use Innmind\CrawlerBundle\Event\ResourceEvent;
use Innmind\CrawlerBundle\Entity\HtmlPage;
use Symfony\Component\DomCrawler\Crawler;

/**
 * Extract the theme color from the page if possible
 */
class ThemeColorPass
{
    const SHORT_HEX_PATTERN = '/^#[0-9a-fA-F]{3}$/';
    const LONG_HEX_PATTERN = '/^#[0-9a-fA-F]{6}$/';
    const RGB_PATTERN = '/^rgb\((?P<red>\d{1,3}), ?(?P<green>\d{1,3}), ?(?P<blue>\d{1,3})\)$/';
    const HSL_PATTERN = '/^hsl\((?P<hue>\d{1,3}), ?(?P<sat>\d{1,3})%, ?(?P<lit>\d{1,3})%\)$/';

    /**
     * Process the crawled resource
     *
     * @param ResourceEvent $event
     */

    public function handle(ResourceEvent $event)
    {
        $resource = $event->getResource();

        if (!($resource instanceof HtmlPage)) {
            return;
        }

        $dom = $event->getDOM();
        $color = $dom->filter('meta[name="theme-color"][content]');

        if ($color->count() === 1) {
            $color = $color->attr('content');

            if (preg_match(self::SHORT_HEX_PATTERN, $color)) {
                $parts = str_split(substr($color, 1));
                foreach ($parts as &$part) {
                    $part = hexdec($part.$part);
                }
                list($red, $green, $blue) = $parts;
            } else if (preg_match(self::LONG_HEX_PATTERN, $color)) {
                $parts = str_split(substr($color, 1), 2);
                foreach ($parts as &$part) {
                    $part = hexdec($part);
                }
                list($red, $green, $blue) = $parts;
            } else if (preg_match(self::RGB_PATTERN, $color, $rgb)) {
                $red = $rgb['red'];
                $green = $rgb['green'];
                $blue = $rgb['blue'];
            } else if (preg_match(self::HSL_PATTERN, $color, $hsl)) {
                $resource->setThemeColor(
                    $hsl['hue'],
                    $hsl['sat'],
                    $hsl['lit']
                );
            }

            if (isset($red) && isset($green) && isset($blue)) {
                list($hue, $sat, $lit) = $this->convertRgbToHsl(
                    $red,
                    $green,
                    $blue
                );

                $resource->setThemeColor($hue, $sat, $lit);
            }
        }
    }

    /**
     * Convert a rgb code to a valid hsl components
     *
     * @param int $red
     * @param int $green
     * @param int $blue
     *
     * @return array
     */

    protected function convertRgbToHsl($red, $green, $blue)
    {
        $red = (int) $red;
        $green = (int) $green;
        $blue = (int) $blue;
        $red /= 255;
        $green /= 255;
        $blue /= 255;

        $max = max($red, $green, $blue);
        $min = min($red, $green, $blue);
        $lit = ($max + $min) / 2;

        if ($max === $min) {
            $hue = $sat = 0;
        } else {
            $diff = $max - $min;
            $sat = $lit > 0.5 ?
                $diff / (2 - $max - $min) :
                $diff / ($max + $min);

            switch ($max) {
                case $red:
                    $hue = ($green - $blue) / $diff + ($green < $blue ? 6 : 0);
                    break;

                case $green:
                    $hue = ($blue - $red) / $diff + 2;
                    break;

                case $blue:
                    $hue = ($red - $green) / $diff + 4;
                    break;
            }

            $hue /= 6;
        }

        $hue = $hue * 100 + 0.5;
        $sat = $sat * 100 + 0.5;
        $lit = $lit * 100 + 0.5;

        return [
            $hue ? $hue : 0,
            $sat ? $sat : 0,
            $lit ? $lit : 0,
        ];
    }
}
