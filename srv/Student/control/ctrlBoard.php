<?php


namespace Student;


use _\dataLessonAccess;
use _\dataLessonBoard;
use _\servLessonBoard;
use _\servQiniu;
use _\unitBoardMessage;

class ctrlBoard extends ctrlSess
{
    const ERR_NOT_ENROLLED = ['1', 'please enroll first'];
    const ERR_NOT_BOARD_OWNER = ['2', 'not board owner'];
    const ERR_ILLEGAL_MESSAGE = ['3', '%s'];

    public function _GET_init()
    {
//        $lessonSn = $this->apiGET('lesson_sn');
        $data = [
            'type_map' => [
                'argue' => '讨论',
                'null' => '我的',
            ],
            'type_now' => servLessonBoard::TYPE_MAP[dataLessonBoard::TYPE_ARGUE]
        ];
        $this->apiSuccess($data);
    }
    

    public function _GET_slice()
    {
        $type = $this->apiGET('type');
        $lessonSn = $this->apiGET('lesson_sn');
        $sort = $this->apiGET('sort', servLessonBoard::SORT_BY_WEIGHT);
        $cursor = $this->apiGET('cursor', '');
        $limit = $this->apiGET('limit', 10);

        $this->contentProtect($lessonSn, $cursor);

        $defaultSort = [];
        if ($sort == 'default') {
            if (isset($defaultSort[$type])) {
                $sort = $defaultSort[$type];
            } else {
                $sort = 'weight';
            }
        }

        $srvBoard = servLessonBoard::sole($this->platform);
        if ($type == servLessonBoard::TYPE_MAP[dataLessonBoard::TYPE_NULL]) {
            $filter = ['uid' => $this->uid];
            $sort = servLessonBoard::SORT_BY_TIME_DESC;
        } else {
            $filter = [];
        }
        $list = $srvBoard->slice($type, $lessonSn, $sort, $cursor, $limit, $filter);
        $list = $this->coself($list, $lessonSn);
        if (empty($cursor) && empty($list) && $type == servLessonBoard::TYPE_MAP[dataLessonBoard::TYPE_ARGUE]) {
            $this->defaultContent();
        }
        $this->apiSuccess($list);
    }


    public function _DO_focus()
    {
        $lessonSn = $this->apiGET('lesson_sn');
        $target = $this->apiGET('target');
        $this->contentProtect($lessonSn, '');
        $srvBoard = servLessonBoard::sole($this->platform);
        $data = $srvBoard->focus($target);
        $res = $this->coself([$data], $lessonSn);
        $this->apiSuccess($res[0]);
    }

    public function _DO_refer()
    {
        $lessonSn = $this->apiGET('lesson_sn');
        $target = $this->apiGET('target');
        $cursor = $this->apiGET('cursor', '');
        $limit = $this->apiGET('limit', 10);
        $this->contentProtect($lessonSn, $cursor);
        $srvBoard = servLessonBoard::sole($this->platform);
        $list = $srvBoard->refer($target, $cursor, $limit);
        $list = $this->coself($list, $lessonSn);
        $this->apiSuccess($list);
    }

    public function _GET_chain()
    {
        $lessonSn = $this->apiGET('lesson_sn');
        $target = $this->apiGET('target');
        $cursor = $this->apiGET('cursor', '');
        $limit = $this->apiGET('limit', 10);
        $this->contentProtect($lessonSn, $cursor);
        $srvBoard = servLessonBoard::sole($this->platform);
        $list = $srvBoard->chain($target, $cursor, $limit);
        $list = $this->coself($list, $lessonSn);
        $this->apiSuccess($list);
    }

    public function _GET_assoc()
    {
        $lessonSn = $this->apiGET('lesson_sn');
        $target = $this->apiGET('target');
        $cursor = $this->apiGET('cursor', '');
        $limit = $this->apiGET('limit', 10);
        $this->contentProtect($lessonSn, $cursor);
        $srvBoard = servLessonBoard::sole($this->platform);
        $list = $srvBoard->assoc($target, $cursor, $limit);
        $list = $this->coself($list, $lessonSn);
        $this->apiSuccess($list);
    }

    public function _POST_comment()
    {
        $type = $this->apiPOST('type');
        $lessonSn = $this->apiPOST('lesson_sn');
        $text = $this->apiPOST('text', '');
        $file = $this->apiPOST('file', '');
        $image = $this->apiPOST('image', []);
        $audio = $this->apiPOST('audio', '');

        $lessonId = servLesson::sole($this->platform)->sn2id($lessonSn);
        if (servLesson::sole($this->platform)->checkAccess($lessonSn, $this->usn)) {
            $message = unitBoardMessage::inst();
            $message->text = $text;
            $message->file = $file;
            $message->image = $image;
            $message->audio = $audio;
            $this->checkMessage($message);
            $res = servLessonBoard::sole($this->platform)->comment($type, $lessonId, $this->uid, $message);
            $this->apiSuccess($res);
        } else {
            $this->apiFailure(self::ERR_NOT_ENROLLED);
        }
    }

    public function _POST_reply()
    {
        $cursor = $this->apiPOST('cursor');
        $text = $this->apiPOST('text', '');
        $file = $this->apiPOST('file', '');
        $image = $this->apiPOST('image', []);
        $audio = $this->apiPOST('audio', '');

        $brief = servLessonBoard::sole($this->platform)->brief($cursor);
        $lessonSn = servLesson::sole($this->platform)->id2sn($brief['lesson_id']);
        if (servLesson::sole($this->platform)->checkAccess($lessonSn, $this->usn)) {
            $message = unitBoardMessage::inst();
            $message->text = $text;
            $message->file = $file;
            $message->image = $image;
            $message->audio = $audio;
            $this->checkMessage($message);
            $lessonOwner = servLesson::sole($this->platform)->isOwner($brief['lesson_id'], $this->uid);
            $res = servLessonBoard::sole($this->platform)->reply($cursor, $this->uid, $message);
            if ($lessonOwner) {
                servLessonBoard::sole($this->platform)->replyMsg2user($this->uid, $cursor, $brief['lesson_id'], $text);
            }
            $this->apiSuccess($res);
        } else {
            $this->apiFailure(self::ERR_NOT_ENROLLED);
        }
    }

    public function _POST_like()
    {
        $cursor = $this->apiPOST('cursor');
        $brief = servLessonBoard::sole($this->platform)->brief($cursor);
        if (1 || servLesson::sole($this->platform)->isEnrolled($brief['lesson_id'], $this->uid)) {
            $res = servLessonBoard::sole($this->platform)->like($cursor, $this->uid);
            $this->apiSuccess($res);
        } else {
            $this->apiFailure(self::ERR_NOT_ENROLLED);
        }
    }

    public function _POST_delete()
    {
        $cursor = $this->apiPOST('cursor');
        $reason = $this->apiGET('reason', '');
        $brief = servLessonBoard::sole($this->platform)->brief($cursor);
        if ($brief && $brief['uid'] == $this->uid) {
            $res = servLessonBoard::sole($this->platform)->deleteByCursor($cursor, $this->uid, $reason);
            if ($res) {
                $this->apiSuccess();
            }
            $this->apiFailure(self::ERR_UNDEFINED);

        }
        $this->apiFailure(self::ERR_NOT_BOARD_OWNER);
    }

    public function _POST_tipoff()
    {
        $cursor = $this->apiPOST('cursor');
        $text = $this->apiPOST('text', '');
        $file = $this->apiPOST('file', '');
        $image = $this->apiPOST('image', []);
        $audio = $this->apiPOST('audio', '');

        $brief = servLessonBoard::sole($this->platform)->brief($cursor);
        $lessonSn = servLesson::sole($this->platform)->id2sn($brief['lesson_id']);
        if (servLesson::sole($this->platform)->checkAccess($lessonSn, $this->usn)) {
            $message = unitBoardMessage::inst();
            $message->text = $text;
            $message->file = $file;
            $message->image = $image;
            $message->audio = $audio;
            $this->checkMessage($message);
            $res = servLessonBoard::sole($this->platform)->tipoff($cursor, $this->uid, $message);
            $this->apiSuccess($res);
        } else {
            $this->apiFailure(self::ERR_NOT_ENROLLED);
        }
    }

    public function _GET_draft()
    {
        $key = uniqid('board/comment/');
        if ($name = $this->apiGET('name', '')) {
            $key .= "/$name";
        }
        $token = servQiniu::inst()->getUploadToken($key, ['fsizeLimit' => 500 * servQiniu::SIZE_MB]);
        $data = [
            'upload' => \config::load('qiniu', 'os', 'Upload', '', '_'),
            'token' => $token,
            'key' => $key,
            'url' => \view::upload($key)
        ];
        $this->apiSuccess($data);
    }

    protected function coself($list, $lessonSn)
    {
        $srvBoard = servLessonBoard::sole($this->platform);
        $lessonInfo = servLesson::sole($this->platform)->sn2info($lessonSn, ['id', 'tuid']);
        $tusn = servUser::sole($this->platform)->uid2usn($lessonInfo['tuid']);
        $coself = $srvBoard->coself($lessonInfo['id'], $this->uid);
        $data = [];
        foreach ($list as &$item) {
            switch ($item['user']['sn']) {
                case $this->usn:
                    $item['user']['label'] = 'self';
                    break;
                case $tusn:
                    $item['user']['label'] = 'teacher';
                    break;
                default:
                    $item['user']['label'] = null;
                    break;
            }
            $_anchor = $srvBoard->parseCursor($item['cursor']);
            if (isset($coself[$_anchor['id']])) {
                $item['self'] = $coself[$_anchor['id']];
            } else {
                $item['self'] = null;
            }
            $item['menu'] = [];
            if ($item['refer']) {
                $item['menu'][] = 'chain';
            }
            if ($item['stats']['reply'] ?? null) {
                $item['menu'][] = 'assoc';
            }
            if ($item['user']['label'] == 'self') {
                $item['menu'][] = 'delete';
            } else {
                $item['menu'][] = 'tipoff';
            }
            $data[$item['cursor']] = $item;
        }
        return array_values($data);
    }

    protected function contentProtect($lessonSn, $cursor)
    {
        if (!servLesson::sole($this->platform)->checkAccess($lessonSn, $this->usn)) {
            $row = [
                'user' => servUser::sole($this->platform)->uid2profile(1),
                'message' => [
                    'text' => "报名后才能参与交流"
                ],
                'stats' => [
                    'liked' => 0,
                    'reply' => 0,
                ],
                'tms_create' => '',
                'cursor' => '--',
                'self' => null,
            ];
            if ($cursor) {
                $this->apiSuccess([]);
            } else {
                $this->apiSuccess([$row]);
            }
        }
    }

    protected function defaultContent()
    {
        $row = [
            'user' => servUser::sole($this->platform)->uid2profile(1),
            'message' => [
                'text' => "亲爱的学员，欢迎来到交流区，
这里可以与讲师及其他学员沟通讨论，
赶快来发表你的想法吧。"
            ],
            'stats' => [
                'liked' => 0,
                'reply' => 0,
            ],
            'tms_create' => '',
            'cursor' => '--',
            'self' => null,
        ];
        $this->apiSuccess([$row]);
    }

    protected function checkMessage(unitBoardMessage $message)
    {
        if (mb_strlen($message->text) > 10000) {
            $this->apiFailure(self::ERR_ILLEGAL_MESSAGE, ['输入文字超长']);
        }
    }
}