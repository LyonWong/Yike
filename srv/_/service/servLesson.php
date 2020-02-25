<?php


namespace _;


use Admin\servAccess;
use Core\library\Tool;
use Core\unitInstance;


class servLesson extends serv_
{
    use unitInstance;

    const PREFIX_SLICE_CACHE = 'SLICE_CACHE_';

    const FORM_MAP = [
        dataLesson::FORM_IM => 'im',
        dataLesson::FORM_VIEW => 'view',
        dataLesson::FORM_ARTICLE => 'article',
        dataLesson::FORM_COLUMN => 'column',
        dataLesson::FORM_TRY => 'try',
        dataLesson::FORM_IM_HIDE => 'im_hide'
    ];

    const STEP_MAP = [
        dataLesson::STEP_SUBMIT => 'submit',
        dataLesson::STEP_DENIED => 'denied',
        dataLesson::STEP_OPENED => 'opened',
        dataLesson::STEP_ONLIVE => 'onlive',
        dataLesson::STEP_REPOSE => 'repose',
        dataLesson::STEP_FINISH => 'finish',
        dataLesson::STEP_CLOSED => 'closed',
        dataLesson::STEP_HALTED => 'halted',
    ];

    const PROCESS_MAP = [
        dataLessonProcess::EVENT_SUBMIT => 'submit',
        dataLessonProcess::EVENT_OPENED => 'opened',
        dataLessonProcess::EVENT_ONLIVE => 'onlive',
        dataLessonProcess::EVENT_REPOSE => 'repose',
        dataLessonProcess::EVENT_FINISH => 'finish',
        dataLessonProcess::EVENT_MODIFY => 'modify',
        dataLessonProcess::EVENT_DENIED => 'denied',
        dataLessonProcess::EVENT_CLOSED => 'closed',
    ];

    const ACCESS_MAP = [
        dataLessonAccess::EVENT_BROWSE => 'browse',
        dataLessonAccess::EVENT_ENROLL => 'enroll',
        dataLessonAccess::EVENT_ACCESS => 'access',
        dataLessonAccess::EVENT_CONFIRM => 'confirm',
        dataLessonAccess::EVENT_LEAVE => 'leave',
        dataLessonAccess::EVENT_RECEIVE => 'receive',
        dataLessonAccess::EVENT_INVITED => 'invited',
        dataLessonAccess::EVENT_RESET => 'reset',
        dataLessonAccess::EVENT_CANCEL => 'cancel',
        dataLessonAccess::EVENT_REFUND => 'refund',
    ];

    const RECORD_FORM_MAP = [
        dataLessonRecord::FORM_TIM => 'tim',
        dataLessonRecord::FORM_VIEW => 'view'
    ];

    const REFUND_MODE_FREELY = 'freely';
    const REFUND_MODE_APPLY = 'apply';
    const REFUND_MODE_APPEAL = 'appeal';

    /**
     * @param $platform
     * @return self
     */
    public static function sole($platform = null)
    {
        return self::_singleton($platform);
    }

    public function makeCoverPath($lessonSn)
    {
        return "lesson/$lessonSn/cover";
    }

    public function create($uid, unitLesson $unitLesson)
    {
        $id = dataLesson::sole($this->platform)->append($uid, $unitLesson);
        if ($id) {
            $sn = $this->id2sn($id);
            $profile = $this->sn2profile($sn);
        } else {
            $profile = false;
        }
        return $profile;
    }

    public function edit($sn, unitLesson $unitLesson)
    {
        return dataLesson::sole($this->platform)->modify($sn, $unitLesson);
    }

    /**
     * 反转i_form，标记删除
     * @param $sn
     * @return bool|mixed
     */
    public function delete($sn)
    {
        return dataLesson::sole($this->platform)->update("i_form=-i_form", ['sn' => $sn])->rowCount();
    }

    public function browse($lessonSn, $uid, $originId)
    {
        $lessonId = dataLesson::sole($this->platform)->fetchOne(['sn' => $lessonSn], 'id', 0);
        $access = dataLessonAccess::sole($this->platform);
        if (!$access->lastEventOnLesson($lessonId, $uid, [dataLessonAccess::EVENT_BROWSE])) {
            $access->append($lessonId, $uid, dataLessonAccess::EVENT_BROWSE);
            stats\servLesson::sole($this->platform)->varBrowse($lessonId, $originId);
            return true;
        } else {
            return false;
        }
    }

    public function audition($lessonSn, $uid)
    {
        $lessonId = dataLesson::sole($this->platform)->fetchOne(['sn' => $lessonSn], 'id', 0);
        $access = dataLessonAccess::sole($this->platform);
        $access->append($lessonId, $uid, dataLessonAccess::EVENT_AUDITION);
    }

    public function enable($lessonId, $uid, $originId, $redo=false)
    {
        $dao = dataLessonAccess::sole($this->platform);
        if ($this->isEnrolled($lessonId, $uid)) {
            if ($redo &&
                !$dao->fetchOne([
                    'lesson_id' => $lessonId,
                    'uid' => $uid,
                    'i_event' => dataLessonAccess::EVENT_ENROLL,
                    "args->'$.origin' = $originId"], 'id', 0)
            ) {
                dataLessonAccess::sole($this->platform)->append($lessonId, $uid, dataLessonAccess::EVENT_ENROLL, ['origin'=>$originId]);
                stats\servLesson::sole($this->platform)->varEnroll($lessonId, $originId);
            }
            return true;
        } else {
            dataLessonAccess::sole($this->platform)->append($lessonId, $uid, dataLessonAccess::EVENT_ENROLL, ['origin'=>$originId]);
            stats\servLesson::sole($this->platform)->varEnroll($lessonId, $originId);
            return true;
        }
    }

    public function access($lessonSn, $uid)
    {
        $lesson = dataLesson::sole($this->platform)->fetchOne(['sn' => $lessonSn], ['id', 'tuid']);
        $iEvents = dataLessonAccess::sole($this->platform)->events($lesson['id'], $uid);
        $column = array_column($iEvents, 'args', 'i_event');
        $originId = $column[dataLessonAccess::EVENT_ENROLL]['origin'] ?? 0; //从报名记录中获取来源ID
        //统计课程进入，并区分是否首次
        if (!isset($column[dataLessonAccess::EVENT_ACCESS])) {
            stats\servLesson::sole($this->platform)->varAccess($lesson['id'], $originId, true);
            servTrigger::sole($this->platform)->onLessonFirstEntry($lessonSn, $uid);
        }
        $args = [
            'ip' => \input::ip()
        ];
        dataLessonAccess::sole($this->platform)->append($lesson['id'], $uid, dataLessonAccess::EVENT_ACCESS, $args);
    }

    //用户下线，离开课程
    public function leave($usn, $args=[])
    {
        $uid = servUser::sole($this->platform)->usn2uid($usn);
        $access = dataLessonAccess::sole($this->platform);
        $list = $access->lastEvents($uid);
        foreach ($list as $item) {
            //离开课堂
            if (in_array($item['i_event'], [dataLessonAccess::EVENT_ACCESS, dataLessonAccess::EVENT_CONFIRM])) {
                $lessonSn = $this->id2sn($item['lesson_id']);
                servTIM::sole($this->platform)->deleteGroupMember("$lessonSn-D", $usn);
                $access->append($item['lesson_id'], $item['uid'], dataLessonAccess::EVENT_LEAVE, $args);
            }
        }
    }

    public function pause($lessonSn)
    {
        $room = self::sn2room($lessonSn);
        servTIM::sole($this->platform)->tim()->group_send_group_system_notification2($room['teach'], "讲师暂时离开", []);
    }

    public function repose($lessonSn, $uid)
    {
        $lessonId = self::sn2id($lessonSn);
        dataLessonProcess::sole($this->platform)->append($lessonId, $uid, dataLessonProcess::EVENT_REPOSE);
        dataLesson::sole($this->platform)->update([
            'i_step' => dataLesson::STEP_REPOSE
        ], ['id' => $lessonId]);
        servTrigger::sole($this->platform)->onLessonRepose($lessonSn);
        $room = self::sn2room($lessonSn);
        servTIM::sole($this->platform)->systemMessage(
            $room['teach'],
            servTIM::SYS_MSG_HINT,
            '正课结束，现在是课后交流时间');
        return true;
    }

    public function finish($lessonSn, $uid)
    {
        $lessonId = self::sn2id($lessonSn);
        $lesson = self::sn2info($lessonSn, ['id', 'i_form']);
        dataLessonProcess::sole($this->platform)->append($lesson['id'], $uid, dataLessonProcess::EVENT_FINISH);
        dataLesson::sole($this->platform)->update([
            'i_step' => dataLesson::STEP_FINISH
        ], ['id' => $lessonId]);
        $room = self::sn2room($lessonSn);
        servTIM::sole($this->platform)->systemMessage(
            $room['teach'],
            servTIM::SYS_MSG_NOTE,
            'finish'
        );
        dataLessonRecord::sole($this->platform)->append($lesson['id'], 1, $lesson['i_form'], json_encode([
            [
                'MsgType' => 'SYSTEM',
                'MsgCommand' => 'lessonStep',
                'MsgContent' => 'finish'
            ]
        ]));
        servTrigger::sole($this->platform)->onLessonFinish($lessonSn);
        return true;
    }

    public function close($lessonSn, $uid)
    {
        $lessonId = self::sn2id($lessonSn);
        dataLessonProcess::sole($this->platform)->append($lessonId, $uid, dataLessonProcess::EVENT_CLOSED);
        dataLesson::sole($this->platform)->update([
            'i_step' => dataLesson::STEP_CLOSED
        ], ['id' => $lessonId]);
    }

    public function online($lessonSn)
    {
        $lessonId = $this->sn2id($lessonSn);
        $online = dataLessonAccess::sole($this->platform)->unique($lessonId, [
            dataLessonAccess::EVENT_ACCESS,
            dataLessonAccess::EVENT_CONFIRM,
        ]);
        return $online;
    }

    public function onlineList($lessonSn): array
    {
        $lessonId = $this->sn2id($lessonSn);
        $list = dataLessonAccess::sole($this->platform)->tillEvent($lessonId, dataLessonAccess::EVENT_ACCESS);
        foreach ($list as $uid => &$item) {
            $item['user'] = servUser::sole($this->platform)->uid2profile($uid);
        }
        return $list;
    }

    public function checkAccess($lessonSn, $usn, &$message = null)
    {
        $uid = servUser::sole($this->platform)->usn2uid($usn);
        $lesson = dataLesson::sole($this->platform)->fetchOne(['sn' => $lessonSn], ['id', 'tuid', 'category', 'price']);

        if (!$uid || !$lesson) {
            return false;
        }

        // 试听权限
        if (servPromote::sole($this->platform)->audition($uid, $lesson['id'])) {
            $message = 'audition';
            return true;
        }

        //管理员视察
        $isAdminInspect = servAccess::inst($uid, servAccess::SCOPE_ADMIN)
            ->assign('admin')
            ->isAllowed('lesson-inspect', servAccess::PRIV_VIEW);
        if ($isAdminInspect) {
            return true;
        }
        if ($this->hasEvent($lessonSn, $uid, dataLessonAccess::EVENT_INVITED)) {
            return true;
        }

        if ($lesson['tuid'] == $uid) { //讲师登录
            return true;
        }

        $suid = dataLessonSeries::sole($this->platform)->fetchOne(['sn' => $lesson['category']], 'uid', 0);
        if ($uid == $suid) {
            return true;
        }

        if (substr($lesson['category'], 0, 1) == 'L') { // 专栏从属
            $lid = $this->sn2id($lesson['category']);
            $iEvent = dataLessonAccess::sole($this->platform)->lastEventOnLesson($lid, $uid, [
                    dataLessonAccess::EVENT_ENROLL,
                    dataLessonAccess::EVENT_ACCESS,
                    dataLessonAccess::EVENT_INVITED,
                    dataLessonAccess::EVENT_REFUND,
                    dataLessonAccess::EVENT_RESET,
                ])['i_event'] ?? null;
            if ($iEvent > 0) { // 已订阅专栏
                return true;
            }
        }
        $iEvent = dataLessonAccess::sole($this->platform)->lastEventOnLesson($lesson['id'], $uid, [
                dataLessonAccess::EVENT_ENROLL,
                dataLessonAccess::EVENT_ACCESS,
                dataLessonAccess::EVENT_INVITED,
                dataLessonAccess::EVENT_REFUND,
                dataLessonAccess::EVENT_RESET,
            ])['i_event'] ?? null;
        $message = $iEvent ? "$iEvent#" . servLesson::ACCESS_MAP[$iEvent] : "No access to lesson";
        return $iEvent > 0;
    }

    public function checkSpeak($lessonSn, $usn)
    {
        $uid = servUser::sole($this->platform)->usn2uid($usn);
        $lesson = dataLesson::sole($this->platform)->fetchOne(['sn' => $lessonSn], ['id', 'tuid', 'category']);

        //管理员视察
        $isAdminInspect = servAccess::inst($uid, servAccess::SCOPE_ADMIN)
            ->assign('admin')
            ->isAllowed('lesson-inspect', servAccess::PRIV_EDIT);

        if ($lesson['tuid'] == $uid) {
            return true;
        }
        if ($lesson['category']) {
            $suid = dataLessonSeries::sole($this->platform)->fetchOne(['sn' => $lesson['category']], 'uid', 0);
            if ($suid == $uid) {
                return true;
            }
        }
        if ($this->hasEvent($lessonSn, $uid, dataLessonAccess::EVENT_INVITED)) {
            return true;
        }
        if ($isAdminInspect) {
            return true;
        }
        return false;
    }

    public function inviteGuest($lessonSn, $usn)
    {
        $lessonId = $this->sn2id($lessonSn);
        $uid = servUser::sole($this->platform)->usn2uid($usn);
        dataLesson::sole($this->platform)->addGuest($lessonSn, $usn);
        return dataLessonAccess::sole($this->platform)->append($lessonId, $uid, dataLessonAccess::EVENT_INVITED);
    }

    public function checkRefundMode($lessonSn, $uid)
    {
        $lessonId = $this->sn2id($lessonSn);

        $access = dataLessonAccess::sole($this->platform)->events($lessonId, $uid, [
            dataLessonAccess::EVENT_ACCESS, dataLessonAccess::EVENT_ENROLL
        ]);
        $accessTime = strtotime(current($access)['tms']);
        if ($accessTime > strtotime('-1 hour')) {
            return self::REFUND_MODE_FREELY;
        } elseif ($accessTime > strtotime('-7 days')) {
            //todo 增加申诉退款判断
            $iStatus = dataTicket::sole($this->platform)->fetchOne(
                [
                    '_id' => $lessonId,
                    'uid' => $uid,
                    'i_type' => dataTicket::TYPE_REFUND_APPLY
                ],
                'i_status', 'i_status');
            if ($iStatus) {
                return false;
            } else {
                return self::REFUND_MODE_APPLY;
            }

        } else {

            $iStatus = dataTicket::sole($this->platform)->fetchOne(
                [
                    '_id' => $lessonId,
                    'uid' => $uid,
                    'i_type' => dataTicket::TYPE_REFUND_APPEAL
                ],
                'i_status', 'i_status');
            if ($iStatus) {
                return false;
            } else {
                return self::REFUND_MODE_APPEAL;
            }
        }
    }

    public function returnRefundMode($lessonSn, $uid)
    {
        $lessonId = $this->sn2id($lessonSn);

        $lastAccess = dataLessonAccess::sole($this->platform)->lastEventOnLesson($lessonId, $uid);
        if ($lastAccess && $lastAccess['i_event'] == dataLessonAccess::EVENT_RESET) {
            return false;
        }
        $access = dataLessonAccess::sole($this->platform)->firstEventsOnLesson($lessonId, $uid);
        $order = dataOrder::sole($this->platform)->getInfo($uid, $lessonId);
        if (!$order || $order['i_status']<=0 || ($order['order_amount'] ?? 0) == 0) {
            return false;
        }
        if (isset($access[dataLessonAccess::EVENT_CONFIRM])) { // 已确认的订单不可退款
            return false;
        }
        if (isset($access[dataLessonAccess::EVENT_ACCESS])) {
            $accessTmi = strtotime($access[dataLessonAccess::EVENT_ACCESS]['tms']);
            if ((time() - $accessTmi) < SECONDS_HOUR) {
                return self::REFUND_MODE_FREELY;
            }
            $ticket = dataTicket::sole($this->platform)->fetchLastLessonItem($uid, $lessonSn, dataTicket::TYPE_REFUND_APPLY);
            if (!$ticket && (time() - $accessTmi) < SECONDS_DAY * 3) {
                //未提交申请且距离首次进入课程不超过三天，可向讲师申请退款
                return self::REFUND_MODE_APPLY;
            } elseif ($ticket && $ticket['i_status'] == dataTicket::STATUS_REJECT && time() < strtotime("$ticket[tms_update] +3 days")) {
                //提交退款被拒绝不超过三天，可以向平台申诉退款
                return self::REFUND_MODE_APPEAL;
            } else {
                return false;
            }
        } else {
            //报名未听课可自由退款
            return self::REFUND_MODE_FREELY;
        }
    }

    public function refund($lessonSn, $uid)
    {
        return \Student\pay\servRefund::sole($this->platform)->refund($lessonSn, $uid);
    }

    public function sn2id($lessonSn)
    {
        return dataLesson::sole($this->platform)->fetchOne(['sn' => $lessonSn], 'id', 0);
    }

    public function id2sn($id)
    {
        return dataLesson::sole($this->platform)->fetchOne(['id' => $id], 'sn', 'sn');
    }

    public function id2info($id, $fields='*')
    {
        return dataLesson::sole($this->platform)->inquireOne(['id' => $id], $fields);
    }

    public function createRecord($lessonSn, $fromUsn, $form, $content)
    {
        $lessonId = $this->sn2id($lessonSn);
        $uid = servUser::sole($this->platform)->usn2uid($fromUsn);
        $iForm = array_search($form, self::RECORD_FORM_MAP);
        $content = json_encode(json_decode($content), JSON_UNESCAPED_UNICODE);
        return dataLessonRecord::sole($this->platform)->append($lessonId, $uid, $iForm, $content);
    }

    public function setRecord($lessonSn, $fromUsn, $msgData)
    {
//        $lessonId = $this->sn2id($lessonSn);
        $msgBody = $msgData->MsgBody;
        $lesson = dataLesson::sole($this->platform)->fetchOne(['sn' => $lessonSn], ['id', 'i_step']);
        $fromUid = servUser::sole($this->platform)->usn2uid($fromUsn);
        if (!($lesson['id'] && $fromUid)) {
            return false;
        }
        //todo 增加失败处理
        foreach ($msgBody as &$msgItem) {
            $msgItem = $this->restoreRecord($msgItem, $msgData);
        }

        if ($lesson['i_step'] == dataLesson::STEP_ONLIVE) { // 直播状态设置触发
            servTrigger::sole($this->platform)->onLessonLive($lessonSn);
        }

        return dataLessonRecord::sole($this->platform)->append($lesson['id'], $fromUid, dataLessonRecord::FORM_TIM, json_encode($msgBody));
    }

    protected function restoreRecord($msgItem, $msgData)
    {
        $qiniu = servQiniu::inst();
        switch ($msgItem->MsgType) {
            case 'TIMCustomElem':
                $msgContent = &$msgItem->MsgContent;
                $msgContent->Ext .= '-' . $msgData->MsgSeq;
                $urls = parse_url($msgContent->Data);
                if (isset($urls['scheme']) && "$urls[scheme]://$urls[host]" == config::load('boot', 'public', 'upload')) {
//                    $name = substr($urls['path'], 1); //去掉开头`/`
//                    $qiniu->deleteAfterDays($name, 0);
                    //来自自身存储的资源不转存
                    break;
                }
                if (in_array($msgContent->Desc, ['QUOTE', 'MARK', 'ADMIRE'])) {
                    break;
                }
                $path = "lesson/record/" . md5($msgContent->Data) . "-$msgContent->Ext";
                $qiniu->fetch($msgContent->Data, $path);
                $msgContent->Data = \view::upload($path);
                break;
            case 'TIMImageElem':
                foreach ($msgItem->MsgContent->ImageInfoArray as &$imageItem) {
                    $path = "lesson/record/" . md5($imageItem->URL);
                    $qiniu->fetch($imageItem->URL, $path);
                    $imageItem->URL = \view::upload($path);
                }
                break;
        }
        return $msgItem;
    }

    public function getRecord($lessonSn, $iType)
    {
        $res = dataLessonRecord::sole($this->platform)->fetchAll([
            'lesson_id' => $this->sn2id($lessonSn),
            'i_type' => $iType
        ], ['from_uid', 'content', 'tms']
        );
        $servUser = servUser::sole($this->platform);
        foreach ($res as &$row) {
            $profile = $servUser->uid2profile($row['from_uid']);
            $row['from_account'] = $profile['sn'];
            $row['accountNick'] = $profile['name'];
            unset($row['from_uid']);
            $row['content'] = json_decode($row['content']);
            $row['sess']['_impl']['id'] = "$lessonSn-T";
        }
        return $res;
    }

    public function sliceRecord($iType, $lessonSn, $cursor, $toward, $limit)
    {
        list($id) = explode('-', $cursor);
        $lessonId = $this->sn2id($lessonSn);
        $daoRecord = dataLessonRecord::sole($this->platform);
        if ($toward == data::TOWARD_NEAR) {
            $res_ = $daoRecord->slice($iType, $lessonId, $id, data::TOWARD_FORE, round($limit / 2));
            $_res = $daoRecord->slice($iType, $lessonId, $id, data::TOWARD_PREV, $limit - count($res_));
            $res = array_merge($_res, $res_);
        } else {
            $res = dataLessonRecord::sole($this->platform)->slice($iType, $lessonId, $id, $toward, (int)$limit);
        }
        foreach ($res as &$row) {
            $row = $this->boostRecord($row, ['lessonSn' => $lessonSn]);
        }

        return $res;
    }

    public function record($iType, $lessonSn)
    {
        $lessonId = $this->sn2id($lessonSn);
        return $res = dataLessonRecord::sole($this->platform)->fetchAll([
            'i_type' => $iType,
            'lesson_id' => $lessonId,
        ], '*');
    }

    public function listRecord($lessonSn, $type=null)
    {
        $lessonId = $this->sn2id($lessonSn);
        $where = [
            'lesson_id' => $lessonId
        ];
        if ($iType = array_search($type, self::RECORD_FORM_MAP)) {
            $where['i_type'] = $iType;
        }
        return $res = dataLessonRecord::sole($this->platform)->fetchAll($where, '*');
    }

    public function viewRecord($recordId)
    {
        $res = dataLessonRecord::sole($this->platform)->fetchOne(['id' => $recordId], '*');
        $res['content'] = json_decode($res['content'], true);
        return $res;
    }

    public function deleteRecordById($cursor, $uid)
    {
        list($recordId) = explode('-', $cursor);
        $dao = dataLessonRecord::sole($this->platform);
        $pre = $dao->fetchOne(['id' => $recordId], ['lesson_id', 'i_type', 'from_uid']);
        if ($pre['from_uid'] != $uid) {
            return false;
        }
        $dao->append($pre['lesson_id'], $uid, $pre['i_type'], json_encode([[
           'MsgType' => 'SYSTEM',
           'MsgCommand' => 'deleteRecord',
           'MsgContent' => $cursor
        ]]));
        $rec['i_type'] = dataLessonRecord::TYPE_DELETE;
        return $dao->update($rec, ['id' => $recordId]);
    }

    public function modifyRecord($recordId, $content)
    {
        $dao = dataLessonRecord::sole($this->platform);
        $rec['content'] = $content;
        return $dao->update($rec, ['id' => $recordId]);
    }

    public function sliceCache($key, $val = null)
    {
        $srvCache = servCache::sole($this->platform);
        $rkey = servCache::TAG_LESSON_SLICE . $key;
        if ($val) {
            return $srvCache->setJson($rkey, $val, SECONDS_HOUR);
        } else {
            return $srvCache->getJson($rkey);
        }
    }

    public function boostRecord($row, $args)
    {
        $profile = servUser::sole($this->platform)->uid2profile($row['from_uid']);
        $row['cursor'] = "$row[id]-" . Tool::timeEncode($row['tms']);
        $row['from_account'] = $profile['sn'];
        $row['accountNick'] = $profile['name'];
        $row['content'] = json_decode($row['content'], true);
        $row['sess']['_impl']['id'] = "$args[lessonSn]-T";
        unset($row['id'], $row['from_uid']);
        return $row;
    }

    public function profile($key, $at = 'sn')
    {
        $res = dataLesson::sole($this->platform)->fetchOne([$at => $key],
            ['id', 'sn', 'title', 'price', 'brief', 'category', 'extra', 'i_form', 'i_step', 'tuid', 'plan', 'tms_update']);
        return $this->boost($res);
    }

    public function detail($key, $at = 'sn')
    {
        $res = dataLesson::sole($this->platform)->fetchOne([$at => $key], [
            'id', 'sn', 'title', 'brief', 'category', 'tags', 'i_form', 'extra', 'price', 'quota', 'i_step', 'plan', 'tuid', 'tms_update'
        ]);
        $detail = self::boost($res);
        $detail['id'] = $res['id'];
        $detail['teacher']['about'] = servTeacher::sole($this->platform)->datum($res['tuid'])['about'];
        return $detail;
    }

    public function sn2profile($sn)
    {
        $fields = ['id', 'sn', 'category', 'title', 'tuid', 'price', 'plan', 'i_step', 'i_form', 'extra'];
        $raw = dataLesson::sole($this->platform)->fetchOne(['sn' => $sn], $fields);
        return $this->boostProfile($raw ?? []);
    }

    public function sn2introduce($sn)
    {
        return dataLesson::sole($this->platform)->fetchOne(['sn' => $sn], ['brief'], 0);
    }

    public function sn2relative($sn)
    {
        $dao = dataLesson::sole($this->platform);
        $res = [];
        $self = $dao->fetchOne(['sn' => $sn], ['id','category', 'plan']);
        if (!$self['category']) {
            return $res;
        }
        $self['plan'] = json_decode($self['plan'], true);
        $fields = ['id', 'sn', 'category', 'title', 'tuid', 'price', 'plan', 'i_step', 'i_form'];
        $_raws = $dao->fetchAll(
            ['category' => $self['category'], 'i_step>0', 'i_form>0', "plan->>'$.dtm_start'<'{$self['plan']['dtm_start']}'"],
            $fields,
            null,
            null,
            "order by plan->'$.dtm_start' desc limit 2");
        $raws_ = $dao->fetchAll(
            ['category' => $self['category'], 'i_step>0', 'i_form>0', "plan->>'$.dtm_start'>'{$self['plan']['dtm_start']}'"],
            $fields,
            null,
            null,
            "order by plan->'$.dtm_start' limit 2");
        $raws = array_merge(array_reverse($_raws), $raws_);
        foreach ($raws as $raw) {
            $res[] = $this->boostProfile($raw);
        }
        return $res;
    }

    public function sn2nearby($sn, int $limit=1)
    {
        $dao = dataLesson::sole($this->platform);
        $self = $dao->fetchOne(['sn' => $sn], ['id', 'category', 'plan']);
        if (!$self['category']) {
            return null;
        }
        $self['plan'] = json_decode($self['plan'], true);
        $fields = ['id', 'sn', 'category', 'title', 'tuid', 'price', 'plan', 'i_step', 'i_form'];
        $prev = $dao->fetchAll(
            ['category' => $self['category'], 'i_step>0', 'i_form>0', "plan->>'$.dtm_start'<'{$self['plan']['dtm_start']}'"],
            $fields,
            null,
            null,
            "order by plan->'$.dtm_start' desc, id desc limit $limit"
        );
        $next = $dao->fetchAll(
            ['category' => $self['category'], 'i_step>0', 'i_form>0', "plan->>'$.dtm_start'>'{$self['plan']['dtm_start']}'"],
            $fields,
            null,
            null,
            "order by plan->'$.dtm_start', id limit $limit"
        );
        $res = [
            'prev' => [],
            'next' => []
        ];
        foreach ($prev as $raw) {
            $res['prev'][] = $this->boostProfile($raw);
        }
        foreach ($next as $raw) {
            $res['next'][] = $this->boostProfile($raw);
        }
        return $res;
    }

    public function sn2subview($sn)
    {
        $dao = dataLesson::sole($this->platform);
        $fields = ['id', 'sn', 'category', 'title', 'tuid', 'price', 'plan', 'i_step', 'i_form', 'extra'];
        $raws = $dao->fetchAll(['category'=>$sn, 'i_step>0', 'i_form>0'], $fields, null, null, 'order by id');
        $res = [];
        foreach ($raws as $raw) {
            $res[] = $this->boostProfile($raw);
        }
        return $res;
    }

    public function sn2info($lessonSn, $fields)
    {
        return dataLesson::sole($this->platform)->fetchOne(['sn' => $lessonSn], $fields);
    }

    public function sn2room($lessonSn)
    {
        return [
            'teach' => "$lessonSn-T",
            'discuss' => "$lessonSn-D"
        ];
    }

    public function id2tuid($lessonId)
    {
        return dataLesson::sole($this->platform)->fetchOne(['id' => $lessonId], ['tuid'], 0);
    }

    public function sn2tuid($lessonSn)
    {
        $lessonId = $this->sn2id($lessonSn);
        return $this->id2tuid($lessonId);
    }

    public function getRidOf($usn, $lessonSn)
    {
        $room = $this->sn2room($lessonSn);
        $tim = servTIM::sole($this->platform)->tim();
        $tim->group_delete_group_member($room['teach'], $usn, 1);
        $tim->group_delete_group_member($room['discuss'], $usn, 1);
    }

    /**
     * 设置课程系列
     * @param $lessonSn
     * @param $seriesSn
     */
    public function setSeries($lessonSn, $seriesSn)
    {
        if ($seriesSn) {
            $this->addSeries($lessonSn, $seriesSn);
        } else {
            $this->removeSeries($lessonSn);
        }
    }

    /**
     * 将课程添加到系列中
     * @param $lessonId
     * @param $seriesSn
     * @return bool|mixed
     */
    public function addSeries($lessonId, $seriesSn)
    {
        $lessonIds = dataLessonSeries::sole($this->platform)->fetchOne(
            ['sn' => $seriesSn],
            'lesson_ids', 0);
        $lessonIDs = json_decode($lessonIds, true);
        $lessonIDs[] = $lessonId;
        dataLesson::sole($this->platform)->update(
            ['category' => $seriesSn],
            ['id' => $lessonId]
        );
        return dataLessonSeries::sole($this->platform)->update(
            ['lesson_ids' => json_encode(array_unique($lessonIDs))],
            ['sn' => $seriesSn]
        )->rowCount();
    }

    /**
     * 移除课程系列
     * @param $lessonSn
     */
    public function removeSeries($lessonSn)
    {
        $lessonId = $this->sn2id($lessonSn);
        $dataLesson = dataLesson::sole($this->platform);
        $category = $dataLesson->fetchOne(['id' => $lessonId], 'category', 0);
        if ($category) {
            $dataLesson->update(['category' => ''], ['id' => $lessonId]);
            $dataSeries = dataLessonSeries::sole($this->platform);
            $lessonIds = $dataSeries->fetchOne(['sn' => $category], 'lesson_ids', 0);
            $lessonIDs = json_decode($lessonIds, true);
            $cursor = array_search($lessonId, $lessonIDs);
            if ($cursor !== false) {
                unset($lessonIDs[$cursor]);
                $dataSeries->update(
                    ['lesson_ids' => json_encode(array_values($lessonIDs))],
                    ['sn' => $category]
                );
            }
        }
    }

    /**
     * 下一课
     * @param $lessonId
     * @param $seriesSn
     * @return mixed|null
     */
    public function nextLessonId($lessonId, $seriesSn)
    {
        $ids = dataLessonSeries::sole($this->platform)->fetchOne(
            ['sn' => $seriesSn],
            'lesson_ids',
            0
        );
        $IDs = json_decode($ids, true);
        $lessons = $IDs ? dataLesson::sole($this->platform)->fetchByIDs($IDs, ['id', 'i_step', 'extra']) : [];
        $res = [];
        foreach ($lessons as $k => $lesson) {
            $categoryCheck = json_decode($lesson['extra'], true)['category_check'] ?? 0;
            $lesson['category_check'] = $categoryCheck;
            if (!$categoryCheck || $lesson['i_step'] < 1) {
                continue;
            }
            $res[] = $lesson['id'];
        }
        $k = array_search($lessonId, $res);
        return $res[$k + 1] ?? null;
    }

    public function seekOriginId($lessonId, $uid)
    {
        if ($originId = dataOrder::sole($this->platform)->fetchOne(
            ['lesson_id' => $lessonId, 'uid' => $uid, 'i_status<>0'],
            ['origin_id'], 0)
        ) {
            return $originId;
        } else {
            $originId = dataUser::sole($this->platform)->fetchOne(['id' => $uid], 'origin_id', 0);
            return $originId;
        }
    }


    public function sn2cover($lessonSn)
    {
        return json_decode(dataLesson::sole($this->platform)->fetchOne(['sn' => $lessonSn], 'extra->"$.cover" as cover', 'cover'));
    }

    public function sn2banner($lessonSn)
    {
        $res = dataLesson::sole($this->platform)->fetchOne(['sn' => $lessonSn], 'extra->>"$.cover" as cover, extra->>"$.banner" as banner');
        return $res['banner'] ?? $res['cover'];
    }


    public function hasEvent($lessonSn, $uid, $iEvent): bool
    {
        $lessonId = $this->sn2id($lessonSn);
        $id = dataLessonAccess::sole($this->platform)->fetchOne([
            'lesson_id' => $lessonId,
            'uid' => $uid,
            'i_event' => $iEvent
        ], 'id', 0);
        return boolval($id);
    }

    public function hasRefundApply($lessonSn, $uid)
    {
        $ret = servTicket::sole($this->platform)->sn2refundInfo($lessonSn, $uid);
        return boolval($ret);
    }

    public function boostProfile($raw)
    {
        if (substr($raw['category'], 0, 1) == 'S') {
            $series = dataLessonSeries::sole($this->platform)->inquireOne(['sn' => $raw['category']], 'sn,name,i_status,scheme->"$.price" as price');
            $series['status'] = servLessonSeries::STATUS_MAP[$series['i_status']];
            unset($series['i_status']);
        }  elseif (substr($raw['category'], 0, 1) == 'L') {
            $series = dataLesson::sole($this->platform)->fetchOne(['sn' => $raw['category']], ['sn', 'title', 'i_form', 'price']);
            $series['price'] /= 100;
            $series['form'] = self::FORM_MAP[abs($series['i_form'])];
            unset($series['i_form']);
        } else {
            $series = null;
        }
        $summary = stats\servLesson::sole($this->platform)->getSummary($raw['id']);
        $plan = json_decode($raw['plan'], true);
        $plan['countdown'] = strtotime($plan['dtm_start'] ?? 'now') - time();
        $plan['dtm_now'] = date('Y-m-d H:i:s');
        $conf = json_decode($raw['extra']??null, true)['conf'] ?? [];
        $profile = [
            'sn' => $raw['sn'],
            'type' => 'lesson',
            'title' => $raw['title'],
            'cover' => \view::upload(self::sn2cover($raw['sn'])),
            'banner' => \view::upload(self::sn2banner($raw['sn'])),
            'series' => $series,
            'teacher' => servUser::sole($this->platform)->uid2profile($raw['tuid']),
            'progress' => [1, $raw['i_step'] > dataLesson::STEP_OPENED ? 0 : 1],
            'status' => self::STEP_MAP[$raw['i_step']],
            'form' => self::FORM_MAP[abs($raw['i_form'])],
            'show' => $raw['i_form'] > 0,
            'browse' => $summary['lesson.browse.unique'] ?? 0,
            'enrollment' => $summary['lesson.enroll.unique'] ?? 0,
            'price' => $raw['price']/100,
            'plan' => $plan,
            'conf' => [
                'indie' => $conf['indie'] ?? null, // 独立付费
                'discuss' => $conf['discuss'] ?? true // 开启留言
            ]
        ];
        return $profile;
    }

    public function boost($raw)
    {
        $res = [];
        foreach ($raw as $key => $item) {
            switch ($key) {
                case 'id':
                    $res['participants'] = dataLessonAccess::sole($this->platform)->count($item);
                    break;
                case 'sn':
                    $res['sn'] = $item;
                    $res['cover'] = \view::upload(self::sn2cover($raw['sn']));
                    break;
                case 'i_form':
                    $res['form'] = self::FORM_MAP[abs($item)] ?? null;
                    $res['isPublic'] = $raw['i_form'] > 0 ? "1" : "0";
                    $res['overt'] = $raw['i_form'] > 0;
                    break;
                case 'i_step':
                    $res['step'] = self::STEP_MAP[$item] ?? null;
                    break;
                case 'plan':
                    $res['plan'] = json_decode($item, true);
                    $res['plan']['countdown'] = strtotime($res['plan']['dtm_start'] ?? 'now') - time();
                    $res['plan']['dtm_now'] = date('Y-m-d H:i:s');
                    break;
                case 'tuid':
                    $res['teacher'] = servUser::sole($this->platform)->uid2profile($item);
                    break;
                case 'price':
                    $res['price'] = $raw['price'] / 100;
                    break;
                case 'category':
//                    $categoryCheck = json_decode($raw['extra'],true)['category_check'] ?? 0;
                    $res['category'] = $raw['category'];
                    if (substr($res['category'], 0, 1) === 'L') {
                        $res['categoryInfo'] = $this->detail($res['category']);
                    }
                    if (substr($res['category'], 0, 1) === 'S') {
                        $res['categoryInfo'] = servLessonSeries::sole($this->platform)->detail($raw['category']);
                    }
                    break;
                case 'extra':
                    $res['extra'] = json_decode($raw['extra'], true);
                    if (isset($res['extra']['banner'])) {
                        $res['banner'] = \view::upload($res['extra']['banner']);
                    }
                    break;
                default:
                    $res[$key] = $item;
                    break;
            }
        }
        return $res;
    }

    public function isEnrolled($lessonId, $uid): bool
    {
        $lastAccess = dataLessonAccess::sole($this->platform)->lastEventOnLesson($lessonId, $uid, [
            dataLessonAccess::EVENT_ENROLL,
            dataLessonAccess::EVENT_REFUND,
            dataLessonAccess::EVENT_RESET,
        ]);
        if ($lastAccess && $lastAccess['i_event'] == dataLessonAccess::EVENT_RESET) {
            return false;
        }
        $id = dataLessonAccess::sole($this->platform)->fetchOne([
            'lesson_id' => $lessonId,
            'uid' => $uid,
            'i_event' => dataLessonAccess::EVENT_ENROLL
        ], 'id', 0);
        return boolval($id);
    }

    public function recent($lessonSn, $uid, array $event = [])
    {
        $lessonId = $this->sn2id($lessonSn);
        $res = dataLessonAccess::sole($this->platform)->lastEventOnLesson($lessonId, $uid, $event);
        $mode = self::returnRefundMode($lessonSn, $uid);
        $refundInfo = servTicket::sole($this->platform)->sn2refundInfo(servLesson::sole($this->platform)->id2sn($lessonId), $uid);
        $recent = [
            'event' => isset($res['i_event']) ? self::ACCESS_MAP[$res['i_event']] : null,
            'args' => $res['args'] ?? null,
            'tms' => $res['tms'] ?? null,
            'refund_mode' => $mode,
            'refund_info' => $refundInfo
        ];
        if ($mode === self::REFUND_MODE_FREELY) {
            $access = dataLessonAccess::sole($this->platform)->firstEventOnLesson($lessonId, $uid, [dataLessonAccess::EVENT_ACCESS]);
            if ($access) {
                $recent['refund_countdown'] = SECONDS_HOUR - (time() - strtotime($access['tms']));
            }
        }
        return $recent;
    }

    public function id2extra($lessonId)
    {
        $extra = dataLesson::sole($this->platform)->fetchOne(['id' => $lessonId], 'extra', 'extra');
        return json_decode($extra, true);
    }

    public function step($lessonSn, $iStep = null)
    {
        if ($iStep) {
            $res = dataLesson::sole($this->platform)->update(['i_step' => $iStep], ['sn' => $lessonSn])->rowCount();
        } else {
            $res = dataLesson::sole($this->platform)->fetchOne(['sn' => $lessonSn], 'i_step', 0);
        }
        return $res;
    }

    public function conf($lessonSn)
    {
        $conf = dataLesson::sole($this->platform)->fetchOne(['sn' => $lessonSn], 'extra->"$.conf"', 0);
        if ($conf) {
            return json_decode($conf, true);
        } else {
            $menu = [
                [
                    'text' => '返回首页',
                    'href' => '/',
                ],
//                [
//                    'text' => '优惠活动',
//                    'href' => "/promote?target_sn=$lessonSn"
//                ]
            ];
            $activity = [
                'text' => '邀请有奖',
                'href' => "/promote/invite?sn=$lessonSn"
            ];
            $conf = [
                'menu' => $menu,
                'activity' => $activity,
            ];
            return $conf;
        }
    }

    public function liveConf($lessonSn, $uid)
    {
        $url = "$_SERVER[REQUEST_SCHEME]://" . \config::load('boot', 'public', 'domain', '', 'Student');

        $usn = servUser::sole($this->platform)->uid2usn($uid);
        if ($this->checkSpeak($lessonSn, $usn)) {
            return $conf = [
                'menu' => [
                    [
                        'key' => 'home',
                        'text' => '回到首页',
                        'href' => $url,
                    ],
                    [
                        'key' => 'board',
                        'text' => '讨论交流',
                        'href' => "$url/#/course/message/$lessonSn"
                    ],
                    [
                        'key' => 'admire',
                        'text' => '赞赏讲师',
                        'href' => ''
                    ]
                ],
                'footer' => [
                    [
                        'key' => 'board',
                        'text' => '参与讨论交流',
                        'href' => "$url/#/course/message/$lessonSn"
                    ]
                ],
            ];
        }
        $lesson = dataLesson::sole($this->platform)->fetchOne(['sn' => $lessonSn], ['id', 'tuid', 'category', 'i_step']);
        $menu = [
            [
                'key' => 'home',
                'text' => '回到首页',
                'href' => $url,
            ]
        ];
        $rated = servRating::sole($this->platform)->rated($lessonSn, $uid);
        if (!$rated) {
            $menu[] = [
                'key' => 'rate',
                'text' => '评价课程',
                'href' => $url . '/weixin-evaluate?lesson_sn=' . $lessonSn
            ];

            $footer[] = [
                'key' => 'rate',
                'text' => '评价课程',
                'href' => $url . '/weixin-evaluate?lesson_sn=' . $lessonSn
            ];
        } elseif ($lesson['i_step'] == dataLesson::STEP_FINISH) {
            $footer[] = [
                'key' => 'rate',
                'text' => '查看评价',
                'href' => $url . '/weixin-courseTask?lesson_sn=' . $lessonSn
            ];
        }
        $refundMode = $this->returnRefundMode($lessonSn, $uid);
        if ($refundMode) {
            $menu[] = [
                'key' => 'refund',
                'text' => '申请退款',
                'href' => $url . '/weixin-refundPage?lesson_sn=' . $lessonSn . '&mode=' . $refundMode
            ];
        }

        $menu[] = [
            'key' => 'admire',
            'text' => '赞赏讲师',
            'href' => ''
        ];


        $ifFollow = servRelation::sole($this->platform)->isFollow($uid, $lesson['tuid']);

        if (0 && !$ifFollow) {
            $tusn = servUser::sole($this->platform)->uid2usn($lesson['tuid']);
            $footer[] = [
                'key' => 'follow',
                'text' => '关注讲师',
                'href' => $url . '/weixin-teacherCourse?tusn=' . $tusn
            ];
        }
        if ($lesson['category']) {
            $nextLessonId = $this->nextLessonId($lesson['id'], $lesson['category']);
            if ($nextLessonId) {
                $lesson = dataLesson::sole($this->platform)->fetchOne(['id' => $nextLessonId], ['sn', 'i_step']);
                $lessonSn = $lesson['sn'];
                $isEnrolled = $this->isEnrolled($nextLessonId, $uid);
                if ($lesson['i_step'] < dataLesson::STEP_ONLIVE || !$isEnrolled) {
                    $url = $url . '/weixin-lesson?lesson_sn=' . $lessonSn;
                } else {

                    $url = $url . "/live?isOwner=no&lesson_sn=$lessonSn&teach=$lessonSn-T&discuss=$lessonSn-D#/";
                }

                $footer[] = [
                    'key' => 'next',
                    'text' => '观看下一节',
                    'href' => $url
                ];
            }
        }
        $conf = [
            'menu' => $menu,
            'footer' => $footer,
        ];

        return $conf;


    }


    public function activityConf($lessonSn)
    {
        $conf = dataLesson::sole($this->platform)->fetchOne(['sn' => $lessonSn], 'extra->"$.activity_conf"', 0);
        if ($conf) {
            return json_decode($conf, true);
        } else {
            $menuIcon = [];
            return [
                'commissionRatio' => 0.3,
                'discountRatio' => 0.0,
                'menu_icon' => $menuIcon,
                'data' => [],
            ];
        }
    }

    public function updateActivityConf($lessonSn, $conf)
    {
        $extra = dataLesson::sole($this->platform)->fetchOne(['sn' => $lessonSn], 'extra', 0);
        $extra = json_decode($extra, true);
        $extra['activity_conf'] = json_decode($conf, true);
        return dataLesson::sole($this->platform)->update(['extra' => json_encode($extra)], ['sn' => $lessonSn])->rowCount();
    }

    public function updateConf($lessonSn, array $conf)
    {
        $extra = dataLesson::sole($this->platform)->fetchOne(['sn' => $lessonSn], 'extra', 0);
        $extra = json_decode($extra, true);
        $extra['conf'] = arrayMergeForce($extra['conf'] ?? [], $conf);
        return dataLesson::sole($this->platform)->update(['extra' => json_encode($extra)], ['sn' => $lessonSn])->rowCount();
    }


    public function reviewCreate($lessonSn)
    {
        $lesson = dataLesson::sole($this->platform)->inquireOne(['sn' => $lessonSn], ['tuid', 'plan']);
        servLesson::sole($this->platform)->step($lessonSn, dataLesson::STEP_OPENED);
        servTrigger::sole($this->platform)->onLessonStartSet($lessonSn, $lesson['plan']['dtm_start']);
        servMpMsg::sole($this->platform)->sendReview($lessonSn, true);
    }

    public function updateByparams($lessonSn, $params, $cover, $banner)
    {
        $lesson = dataLesson::sole($this->platform)->fetchOne(['sn' => $lessonSn], ['sn', 'title', 'brief', 'category', 'tags', 'i_form', 'price', 'plan', 'extra']);
        if ($lesson) {
            $lesson['extra'] = json_decode($lesson['extra'], true);
            $lesson['plan'] = json_decode($lesson['plan'], true);

            if (isset($params['brief'])) {
                $lesson['brief'] = $params['brief'];
            }
            if (isset($params['title'])) {
                $lesson['title'] = $params['title'];
            }
            if (isset($params['price'])) {
                $lesson['price'] = $params['price'] * 100;
            }
            if (isset($params['duration'])) {
                $lesson['plan']['duration'] = $params['duration'];
            }
            if (isset($params['dtm_start'])) {
                $lesson['plan']['dtm_start'] = $params['dtm_start'];
            }
            if (isset($params['isPublic'])) {
                $lesson['i_form'] = $params['isPublic'] ? abs($lesson['i_form']) : -abs($lesson['i_form']);
            }
            if (!empty($cover)) {
                //删除旧图
                if (isset($lesson['extra']['cover']) && $lesson['extra']['cover'] != 'lesson/cover/tryLesson') {
                    servQiniu::inst()->delete($lesson['extra']['cover']);
                }
                $key = uniqid('lesson/cover/');
                servQiniu::inst()->putFile($key, $cover);
                $lesson['extra']['cover'] = $key;
            }

            if (!empty($banner)) {
                //删除旧图
                if (isset($lesson['extra']['banner'])) {
                    servQiniu::inst()->delete($lesson['extra']['banner']);
                }
                $key = uniqid('lesson/banner/');
                servQiniu::inst()->putFile($key, $banner);
                $lesson['extra']['banner'] = $key;
            }

            $lesson['plan'] = json_encode($lesson['plan']);
            $lesson['extra'] = json_encode($lesson['extra']);
            return dataLesson::sole($this->platform)->update($lesson, ['sn' => $lessonSn])->rowCount();

        }

        return false;
    }

    public function sn2extra($lessonSn, $path=null)
    {
        if ($path) {
            $field = "extra->'$.$path'";
        } else {
            $field = "extra";
        }
        $res = dataLesson::sole($this->platform)->fetchOne(['sn' =>$lessonSn], $field, 0);
        return json_decode($res, true);
    }

    public function isOwner($lessonId, $uid)
    {
        $tuid = dataLesson::sole($this->platform)->fetchOne(['id' => $lessonId], 'tuid', 'tuid');
        return $tuid == $uid ? true : false;
    }

    public function lastLessonIevent($lessonSn, $uid)
    {
        $lessonId = servLesson::sole($this->platform)->sn2id($lessonSn);
        return dataLessonAccess::sole($this->platform)->lastAccessEventsByLessonUid($lessonId, $uid);
    }

    public function admire($uid, $lessonId, $amount)
    {
        $tuid = $this->id2tuid($lessonId);
        // 赞赏金额99%归讲师
        servMoney::sole($this->platform)->change(dataMoney::ITEM_ADMIRE, $tuid, $amount-round($amount*0.01), ['lesson_id' => $lessonId, 'amount' => $amount, 'uid' => $uid]);
        // send admire message
        $content = [
            'MsgType' => 'TIMCustomElem',
            'MsgContent' => [
                'Data' => "$amount",
                'Desc' => 'ADMIRE',
                'Ext' => ''
            ]
        ];
        $usn = servUser::sole($this->platform)->uid2usn($uid);
        $lsn = $this->id2sn($lessonId);
        $room = $this->sn2room($lsn);
        servTIM::sole($this->platform)->tim()->group_send_group_msg2($usn, $room['teach'], [$content]);
    }

}