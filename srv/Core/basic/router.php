<?php

/**
 * Class router
 *
 * Usage
 * -----
 * router::attr('lang','en|zh');
 * router::attt('key','\w+');
 * router::rewrite('/{lang}/{key}/','/');
 */
class router
{
    const STOP = 0;
    const GOON = 1;
    const REDO = -1;

    private static $rules = [];

    private static $attrs = [];

    public static function request($URI)
    {
        $_router = PATH_SPACE . '/router.php';
        if (is_file($_router)) {
            include_once $_router;
        }
        $path = parse_url($URI)['path'];
        if (preg_match('#^(.*/[-\w]+)(\.\w+)?$#', $path, $matches)) {
            $WAY = $matches[1];
            $EXT = ltrim($matches[2]??'', '.');
        } else {
            $WAY = $path;
            $EXT = null;
        }

        $attr = self::walk($WAY);
        $attr['URI'] = $URI;
        $attr['WAY'] = $WAY;
        $attr['EXT'] = $EXT;

        $res = self::parse($WAY);
        $res['attr'] = $attr;
        return $res;
    }

    public static function attr($name, $pattern)
    {
        self::$attrs[$name] = $pattern;
    }

    public static function rewrite($origin, $reform, $then = self::STOP)
    {
        self::$rules[] = [
            'origin' => $origin,
            'reform' => $reform,
            'then' => $then,
        ];
        return true;
    }

    /**
     * ִ��·�ɹ���
     * @param $WAY
     * @return array
     * @throws coreException
     */
    public static function walk(&$WAY)
    {
        $attrs = [];
        foreach (self::$rules as $i => $rule) {
            $pattern = self::makePattern($rule['origin']);
            $reform = $rule['reform'];
            if (preg_match($pattern, $WAY, $matches)) {
                if (is_callable($reform)) {
                    $_attrs = $reform($WAY, $matches);
                    $attrs = array_merge($attrs, $_attrs);
                } else {
                    foreach ($matches as $k => $v) {
                        if (!is_int($k)) {
                            $attrs[$k] = $v;
                            $reform = str_replace('{' . $k . '}', $v, $reform);
                        }
                    }
                    $WAY = preg_replace($pattern, $reform, $WAY);
                }
                switch ($rule['then']) {
                    case self::STOP:
                        return $attrs;
                    case self::GOON:
                        break;
                    case self::REDO:
                        unset (self::$rules[$i]);
                        $_attrs = self::walk($WAY);
                        $attrs = array_merge($attrs, $_attrs);
                        return $attrs;
                    default:
                        throw new coreException("Undefind router rewrite rule `then-$rule[then]`");
                }
            }
        }
        return $attrs;
    }

    /**
     * @param $WAY
     * @return array|bool
     */
    public static function parse(&$WAY)
    {
        $WAY = preg_replace('#/+#', '/', $WAY);
        $frags = explode('/', $WAY);
        $args = explode('-', array_pop($frags));
        $NS = '\\' . SPACE . implode('\\', $frags);
        $ctrl = "$NS\\ctrl" . ucfirst($args[0]);
        unset($args[0]);
        return [
            'ctrl' => $ctrl,
            'args' => array_values($args),
        ];
    }

    private static function makePattern($format)
    {
        foreach (self::$attrs as $name => $pattern) {
            $format = str_replace('{' . $name . '}', "(?<$name>$pattern)", $format);
        }
        $format = preg_replace('#{(\w+)}#', '(?<$1>.*)', $format);
        return "#^$format#";
    }

}
