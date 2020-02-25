<?php

class config
{
    protected static $space = SPACE;
    
    protected static $path = PATH_ROOT . '/' . SPACE . '/config';

    protected static $tiers = ['basic', 'local'];

    private static $data = [];
    

    public static function load($file, $section = null, $item = null, $default = null, $space=SPACE)
    {
        //load file
        $dataKey = "$space|$file";
        if (!isset (self::$data[$dataKey])) {
            $cnf = self::read($file, $space);
            if ($cnf) {
                self::$data[$dataKey] = $cnf;
            } elseif ($default !== null) {
                return $default;
            } else {
                throw new coreException("Can't load config file `$file`");
            }
        }
        $config = self::$data[$dataKey];

        //load section
        if ($section !== null) {
            if (isset ($config[$section])) {
                $config = $config[$section];
            } elseif ($default !== null) {
                return $default;
            } else {
                throw new coreException("Can't load config section `$file->$section`");
            }
        }

        //load item
        if ($item !== null) {
            if (isset($config[$item])) {
                $config = $config[$item];
            } elseif ($default !== null) {
                return $default;
            } else {
                throw new coreException("Can't load config item `$file->$section->$item`");
            }
        }

        return $config;
    }

    public static function make($space = SPACE)
    {
        self::$path = PATH_ROOT . "/$space/config";
        $config = [];
        foreach (self::$tiers as $tier) {
            $_config = self::parse($tier);
            $config = arrayMergeForce($config, $_config);
        }
        self::write($config);
    }

    protected static function parse($tier)
    {
        $pattern = self::$path . '/' . $tier . '/*';
        $paths = glob($pattern);
        $config = [];
        foreach ($paths as $path) {
            $pathinfo = pathinfo($path);
            if (is_dir($path)) {
                $_config = self::parse("$tier/{$pathinfo['basename']}");
                $config = array_merge($config, $_config);
            } elseif ($pathinfo['extension'] == 'ini') {
                $module = strstr($tier, '/') . '/' . $pathinfo['filename'];
                $config[$module] = parse_ini_file($path, true);
            }
        }
        return $config;
    }


    private static function read($file, $space=SPACE)
    {
        $path = PATH_ROOT."/$space/config";
        $fileConf =  "$path/.conf/$file.php";
        if (is_file($fileConf)) {
            /** @noinspection PhpIncludeInspection */
            return require($fileConf);
        }
        $config = [];
        foreach (self::$tiers as $tier) {
            $fileIni = "$path/$tier/$file.ini";
            if (is_file($fileIni)) {
                $_config = parse_ini_file($fileIni, true);
                $config = arrayMergeForce($config, $_config);
            }
        }
        return $config;
    }

    private static function write($config)
    {
        $pathConf = self::$path . '/.conf';
        foreach ($config as $module => $data) {
            $fileConf = $pathConf . $module . '.php';
            $dirname = dirname($fileConf);
            if (!is_dir($dirname)) {
                mkdir($dirname, 0777, true);
            }
            $dataConf = "<?php\nreturn " . var_export($data, true) . ";\n";
            file_put_contents($fileConf, $dataConf);
        }
    }
}
