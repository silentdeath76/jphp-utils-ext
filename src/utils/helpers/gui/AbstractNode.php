<?php


namespace utils\helpers\gui;


use php\gui\layout\UXPane;

/**
 * Class AbstractNode
 * @package utils\helpers\gui
 * @packages helpers
 */
abstract class AbstractNode implements ICustomNode
{

    /**
     * @var UXPane
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