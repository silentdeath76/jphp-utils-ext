<?php

namespace utils\helpers\traits;

trait Shortcutable
{
    public function bindShortcut($node, $callback, $keys)
    {
        $node->on('keyDown', function ($ev) use ($keys, $callback) {
            if ($ev->matches($keys)) $callback();
        }, $this->getGroup($keys));
    }

    public function unbindShortcut($node, $keys)
    {
        $node->off('keyDown', $this->getGroup($keys));
    }

    private function getGroup($pref): string
    {
        return __CLASS__ . '::' . $pref;
    }
}