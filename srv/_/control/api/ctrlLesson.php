<?php


namespace _\api;


use _\config;
use _\dataBlock;
use _\dataRating;
use _\dataSettings;
use _\servBlock;
use _\servCache;
use _\servLesson;
use _\servLessonHub;
use _\servRating;
use _\servSettings;

class ctrlLesson extends ctrl_
{
    public function _DO_home()
    {
        $channel = $this->apiGET('channel', servBlock::TYPE_MAP[dataBlock::TYPE_HOME]);
        $srvCache = servCache::sole($this->platform);
        $ckey = servCache::TAG_LESSON_HOME.$channel;
        if ($data = $srvCache->getJson($ckey)) {
            $this->apiSuccess($data);
        }
        $srvBlock = servBlock::sole($this->platform);
        $srvHub = servLessonHub::sole($this->platform);
        $data = [];
        $sections = $srvBlock->list($channel);
        foreach ($sections as $section) {
            $_list = [];
            foreach ($section['extra']['list']  as $_sn) {
                $_profile = $srvHub->profile($_sn);
                $_list[] = [
                    'tsn' => $_sn,
                    'title' => $_profile['title'],
                    'profile' => $_profile,
                ];
            }
            $data[] = [
                'title' => $section['name'],
                'form' => $section['extra']['form'],
                'list' => $_list,
                'tag' => "BLOCK:$section[key]",
            ];
        }
        $srvCache->setJson($ckey, $data, SECONDS_MINUTE*10);
        $this->apiSuccess($data);
    }

    public function _GET_list()
    {
        $tag = $this->apiGET('tag', '');
        $cursor = $this->apiGET('cursor', '--');
        $limit = $this->apiGET('limit', 10);
        $data = servLessonHub::sole($this->platform)->slice($tag, $cursor, $limit);
        $this->apiSuccess($data);
    }

    public function _GET_search($mode)
    {
        $query = $this->apiGET('query', '');
        $tags = $this->apiGET('tags', []);
        $cursor = $this->apiGET('cursor', '--');
        $limit = $this->apiGET('limit', 10);
        $srv = servLessonHub::sole($this->platform);
        $data = $srv->search($query, $tags, $cursor, $limit);
        switch ($mode) {
            case 'prompt':
                $this->apiSuccess($data);
                break;
            case 'explore':
                foreach ($data as &$item) {
                    $item['profile'] = $srv->profile($item['tsn']);
                }
                \output::debug('search', ['ip' => \input::ip(), 'tags' => $tags, 'query' => $query, 'cursor' => $cursor], DEBUG_SLOT_NOTE, DEBUG_REPORT_LOG);
                $this->apiSuccess($data);
                break;
            default:
                break;
        }
    }

    public function _GET_tags()
    {
        $tags = servSettings::sole($this->platform)->tags(dataSettings::TYPE_LESSON_TAG);
        $this->apiSuccess($tags);
    }

    public function _DO_profile()
    {
        $sn = $this->apiGET('sn');
        $res = servLesson::sole($this->platform)->sn2profile($sn);
        $this->apiSuccess($res);
    }

    public function _DO_introduce()
    {
        $sn = $this->apiGET('sn');
        $res = servLesson::sole($this->platform)->sn2introduce($sn);
        $this->apiSuccess($res);
    }

    public function _GET_rating()
    {
        $sn = $this->apiGET('sn');
        $cursor = 0;
        $limit = 5;
        $toward = dataRating::TOWARD_PREVIOUS;
        $srvCache = servCache::sole($this->platform);
        $ckey = servCache::TAG_LESSON_RATE_LIST . "$sn|$cursor|$limit|$toward";
        if (($list = $srvCache->getJson($ckey)) === null) {
            $list = servRating::sole($this->platform)->rating($sn, $cursor, $limit, $toward);
            $srvCache->setJson($ckey, $list, SECONDS_MINUTE);
        }
        $data = [
            'stats' => servRating::sole($this->platform)->stats($sn),
            'list' => $list,
        ];
        $this->apiSuccess($data);
    }

    public function _GET_relative()
    {
        $sn = $this->apiGET('sn');
        $data = servLesson::sole($this->platform)->sn2relative($sn);
        $this->apiSuccess($data);
    }

    public function _GET_subview()
    {
        $sn = $this->apiGET('sn');
        $data = servLesson::sole($this->platform)->sn2subview($sn);
        $this->apiSuccess($data);
    }

    public function _GET_nearby()
    {
        $sn = $this->apiGET('sn');
        $res = servLesson::sole($this->platform)->sn2nearby($sn);
        $data = [
            'prev' => $res['prev'][0] ?? null,
            'next' => $res['next'][0] ?? null
        ];
        $this->apiSuccess(array_filter($data) ?: null);
    }

    public function _GET_conf()
    {
        $sn = $this->apiGET('sn');
        $conf = servLesson::sole($this->platform)->conf($sn);
        $this->apiSuccess($conf);
    }
}


