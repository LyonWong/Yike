<?php


namespace Core;


trait unitFile
{
    public function fileWrite($name, $content, $flags=null)
    {
        if ($file = $this->filePath($name)) {
            return file_put_contents($file, $content, $flags);
        } else {
            return false;
        }
    }

    public function fileRead($name)
    {
        $file = $this->filePath($name);
        return file_get_contents($file);
    }

    public function fileCheck($name)
    {
        $file = $this->filePath($name);
        return file_exists($file) ? $file : false;
    }

    public function fileRemove($name)
    {
        $file = $this->filePath($name);
        return unlink($file);
    }

    public function filePath($name)
    {
        if ($prefix = realpath(PATH_SPACE.'/file')) {
            return "$prefix/$name";
        } else {
            return false;
        }

    }

}