<?php


namespace _;


use Core\unitInstance;

class dataTicket extends dataSole
{
    use unitInstance;

    const TABLE = 'ticket';

    //common 0~9
    const TYPE_REPORT = 1;
    const TYPE_FEEDBACK = 2;

    //teacher 20~29
    const TYPE_TEACHER_APPLY = 20;
    const TYPE_CREATE_LESSON = 21;
    const TYPE_MODIFY_LESSON = 22;
    const TYPE_WITHDRAW_CASH = 23;
    const TYPE_CREATE_SERIES = 24;
    const TYPE_MODIFY_SERIES = 25;

    //student 30~39
    const TYPE_REFUND_APPLY = 31; // 申请退款
    const TYPE_REFUND_APPEAL = 32; // 申诉退款


    const STATUS_START = 1;
    const STATUS_PENDING = 2;
    const STATUS_AGREE = 0;
    const STATUS_REJECT = -1;
    const STATUS_CLOSE = -2;

    /**
     * @param $platform
     * @return self
     */
    public static function sole($platform = null)
    {
        return self::_singleton($platform);
    }

    public function __construct($platform)
    {
        parent::__construct($platform);
        $this->TABLE = self::TABLE;
    }

    public function list($where)
    {
//        $res = $this->fetchAll($where, '*');
        $res = $this->mysql->select($this->TABLE,'*',$where,'order by id desc')->fetchAll();
        foreach ($res as &$row) {
            $row = self::boost($row);
        }
        return $res;
    }

    public function todoList($iType)
    {
        $res = $this->mysql->select($this->TABLE, '*', [
            'i_type' => $iType,
            'i_status>0',
        ], "order by id desc")->fetchAll();
        foreach ($res as &$row) {
            $row = self::boost($row);
        }
        return $res;
    }

    public function commit($iType, $uid, array $content)
    {
        $data = self::adopt([
            'i_type' => $iType,
            'uid' => $uid,
            'content' => $content,
            'i_status' => self::STATUS_START,
        ]);
        $this->insert($data);
        $id = $this->mysql->lastInsertId();
        return $id;
    }

    public function assign($ticketId, $uid)
    {
        return $this->update(['_uid' => $uid], ['id' => $ticketId])->rowCount();
    }

    public function response($toId, $uid, array $content)
    {
        $data = [
            '_id' => $toId,
            'uid' => $uid,
            'content' => json_encode($content)
        ];
        $this->insert($data);
        $this->update(['status' => self::STATUS_PENDING], ['id' => $toId]);
        $id = $this->mysql->lastInsertId();
        return $id;
    }

    public function remark($id, $remark)
    {
        return $this->update(['remark' => $remark], ['id' => $id]);
    }

    public function status($id, $iStatus = null)
    {
        $where = ['id' => $id];
        if (!$iStatus) {
            return $this->fetchOne($where, 'i_status', 0);
        } else {
            return $this->update(['i_status' => $iStatus], $where)->rowCount();
        }
    }

    public function fetchLastLessonItem($uid, $lessonSn, $iType)
    {
        $where = [
            'uid' => $uid,
            'i_type' => $iType,
        ];
        return $this->mysql
            ->s("select * from {$this->TABLE}")
            ->w($where)
            ->a("and content->'$.lesson_sn'=?", [$lessonSn])
            ->a("order by id desc")
            ->e()
            ->fetch();
    }

    public function fetchLastSeriesItem($uid, $seriesSn, $iType)
    {
        $where = [
            'uid' => $uid,
            'i_type' => $iType,
        ];
        return $this->mysql
            ->s("select * from {$this->TABLE}")
            ->w($where)
            ->a("and content->'$.series_sn'=?", [$seriesSn])
            ->a("order by id desc")
            ->e()
            ->fetch();
    }

    public function fetchAllRefund($uid, $lessonSn)
    {
        $where = [
          'uid' => $uid,
        ];
        return $this->mysql
            ->s("select JSON_UNQUOTE(content->'$.reason') as reason,i_type, i_status,remark,tms_create,tms_update from {$this->TABLE}")
            ->w($where)
            ->a("and i_type in(?,?)",[self::TYPE_REFUND_APPLY,self::TYPE_REFUND_APPEAL])
            ->a("and content->'$.lesson_sn'=?", [$lessonSn])
            ->e()
            ->fetchAll();

    }

    protected static function boost($data)
    {
        if (isset($data['content'])) {
            $data['content'] = json_decode($data['content'], true);
        }
        return $data;
    }

    protected static function adopt($data)
    {
        if (isset($data['content'])) {
            $data['content'] = json_encode($data['content']);
        }
        return $data;
    }

}