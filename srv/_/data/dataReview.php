<?php


namespace _;


use Core\unitInstance;

class dataReview extends dataSole
{
    use unitInstance;

    const TABLE = 'review';

    //1~7
    const TYPE_TEACHER_CERTIFICATE = 1;
    const TYPE_CREATE_LESSON = 2;

    //9~15
    const TYPE_REFUND_APPLY = 9;

    const TYPE_FEEDBACK = 16;

    const STATUS_START = 1;
    const STATUS_PENDING = 2;
    const STATUS_CLOSE = -1;

    public static function sole($platform)
    {
        return self::_singleton($platform);
    }

    public function __construct($platform)
    {
        parent::__construct($platform);
        $this->TABLE = self::TABLE;
    }

    public function commit($iType, $uid, array $content)
    {
        $data = [
            'i_type' => $iType,
            'uid' => $uid,
            'content' => json_encode($content),
            'i_status' => self::STATUS_START,
        ];
        $this->insert($data);
        $id = $this->mysql->lastInsertId();
        return $id;
    }

    public function response($toId, $uid, array $content)
    {
        $data = [
            '_id' => $toId,
            'uid' => $uid,
            'content' => json_encode($content)
        ];
        $this->insert($data);
        $this->update(['status'=>self::STATUS_PENDING], ['id'=>$toId]);
        $id = $this->mysql->lastInsertId();
        return $id;
    }

    public function remark($id, $remark)
    {
        return $this->update(['remark' => $remark], ['id' => $id]);
    }

    public function status($id, $iStatus=null)
    {
        $where = ['id'=>$id];
        if ($iStatus) {
            return $this->fetchOne($where, 'i_status', 0);
        } else {
            return $this->update(['i_status'=>$iStatus], $where);
        }
    }

}