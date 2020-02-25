<?php


namespace _;


use Core\unitInstance;
use Teacher\servStats;


class servRelation extends serv_
{
    use unitInstance;

    /**
     * @param $platform
     * @return self
     */
    public static function sole($platform)
    {
        return self::_singleton($platform);
    }


    public function isFollow($uid, $_uid)
    {
        return (bool)dataRelation::sole($this->platform)->fetchOne(['uid' => $uid, '_uid' => $_uid, 'i_type' => dataRelation::TYPE_FOLLOW], 'score', 'score');
    }

    /**
     * 关注/取消关注
     * @param $uid
     * @param $_uid
     * @return array
     */
    public function follow($uid, $_uid)
    {
        $dao = dataRelation::sole($this->platform);
        $pre = $dao->fetchOne(['uid' => $uid, '_uid' => $_uid, 'i_type' => dataRelation::TYPE_FOLLOW], ['id', 'score']);
        if ($pre) {
            $score = $pre['score'] ? 0 : 1;
            $dao->update(['score' => $score], ['id' => $pre['id']]);
        } else {
            $score = 1;
            $dao->append($uid, $_uid);
        }

        return [
            'isFollow' => boolval($score),
        ];
    }


    public function followList($uid, $cursor, $limit)
    {
        $where = [
            'uid' => $uid,
            'i_type' => dataRelation::TYPE_FOLLOW,
            'score' => 1
        ];
        $list = dataRelation::sole($this->platform)->getList($cursor, $limit, $where, ['id', '_uid']);
        foreach ($list as &$item) {
            $cursor = $item['id'];
            $tuid = $item['_uid'];
            $item = servUser::sole($this->platform)->uid2profile($tuid);
            $item['about'] = servTeacher::sole($this->platform)->datum($tuid)['about'];
            $item['stats'] = servStats::sole($this->platform)->overview($tuid);
            $item['cursor'] = $cursor;
        }
        return $list;
    }


}