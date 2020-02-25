<?php


namespace _\api;


use _\dataLesson;
use _\dataUser;
use _\servLesson;
use _\servLessonHub;
use _\servLessonPrepare;
use _\servMpMsg;
use _\servQiniu;
use _\servTeacher;
use _\servUser;
use _\unitLesson;

class ctrlCreate extends ctrlSigned
{
    const ERR_UNREGISTERED = ['1', '尚未注册为讲师'];
    const ERR_ACCESS_DENIED = ['2', '没有访问权限'];

    public function runBefore()
    {
        $res = parent::runBefore();
        $teacherStatus = servTeacher::sole($this->platform)->stats($this->uid);
        if ($teacherStatus>0) {
            if ($sn = $this->apiRequest('sn', '')) {
                if (!servLesson::sole($this->platform)->checkSpeak($sn, $this->usn)) {
                    $this->apiFailure(self::ERR_ACCESS_DENIED);
                }
            }
            return $res;
        } else {
            $this->apiFailure(self::ERR_UNDEFINED);
            return false;
        }
    }

    public function _POST_post($type)
    {
        $unitLesson = new unitLesson();
        $unitLesson->category = $this->apiPOST('category', '');
        switch ($type) {
            case 'article':
                $unitLesson->iForm = dataLesson::FORM_ARTICLE;
                $profile = servLesson::sole($this->platform)->create($this->uid, $unitLesson);
                break;
            case 'column':
                $unitLesson->iForm = dataLesson::FORM_COLUMN;
                $unitLesson->dtmStart = date('Y-m-d');
                $profile = servLesson::sole($this->platform)->create($this->uid, $unitLesson);
                break;
            default:
                $profile = null;
        }
        $this->apiSuccess($profile);
    }

    public function _POST_modify()
    {
        $unitLesson = new unitLesson();
        $sn = $this->apiPOST('sn');
        $params = $this->apiRequest(null);
        foreach (['title'] as $attr) {
            if ($attr=='title' && mb_strlen($params['title'])>14) {
                $this->apiFailure(self::ERR_UNDEFINED, ['标题不能超过14字']);
            }
            $unitLesson->$attr = $params[$attr] ?? null;
        }
        servLesson::sole($this->platform)->edit($sn, $unitLesson);
        $profile = servLesson::sole($this->platform)->profile($sn);
        $this->apiSuccess($profile);
    }


    public function _POST_commit()
    {
        $unitLesson = new unitLesson();
        $sn = $this->apiPOST('sn');
        $indie = $this->apiPOST('indie', true); // 是否允许单独售卖
        $discuss = $this->apiPOST('discuss', true); // 是否开启留言板
//        $form = $this->apiPOST('form');
//        $overt = $this->apiPOST('overt'); // 是否公开，若false，则反转iForm
        $cover = $this->apiPOST('cover', ''); // false表示删除封面，''为忽略
        $commission = $this->apiPOST('commission', 0);
        $refundable = $this->apiPOST('refundable', true);
        $preemptive = $this->apiPOST('preemptive', false); // 限时收费
        $inform = $this->apiPOST('inform', false); // 公众号通知
        $params = $this->apiRequest(null);
        $params['price'] *= 100;
        if ($cover) {
            $qiniu = servQiniu::inst();
            $basename = basename($cover);
            $path = "course/cover/$basename";
            $qiniu->move($cover, $path);
            $qiniu->deleteAfterDays($cover, 0);
            $unitLesson->cover = $path;
        }
        if ($cover===false) {
            $unitLesson->cover = false;
        }
        foreach (['title', 'price'] as $attr) {
            $unitLesson->$attr = $params[$attr] ?? null;
        }
        /*
        $unitLesson->iForm = array_search($form, servLesson::FORM_MAP) * ($overt ? 1 : -1);
        if (!$unitLesson->iForm) {
            $this->apiFailure(self::ERR_UNDEFINED, ['illegal form']);
        }
        */
        $unitLesson->brief = $this->apiPOST('brief', ''); // 简介
        if (!$indie) { // 不能单卖的，价格和佣金继承专栏
            $info = servLesson::sole($this->platform)->sn2info($sn, ['category']);
            $cateinfo = servLesson::sole($this->platform)->sn2info($info['category'], "price,extra->'$.conf.commission' as commission");
            $commission = $cateinfo['commission'];
            $unitLesson->price = $cateinfo['price'];
        }
        servLesson::sole($this->platform)->edit($sn, $unitLesson);
        $conf = [
            'commission' => $commission, // 邀请佣金
            'refundable' => $refundable, // 允许退款
            'preemptive' => $preemptive, // 限时收费
            'inform' => $inform, // 公众号通知
            'indie' => $indie, // 独立付费
            'discuss' => $discuss, // 留言板
            'activity' => [
                'text' => '邀请有奖',
                'href' => "/promote/invite?sn=$sn"
            ],
            'sharing' => $params['sharing'] ?? null
        ];
        servLesson::sole($this->platform)->updateConf($sn, $conf);
        $profile = servLesson::sole($this->platform)->profile($sn);
        $this->apiSuccess($profile);
    }

    public function _POST_delete()
    {
        $sn = $this->apiPOST('sn');
        $res = servLesson::sole($this->platform)->delete($sn);
        if ($res) {
            $this->apiSuccess();
        } else {
            $this->apiFailure(self::ERR_UNDEFINED, ['Failed to delete']);
        }
    }

    public function _POST_release($form)
    {
        $sn = $this->apiPOST('sn');
        switch ($form) {
            case 'lesson':
                $res = null;
                break;
            case 'view':
            case 'article':
                $res = servLessonPrepare::sole($this->platform)->release($sn);
                break;
            case 'column':
                $res = servLesson::sole($this->platform)->step($sn, dataLesson::STEP_FINISH);

                break;
            default:
                $res = null;
                break;
        }
        if ($res) {
            $profile = servLessonHub::sole($this->platform)->target($sn);
            $tuid = servUser::sole($this->platform)->usn2uid($profile['teacher']['sn']);
            $telephone = servUser::sole($this->platform)->uid2info($tuid, "telephone");
            $href = "$_SERVER[REQUEST_SCHEME]://$_SERVER[HTTP_HOST]";
            servMpMsg::sole($this->platform)->callNotice(
                "请审核新发布的《$profile[title]》",
                $profile['teacher']['name'],
                date('Y-m-d H:i:s'),
                $telephone,
                [],
                "$href/home/{$profile['teacher']['sn']}/$form/$sn"
            );
        }
        $this->apiSuccess($res);
    }

    public function _GET_profile()
    {
        $sn = $this->apiGET('sn');
        $profile = servLesson::sole($this->platform)->profile($sn);
        if ($this->usn == $profile['teacher']['sn']) {
            $this->apiSuccess($profile);
        } else {
            $this->apiFailure(self::ERR_ACCESS_DENIED);
        }
    }

    public function _GET_list($opt=null)
    {
        switch ($opt) {
            case 'course':
                $filter = ['i_form in (?,?)' => [dataLesson::FORM_IM, dataLesson::FORM_VIEW]];
                break;
            case 'posts':
                $filter = ['i_form in (?,?)' => [
                    dataLesson::FORM_ARTICLE,
                    dataLesson::FORM_COLUMN,
                    ]
                ];
                break;
            default:
                $filter = [];
        }
        $res = servLessonHub::sole($this->platform)->created($this->uid, $filter);
        $this->apiSuccess($res);
    }

}