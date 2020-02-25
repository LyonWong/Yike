<?php
/**
 * use when parent class has been used unitInstance
 */

namespace Core;


trait unitInstance_
{
    /**
     * @param array ...$keys
     * @return self
     */
    protected static function _singleton(...$keys)
    {
        $key = __CLASS__.json_encode($keys);
        if (isset(self::$instances[$key]) && self::$instances[$key] instanceof self) {
            $inst = self::$instances[$key];
        } else {
            $inst =  new self(...$keys);
            self::$instances[$key] = $inst;
        }
        return $inst;
    }
}