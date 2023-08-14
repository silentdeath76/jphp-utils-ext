<?php

namespace utils\helpers;


use php\gui\{event\UXEvent, event\UXMouseEvent, shape\UXRectangle, UXForm, UXImage, UXImageArea, UXNode};
use php\io\MemoryStream;

/**
 * Class FormResizer
 * @package utils\helpers\gui
 * @packages helpers
 */
class FormResizer
{
    public static $debug = false;
    public $disabled;

    private static $imageGreen;
    private static $imageTransparent;

    private $clickPos = [];
    private $screenClickPos = [];

    private $items = [];

    /**
     * @var UXForm
     */
    private $form;

    /**
     * @var array
     */
    private $formSize;

    /**
     * @param \php\gui\UXForm $form
     * @param array $config
     * @return \utils\helpers\FormResizer
     * @throws \php\io\IOException
     */
    public static function init(UXForm $form, $config = [])
    {
        $image = base64_decode('iVBORw0KGgoAAAANSUhEUgAAACMAAAAmCAIAAADBdrW9AAAAAXNSR0IArs4c6QAAAARnQU1BAACxjwv8YQUAAAAJcEhZcwAADsMAAA7DAcdvqGQAAAA2SURBVFhH7c1BDQAgDAAxtKBnZucQEU14XdJ/z935o0k0iSbRJJpEk2gSTaJJNIkm0SR+TTsPTRjTWhuFg8MAAAAASUVORK5CYII=');
        self::$imageGreen = new MemoryStream();
        self::$imageGreen->write($image);
        self::$imageGreen->seek(0);

        $image = base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABAQMAAAAl21bKAAAAA1BMVEX/TQBcNTh/AAAAAXRSTlMAQObYZgAAAApJREFUeJxjYgAAAAYAAzY3fKgAAAAASUVORK5CYII=');
        self::$imageTransparent = new MemoryStream();
        self::$imageTransparent->write($image);
        self::$imageTransparent->seek(0);

        return new self($form, $config);
    }


    /**
     * FormResizer constructor.
     * @param \php\gui\UXForm $form
     * @param array $config
     */
    public function __construct(UXForm $form, array $config)
    {
        $this->form = $form;
        $this->config = $config;

        $this->listener = $form->observer("maximized")->addListener(function ($o, $n) {
            // if new value is TRUE then called method "disable" else "enable"
            $this->{($n) ? "disable" : "enable"}();
        });


        $this->createControls();
    }

    /**
     * @param \php\gui\UXNode $node
     */
    public function setTitleOutput(UXNode $node)
    {
        $node->text = $this->form->title;
    }

    /**
     * @throws \php\lang\IllegalStateException
     */
    private function createControls()
    {
        /**
         * cursors
         * N_RESIZE, S_RESIZE, V_RESIZE - top-down
         * E_RESIZE, H_RESIZE, W_RESIZE - left-right
         *
         * NE_RESIZE, SW_RESIZE - top-right, down-left
         * NW_RESIZE, SE_RESIZE - left-top, down-right
         */

        $width = $this->config["width"] ?: 7;
        $height = $this->config["height"] ?: 7;


        $mouseDownEvent = function (UXEvent $e) {
            $this->clickPos = [$e->x, $e->y];

            $x = $this->form->x + $e->x;
            $y = $this->form->y + $e->y;

            $this->screenClickPos = [$x, $y];
            $this->formSize = [$this->form->width, $this->form->height];
        };

        // bottom left top
        $left = self::createPoint($mouseDownEvent, [$width, $this->form->height], [0, 0], self::$debug);
        $left->cursor = "H_RESIZE";
        $left->anchors = ['left' => 0, 'top' => 0, 'bottom' => 0];
        $left->on("mouseDrag", function (UXMouseEvent $e) {
            $x = $e->screenX;

            $this->form->x = $x - $this->clickPos[0];
            $this->form->width = $this->formSize[0] + ($this->screenClickPos[0] - $x);
        }, __CLASS__);

        $this->form->add($left);

        // left top right
        $top = self::createPoint($mouseDownEvent, [$this->form->width, $height], [0, 0], self::$debug);
        $top->cursor = "V_RESIZE";
        $top->anchors = ['right' => 0, 'left' => 0, 'top' => 0];
        $top->on("mouseDrag", function (UXMouseEvent $e) {
            $y = $e->screenY;

            $this->form->y = $y - $this->clickPos[1];
            $this->form->height = $this->formSize[1] + ($this->screenClickPos[1] - $y);
        }, __CLASS__);

        $this->form->add($top);

        // top right bottom
        $right = self::createPoint($mouseDownEvent, [$width, $this->form->height], [$this->form->width - $width, 0], self::$debug);
        $right->cursor = "H_RESIZE";
        $right->anchors = ['right' => 0, 'top' => 0, 'bottom' => 0];
        $right->on("mouseDrag", function (UXMouseEvent $e) {
            $x = $e->screenX - $this->formSize[0];

            $this->form->width = $this->formSize[0] - ($this->screenClickPos[0] - $x);
        }, __CLASS__);

        $this->form->add($right);

        // right bottom left
        $down = self::createPoint($mouseDownEvent, [$this->form->width, $height], [$this->form->width - $width, $this->form->height - $height], self::$debug);
        $down->cursor = "V_RESIZE";
        $down->anchors = ['left' => 0, 'right' => 0, 'bottom' => 0];
        $down->on("mouseDrag", function (UXMouseEvent $e) {
            $y = $e->screenY - $this->formSize[1];

            $this->form->height = $this->formSize[1] - ($this->screenClickPos[1] - $y);
        }, __CLASS__);

        $this->form->add($down);

        // left top
        $leftTop = self::createPoint($mouseDownEvent, [$width, $height], [0, 0], self::$debug);
        $leftTop->cursor = "NW_RESIZE";
        $leftTop->anchors = ['left' => 0, 'top' => 0];

        $leftTop->on("mouseDrag", function (UXMouseEvent $e) {
            $x = $e->screenX;
            $y = $e->screenY;

            $this->form->x = $x - $this->clickPos[0];
            $this->form->width = $this->formSize[0] + ($this->screenClickPos[0] - $x);

            $this->form->y = $y - ($this->clickPos[1]);
            $this->form->height = $this->formSize[1] + ($this->screenClickPos[1] - $y);
        }, __CLASS__);

        $this->form->add($leftTop);

        // top right
        $topRight = self::createPoint($mouseDownEvent, [$width, $height], [$this->form->width, 0], self::$debug);
        $topRight->cursor = "NE_RESIZE";
        $topRight->anchors = ['top' => 0, 'right' => 0];

        $topRight->on("mouseDrag", function (UXMouseEvent $e) {
            $x = $e->screenX - $this->formSize[0];
            $y = $e->screenY;

            $this->form->y = $y - $this->clickPos[1];
            $this->form->width = $this->formSize[0] - ($this->screenClickPos[0] - $x);
            $this->form->height = $this->formSize[1] + ($this->screenClickPos[1] - $y);
        }, __CLASS__);

        $this->form->add($topRight);

        // right bottom
        $rightDown = self::createPoint($mouseDownEvent, [$width, $height], [$this->form->width, $this->form->height], self::$debug);
        $rightDown->cursor = "SE_RESIZE";
        $rightDown->anchors = ['right' => 0, 'bottom' => 0];
        $rightDown->on("mouseDrag", function (UXMouseEvent $e) {
            $x = $e->screenX - $this->formSize[0];
            $y = $e->screenY - $this->formSize[1];

            $this->form->width = $this->formSize[0] - ($this->screenClickPos[0] - $x);
            $this->form->height = $this->formSize[1] - ($this->screenClickPos[1] - $y);
        }, __CLASS__);

        $this->form->add($rightDown);

        // bottom left
        $leftDown = self::createPoint($mouseDownEvent, [$width, $height], [0, $this->form->height], self::$debug);
        $leftDown->cursor = "SW_RESIZE";
        $leftDown->anchors = ['left' => 0, 'bottom' => 0];
        $leftDown->on("mouseDrag", function (UXMouseEvent $e) {
            $x = $e->screenX;
            $y = $e->screenY - $this->formSize[1];

            $this->form->x = $x - $this->clickPos[0];
            $this->form->width = $this->formSize[0] + ($this->screenClickPos[0] - $x);
            $this->form->height = $this->formSize[1] - ($this->screenClickPos[1] - $y);
        }, __CLASS__);

        $this->form->add($leftDown);


        $this->items = [$leftTop, $top, $topRight, $right, $rightDown, $down, $leftDown, $left];
    }

    /**
     * @throws \php\lang\IllegalStateException
     */
    public function enable()
    {
        if ($this->disabled) {
            $this->createControls();

            $this->disabled = false;
        }
    }

    public function disable()
    {
        if (!$this->disabled) {
            foreach ($this->items as $item) {
                $item->free();
            }

            $this->disabled = true;
        }
    }

    /**
     * @param callable $callback
     * @param array $size
     * @param array $position
     * @param bool $color
     * @return \php\gui\UXImageArea
     */
    private static function createPoint(callable $callback, array $size, array $position, $color = false)
    {
        self::$imageGreen->seek(0);
        self::$imageTransparent->seek(0);

        $point = new UXImageArea(new UXImage(($color === true) ? self::$imageGreen : self::$imageTransparent));
        $point->mosaic = true;
        $point->width = ($size["width"]) ?: $size[0];
        $point->height = ($size["height"]) ?: $size[1];
        $point->x = ($position["x"]) ?: $position[0];
        $point->y = ($position["y"]) ?: $position[1];
        $point->on("mouseDown", $callback, __CLASS__);

        return $point;
    }

    /**
     * @param int $width
     * @param int $height
     */
    public function minSize(int $width, int $height)
    {
        $this->form->observer("width")->addListener(function ($val) use ($width) {
            if ($this->form->width < $width) {
                $this->form->width = $width;
            }
        });

        $this->form->observer("height")->addListener(function ($val) use ($height) {
            if ($this->form->height < $height) {
                $this->form->height = $height;
            }
        });
    }
}
