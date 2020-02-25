<?php


namespace _;


use Admin\unitQueryTicket;
use Core\library\Mysql;
use Core\unitInstance;

class servTicket extends serv_
{
    use unitInstance;

    const STATUS_MAP = [
        dataTicket::STATUS_START => 'start',
        dataTicket::STATUS_PENDING => 'pending',
        dataTicket::STATUS_AGREE => 'agree',
        dataTicket::STATUS_REJECT => 'reject',
        dataTicket::STATUS_CLOSE => 'close'
    ];

    const TYPE_MAP = [
        dataTicket::TYPE_REFUND_APPLY => 'apply',
        dataTicket::TYPE_REFUND_APPEAL => 'appeal'
    ];

    /**
     * @var dataTicket
     */
    protected $data;

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
        $this->data = dataTicket::sole($this->platform);
    }

    public function close($ticketId, $_uid, $remark = '')
    {
        $this->data->update([
            '_uid' => $_uid,
            'i_status' => dataTicket::STATUS_CLOSE,
            'remark' => $remark
        ], [
            'id' => $ticketId
        ]);
    }

    public function updateStatus($ticketId, $_uid, $remark = '',$status)
    {
        $this->data->update([
            '_uid' => $_uid,
            'i_status' => $status,
            'remark' => $remark
        ], [
            'id' => $ticketId
        ]);
    }



    public function reject($ticketId, $_uid, $remark = '')
    {
        $this->data->update([
            '_uid' => $_uid,
            'i_status' => dataTicket::STATUS_REJECT,
            'remark' => $remark
        ], [
            'id' => $ticketId
        ]);
    }

    public function refund($status = null)
    {
        $where = [
            'i_type' => dataTicket::TYPE_REFUND_APPEAL
        ];
        $dict = array_flip(self::STATUS_MAP);
        if ($status && isset($dict[$status])) {
            $where['i_status'] = $dict['status'];
        }
        $list = $this->data->list($where);
        foreach ($list as &$item) {
            $item['student'] = servUser::sole($this->platform)->uid2profile($item['uid']);
            $item['lesson'] = servLesson::sole($this->platform)->profile($item['content']['lesson_sn']);
            $item['status'] = self::STATUS_MAP[$item['i_status']];
            $item['refundInfo'] = $this->sn2refundInfo($item['content']['lesson_sn'], $item['uid']);
            $item['order'] = dataOrder::sole($this->platform)->getInfo($item['uid'], servLesson::sole($this->platform)->sn2id($item['content']['lesson_sn']));
            $item['order']['order_amount'] /= 100;
            $item['order']['paid_amount'] /= 100;
        }
        return $list;
    }


    public function page($iType, unitQueryTicket $query, $pageNum, $pageStep)
    {
        $where = [
            'tms_create between ? and ?' => ["$query->dateStart 00:00:00", "$query->dateEnd 23:59:59"],
            'i_type' => $iType
        ];
        if ($query->userValue) {
            if ($query->userField == 'id') {
                $where['uid'] = $query->userValue;
            } elseif ($query->userField == 'sn') {
                $where['sn'] = $query->userValue;
            } else {
                $uids = dataUser::sole($this->platform)->searchByName($query->userValue);
                $made = Mysql::makeData($uids, '?', ',');
                $where["uid in ($made[clause])"] = $made['params'];
            }
        }
        if ($query->status) {
            $iStatus = array_search($query->status, servTicket::STATUS_MAP);
            if ($iStatus !== false) {
                $where['i_status'] = $iStatus;
            }
        }
        return $this->data->paging($pageNum, $pageStep, $where, '*', 'id desc');
    }

    public function series($status = null)
    {
        $where = [
            'i_type' => dataTicket::TYPE_MODIFY_SERIES
        ];
        $dict = array_flip(self::STATUS_MAP);
        if ($status && isset($dict[$status])) {
            $where['i_status'] = $dict['status'];
        }
        $list = $this->data->list($where);

        foreach ($list as &$item) {
            $item['status'] = self::STATUS_MAP[$item['i_status']];
            $item['series'] = \Admin\servLessonSeries::sole($this->platform)->detail($item['content']['series_sn']);
            unset($item['content']['series_sn']);
        }

        return $list;
    }

    public function lessonDetail($ticketId)
    {
        $ret = dataTicket::sole($this->platform)->fetchOne(['id' => $ticketId], '*');
        $ret['content'] = json_decode($ret['content'], true);

        if (!isset($ret['content']['extra']['cover'])) {
            $coverPath = servLesson::sole($this->platform)->sn2cover($ret['content']['lesson_sn']);
            $ret['content']['extra']['cover'] = $coverPath;
        }
        $ret['content']['price'] /= 100;
        $ret['content']['isPublic'] = $ret['content']['isPublic'] ?? 1;
        $ret['lesson'] = servLesson::sole($this->platform)->profile($ret['content']['lesson_sn']);
        $ret['status'] = self::STATUS_MAP[$ret['i_status']];
        unset($ret['content']['lesson_sn']);
        return $ret;
    }

    public function seriesDetail($ticketId)
    {
        $ret = dataTicket::sole($this->platform)->fetchOne(['id' => $ticketId], '*');
        $ret['content'] = json_decode($ret['content'], true);

        if (isset($ret['content']['introduce']['cover'])) {
            $ret['content']['introduce']['cover'] = \view::upload($ret['content']['introduce']['cover']);
        }
        if (isset($ret['content']['scheme']['price'])) {
            $ret['content']['scheme']['price'] /= 100;
        }
        $series = \Admin\servLessonSeries::sole($this->platform)->detail($ret['content']['series_sn']);
        unset($ret['content']['lesson_sn']);
        $data = arrayMergeForce($series, $ret['content']??[]);
        $data['id'] = $ticketId;
        return $data;
    }

    public function refundDetail($ticketId)
    {
        $ret = dataTicket::sole($this->platform)->fetchOne(['id' => $ticketId], '*');
        $ret['content'] = json_decode($ret['content'], true);

        $ret['student'] = servUser::sole($this->platform)->uid2profile($ret['uid']);
        $ret['lesson'] = servLesson::sole($this->platform)->profile($ret['content']['lesson_sn']);
        $ret['status'] = self::STATUS_MAP[$ret['i_status']];
        $ret['refundInfo'] = $this->sn2refundInfo($ret['content']['lesson_sn'], $ret['uid']);
        $ret['order'] = dataOrder::sole($this->platform)->getInfo($ret['uid'], servLesson::sole($this->platform)->sn2id($ret['content']['lesson_sn']));
        $ret['order']['order_amount'] /= 100;
        $ret['order']['paid_amount'] /= 100;

        return $ret;


    }

    public function dealLesson($uid, $ticketId, $operate, $remark, $sendMsg = 0, $changeText = '')
    {
        $content = json_decode(dataTicket::sole($this->platform)->fetchOne(['id' => $ticketId], 'content', 'content'), true);
        $content['plan'] = json_encode($content['plan']);
        if (isset($content['extra'])) {
            $content['extra'] = json_encode($content['extra']);
        }
        $lessonSn = $content['lesson_sn'];
        unset($content['lesson_sn']);
        if ($operate == dataTicket::STATUS_AGREE) {
            $detail = dataLesson::sole($this->platform)->fetchOne(['sn' => $lessonSn], ['tuid', 'category', 'i_form', 'i_step']);
            if ($detail['i_step'] <= \Admin\dataLesson::STEP_SUBMIT) {
                servLesson::sole($this->platform)->step($lessonSn, dataLesson::STEP_OPENED);
            }
            //如果是归属系列课，添加到系列课当中去
            if ($content['category'] && $content['category'] != $detail['category']) {
                //移除出之前系列课
                $lessonId = servLesson::sole($this->platform)->sn2id($lessonSn);
                $category = dataLesson::sole($this->platform)->fetchOne(['id' => $lessonId], 'category', 'category');
                if ($category != $content['category']) {
                    servLesson::sole($this->platform)->removeSeries($lessonSn);
                }
                //添加到新的系列课中去
                servLesson::sole($this->platform)->addSeries($lessonId, $content['category']);
            }
            //去除归属系列课
            if(($content['category'] == '' || !isset($content['category'])) && $detail['category']) {
                servLesson::sole($this->platform)->removeSeries($lessonSn);
            }

            $coverPath = servLesson::sole($this->platform)->sn2cover($lessonSn);

            $iForm = array_search($content['form']??null, servLesson::FORM_MAP) ?: $detail['i_form'];
            $isPublic = $content['isPublic']?? dataLesson::FORM_IM;
            $content['i_form'] = $isPublic ? abs($iForm) : -abs($iForm);
            unset($content['isPublic'], $content['form']);
            $ret = dataLesson::sole($this->platform)->update($content, ['sn' => $lessonSn])->rowCount();

            //向学员发送课程变更通知
            if ($sendMsg) {
                $this->sendChangeMsg($changeText, $lessonSn);

            }
            $dtmStart = dataLesson::sole($this->platform)->fetchDtmStart($lessonSn);
            servTrigger::sole($this->platform)->onLessonStartSet($lessonSn, $dtmStart);
            if ($ret !== false) {
                dataTicket::sole($this->platform)->update([
                    '_uid' => $uid,
                    'i_status' => dataTicket::STATUS_AGREE,
                    'remark' => $remark
                ], [
                    'id' => $ticketId
                ]);
                if ($detail['tuid'] != $uid) {
                    servMpMsg::sole($this->platform)->sendReview($lessonSn, true);
                }
                //删除旧图
                if ($coverPath != 'lesson/cover/tryLesson' && isset($content['extra']['cover'])) {
                    servQiniu::inst()->delete($coverPath);
                }
            }
        } else {
            dataTicket::sole($this->platform)->update([
                '_uid' => $uid,
                'i_status' => dataTicket::STATUS_REJECT,
                'remark' => $remark
            ], [
                'id' => $ticketId
            ]);
            servMpMsg::sole($this->platform)->sendReview($lessonSn, false, $remark);

        }
    }

    public function dealSeries($uid, $ticketId, $operate, $remark)
    {
        $content = json_decode(dataTicket::sole($this->platform)->fetchOne(['id' => $ticketId], 'content', 'content'), true);
        if ($operate == dataTicket::STATUS_AGREE) {
            $series = dataLessonSeries::sole($this->platform)->fetchOne(['sn' => $content['series_sn']], ['name', 'introduce', 'scheme']);
            $series['introduce'] = json_decode($series['introduce'], true);
            $series['scheme'] = json_decode($series['scheme'], true);
            $data = arrayMergeForce($series, $content);
            $data['introduce'] = json_encode($data['introduce']);
            $data['scheme'] = json_encode($data['scheme']);
            unset($data['series_sn']);
            $ret = dataLessonSeries::sole($this->platform)->update($data, ['sn' => $content['series_sn']])->execute();
            if ($ret) {
                dataTicket::sole($this->platform)->update([
                    '_uid' => $uid,
                    'i_status' => dataTicket::STATUS_AGREE,
                    'remark' => $remark
                ], [
                    'id' => $ticketId
                ]);
                servMpMsg::sole($this->platform)->sendSeriesReview($content['series_sn'], true);

                //删除旧图
                if (isset($content['introduce']['cover'])) {
                    servQiniu::inst()->delete($series['introduce']['cover']);
                }
            }
        } else {
            dataTicket::sole($this->platform)->update([
                '_uid' => $uid,
                'i_status' => dataTicket::STATUS_REJECT,
                'remark' => $remark
            ], [
                'id' => $ticketId
            ]);
            servMpMsg::sole($this->platform)->sendSeriesReview($content['series_sn'], false, $remark);
        }

    }

    public function sendChangeMsg($changeText, $lessonSn)
    {
        if (!empty($changeText)) {
            servMpMsg::sole($this->platform)->sendChangeNotice(
                $lessonSn,
                $changeText,
                '点击详情查看变更事项',
                "$_SERVER[REQUEST_SCHEME]://" . \config::load('boot', 'public', 'domain', '', 'Student') . '/weixin-lesson?lesson_sn=' . $lessonSn);
        }
    }

    public function lessonInReview($uid, $lessonSn)
    {
        $ticket = dataTicket::sole($this->platform)->fetchLastLessonItem($uid, $lessonSn, dataTicket::TYPE_MODIFY_LESSON);
        if ($ticket) {
            $content = json_decode($ticket['content'], true);
            $content['plan'] = json_encode($content['plan']);
            $data['content'] = servLesson::sole($this->platform)->boost($content);
            if (isset($content['extra']['cover'])) {
                $data['content']['cover'] = \view::upload($content['extra']['cover']);
            }
            $data['review_status'] = self::STATUS_MAP[$ticket['i_status']];
            return $data;
        }
        return ['review_status' => ''];
    }

    public function seriesInReview($uid, $seriesSn)
    {
        $ticket = dataTicket::sole($this->platform)->fetchLastSeriesItem($uid, $seriesSn, dataTicket::TYPE_MODIFY_SERIES);
        if ($ticket) {
            $data = json_decode($ticket['content'], true);
            if (isset($data['introduce']['cover'])) {
                $data['introduce']['cover'] = \view::upload($data['introduce']['cover']);
            }
            $data['review_status'] = self::STATUS_MAP[$ticket['i_status']];
            if ($data['i_status'] == dataTicket::STATUS_AGREE) {
                unset($data['scheme']['price']);
            }
            unset($data['series_sn']);
            return $data;
        }
        return ['review_status' => ''];
    }

    public function sn2refundInfo($lessonSn, $uid)
    {
        $return = null;
        $rets = $this->data->fetchAllRefund($uid, $lessonSn);
        foreach ($rets as &$ret) {
            $return[self::TYPE_MAP[$ret['i_type']]] = $ret;
            $return[self::TYPE_MAP[$ret['i_type']]]['status'] = self::STATUS_MAP[$ret['i_status']];
        }
        return $return;
    }

    public function refundApplyToAppeal($deadline)
    {
        $list = $this->data->fetchAll([
            'i_type' => dataTicket::TYPE_REFUND_APPLY,
            'i_status' => dataTicket::STATUS_START,
            'tms_create < ?' => [$deadline]
        ], ['id', 'uid', 'content']);
        $todo = count($list);
        $done = 0;
        foreach ($list as $item) {
            $_content = json_decode($item['content']);
            $id = $this->data->commit(dataTicket::TYPE_REFUND_APPEAL, $item['uid'], [
                "reason" => "[Apply Untreated]{$_content->reason}",
                "lesson_sn" => $_content->lesson_sn,
                "refer_id" => $item['id'],
            ]);

            //向管理员发送通知
            $info = servUser::sole($this->platform)->uid2info($item['uid'], 'name, telephone');

            servMpMsg::sole($this->platform)->callNotice(
                '您好，' . $info['name'] . '的退款申请超过3天未处理，自动转到后台退款申诉，请及时处理',
                $info['name'],
                date('Y-m-d H:i'),
                $info['telephone']
            );


            if ($id) {
                $this->data->update([
                    'i_status' => dataTicket::STATUS_CLOSE
                ], ['id' => $item['id']]);
                $done++;
            }
        }
        return "$todo.$done";
    }


}