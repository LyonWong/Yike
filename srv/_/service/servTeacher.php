<?php


namespace _;


use Core\unitInstance;
use Core\library\Tool;


class servTeacher extends serv_
{
    use unitInstance;

    const STATUS_MAP = [
        dataTeacher::STATUS_APPLYING => 'applying',
        dataTeacher::STATUS_ACCEPTED => 'accepted',
        dataTeacher::STATUS_REJECTED => 'rejected',
    ];
    const INVITE_TOKEN_PREFIX = 'TEACHER_INVITE_';

    protected $data;

    protected $cache = [];

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
        $this->data = dataTeacher::sole($this->platform);
    }

    public function list()
    {
        $res = $this->data->fetchAll(null, '*');
        foreach ($res as &$item) {
            $item['datum'] = json_decode($item['datum'], true);
        }
        return $res;
    }

    public function datum($tuid)
    {
        $ckey = "datum-$tuid";
        if (empty($this->cache[$ckey]) &&
            $res = $this->data->fetchOne(['tuid'=>$tuid], ['datum'], 0)
        ) {
            $this->cache[$ckey] = json_decode($res, true);
            $this->cache[$ckey]['stats'] = $this->stats($tuid);
        }
        return $this->cache[$ckey] ?? null;
    }

    public function stats($tuid)
    {
        $lesson = dataLesson::sole($this->platform)->fetchAll([
            'tuid' => $tuid,
            'i_form > 0',
            'i_step > 0',
            'category' => ''
        ], 'i_form, count(*) as count', 'i_form', 'count', 'group by i_form');
        $series = dataLessonSeries::sole($this->platform)->fetchOne([
            'uid' => $tuid,
            'i_status>0'
        ], 'count(*)', 0);
        $data = [
            'lesson' => ($lesson[dataLesson::FORM_IM]??0) + ($lesson[dataLesson::FORM_VIEW]??0),
            'series' => $series,
            'article' => $lesson[dataLesson::FORM_ARTICLE] ?? 0,
            'column' => $lesson[dataLesson::FORM_COLUMN] ?? 0
        ];
        return $data;
    }

    public function status($tuid, $status=null)
    {
        $where = ['tuid' => $tuid];
        if ($status) {
            $res = $this->data->update([
                'i_status' => $status,
            ], $where);
        } else {
            $res = $this->data->fetchOne($where, 'i_status', 0);
        }
        return $res;
    }

    public function genToken($account)
    {
        $token = Tool::genSecret(32, Tool::STR_FORMAL);
        $redisKey = self::INVITE_TOKEN_PREFIX . $token;
        data::redis()->set($redisKey, $account, 3600 * 24 * 5);
        return $token;
    }

    public function verToken($token)
    {
        $redisKey = self::INVITE_TOKEN_PREFIX . $token;
        return data::redis()->get($redisKey);
    }

    public function apply($tuid, unitTeacherDatum $unitTeacherDatum)
    {
        $profile = servUser::sole($this->platform)->uid2profile($tuid);
        servOrigin::sole($this->platform)->checkIn("home-$profile[sn]", "讲师主页-$profile[name]");
        return dataTeacher::sole($this->platform)->append($tuid, $unitTeacherDatum);
    }

    public function update($tuid, unitTeacherDatum $unitTeacherDatum)
    {
        return dataTeacher::sole($this->platform)->update([
            'datum' => json_encode([
                'about' => $unitTeacherDatum->about
            ])
        ], ['tuid' => $tuid])->rowCount();
    }

    public function uid2datum($tuid)
    {
        return dataTeacher::sole($this->platform)->fetchOne(['tuid'=>$tuid],'datum','datum');
    }


    public function uid2status($tuid)
    {
        return dataTeacher::sole($this->platform)->fetchOne(['tuid'=>$tuid],'i_status','i_status');
    }
}