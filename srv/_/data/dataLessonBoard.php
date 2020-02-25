<?php


namespace _;


use Core\unitInstance;

class dataLessonBoard extends dataSole
{
    use unitInstance;

    const TABLE = 'lesson_board';

    const TYPE_ARGUE = 1;

    const TYPE_NULL = 0;

    const TYPE_LIKE = -1;
    const TYPE_DELETE = -2;
    const TYPE_TIPOFF = -3;
    const TYPE_HIDDEN = -4;


    public static function sole($platform)
    {
        return self::_singleton($platform);
    }

    public function __construct($platform)
    {
        parent::__construct($platform);
        $this->TABLE = self::TABLE;
    }

    public function append($iType, $lessonId, $uid, unitBoardMessage $message)
    {
        $data = [
            'i_type' => $iType,
            'lesson_id' => $lessonId,
            'uid' => $uid,
            'message' => $message->encode(),
            'stats' => '{}',
            'extra' => '{}',
            'weight' => 0,
        ];
        $this->insert($data);
        $id = $this->mysql->lastInsertId();
        return $id;
    }

    public function reply($boardId, $uid, unitBoardMessage $message)
    {
        $_ = $this->fetchOne(['id'=>$boardId], 'id_,uid,i_type,lesson_id,json_unquote(extra->"$.chain") as chain');
        $u = ($uid > $_['uid']) ? "$_[uid]|$uid" : "$uid|$_[uid]";
        if (strpos($_['chain'], $u) === 0) {
            $chain = $_['chain']; //延续会话链
        } else {
            $chain = "$u-$boardId"; //新建会话链
        }
        $extra = [
            'chain' => $chain
        ];
        $data = [
            '_id' => $boardId,
            'id_' => $_['id_'] ?: $boardId,
            'i_type' => $_['i_type'],
            'lesson_id' => $_['lesson_id'],
            'uid' => $uid,
            'message' => $message->encode(),
            'stats' => '{}',
            'extra' => json_encode($extra),
        ];
        $this->insert($data);
        $id = $this->mysql->lastInsertId();
        return $id;
    }

    public function tipoff($boardId, $uid, unitBoardMessage $message)
    {
        $_ = $this->fetchOne(['id'=>$boardId], 'id_,uid,i_type,lesson_id,weight');
        $data = [
            '_id' => $boardId,
            'id_' => $_['id_'] ?: $boardId,
            'i_type' => self::TYPE_TIPOFF,
            'lesson_id' => $_['lesson_id'],
            'uid' => $uid,
            'message' => $message->encode(),
            'stats' => '{}',
            'extra' => '{}',
        ];
        $this->insert($data);
        $id = $this->mysql->lastInsertId();
        return $id;
    }

    public function slice($iType, $lessonId, $fields, array $filter=[], $orderBy, int $limit)
    {
        $where = array_merge([
            'lesson_id' => $lessonId,
        ], $filter);
        if ($iType) {
            $where['i_type'] = $iType;
        }
        $res = $this->mysql->select($this->TABLE, $fields, $where, "order by $orderBy limit $limit")->fetchAll();
        return $res;
    }

    public function _inquireParse(array $row)
    {
        foreach ($row as $key => &$val) {
            switch ($key) {
                case 'message':
                case 'stats':
                case 'extra':
                    $val = json_decode($val, true);
                    break;
            }
        }
        return $row;
    }

}