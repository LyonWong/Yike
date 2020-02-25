<?php


namespace Admin;

use Core\unitDoAction;

class ctrlSess extends \_\ctrlSess
{
    use unitDoAction;

    protected $uid;

    protected $flag;

    protected $scopeKey;

    protected $platform;

    public function __construct()
    {
        parent::__construct();
        $this->flag = base_convert(crc32($_SERVER['HTTP_USER_AGENT']), 10, 32);
    }

    public function runBefore()
    {
        $pres = parent::runBefore();

        servSession::$scopeKey = $this->scopeKey ?: str_replace('/','-', trim($this->_WAY_, '/'));
        servSession::$access = servAccess::inst($this->uid, servAccess::SCOPE_ADMIN)->assign('admin');
        servSession::$access->checkView(servSession::$scopeKey);


        return $pres;
    }
}