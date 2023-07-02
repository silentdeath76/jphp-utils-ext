<?php


namespace utils\helpers\gui;


use php\gui\{UXContextMenu, UXImage, UXImageView, UXLabel, UXLabelEx, UXMenu, UXMenuBar, UXMenuItem, UXNode};
use php\lang\IllegalArgumentException;
use php\util\Configuration;

class ContextMenuHelper
{
    const GRAPHIC_WIDTH = 'graphic.width';
    const GRAPHIC_HEIGHT = 'graphic.height';

    /**
     * @var UXContextMenu
     */
    private $target;

    /**
     * @var Configuration
     */
    private $config;

    /**
     * @param $target
     * @param \php\util\Configuration|null $config
     * @return ContextMenuHelper
     * @throws \php\lang\IllegalArgumentException
     */
    public static function of($target, Configuration $config = null)
    {
        if (!($target instanceof UXContextMenu || $target instanceof UXMenu || $target instanceof UXMenuBar)) {
            throw new IllegalArgumentException('Args most be php\gui\UXContextMenu or php\gui\UXMenu ot php\gui\UXMenuBar, now $target = ' . get_class($target));
        }

        return new self($target, $config);
    }

    /**
     * ContextMenuHelper constructor.
     * @param $target
     * @param $config
     */
    private function __construct($target, $config)
    {
        $this->target = $target;
        $this->config = $config;
    }

    /**
     * @param $text
     * @param $callback
     * @param \php\gui\UXNode|null $graphic
     * @return UXMenuItem
     */
    public function addItem($text, $callback, UXNode $graphic = null)
    {
        $this->target->items->add($node = new UXMenuItem());
        $this->setGraphic($node, $label = new UXLabelEx($text));
        $this->setGraphic($label, $graphic);
        $label->autoSize = true;
        $node->on('action', $callback);

        return $node;
    }

    /**
     * @param $text
     * @param callable|null $callback
     * @param \php\gui\UXNode|null $graphic
     * @return ContextMenuHelper
     * @throws \php\lang\IllegalArgumentException
     */
    public function addCategory($text, callable $callback = null, UXNode $graphic = null)
    {
        $param = 'items';

        if ($this->target instanceof UXMenuBar) {
            $param = 'menus';
        }

        $this->target->{$param}->add($node = new UXMenu());
        $this->setGraphic($node, $label = new UXLabelEx($text));
        $label->autoSize = true;
        $this->setGraphic($label, $graphic); // ??

        if ($callback !== null) {
            $label->on("click", $callback);
        }

        return ContextMenuHelper::of($node, $this->config);
    }

    public function getTarget()
    {
        return $this->target;
    }

    public function addSeparator()
    {
        $this->target->items->add(UXMenu::createSeparator());
    }

    /**
     * @param $file
     * @return UXImageView
     */
    public function makeIcon($file)
    {
        return new UXImageView(new UXImage($file));
    }


    private function setGraphic($node, $graphic)
    {
        if ($graphic !== null) {
            $node->graphic = $graphic;
        } else {
            $image = base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABAQMAAAAl21bKAAAAA1BMVEX/TQBcNTh/AAAAAXRSTlMAQObYZgAAAApJREFUeJxjYgAAAAYAAzY3fKgAAAAASUVORK5CYII=');
            $imageTransparent = new \php\io\MemoryStream();
            $imageTransparent->write($image);
            $imageTransparent->seek(0);
            $node->graphic = $this->makeIcon($imageTransparent);
        }

        if ($this->config instanceof Configuration) $this->configurate($node);
    }

    /**
     * @param $node
     */
    private function configurate($node)
    {
        if ($node->graphic === null) return;

        if ($this->config->has(ContextMenuHelper::GRAPHIC_WIDTH)) {
            $node->graphic->width = $this->config->get(ContextMenuHelper::GRAPHIC_WIDTH);
        }

        if ($this->config->has(ContextMenuHelper::GRAPHIC_HEIGHT)) {
            $node->graphic->height = $this->config->get(ContextMenuHelper::GRAPHIC_HEIGHT);
        }
    }
}