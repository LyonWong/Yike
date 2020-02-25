<?php


namespace _;


use Core\unitInstance;

class dataPromote extends dataSole
{
    use unitInstance;

    const TABLE = 'lesson_promote';

    const TYPE_COUPON = 1; //折扣券，扣除后分成
    const TYPE_VOUCHER = 2; //抵用券，扣除前分成
    const TYPE_HAGGLE = 3; //砍价券，扣除前分成
    const TYPE_REWARD = 4; //奖励券, 扣除前分成
    const TYPE_AUDITION = 5; //试听券，有限时间试听

    const STATUS_AVAILABLE = 1; // 可用
    const STATUS_DISABLED = -1; // 停用


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

    public function append($uid, $lessonId, $originId, unitLessonPromote $unitLessonPromote)
    {
        $extra = [
            'quantity' => $unitLessonPromote->quantity,
            'expire' => $unitLessonPromote->expire,
            'duration' => $unitLessonPromote->duration,
            'payoff' => $unitLessonPromote->payoff,
            'price' => $unitLessonPromote->price,
            'args' => $unitLessonPromote->args,
        ];
        $data = [
            'i_type' => $unitLessonPromote->iType,
            'i_status' => self::STATUS_AVAILABLE,
            'uid' => $uid,
            'series_id' => $unitLessonPromote->seriesId,
            'lesson_id' => $lessonId,
            'origin_id' => $originId,
            'discount' => $unitLessonPromote->discount,
            'commission' => $unitLessonPromote->commission,
            'remark' => $unitLessonPromote->remark,
            'extra' => json_encode($extra),
        ];
        $try = 10;
        do {
            $data['sn'] = $this->uniqueSN(data::SN_PROMOTE);
            $this->insert($data);
            $id = $this->mysql->lastInsertId();
        } while (!$id && --$try>0);
        return $id ? $data['sn'] : false;
    }

    public function status($sn, int $iStatus=null)
    {
        if ($iStatus) {
            $cnt = $this->update(
                [ 'i_status' => $iStatus],
                ['sn' => $sn]
            )->rowCount();
            if ($cnt) {
                return $iStatus;
            } else {
                return false;
            }
        } else {
            return $this->fetchOne(['sn'=> $sn], 'i_status', 0);
        }
    }

    public function extra($where, $field, $value=null)
    {
        if ($value !== null) {
            return $this->update("extra=json_set(extra, '$.$field', $value)", $where)->rowCount();
        } else {
            return $this->fetchOne($where, "extra->'$.$field'", 0);
        }
    }



}