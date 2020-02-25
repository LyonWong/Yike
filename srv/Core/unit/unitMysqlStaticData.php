<?php


namespace Core;


trait unitMysqlStaticData
{
    /**
     * @param string $index
     * @return library\Mysql
     */
    abstract public static function mysql($index='');

    public static function _def($name)
    {
        return isset(self::$$name) ? self::$$name : constant(__CLASS__.'::'.$name);
    }

    final public static function insert($data, $onDuplicate=null)
    {
        return self::mysql()->insert(self::_def('TABLE'), $data, $onDuplicate);
    }

    final public static function delete($where, $_)
    {
        return self::mysql()->delete(self::_def('TABLE'), $where, $_);
    }

    final public static function update($data, $where, $_=null)
    {
        return self::mysql()->update(self::_def('TABLE'), $data, $where, $_);
    }

    final public static function fetchOne($where, $fields, $index=null)
    {
        return self::mysql()->select(self::_def('TABLE'), $fields, $where)->fetch($index);
    }

    final public static function fetchAll($where, $fields, $index=null)
    {
        return self::mysql()->select(self::_def('TABLE'), $fields, $where)->fetchAll($index);
    }
}
