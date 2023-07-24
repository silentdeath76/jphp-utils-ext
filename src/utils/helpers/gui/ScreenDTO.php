<?php


namespace utils\helpers\gui;


class ScreenDTO
{
    public $x;
    public $y;
    public $width;
    public $height;

    public function __construct($array)
    {
        $this->x = $array["x"];
        $this->y = $array["y"];
        $this->width = $array["width"];
        $this->height = $array["height"];
    }
}