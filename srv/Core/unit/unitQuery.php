<?php


namespace Core;


class unitQuery
{
    const _LIST_ = [];

    public $params = [];


    /**
     * @param array $params
     * @return static
     */
    public static function init($params=[])
    {
        return new self($params);
    }

    public function __construct(array $params)
    {
        $this->params = $params;
        foreach ($params as $key => $val) {
            $this->$key = $val;
        }
    }

    public function toWhere()
    {
        $where = [];
        foreach ($this::_LIST_ as $key) {
            if (isset($this->$key)) {
                $where[$key] = $this->$key;
            }
        }
        return $where;
    }

    public function toString()
    {
        $vars = get_class_vars(__CLASS__);
        foreach ($vars as $key => &$val) {
            $val = $this->$key;
        }
        return http_build_query($vars);
    }

}
