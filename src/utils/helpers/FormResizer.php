<?php

namespace utils\helpers;


use php\gui\{event\UXEvent, event\UXMouseEvent, shape\UXRectangle, UXForm, UXImage, UXImageArea, UXNode};
use php\io\MemoryStream;

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


        $this->create();
    }

    /**
     * @param \php\gui\UXNode $node
     */
    public function setTitleOutput(UXNode $node)
    {
        $node->text = $this->form->title;
    }

    private function create()
    {
        $form = $this->form;
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

        $robot = new Robot();

        $mouseDownEvent = function (UXEvent $e) use ($robot, $form) {
            $this->clickPos = [$e->x, $e->y];
            $this->screenClickPos = $robot->position;
            $this->formSize = [$form->width, $form->height];
        };

        $left = self::createPoint($mouseDownEvent, [$width, $form->height], [0, 0], self::$debug);
        $left->cursor = "H_RESIZE";
        $left->anchors = ['left' => 0, 'top' => 0, 'bottom' => 0];
        $left->on("mouseDrag", function (UXMouseEvent $e) use ($robot, $form) {
            $form->x = $robot->position[0] - $this->clickPos[0];
            $form->width = $this->formSize[0] + ($this->screenClickPos[0] - $robot->position[0]);
        }, "FormResizer");

        $form->add($left);


        $top = self::createPoint($mouseDownEvent, [$form->width, $height], [0, 0], self::$debug);
        $top->cursor = "V_RESIZE";
        $top->anchors = ['right' => 0, 'left' => 0, 'top' => 0];
        $top->on("mouseDrag", function (UXMouseEvent $e) use ($robot, $form) {
            $form->y = $robot->position[1] - $this->clickPos[1];
            $form->height = $this->formSize[1] + ($this->screenClickPos[1] - $robot->position[1]);
        }, "FormResizer");

        $form->add($top);


        $right = self::createPoint($mouseDownEvent, [$width, $form->height], [$form->width - $width, 0], self::$debug);
        $right->cursor = "H_RESIZE";
        $right->anchors = ['right' => 0, 'top' => 0, 'bottom' => 0];
        $right->on("mouseDrag", function (UXMouseEvent $e) use ($robot, $form) {
            $form->width = $this->formSize[0] - ($this->screenClickPos[0] - $robot->position[0]);
        }, "FormResizer");

        $form->add($right);


        $down = self::createPoint($mouseDownEvent, [$this->width, $height], [$form->width - $width, $form->height - $height], self::$debug);
        $down->cursor = "V_RESIZE";
        $down->anchors = ['left' => 0, 'right' => 0, 'bottom' => 0];
        $down->on("mouseDrag", function (UXMouseEvent $e) use ($robot, $form) {
            $form->height = $this->formSize[1] - ($this->screenClickPos[1] - $robot->position[1]);
        }, "FormResizer");
        $down->on("click", function () use ($form, $width, $height) {
            var_dump($form->width - $width, $form->height - $height);
        });

        $form->add($down);


        $leftTop = self::createPoint($mouseDownEvent, [$width, $height], [0, 0], self::$debug);
        $leftTop->cursor = "NW_RESIZE";
        $leftTop->anchors = ['left' => 0, 'top' => 0];

        $leftTop->on("mouseDrag", function (UXMouseEvent $e) use ($robot, $form) {
            $form->x = $robot->position[0] - $this->clickPos[0];
            $form->width = $this->formSize[0] + ($this->screenClickPos[0] - $robot->position[0]);

            $form->y = $robot->position[1] - ($this->clickPos[1]);
            $form->height = $this->formSize[1] + ($this->screenClickPos[1] - $robot->position[1]);
        }, "FormResizer");

        $form->add($leftTop);


        $topRight = self::createPoint($mouseDownEvent, [$width, $height], [$form->width, 0], self::$debug);
        $topRight->cursor = "NE_RESIZE";
        $topRight->anchors = ['top' => 0, 'right' => 0];

        $topRight->on("mouseDrag", function (UXMouseEvent $e) use ($robot, $form) {
            $form->y = $robot->position[1] - $this->clickPos[1];
            $form->width = $this->formSize[0] - ($this->screenClickPos[0] - $robot->position[0]);
            $form->height = $this->formSize[1] + ($this->screenClickPos[1] - $robot->position[1]);
        }, "FormResizer");

        $form->add($topRight);


        $rightDown = self::createPoint($mouseDownEvent, [$width, $height], [$form->width, $form->height], self::$debug);
        $rightDown->cursor = "NW_RESIZE";
        $rightDown->anchors = ['right' => 0, 'bottom' => 0];
        $rightDown->on("mouseDrag", function (UXMouseEvent $e) use ($robot, $form) {
            $form->width = $this->formSize[0] - ($this->screenClickPos[0] - $robot->position[0]);
            $form->height = $this->formSize[1] - ($this->screenClickPos[1] - $robot->position[1]);
        }, "FormResizer");

        $form->add($rightDown);


        $leftDown = self::createPoint($mouseDownEvent, [$width, $height], [0, $form->height], self::$debug);
        $leftDown->cursor = "NE_RESIZE";
        $leftDown->anchors = ['left' => 0, 'bottom' => 0];
        $leftDown->on("mouseDrag", function () use ($robot, $form) {
            $form->x = $robot->position[0] - $this->clickPos[0];
            $form->width = $this->formSize[0] + ($this->screenClickPos[0] - $robot->position[0]);
            $form->height = $this->formSize[1] - ($this->screenClickPos[1] - $robot->position[1]);
        }, "FormResizer");

        $form->add($leftDown);


        $this->items = [$leftTop, $top, $topRight, $right, $rightDown, $down, $leftDown, $left];
    }

    public function enable()
    {
        if ($this->disabled) {
            $this->create();

            $this->disabled = false;
        }
    }

    public function disable()
    {
        if (!$this->dissabed) {
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
        $point->on("mouseDown", $callback, "FormResizer");

        return $point;
    }

    /**
     * @param int $width
     * @param int $height
     */
    public function minSize($width, $height)
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