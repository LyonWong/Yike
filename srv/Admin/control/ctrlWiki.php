<?php


namespace Admin;


use Core\unitFile;

class ctrlWiki extends ctrlSess
{
    use unitFile;
    public $scopeKey = '*';

    public function _DO_()
    {
        $path = parse_url($this->_URI_)['path'] ?? 'index.html';
        $res = $this->fileRead($path);
        echo $res;
    }
}