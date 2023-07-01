<?php


namespace utils\helpers\gui;


use php\gui\layout\UXPane;

abstract class AbstractNode implements ICustomNode
{

    /**
     * @var \php\gui\layout\UXPane
     */
    protected $container;

    /**
     * AbstractNode constructor.
     */
    public function __construct()
    {
        $this->make();
    }

    /**
     * @return UXPane
     */
    public function getNode()
    {
        return $this->container;
    }

    abstract protected function make();
}