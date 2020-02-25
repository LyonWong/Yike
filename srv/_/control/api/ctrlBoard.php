<?php


namespace _\api;

use _\servLesson;
use _\servLessonBoard;
use _\servQiniu;
use _\servUser;
use _\unitBoardMessage;

class ctrlBoard extends ctrlSigned
{
    const ERR_NOT_ENROLLED = ['1', '报名后才能参与交流'];
    const ERR_NOT_BOARD_OWNER = ['2', 'not board owner'];
    const ERR_ILLEGAL_MESSAGE = ['3', '%s'];

    const MAX_TEXT_LENGTH = 10000;

    /**
     * 获取留言切片
     * @param string $mode all|prime
     */
    public function _GET_slice($mode='all')
    {
        $type = $this->apiGET('type');
        $sn = $this->apiGET('sn');
        $sort = $this->apiGET('sort', servLessonBoard::SORT_BY_WEIGHT);
        $cursor = $this->apiGET('cursor', '');
        $limit = $this->apiGET('limit', 10);

        $srvBoard = servLessonBoard::sole($this->platform);
        switch ($mode) {
            case 'prime': // 一级留言
                $filter = [
                    '_id' => 0,
                    'id_' => 0,
                ];
                break;
            default:
                $filter = [];
                break;
        }
        $list = $srvBoard->slice($type, $sn, $sort, $cursor, $limit, $filter);
        $list = $this->coself($list, $sn);
        $this->apiSuccess($list);
    }

    public function _GET_focus()
    {
        $sn = $this->apiGET('sn');
        $target = $this->apiGET('target');
        $srvBoard = servLessonBoard::sole($this->platform);
        $data = $srvBoard->focus($target);
        $res = $this->coself([$data], $sn);
        $this->apiSuccess($res[0]);
    }

    public function _GET_refer()
    {
        $sn = $this->apiGET('sn');
        $target = $this->apiGET('target');
        $cursor = $this->apiGET('cursor', '');
        $limit = $this->apiGET('limit', 10);
        $srvBoard = servLessonBoard::sole($this->platform);
        $list = $srvBoard->refer($target, $cursor, $limit);
        $list = $this->coself($list, $sn);
        $this->apiSuccess($list);
    }

    public function _GET_chain()
    {
        $sn = $this->apiGET('sn');
        $target = $this->apiGET('target');
        $cursor = $this->apiGET('cursor', '');
        $limit = $this->apiGET('limit', 10);
        $srvBoard = servLessonBoard::sole($this->platform);
        $list = $srvBoard->chain($target, $cursor, $limit);
        $list = $this->coself($list, $sn);
        $this->apiSuccess($list);
    }

    public function _GET_assoc()
    {
        $sn = $this->apiGET('sn');
        $target = $this->apiGET('target');
        $cursor = $this->apiGET('cursor', '');
        $limit = $this->apiGET('limit', 10);
        $srvBoard = servLessonBoard::sole($this->platform);
        $list = $srvBoard->assoc($target, $cursor, $limit);
        $list = $this->coself($list, $sn);
        $this->apiSuccess($list);
    }

    /**
     * 新写一条留言
     */
    public function _POST_comment()
    {
        $type = $this->apiPOST('type');
        $sn = $this->apiPOST('sn');
        $text = $this->apiPOST('text', '');
        $file = $this->apiPOST('file', '');
        $image = $this->apiPOST('image', []);
        $audio = $this->apiPOST('audio', '');

        if (strlen($text) > self::MAX_TEXT_LENGTH) {
            $this->apiFailure(self::ERR_ILLEGAL_MESSAGE, ['文本内容请勿超过'.self::MAX_TEXT_LENGTH.'字符']);
        }

        $lessonId = servLesson::sole($this->platform)->sn2id($sn);
        if (servLesson::sole($this->platform)->checkAccess($sn, $this->usn)) {
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

    /**
     * 回复别人的留言
     */
    public function _POST_reply()
    {
        $cursor = $this->apiPOST('cursor');
        $text = $this->apiPOST('text', '');
        $file = $this->apiPOST('file', '');
        $image = $this->apiPOST('image', []);
        $audio = $this->apiPOST('audio', '');

        if (strlen($text) > self::MAX_TEXT_LENGTH) {
            $this->apiFailure(self::ERR_ILLEGAL_MESSAGE, ['文本内容请勿超过'.self::MAX_TEXT_LENGTH.'字符']);
        }

        $brief = servLessonBoard::sole($this->platform)->brief($cursor);
        $lessonSn = servLesson::sole($this->platform)->id2sn($brief['lesson_id']);
        if (servLesson::sole($this->platform)->checkAccess($lessonSn, $this->usn)) {
            $message = unitBoardMessage::inst();
            $message->text = $text;
            $message->file = $file;
            $message->image = $image;
            $message->audio = $audio;
            $this->checkMessage($message);
//            $lessonOwner = servLesson::sole($this->platform)->isOwner($brief['lesson_id'], $this->uid);
            $res = servLessonBoard::sole($this->platform)->reply($cursor, $this->uid, $message);
            servLessonBoard::sole($this->platform)->replyMsg2user($this->uid, $cursor, $brief['lesson_id'], $text);
//            if ($lessonOwner) {
//                servLessonBoard::sole($this->platform)->replyMsg2user($this->uid, $cursor, $brief['lesson_id'], $text);
//            }
            $this->apiSuccess($res);
        } else {
            $this->apiFailure(self::ERR_NOT_ENROLLED);
        }
    }

    /**
     * 点赞
     */
    public function _POST_like()
    {
        $cursor = $this->apiPOST('cursor');
        $brief = servLessonBoard::sole($this->platform)->brief($cursor);
        $lessonSn = servLesson::sole($this->platform)->id2sn($brief['lesson_id']);
        if (servLesson::sole($this->platform)->checkAccess($lessonSn, $this->usn)) {
            $res = servLessonBoard::sole($this->platform)->like($cursor, $this->uid);
            $this->apiSuccess($res);
        } else {
            $this->apiFailure(self::ERR_NOT_ENROLLED);
        }
    }

    public function _POST_remove()
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

        if (strlen($text) > self::MAX_TEXT_LENGTH) {
            $this->apiFailure(self::ERR_ILLEGAL_MESSAGE, ['文本内容请勿超过'.self::MAX_TEXT_LENGTH.'字符']);
        }

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

    public function _DO_draft()
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

    protected function coself($list, $sn)
    {
        $srvBoard = servLessonBoard::sole($this->platform);
        $lessonInfo = servLesson::sole($this->platform)->sn2info($sn, ['id', 'tuid']);
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
                $item['menu'][] = 'remove';
            } else {
                $item['menu'][] = 'tipoff';
            }
            $data[$item['cursor']] = $item;
        }
        return array_values($data);
    }


    protected function checkMessage(unitBoardMessage $message)
    {
        if (mb_strlen($message->text) > 10000) {
            $this->apiFailure(self::ERR_ILLEGAL_MESSAGE, ['输入文字超长']);
        }
    }

}