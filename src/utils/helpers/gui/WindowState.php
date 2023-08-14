<?php


namespace utils\helpers\gui;


use php\util\Configuration;
use php\gui\{UXForm, UXScreen};

/**
 * Class WindowState
 * @package utils\helpers\gui
 * @packages helpers
 */
class WindowState
{
    /**
     * Загрзука парраметров формы
     * @param \php\gui\UXForm $form
     * @param \php\util\Configuration $config
     */
    public static function load(UXForm $form, Configuration $config, $changeLayoutSize = false)
    {
        $form->maximized = $config->getBoolean("maximized", false);
        if ($form->maximized === true) return;

        $form->width = $config->get("width", 600);
        $form->height = $config->get("height", 400);


        $form->x = $config->get("x", 0);
        $form->y = $config->get("y", 0);


        self::screenDetect($form, $config);
    }

    public static function screenDetect(UXForm $form, Configuration $config)
    {
        $screens = UXScreen::getScreens();

        if (count($screens) == 1) {
            /*if ($form->x > $screens[0]->bounds["width"]) {
                $form->x = $config->getInteger("x") - $screens[0]->bounds["width"];
            }

            if ($form->y > $screens[0]->bounds["height"]) {
                $form->y = $config->getInteger("y") - $screens[0]->bounds["height"];
            }

            if ($form->x < $screens[0]->bounds["x"]) {
                $form->x = $config->getInteger("x") + $screens[0]->bounds["width"];
            }

            if ($form->y < $screens[0]->bounds["x"]) {
                $form->y = $config->getInteger("y") + $screens[0]->bounds["height"];
            }*/

            $found = null;
            foreach (UXScreen::getScreens() as $screen) {
                if (self::intersect($form, new ScreenDTO($screen->bounds))) {
                    $found = $screen;
                    break;
                }
            }

            if (!($found instanceof UXScreen)) {
                $form->centerOnScreen();
            }
        } else {
            return UXScreen::getPrimary();
        }

        return $found;
    }

    /**
     * Сохранение парраметров формы
     * @param \php\gui\UXForm $form
     * @param \php\util\Configuration $config
     */
    public static function save(UXForm $form, Configuration $config)
    {
        $form->observer("width")->addListener(function ($o, $n) use ($config, $form) {
            if ($form->maximized) return;
            if ($n - $o > 100 || $n - $o < -100) return;
            $config->set("width", $n);
        });
        $form->observer("height")->addListener(function ($o, $n) use ($config, $form) {
            if ($form->maximized) return;
            if ($n - $o > 100 || $n - $o < -100) return;
            $config->set("height", $n);
        });
        $form->observer("x")->addListener(function ($o, $n) use ($config, $form) {
            if ($form->maximized) return;
            if ($n - $o > 100 || $n - $o < -100) return;
            $config->set("x", $n);
        });
        $form->observer("y")->addListener(function ($o, $n) use ($config, $form) {
            if ($form->maximized) return;
            if ($n - $o > 100 || $n - $o < -100) return;
            $config->set("y", $n);
        });
        $form->observer("maximized")->addListener(function ($o, $n) use ($config, $form) {
            $config->set("maximized", $n);

            if (!$n) WindowState::load($form, $config, true);
        });
    }


    private static function hasPoint($what, $x, $y)
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
    private static function intersect($one, $two, $type = 'RECTANGLE')
    {
        if ($one instanceof Instances) {
            foreach ($one->getInstances() as $instance) {
                if (self::intersect($instance, $two, $type)) {
                    return true;
                }
            }

            return false;
        }

        if ($two instanceof Instances) {
            foreach ($two->getInstances() as $instance) {
                if (self::intersect($one, $instance, $type)) {
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
}