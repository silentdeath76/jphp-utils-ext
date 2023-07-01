<?php


namespace utils\helpers\gui;

use php\gui\layout\UXPane;

interface ICustomNode
{
    /**
     * @return UXPane
     */
    public function getNode();
}