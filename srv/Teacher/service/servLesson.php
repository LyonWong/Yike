<?php


namespace Teacher;

use _\dataLessonAccess;
use _\dataTicket;
use _\servLessonSeries;
use _\servMpMsg;
use _\servQiniu;
use _\servTicket;
use _\servTIM;
use _\servTrigger;
use Core\unitInstance;
use Core\unitInstance_;

class servLesson extends \_\servLesson
{
    use unitInstance_;

    /**
     * @param $platform
     * @return self
     */
    public static function sole($platform = null)
    {
        return self::_singleton($platform);
    }

    public function create($tuid, \_\unitLesson $unitLesson, $review = true)
    {
        if ($unitLesson->cover) {
            $realCover = str_replace('draft', 'lesson', $unitLesson->cover);
            //七牛图片设置为永久有效
            servQiniu::inst()->deleteAfterDays($unitLesson->cover, 0);
            servQiniu::inst()->move($unitLesson->cover, $realCover);
            $unitLesson->cover = $realCover;
        }
        $lessonId = dataLesson::sole($this->platform)->append($tuid, $unitLesson);
        if (!$lessonId) {
            return false;
        }
        if ($lessonId && $unitLesson->category) {
            //加到系列课
            servLesson::sole($this->platform)->addSeries(intval($lessonId), $unitLesson->category);
        }
        $sn = dataLesson::sole($this->platform)->fetchOne(['id' => $lessonId], 'sn', 0);
        if ($review == true) {
            dataTicket::sole($this->platform)->commit(dataTicket::TYPE_CREATE_LESSON, $tuid, [
                'lesson_sn' => $sn
            ]);
            //向管理员发送审核通知
            $info = servUser::sole($this->platform)->uid2info($tuid, 'name, telephone');

            servMpMsg::sole($this->platform)->callNotice(
                '您好，讲师 ' . $info['name'] . ' 新建了课程《' . $unitLesson->title . '》,有待审核',
                $info['name'] . "\r\n课程串码：" . $sn,
                date('Y-m-d H:i'),
                $info['telephone']
            );
        }
        return $sn;

    }

    public function modify($lessonSn, $params, $uid,$review = true)
    {
        $lessonId = $this->sn2id($lessonSn);
        if ($lessonId) {
            if (isset($params['cover'])) {
                $realCover = str_replace('draft', 'lesson', $params['cover']);
                $coverPath = self::sn2cover($lessonSn);
                if ($params['cover'] != $coverPath) {
//                    $params['extra'] = json_encode([
//                        'cover' => $params['cover'],
//                    ]);
                    $params['extra'] = ['cover' => $realCover];
                    $params['tms_update'] = date('Y-m-d H:i:s');
                    //七牛图片设置为永久有效
                    servQiniu::inst()->deleteAfterDays($params['cover'], 0);
                    servQiniu::inst()->move($params['cover'], $realCover);
                }
                unset($params['cover']);
            }
            if (isset($params['price'])) {
                $params['price'] *= 100;
            }
            if (isset($params['dtm_start'], $params['duration'])) {
                $params['plan'] = [
                    'dtm_start' => $params['dtm_start'],
                    'duration' => $params['duration']
                ];
                unset($params['dtm_start'], $params['duration']);
            }
            //提交工单
            $ret = dataTicket::sole($this->platform)->commit(dataTicket::TYPE_MODIFY_LESSON, $uid, $params);

            if($review) {
                //向管理员发送审核通知
                $info = servUser::sole($this->platform)->uid2info($uid, 'name, telephone');

                $title = \_\dataLesson::sole($this->platform)->fetchOne(['sn'=>$lessonSn],'title',0);
                servMpMsg::sole($this->platform)->callNotice(
                    '您好，讲师 ' . $info['name'] . ' 修改了课程《' . $title . '》,有待审核',
                    $info['name'] . "\r\n课程串码：" . $lessonSn,
                    date('Y-m-d H:i'),
                    $info['telephone']
                );
            } else {
                if($ret) {
                    servTicket::sole($this->platform)->dealLesson($uid,$ret,dataTicket::STATUS_AGREE,'');
                }
            }

            return $ret;
        }
        return false;
    }

    public function open($lessonSn)
    {
        $info = \_\dataLesson::sole($this->platform)->fetchOne(['sn' => $lessonSn], ['id', 'tuid', 'title', 'brief', 'i_step']);
        $identifier = dataUser::sole($this->platform)->fetchOne(['id' => $info['tuid']], 'sn', 0);
        $infosetT = $infosetD = array(
            'introduction' => null,
            'notification' => null,
            'face_url' => null,
            'max_member_num' => null
        );

        //创建TIM授课房间
        $room = self::sn2room($lessonSn);
        $infosetT['group_id'] = $room['teach'];
        $infosetD['group_id'] = $room['discuss'];
        if ($adminAccount = servTIM::adminAccount()) {
            $resT = servTIM::sole($this->platform, servTIM::adminAccount())->tim()
                ->group_create_group2('ChatRoom', $room['teach'], $identifier, $infosetT, []);
            $resD = servTIM::sole($this->platform, servTIM::adminAccount())->tim()
                ->group_create_group2('ChatRoom', $room['discuss'], $identifier, $infosetD, []);
        } else {
            return "TIM未正确配置.";
        }

        //开课
        if ($resT['ErrorCode'] == 0 && $resD['ErrorCode'] == 0) {
//            servMpMsg::sole($this->platform)->sendOpenClass($lessonSn);
            \_\stats\servTeacher::sole($this->platform)->varLesson($info['tuid']);
            servTrigger::sole($this->platform)->onLessonOpen($lessonSn);
        }

        if (($resT['ErrorCode'] == 0 || $resT['ErrorCode'] == 10021) &&
            ($resD['ErrorCode'] == 0 || $resT['ErrorCode'] == 10021)
        ) {
            if ($info['i_step'] == \_\dataLesson::STEP_OPENED) {
                \_\dataLesson::sole($this->platform)->update(['i_step' => \_\dataLesson::STEP_ONLIVE], ['sn' => $lessonSn]);
            }
            dataLessonAccess::sole($this->platform)->append($info['id'], $info['tuid'], dataLessonAccess::EVENT_ACCESS, ['open']);
            return true;
        } else {
            return "$resT[ErrorInfo]|$resD[ErrorInfo]";
        }
    }

    public function getList($tuid)
    {
        $fields = ['id', 'sn', 'title', 'category', 'tags', 'i_form', 'price', 'quota', 'extra', 'i_step', 'tms_update', 'plan'];
        $resTrys = dataLesson::sole($this->platform)->fetchAll(['tuid' => $tuid, 'i_form' => dataLesson::FORM_TRY], ['id', 'sn', 'title', 'category', 'tags', 'i_form', 'extra','price', 'quota', 'i_step', 'tms_update', 'plan']);
        if ($resTrys) {
            foreach ($resTrys as &$resTry) {
                if ($resTry['i_step'] == dataLesson::STEP_FINISH || $resTry['i_step'] < dataLesson::STEP_OPENED) {
                    dataLesson::sole($this->platform)->update(['i_step' => dataLesson::STEP_OPENED], ['sn' => $resTry['sn']]);
                    $resTry['i_step'] = dataLesson::STEP_OPENED;
                }
            }
        }
        $resLe = dataLesson::sole($this->platform)->fetchAll(['tuid' => $tuid, 'i_form <>0'], ['id', 'sn', 'title', 'category', 'tags', 'i_form', 'price', 'quota', 'extra', 'i_step', 'tms_update', 'plan']);

        $guestLids = dataLessonAccess::sole($this->platform)->fetchAll(['uid' => $tuid, 'i_event' => dataLessonAccess::EVENT_INVITED], ['lesson_id'], null, 0);
        $resGuest = dataLesson::sole($this->platform)->fetchByIDs($guestLids, $fields);
        $res = array_merge($resTrys, $resLe, $resGuest);
        $lists = [];
        if ($res) {
            foreach ($res as $row) {
                $row['cover'] = \view::upload(self::sn2cover($row['sn']));
                $row['step'] = self::STEP_MAP[$row['i_step']];
                $row['plan'] = json_decode($row['plan'], true);
                $row['revenue'] = \_\stats\servLesson::sole($this->platform)->getIncome($row['id']);
                $row['stats'] = \_\stats\servLesson::sole($this->platform)->getSummary($row['id']);

                $row['extra'] = json_decode($row['extra'],true);
                $row['categoryInfo'] = servLessonSeries::sole($this->platform)->detail($row['category']);
                $lists[$row['i_step']][] = $row;


                unset($row['i_step']);
            }
        }
        $list = array_merge(
            $lists[dataLesson::STEP_ONLIVE]??[],
            $lists[dataLesson::STEP_REPOSE]??[],
            $lists[dataLesson::STEP_OPENED]??[],
            $lists[dataLesson::STEP_SUBMIT]??[],
            $lists[dataLesson::STEP_DENIED]??[],
            $lists[dataLesson::STEP_FINISH]??[],
            $lists[dataLesson::STEP_CLOSED]??[]
        );
        return $list;
    }


}