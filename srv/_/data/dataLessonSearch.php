<?php


namespace _;


use Core\unitInstance;

class dataLessonSearch extends dataSole
{
    use unitInstance;

    const TABLE = 'lesson_board';

    /**
     * @param $platform
     * @return static
     */
    public static function sole($platform)
    {
        return self::_singleton($platform);
    }

    public function __construct($platform)
    {
        parent::__construct($platform);
        $this->TABLE = self::TABLE;
    }

    public function append($tsn, $title, $tag, $weight)
    {
        $data = [
            'tsn' => $tsn,
            'title' => $title,
            'tag' => $tag,
            'weight' => $weight,
        ];
        $this->insert($data);
        return $this->mysql->lastInsertId();
    }

    public function modify($tsn, $title, $tag, $weight)
    {
        $data = [
            'title' => $title,
            'tag' => $tag,
            'weight' => $weight
        ];
        return $this->update($data, ['tsn'=>$tsn]);
    }

    public function slice(array $filter=[], array $matches=[], int $limit)
    {
        $where = $filter;
        foreach($matches as $match => $against) {
            $where["match(`$match`) against(?)"]=[$against];
        }
        $res = $this->fetchAll($where, ['id', 'tsn', 'weight', 'tms'], null, null, "limit $limit");
        return $res;
    }

}