<?php

namespace utils\helpers;


use Exception;
use php\lang\Thread;
use php\util\SharedValue;

/**
 * Class Mutex
 * @package utils\helpers
 * @packages helpers
 */
class Mutex
{
    /**
     * @var SharedValue
     */
    public $lock;

    public function __construct()
    {
        // create object for tracking lock state and set default "false" value
        $this->lock = new SharedValue(false);
    }

    public function synchronize(callable $callback)
    {
        $th = new Thread(function () use ($callback) {
            while ($this->isBusy()) {
                Thread::sleep(1);
            }

            $this->lock->set(true);

            try {
                $callback();
            } catch (Exception $ex) {
                echo "Error: " . $ex->getMessage();
            } finally {
                $this->lock->set(false);
            }
        });

        $th->setDaemon(true);
        $th->start();
    }

    public function isBusy()
    {
        return $this->lock->synchronize(function () {
            return $this->lock->getAndSet(function ($v) {
                if ($v === false) {
                    return true;
                }

                return $v;
            });
        });
    }

}