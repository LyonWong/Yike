<?php


namespace Core\library;

require PATH_ROOT . '/library/phpmailer/PHPMailerAutoload.php';
require PATH_ROOT . '/library/PhpImap/__autoload.php';

use PhpImap\Mailbox;

class Email
{
    /**
     * @param $index
     * @return mixed|\PHPMailer
     */
    public static function SMTP($index)
    {
        $inst = new \PHPMailer();
        $config = \config::load('email', $index);
        foreach ($config as $key => $val) {
            if (method_exists($inst, $key)) {
                $inst->$key(...$val);
            } else {
                $inst->$key = $val;
            }
        }
        return $inst;
    }

    /**
     * @param $index
     * @return mixed|Mailbox
     */
    public static function IMAP($index)
    {
        $config = \config::load('email', $index);
        $inst = new Mailbox($config['ImapPath'], $config['UserName'], $config['Password']);
        return $inst;
    }
}