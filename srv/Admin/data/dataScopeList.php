<?php


namespace Admin;


use Core\unitInstance;

class dataScopeList extends data_
{
    use unitInstance;

    const TYPE_DEFAULT = 0;
    const TYPE_MENU = 1;
    const TYPE_EXPAND = 2;

    private $TABLE;

    public function __construct($scope)
    {
        $this->TABLE = 'scope_'.$scope;
        parent::__construct();
    }

    /**
     * @param $scope
     * @return self
     */
    public static function inst($scope)
    {
        return self::_singleton($scope);
    }

    public function fetchList()
    {
        return self::mysql()->select($this->TABLE, '*')->fetchAll();
    }

    public function append($data)
    {
        self::mysql()->insert($this->TABLE, $data, ['name', 'type', 'depth', 'rank', 'path']);
        return self::mysql()->lastInsertId();
    }

    public function updateById($id, $data)
    {
        return self::mysql()->update($this->TABLE, $data, ['id'=>$id]);
    }

    public function deleteById($id)
    {
        return self::mysql()->delete($this->TABLE, ['id' => $id]);
    }

}