<?php


namespace utils\helpers\gui;


use action\GeometryWrap;
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

    public static function screenDetect(UXForm $form, Configuration $config = null)
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
                if (GeometryWrap::intersect($form, new ScreenDTO($screen->bounds))) {
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
}