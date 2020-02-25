<?php


namespace _;


use Core\library\Http;

class ctrlZsxq extends ctrlSess
{
    public function _DO_entry()
    {
        $lesson = $this->apiGET('sn');
        $channel = $this->apiGET('channel', 'yiling');
        $conf = \config::load('zsxq', $channel);
        $data = [
//            'ip' => \input::ip(),
            'channelid' => $channel,
            'uid' => $this->code($this->uid),
            'itemid' => $lesson,
            'timestamp' => time(),
            'paystatus' => $this->paystatus($this->uid, $lesson),
            'secret' => $conf['secret'],
        ];
        $data['signature'] = $this->sign($data);
        unset($data['ip'], $data['secret']);
        $url = Http::makeURL($conf['entryUrl'], $data);
        header("Location: $url");
    }

    public function _DO_visit()
    {
        $lesson = $this->apiGET('itemid');
        $channel = $this->apiGET('channelid');
        $conf = \config::load('zsxq', $channel);
        $signature = $this->apiGET('signature');
        $data = [
            'itemid' => $lesson,
            'uid' => $this->apiGET('uid'), // zsxq.uid
            'timestamp' => $this->apiGET('timestamp'),
            'paystatus' => $this->apiGET('paystatus'),
//            'ip' => \input::ip(),
            'channelid' => $channel,
            'secret' => $conf['secret'],
        ];
        $path = "/lesson/course/$lesson";
        if (!$data['uid']) {
            $this->httpLocation("/lesson/course/$lesson");
            exit;
        }
        if (abs(time() - $data['timestamp']) > 900) {
            \view::tpl('error', [
                'code' => '-1',
                'message' => '链接已过期',
                'link' => $path
            ]);
//            $this->apiFailure(self::ERR_UNDEFINED, ['Invalid timestamp']);
        }
        if ($signature != $this->sign($data)) {
            \view::tpl('error', [
                'code' => '-2',
                'message' => '签名错误',
                'link' => $path
            ]);
//            $this->apiFailure(self::ERR_UNDEFINED, ['Invalid signature']);
        }
        $daoUserAuth = dataUserAuth::sole($this->platform);
        $res = $daoUserAuth->search(dataUserAuth::TYPE_ZSXQ, $data['uid']);
        if ($res && $res['uid'] != $this->uid) {
            \view::tpl('error', [
                'code' => '-3',
                'message' => '账号重复授权',
                'link' => $path
            ]);
//            $this->apiFailure(self::ERR_UNDEFINED, ['Account error']);
        }
        if (!$res) {
            $this->code($this->uid, $data['uid']);
        }

        $originId = servOrigin::sole($this->platform)->key2id("zsxq");
        $daoLesson = dataLesson::sole($this->platform);
        if ($lesson[0] == 'L') {
            $lessonIds = $daoLesson->fetchAll(['sn' => $lesson], 'id', null, 0);
            $path = "/lesson/detail?sn=$lesson";
            $conf = servLesson::sole($this->platform)->conf($lesson);
        } else {
            $lessonIds = $daoLesson->fetchAll(['category'=>$lesson, 'extra->"$.category_check"=1'], 'id', null, 0);
            $path = "/lesson/series?sn=$lesson";
            $conf = servLessonSeries::sole($this->platform)->conf($lesson);
        }
        // 为超过付费阈值的用户授权,当thresold=时，允许所有星球用户听课
        if ($data['paystatus'] > ($conf['zsxq']['threshold'] ?? 0)) {
            foreach ($lessonIds as $lid) {
                servLesson::sole($this->platform)->enable($lid, $this->uid, $originId, true);
            }
        }
        $this->httpLocation($path);
    }

    public function sign($data)
    {
        ksort($data);
        $_sign = http_build_query($data);
//        $_sign .= "&secret=yilingtest";
        return sha1($_sign);
    }

    public function code($uid, $uaid=null)
    {
        $dao = dataUserAuth::sole($this->platform);
        $where = [
            'i_type' => dataUserAuth::TYPE_ZSXQ,
            'uid' => $uid
        ];
        if ($res = $dao->fetchOne($where, ['uaid', 'code'])) {
            if ($uaid && $res['uaid'] == $res['code']) {
                $dao->update(['uaid'=>$uaid], $where);
            }
            return $res['code'];
        } else {
            $code = $dao->uniqueSN($dao::SN_ZSXQ);
            $dao->append(dataUserAuth::TYPE_ZSXQ, $uid, $code, $code);
            return $code;
        }
    }

    public function paystatus($uid, $lesson)
    {
        if ($lesson[0] == 'L') {
            $lessonId = servLesson::sole($this->platform)->sn2id($lesson);
            $iStatus = dataOrder::sole($this->platform)->fetchOne(['uid'=>$uid, 'lesson_id'=>$lessonId, 'i_status>0'], 'i_status', 0);
            if ($iStatus == dataOrder::STATUS_FIRM) {
                return 1;
            } else {
                return 0;
            }
        }
        if ($lesson[0] == 'S') {
            $usn = servUser::sole($this->platform)->uid2usn($uid);
            $res = servLessonSeries::sole($this->platform)->checkLessons($lesson, $usn);
            if ($res['lesson'] == count($res['access']) && count($res['refund']) == 0) {
                return 1;
            } else {
                return 0;
            }
        }
    }

}