<?php


namespace _;


use Core\unitInstance;

class dataLesson extends dataSole
{
    use unitInstance;

    const TABLE = 'lesson';

    const FORM_IM = 1; //IM聊天群授课
    const FORM_VIEW = 2; //静态观看
    const FORM_ARTICLE = 3; // 付费文章
    const FORM_COLUMN = 4; // 文章专栏
    const FORM_TRY = 0; //试讲课程
    const FORM_IM_HIDE = -1; //隐身IM

    const STEP_SUBMIT = 0; // 提交申请
    const STEP_DENIED = -1; // 审核拒绝
    const STEP_CLOSED = -2 ; // 关闭下架
    const STEP_HALTED = -3; // 下线停售

    // 大于0为可访问阶段
    const STEP_OPENED = 1; // 报名中,审核通过
    const STEP_ONLIVE = 2; // 直播中
    const STEP_REPOSE = 3; // 课后讨论
    const STEP_FINISH = 4; // 已完成

    /**
     * @param $platform
     * @return self
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

    public function append($tuid, unitLesson $unitLesson)
    {
        $plan = array_filter([
            'dtm_start' => $unitLesson->dtmStart,
            'duration' => $unitLesson->duration,
        ]);
        $extra = [
            'cover' => $unitLesson->cover,
        ];
        $data = [
            'sn' => $this->uniqueSN(self::SN_LESSON),
            'tuid' => $tuid,
            'title' => $unitLesson->title,
            'brief' => $unitLesson->brief,
            'category' => $unitLesson->category,
            'tags' => $unitLesson->tags,
            'i_form' => $unitLesson->iForm,
            'price' => $unitLesson->price,
            'quota' => $unitLesson->quota,
            'homework' => '{}',
            'plan' => json_encode($plan, JSON_FORCE_OBJECT),
            'i_step' => dataLesson::STEP_SUBMIT,
            'extra' => json_encode($extra),
        ];
        $this->insert($data);
        return $this->mysql->lastInsertId();
    }

    public function modify($sn, unitLesson $unitLesson)
    {
        $plan = array_filter([
            'dtm_start' => $unitLesson->dtmStart,
            'duration' => $unitLesson->duration,
        ]);
        // cover=false表示去掉封面设置
        if ($unitLesson->cover || $unitLesson->cover===false) {
            $extra = ['cover' => $unitLesson->cover];
        } else {
            $extra = [];
        }
        $prev = $this->inquireOne(['sn' => $sn], ['plan', 'extra']);
        $plan = array_merge($prev['plan'] ?? [
                'dtm_start' => date('Y-m-d H:i'),
                'duration' => '1'
            ], $plan);
        $extra = array_merge($prev['extra'], $extra);
        $data = array_filter([
            'title' => $unitLesson->title,
            'brief' => $unitLesson->brief,
            'category' => $unitLesson->category,
            'tags' => $unitLesson->tags,
            'i_form' => $unitLesson->iForm,
            'price' => $unitLesson->price,
            'quota' => $unitLesson->quota,
            'plan' => json_encode($plan, JSON_FORCE_OBJECT),
            'extra' => json_encode($extra),
        ]);
        return $this->update($data,  ['sn'=>$sn])->rowCount();
    }

    public function extra($sn, $key, $val=null)
    {
        if ($val) {
            return $this->update("extra=json_set(extra, '$.$key', '$val')", ['sn'=>$sn])->rowCount();
        } else {
            $res =  $this->fetchOne(['sn'=>$sn], "extra->'$.$key'", 0);
            return json_decode($res, true);
        }
    }

    public function fetchDtmStart($sn)
    {
        return $this->mysql->run("select json_unquote(plan->'$.dtm_start') from $this->TABLE where sn=?", [$sn])->fetch(0);
    }

    public function searchByTitle($title)
    {
        $pattern = str_replace('*', '%', $title);
        return $this->fetchAll([
            'title like ?' => [$pattern]
        ], 'id', null, 0);
    }

    public function addGuest($sn, $tusn)
    {
        $this->update("plan=json_merge(plan, '{\"guest\": [\"$tusn\"]}')", ['sn' => $sn]);
    }

    public function fetchByIDs(array $IDs, $fields='*', array $filter=[], $_=null)
    {
        if(empty($IDs)) {
            return [];
        }
        $res = $this->mysql::makeData($IDs, '?', ',');
        $where = array_merge([
            "id in ($res[clause])" => $res['params']
        ], $filter);
        return $this->fetchAll($where, $fields, null, null, $_);
    }

    public function fetchBySNs(array $SNs, $fields='*', array $filter=[])
    {
        $res = $this->mysql::makeData($SNs, '?', ',');
        $where = array_merge([
            "sn in ($res[clause])" => $res['params']
        ], $filter);
        return $this->fetchAll($where, $fields);
    }

    protected function _inquireParse(array $row)
    {
        foreach ($row as $key => &$val) {
            switch ($key){
                case 'plan':
                case 'extra':
                    $val = json_decode($val, true);
                    break;
            }
        }
        return $row;
    }
}