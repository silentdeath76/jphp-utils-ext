<?php

namespace utils\helpers\gui;

use php\gui\framework\Instances;
use php\gui\UXGeometry;

/**
 * Class GeometryWrap
 * @package utils\helpers\gui
 * @packages helpers
 */
class GeometryWrap
{
    /**
     * @param $what
     * @param $x
     * @param $y
     * @return bool
     */
    static function hasPoint($what, $x, $y)
    {
        list($ax, $ay) = [$what->x, $what->y];
        list($aw, $ah) = [$what->width, $what->height];

        if ($x >= $ax &&         // right of the left edge AND
            $x <= $ax + $aw &&    // left of the right edge AND
            $y >= $ay &&         // below the top AND
            $y <= $ay + $ah) {    // above the bottom
            return true;
        }

        return false;
    }

    /**
     * @param object $one
     * @param object $two
     * @param string $type
     * @return bool
     */
    static function intersect($one, $two, $type = 'RECTANGLE')
    {
        if ($one instanceof Instances) {
            foreach ($one->getInstances() as $instance) {
                if (GeometryWrap::intersect($instance, $two, $type)) {
                    return true;
                }
            }

            return false;
        }

        if ($two instanceof Instances) {
            foreach ($two->getInstances() as $instance) {
                if (GeometryWrap::intersect($one, $instance, $type)) {
                    return true;
                }
            }

            return false;
        }

        list($x, $y) = [$one->x, $one->y];
        list($w, $h) = [$one->width, $one->height];

        $nx = $two->x;
        $ny = $two->y;

        $nw = $two->width;
        $nh = $two->height;

        switch ($type) {
            case 'RECTANGLE':
            default:
                $nCenter = [$nx + round($nw / 2), $ny + round($nh / 2)];
                $center = [$x + round($w / 2), $y + round($h / 2)];

                $_w = abs($center[0] - $nCenter[0]);
                $_h = abs($center[1] - $nCenter[1]);

                $checkW = $_w < ($w / 2 + $nw / 2);
                $checkH = $_h < ($h / 2 + $nh / 2);

                return ($checkW && $checkH);
        }

    }

    /**
     * @param float $x1
     * @param float $y1
     * @param float $x2
     * @param float $y2
     * @return float
     */
    static function distance($x1, $y1, $x2, $y2)
    {
        UXGeometry::distance($x1, $y1, $x2, $y2);
    }
}