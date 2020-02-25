<?php

namespace Core;

trait unitDoAction
{
    public function run($action='', ...$args)
    {
        $method = \input::method();
        $actionMethod = '_'.$method.'_'.$action;
        $defaultMethod = '_DO_'.$action;
        if (method_exists($this, $actionMethod)) {
            return $this->$actionMethod(...$args);
        } elseif (method_exists($this, $defaultMethod)) {
            return $this->$defaultMethod(...$args);
        } else {
            throw new \coreException("No method for action `$action`");
        }
    }
}