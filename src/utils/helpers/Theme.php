<?php


namespace utils\helpers;


use php\io\FileStream;

/**
 * Class Theme
 * @package utils\helpers
 * @packages helpers
 */
class Theme
{
    protected $vars = [];

    /**
     * @param $var
     * @param $value
     */
    public function setVar($var, $value)
    {
        $this->vars[$var] = (string)$value;
    }

    /**
     * @param $var
     */
    public function removeVar($var)
    {
        unset($this->vars[$var]);
    }

    /**
     * @param $var
     * @return mixed
     */
    public function getVar($var)
    {
        return $this->vars[$var];
    }

    /**
     * @param $path
     * @throws \php\io\IOException
     */
    public function save($path)
    {
        $json = json_encode($this->vars);

        $stream = new FileStream($path, "w");
        $stream->write($json);
        $stream->close();
    }

    /**
     * @param $path
     * @throws \php\io\IOException
     */
    public function load($path)
    {
        $stream = new FileStream($path);
        $json = $stream->readFully();
        $stream->close();

        $this->vars = json_decode($json, true);
    }

    /**
     * @param $path
     * @throws \php\io\IOException
     */
    public function generateToStyle($path)
    {
        $output = "* {\n%s\n}";

        $lines = [];

        foreach ($this->vars as $var => $value) {
            $lines[] = sprintf("    %s: %s;", $var, $value);
        }

        $css = sprintf($output, implode("\n", $lines));

        $stream = new FileStream($path, "w");
        $stream->write(trim($css));
        $stream->close();
    }

    /**
     * @param $target
     * @param $path
     */
    public static function applyTo($target, $path)
    {
        $target->addStylesheet(self::makeLocalPath($path));
    }

    /**
     * @param $target
     * @param $path
     */
    public static function removeFrom($target, $path)
    {
        $target->removeStylesheet(self::makeLocalPath($path));
    }

    private static function makeLocalPath($path): string
    {
        return 'file:///' . str_replace('\\', '/', $path);
    }

    /**
     * @param $target
     * @param $path
     */
    public static function reload($target, $path)
    {
        self::removeFrom($target, $path);
        self::applyTo($target, $path);
    }
}