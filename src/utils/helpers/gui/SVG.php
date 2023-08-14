<?php

namespace utils\helpers;


use Exception;
use php\gui\UXCanvas;
use php\io\Stream;
use php\util\Regex;
use php\xml\XmlProcessor;

/**
 * Class SVG
 * @package utils\helpers\gui
 * @packages helpers
 */
class SVG
{
    public $scale;

    /**
     * @param $path
     * @param int $scale
     * @return SVG
     * @throws \php\format\ProcessorException
     * @throws \php\io\IOException
     * @throws \php\util\RegexException
     */
    public static function of($path, $scale = 1)
    {
        // похоже что идет обращение к URL в доктайпе изза чего ОЧЕНЬ долно выполняется код, по этому удаляем его
        $doc = Regex::of('(<!DOCTYPE[\w\r\n\/\\\.\":\- \s]+>)')->with(Stream::of($path))->replace('');

        $svg = (new XmlProcessor())->parse($doc);

        if ($svg->find("/svg/path") == null) {
            throw new Exception("Runtime exeption", -100);
        }

        $size = explode(" ", $svg->find('/svg')->get("@viewBox"));

        return new self([
            "width" => (int)$size[2],
            "height" => (int)$size[3],
            "path" => $svg->find("/svg/path")->get("@d"),
        ], $scale);

    }

    private function __construct($data, $scale)
    {
        // Logger::debug("Size: w=" . ($data["width"] * $scale) . " h=" . ($data["height"] * $scale));
        $this->data = $data;
        $this->scale = $scale;
    }


    /**
     * @param \php\gui\UXCanvas $target
     * @param string $color
     * @return UXCanvas
     * @throws \php\util\RegexException
     */
    public function apply(UXCanvas $target, $color = 'green')
    {
        // безсмысленная вещь которую надо удалить. ну а смысл умножать -1 на $scale?
        if ($this->data["width"] !== -1) {
            $target->width = $this->data["width"] * $this->scale;
            $target->height = $this->data["height"] * $this->scale;
        }


        $target->gc()->clearRect(0, 0, 9999, 9999);

        $target->gc()->fillColor = $color;
        $target->gc()->beginPath();
        $target->gc()->appendSVGPath($this->scale($this->data["path"], $this->scale));
        $target->gc()->closePath();
        $target->gc()->fill();

        return $target;
    }

    /**
     * @param $width
     * @param $height
     * @param bool $resizeContainer
     * @return SVG
     * @throws \php\util\RegexException
     */
    public function resize($width, $height, $resizeContainer = true)
    {
        $this->data["path"] = Regex::of('([\d.]+)')->with($this->data["path"])
            ->replaceWithCallback(function (Regex $regex) use ($width, $height) {
                static $i = 1;

                /*
                $int =  $regex->group(1);
                
                if ($i++ % 2 == 0) {
                    return $int * ($width / $this->data["width"]);
                } else {
                    return $int * ($height / $this->data["height"]);
                }
                */

                return $regex->group(1) * (($i++ % 2 == 1) ? ($width / $this->data["width"]) : ($height / $this->data["height"]));
            });

        if ($resizeContainer) {
            $this->data["width"] = $width;
            $this->data["height"] = $height;
        } else {
            $this->data["width"] = -1;
            $this->data["height"] = -1;
        }

        return $this;
    }

    /**
     * @param $svg
     * @param $scale
     * @return mixed|string
     * @throws \php\util\RegexException
     */
    private function scale($svg, $scale)
    {
        if ($scale === 1) return $svg;

        return Regex::of('([\d.]+)')->with($svg)
            ->replaceWithCallback(function (Regex $regex) use ($scale) {
                return $regex->group(1) * $scale;
            });
    }
}