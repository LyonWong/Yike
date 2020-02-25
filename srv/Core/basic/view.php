<?php

class view
{

    public static function tpl($path, $attrs = [])
    {
        return new Template($path, $attrs);
    }

    public static function upload($path, $version='')
    {
        $prefix = config::load('boot', 'public', 'upload', '/assets/upload');
        if (!$path) {
            return null;
        }
        return $version ? "$prefix/$path?$version" : "$prefix/$path";
    }

    public static function src($path, $space=SPACE)
    {
        static $vmap;
        $prefix = PATH_ROOT."/$space/view/assets";
        if (empty($vmap)) {
            $vmap = @include("$prefix/.vmap.php");
        }
        $v = '?';
        $vkey = $path;
        while (!in_array($vkey, ['.', '/', ''])) {
            if (isset($vmap[$vkey])) {
                $v .= $vmap[$vkey];
                break;
            }
            $vkey = dirname($vkey);
        }
        $assetsPrefix = config::load('boot', 'public', 'assets', '/assets', $space);
        return "$assetsPrefix/$path" . $v;
    }

    public static function js($paths, $min = false)
    {
        $paths = self::makePaths($paths);
        $html = "\n";
        foreach ($paths as $path) {
            $file = $min ? "$path.min.js" : "$path.js";
            $src = self::src($file);
            $html .= "\t<script type='text/javascript' src='$src'></script>\n";
        }
        return $html;
    }

    public static function css($paths, $min = false)
    {
        $paths = self::makePaths($paths);
        $html = "\n";
        foreach ($paths as $path) {
            $file = $min ? "$path.min.css" : "$path.css";
            $src = self::src($file);
            $html .= "\t<link href='$src' rel='stylesheet' type='text/css'/>\n";
        }
        return $html;
    }

    public static function ng($path)
    {
        $srcTpl = self::src("angular/template/$path.html");
        $srcCtl = self::src("angular/controller/$path.js");
        $html = "
        <div ng-include=\"'$srcTpl'\"></div>
        <script type='text/javascript' src='$srcCtl'></script>
        ";
        return $html;
    }

    private static function makePaths($paths)
    {
        if (is_array($paths)) {
            $ret = [];
            foreach ($paths as $pre => $sub) {
                if (is_int($pre)) {
                    $ret[] = $sub;
                } else {
                    foreach (self::makePaths($sub) as $item) {
                        $ret[] = "$pre/$item";
                    }
                }
            }
        } else {
            $ret = [$paths];
        }
        return $ret;
    }
}

class Template
{
    /**
     * @var string Current dirname
     */
    private $cdir;

    /**
     * @var string Template basename
     */
    private $base;

    /**
     * @var array Attributes data, will be extended by sub-template
     */
    private $attrs = [];

    private $isRendered = false;
    
    protected static $id = 0;

    public function __construct($path, $attrs = [])
    {
        $info = pathinfo($path);
        $this->cdir = $info['dirname'];
        $this->base = $info['basename'];
        $this->attrs = $attrs;
        self::$id ++;
    }

    public function __destruct()
    {
        if ($this->isRendered === false) {
            $this->render();
        }
    }

    public function render()
    {
        $tpl = $this->cdir . '/' . $this->base;
        if ($tplFile = realpath(PATH_SPACE . "/view/template/$tpl.php")) {
            require $tplFile;
        } else {
            throw new coreException("Can't find template `$tpl`.", 500);
        }
        $this->isRendered = true;
    }

    public function res()
    {
        ob_start();
        $this->render();
        $res = ob_get_contents();
        ob_end_clean();
        return $res;
    }

    public function tpl($path)
    {
        if ($path[0] != '/') {
            $path = $this->cdir . '/' . $path;
        }
        $fpath = $this->cdir . '/'. $this->base;
        if ($fpath == $path) { // refer self is forbidden
            throw new coreException("Template loop `$path`");
        } else {
            return new self($path, $this->attrs);
        }
    }

    public function with($name, $value, $escape = true)
    {
        if ($escape === true) {
            if (is_array($value)) {
                array_walk_recursive($value, function (&$item) {
                    $item = htmlspecialchars($item);
                });
            } else {
                $value = htmlspecialchars($value);
            }
        }
        $this->$name = $value;
        return $this;
    }

    public function attr($attrs)
    {
        $this->attrs = $attrs;
        return $this;
    }

    public function __set($key, $val)
    {
        $this->attrs[$key] = $val;
    }

    public function __get($key)
    {
        return isset($this->attrs[$key]) ? $this->attrs[$key] : null;
    }

}
