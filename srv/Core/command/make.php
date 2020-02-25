<?php
require __DIR__ . '/../bootstrap.php';

const BOOT_MODE = BOOT_MODE_CLI;

$space = \input::cli('space')->value(true);
$target = \input::cli(-1)->value();
boot::init($space);

switch ($target) {
    case 'conf':
        config::make();
        break;
    case 'vmap':
        $path = PATH_SPACE . '/view/assets';
        $vmap = makeVmap($path);
        file_put_contents("$path/.vmap.php", "<?php return " . var_export($vmap, true) . ";");
        file_put_contents("$path/.vmap.json", json_encode($vmap));
        break;
    case 'docs':
        include PATH_ROOT.'/library/tools/Parsedown.php';
        $path = PATH_ROOT.'/document/Framework';
        $res = makeDocs($path);
        print_r($res);
        break;
    default:
        echo "Undefined make target `$target`\n";
}

function makeVmap($path)
{
    static $wraps;
    if (empty($wraps)) {
        $wrap = config::load('option', 'vmapwrap', null, [], SPACE);
        foreach ($wrap as $_wrap => $depth) {
            $_path = "$path/$_wrap".str_repeat('/*', $depth);
            foreach (glob($_path) as $_item) {
                //get last modify time recursively
                $wraps[$_item] = base_convert(recursiveModifyTime($_item), 10, 32);
            }
        }
    }
    $map = [];
    foreach (glob("$path/*") as $item) {
        if (is_dir($item)) {
            $dir = basename($item);
            if (isset($wraps[$item])) {
                $map[$dir] = $wraps[$item];
            } else {
                foreach (makeVmap($item) as $n => $v) {
                    $map["$dir/$n"] = $v;
                }
            }
        } else {
            $file = basename($item);
            $map[$file] = base_convert(filemtime($item), 10, 32);
        }
    }
    return $map;
}

function makeDocs($path)
{
    $docs = [];
    $parseDown = new Parsedown();
    foreach (glob($path."/*") as $_path)  {
        $_pathinfo = pathinfo($_path);
        if (is_dir($_path)) {
            $_docs = makeDocs($_path);
            $docs = array_merge($docs, $_docs);
        } else {
            $text = file_get_contents($_path);
            $docs[$_path] = $parseDown->text($text);
        }
    }
    return $docs;
}

function recursiveModifyTime($path)
{
    $time = filemtime($path);
    if (is_dir($path)) {
        foreach (glob("$path/*") as $_path) {
            $time = max($time, recursiveModifyTime($_path));
        }
    }
    return $time;
}
