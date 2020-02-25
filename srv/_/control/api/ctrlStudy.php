<?php


namespace _\api;

use _\data;
use _\dataLessonRecord;
use _\servCache;
use _\servLesson;
use _\servLessonPrepare;
use _\servOrigin;

class ctrlStudy extends ctrlSigned
{
    const ERR_NO_LESSON_ACCESS = ['1', 'no access to lesson'];

    public function _GET_check()
    {
        $sn = $this->apiGET('sn');
        if (servLesson::sole($this->platform)->checkAccess($sn, $this->usn)) {
            $this->apiSuccess();
        } else {
            $this->apiFailure(self::ERR_NO_LESSON_ACCESS);
        }
    }

    public function _POST_browse()
    {
        $sn = $this->apiPOST('sn');
        $originKey = $this->apiPOST('origin', '_');
        $originId = servOrigin::sole($this->platform)->key2id($originKey);
        $res = servLesson::sole($this->platform)->browse($sn, $this->uid, $originId);
        $this->apiSuccess($res);
    }

    public function _POST_access()
    {
        $sn = $this->apiPOST('sn');
        $srv = servLesson::sole($this->platform);
        if ($srv->checkAccess($sn, $this->usn, $message)) {
            if ($message == 'audition') { // 试听
                $srv->audition($sn, $this->uid);
            } else {
                $srv->access($sn, $this->uid);
            }
            $this->apiSuccess($message);
        } else {
            $this->apiFailure(self::ERR_NO_LESSON_ACCESS);
        }
    }

    public function _GET_slice($type)
    {
        $sn = $this->apiGET('sn');
        $cursor = $this->apiGET('cursor', '-');
        $toward = $this->apiGET('toward', data::TOWARD_NEXT);
        $limit = $this->apiGET('limit', 10);
        $srvCache = servCache::sole($this->platform);
        $caKey = servCache::TAG_LESSON_AUTH . "$sn|$this->usn";
        $caRes = $srvCache->get($caKey);
        $servLesson = servLesson::sole($this->platform);
        if ($caRes === false) {
            $caRes = $servLesson->checkAccess($sn, $this->usn) ? 'yes' : 'no';
            $srvCache->set($caKey, $caRes, SECONDS_MINUTE);
        }
        if ($caRes != 'yes') {
            $this->apiFailure(self::ERR_NO_LESSON_ACCESS);
        }
        $ckey = servCache::TAG_LESSON_SLICE . "$type|$sn|$cursor|$toward|$limit";
        if (($data = $srvCache->getJson($ckey)) !== null) {
            $this->apiSuccess($data);
        }
        if ($type == 'preview') { // 备课预览
            $data = servLessonPrepare::sole($this->platform)->slicePreview($sn, $cursor, $toward, $limit);
            $this->apiSuccess($data);
        } else {
            $iType = array_search($type, servLesson::RECORD_FORM_MAP);
            $data = $servLesson->sliceRecord($iType, $sn, $cursor, $toward, $limit);
        }
        $srvCache->setJson($ckey, $data, (count($data) == $limit) ? SECONDS_MINUTE : 5);
        $this->apiSuccess($data);
    }

    public function _GET_article()
    {
        $sn = $this->apiGET('sn');
        $srvCache = servCache::sole($this->platform);
        $servLesson = servLesson::sole($this->platform);
        $caRes = $servLesson->checkAccess($sn, $this->usn) ? 'yes' : 'no';
        $ckey = servCache::TAG_LESSON_SLICE . "$sn|$caRes";
        if (($data = $srvCache->getJson($ckey)) !== null && 0) {
            $this->apiSuccess($data);
        }
        $data = $servLesson->sliceRecord(dataLessonRecord::FORM_VIEW, $sn, '-', data::TOWARD_NEXT, -1);
        if ($caRes != 'yes') { // 隐藏需要付费解锁的内容
            foreach ($data as $i => $row) {
                if (!$row['content']['free']) {
                    if (isset($locked)) { // 存在连续付费段落
                        unset($data[$i-1]);
                    } else {
                        $locked = [
                            'text' => 0,
                            'image' => 0,
                            'audio' => 0,
                            'video' => 0
                        ];
                    }
                    $locked['text'] += $row['content']['length'];
                    switch ($row['content']['type']) {
                        case 'image':
                            $locked['image'] ++;
                            break;
                        case 'audio':
                        case 'video':
                            $locked[$row['content']['type']] += $row['content']['duration'];
                            break;
                    }
                    unset($data[$i]['content']);
                    $data[$i]['locked'] = $locked;
                } else {
                    unset($locked);
                }
            }
        }
        $data = array_values($data);
        $srvCache->setJson($ckey, $data, SECONDS_HOUR);
        $this->apiSuccess($data);
    }

}