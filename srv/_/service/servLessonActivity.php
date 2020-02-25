<?php


namespace _;


use Core\unitInstance;

class servLessonActivity extends serv_
{
    use unitInstance;

    const TYPE_REMIT = 1;

    /**
     * @param $platform
     * @return self
     */
    public static function sole($platform)
    {
        return self::_singleton($platform);
    }

    public function remitStart($uid, $lessonSn)
    {
        $args = [
            'init' => ['m' => 100, 'n' => 10],
            'done' => ['m' => 0, 'n' => 0],
            'list' => []
        ];
        $sn = dataLessonActivity::sole($this->platform)->append(
            dataLessonActivity::TYPE_REMIT,
            $lessonSn,
            $uid,
            $args
        );
        return $sn;
    }

    public function remitAppend($sn, $uid)
    {
        //一天内累计不超过10次
        $redis = data::redis();
        $rkey = "REMIT_$uid";
        if ($redis->incr($rkey) > 10) {
            return false;
        } else {
            $redis->expire($rkey, SECONDS_DAY);
        }

        $dao = dataLessonActivity::sole($this->platform);
        $res = $dao->fetchOne(['sn'=>$sn], ['refer', 'args', 'i_status']);
        $args = json_decode($res['args'], true);

        //结束后不能再参与
        if ($res['i_status'] == dataLessonActivity::STATUS_DONE) {
            return false;
        }

        //同用户只能参与一次
        $umap = array_column($args['list'], 'num', 'uid');
        if (isset($umap[$uid])) {
            return false;
        }

        //获取随机比例
        $remit = $this->remitRand(
            $args['init']['m'] - $args['done']['m'],
            $args['init']['n'] - $args['done']['n']
        );

        //无法再分配
        if ($remit === false) {
            return false;
        }

        //更新活动数据
        $args['done'] = ['m' => $args['done']['m'] + $remit, 'n' => $args['done']['n'] + 1];
        $args['list'][] = [
            'uid' => $uid,
            'num' => $remit,
        ];
        return (bool)$dao->update(
            ['args' => json_encode($args), 'i_status'=>dataLessonActivity::STATUS_GOON],
            ['sn' => $sn])->rowCount();
    }

    public function remitDone($sn)
    {}

    public function remitRand($m, $n)
    {
        if ($n == 0) {
            return false;
        } else if ($n == 1) {
            return $m;
        } else {
            return min($m, rand(0, 2 * $m / $n));
        }
    }

}