<?php


namespace utils\helpers\gui;

use php\gui\layout\UXPane;

/**
 * Interface ICustomNode
 * @package utils\helpers\gui
 * @packages helpers
 */
interface ICustomNode
{
    /**
     * @return UXPane
     */
    public function getNode();
}