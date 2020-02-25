<?php


namespace _;


class ctrlBestCode extends ctrlSess
{
    public function _DO_call($sn)
    {
        $srvOrder = servOrder::sole($this->platform);
        $lessons = servLessonSeries::sole($this->platform)->checkLessons($sn, $this->usn);
        $data = [
            'total' => $lessons['lesson'],
            'confirmed' => 0,
        ];
        foreach ($lessons['refund'] as $_lesson) {
            $_orderSn = $srvOrder->findPaidOrder($this->uid, $_lesson['sn']);
            if ($_orderSn && $srvOrder->confirm($_orderSn)) {
                $data['confirmed'] ++;
            }
            $_lesson['id'] = servLesson::sole($this->platform)->sn2id($_lesson['sn']);
            dataLessonAccess::sole($this->platform)->append($_lesson['id'], $this->uid, dataLessonAccess::EVENT_CONFIRM, ['mark' => 'manual']);
        }
        if ($data['confirmed']) { // 有新确认的课程
            $user = servUser::sole($this->platform)->uid2profile($this->uid);
            $telephone = dataUserAuth::sole($this->platform)->fetchOne(['uid' => $this->uid, 'i_type' => dataUserAuth::TYPE_TELEPHONE], 'uaid', 0);
            $info = servLessonSeries::sole($this->platform)->sn2info($sn,  ['name']);
            $openIds = ['oOXfP0VhMXZCIsBk4dj4ktE90gK8']; // 百思编程客服
            servMpMsg::sole($this->platform)->callNotice("有新用户刚刚确认了百思编程课《$info[name]》", $user['name'], date('Y-m-d H:i:s'), $telephone, $openIds);
            $this->apiSuccess('您已确认开通百思编程在线练习功能，工作人员正在处理，稍后请注意手机短信通知');
        } else {
            $this->apiSuccess('您已开通练习服务，请在收到手机短信通知后访问https://bestcode.com。有疑问请通过公众号后台或小助手微信yike-01向我们反馈');
        }
    }

}