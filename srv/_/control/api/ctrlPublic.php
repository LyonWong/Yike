<?php


namespace _\api;


use _\data;
use _\dataLessonRecord;
use _\servCache;
use _\servLesson;

class ctrlPublic extends ctrl_
{
    public function _DO_foo()
    {
        $this->apiSuccess('foo');
    }

    public function _DO_bar()
    {
        $this->apiSuccess('bar');
    }

    /**
     * 文章的公开可见部分
     */
    public function _DO_article()
    {
        $sn = $this->apiGET('sn');
        $srvCache = servCache::sole($this->platform);
        $servLesson = servLesson::sole($this->platform);
        $caRes = 'no';
        $ckey = servCache::TAG_LESSON_SLICE . "$sn|$caRes";
        if (($data = $srvCache->getJson($ckey)) !== null && 0) {
            $this->apiSuccess($data);
        }
        $data = $servLesson->sliceRecord(dataLessonRecord::FORM_VIEW, $sn, '-', data::TOWARD_NEXT, -1);
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
        $data = array_values($data);
        $srvCache->setJson($ckey, $data, SECONDS_HOUR);
        $this->apiSuccess($data);
    }

}