<?php


namespace Core;


trait unitMysqlSole
{
    /**
     * @var library\Mysql
     */
    protected $mysql;

    protected $TABLE;

    final public function insert($data, $onDuplicate = null)
    {
        return $this->mysql->insert($this->TABLE, $data, $onDuplicate);
    }

    final public function insertBatch($fields, $values, $onDuplicate=null, $batchSize=0)
    {
        return $this->mysql->insertBatch($this->TABLE, $fields, $values, $onDuplicate, $batchSize);
    }

    final public function delete($where, $_ = null)
    {
        return $this->mysql->delete($this->TABLE, $where, $_);
    }

    final public function update($data, $where, $_ = null)
    {
        return $this->mysql->update($this->TABLE, $data, $where, $_);
    }

    final public function fetchOne($where, $fields, $index = null, $_ = null)
    {
        return $this->mysql->select($this->TABLE, $fields, $where, $_)->fetch($index);
    }

    final public function fetchAll($where, $fields, $index = null, $value = null, $_ = null)
    {
        return $this->mysql->select($this->TABLE, $fields, $where, $_)->fetchAll($index, $value);
    }

    final public function inquireOne($where, $fields)
    {
        $row = $this->fetchOne($where, $fields);
        if ($row && is_array($row)) {
            return $this->_inquireParse($row);
        } else {
            return [];
        }
    }

    final public function parseAll($where, $fields, $index=null)
    {
        $rows = $this->fetchAll($where, $fields, $index);
        return array_map([$this, '_inquireParse'], $rows);
    }

    final public function paging($pageNum = 1, $pageStep = 10, $pageFilter = null, $pageField = '*', $order = '')
    {
        $pageNum = ((int)$pageNum <= 0) ? 1 : $pageNum;
        $pageStep = ((int)$pageStep <= 0) ? 10 : $pageStep;

        $total = $this->mysql->select($this->TABLE, "COUNT(*)", $pageFilter)->fetch(0);
        $offset = ($pageNum - 1) * $pageStep;
        $pages = $this->mysql->select($this->TABLE, $pageField, $pageFilter, "order by $order limit $offset, $pageStep")->fetchAll();
        return [
            'total' => (int)$total,
            'page' => (int)$pageNum,
            'pages' => $pages,
            'totalPage' => ceil($total / $pageStep)
        ];
    }

    protected function _inquireParse(array $row)
    {
        return $row;
    }

}
