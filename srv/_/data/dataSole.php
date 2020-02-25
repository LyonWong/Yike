<?php


namespace _;


use Core\unitMysqlSole;

/**
 * @property \Core\library\Mysql|mixed mysql
 */
class dataSole extends data_
{
    use unitMysqlSole;

    public function __construct($platform)
    {
        parent::__construct($platform);
        $this->mysql = self::mysql();
    }

    /**
     * @return \Core\library\Mysql|mixed
     */
    public function _mysql_()
    {
        return $this->mysql;
    }

}