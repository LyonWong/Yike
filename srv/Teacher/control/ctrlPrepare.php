<?php


namespace Teacher;


use _\weixin\serv;
use Core\library\Http;
use function Qiniu\base64_urlSafeEncode;
use _\config;
use _\servQiniu;

class ctrlPrepare extends ctrlSess
{
    const ERR_LESSON_ACCESS_DENIED = ['1', 'lesson access denied'];
    const ERR_ILLEGAL_CONTENT_TYPE = ['1', 'illegal content type'];
    const ERR_CONTENT_CANNOT_BE_EMPTY = ['1', 'content cannot be empty'];
    const ERR_CONTENT_SECURITY_CHECK = ['2', '含有违规%s内容'];

    private $lessonSn;

    public function runBefore()
    {
        if (!parent::runBefore()) {
            return false;
        }
        $this->lessonSn = $this->apiRequest('lesson_sn');
        if (!servLesson::sole($this->platform)->checkSpeak($this->lessonSn, $this->usn)) {
            $this->apiFailure(self::ERR_LESSON_ACCESS_DENIED);
        }
        return true;
    }

    public function _GET_slice()
    {
        $cursor = $this->apiGET('cursor', 0);
        $toward = $this->apiGET('toward', data::TOWARD_NEXT);
        $limit = $this->apiGET('limit', 1);
        $data = servLessonPrepare::sole($this->platform)->slice($this->lessonSn, $cursor, $toward, $limit);
        $this->apiSuccess($data);
    }

    public function _POST_release()
    {
        $res = servLessonPrepare::sole($this->platform)->releaseView($this->lessonSn);
        $this->apiSuccess($res);
    }

    public function _POST_create($type)
    {
        $content = $this->apiPOST('content');
        $note = $this->apiPOST('note', '');
        $compress = $this->apiPOST('compress', 0);
        $insert = $this->apiPOST('insert', 0);
        $update = $this->apiPOST('update', 0);
        if (!$content) {
            $this->apiFailure(self::ERR_CONTENT_CANNOT_BE_EMPTY);
        }
        /*
        if ($note) { // 文本内容检测
            $wxaAccess = serv::sole($this->platform)->getAccessToken('wxa');
            $res = Http::inst()->post('https://api.weixin.qq.com/wxa/msg_sec_check?access_token=' . $wxaAccess, json_encode([
                'content' => $note
            ], JSON_UNESCAPED_UNICODE));
            $dres = json_decode($res);
            if ($dres->errcode) {
                $this->apiFailure(self::ERR_CONTENT_SECURITY_CHECK, ['文本']);
            }
        }
        */
        switch ($type) {
            case 'text':
            case 'mark':
                break;
            case 'image':
                $qiniu = servQiniu::inst();
                $basename = basename($content);
                $target = "lesson/record/$basename";
                $qiniu->move($content, $target);
                $qiniu->deleteAfterDays($target, 0);
                $content = $target;
                break;
            case 'video':
                $qiniu = servQiniu::inst();
                $basename = basename($content);
                $target = "lesson/record/$basename";
                //视频预览图
                $fops = 'vframe/jpg/offset/1|saveas/' . base64_urlSafeEncode($qiniu->BUCKET() . ":$target-preview.jpg");
                $qiniu->persist($content, $fops);
                if ($compress) {
                    $vb = '240k';
                } else {
                    $vb = '1280k';
                }
                $watermark = '/wmImage/' . base64_urlSafeEncode(\view::upload('watermark!watermark'));
                $fops = "avthumb/m3u8/segtime/10/ab/128k/ar/44100/acodec/libfaac/r/30/vb/$vb/vcodec/libx264/stripmeta/0/noDomain/1" . $watermark . '|saveas/' . base64_urlSafeEncode($qiniu->BUCKET() . ":$target.m3u8");
                $qiniu->persist($content, $fops);
                $fops = 'avthumb/mp4|saveas/' . base64_urlSafeEncode($qiniu->BUCKET() . ":$target");
                $res = $qiniu->persist($content, $fops);
                if ($res[0]) {
                    $persistsId = $res[0];
                } else {
                    $this->apiFailure(self::ERR_UNDEFINED, ['Qiniu API Error'], $res[1]);
                }
//                } else {
//                    $qiniu->move($content, $target);
//                    $qiniu->deleteAfterDays($target, 0);
//                }

                $content = $target;
                break;
            case 'audio':
                $qiniu = servQiniu::inst();
                $basename = basename($content);
                $target = "lesson/record/$basename";
                $qiniu->move($content, $target);
                $qiniu->deleteAfterDays($target, 0);
                $content = [];
                $content['path'] = $target;
                $content['note'] = $note;
                break;
            default:
                $this->apiFailure(self::ERR_ILLEGAL_CONTENT_TYPE);
                break;
        }
        $srvPrepare = servLessonPrepare::sole($this->platform);

        $seqno = $srvPrepare->create($this->lessonSn, $type, $content);
        if ($seqno) {
            $data = $srvPrepare->fetch($this->lessonSn, $seqno);
            if ($type == 'video' && $compress) {
                $data['persistsId'] = $persistsId;
            }
            if ($insert) {
                $data['seqno'] = $srvPrepare->jump($this->lessonSn, $seqno, $insert);
            }
            if ($update) {
                $srvPrepare->swap($this->lessonSn, $seqno, $update);
                $srvPrepare->delete($this->lessonSn, $seqno);
                $data['seqno'] = $update;
            }
            $this->apiSuccess($data);
        } else {
            $this->apiFailure(self::ERR_UNDEFINED, ["failed to create `$type` content"]);
        }
    }

    public function _GET_video_check()
    {
        $pid = $this->apiGET('pid');
        $res = Http::inst()->get('http://api.qiniu.com/status/get/prefop?id=' . $pid);
        $res = json_decode($res);
        if ($res->code == 0) {
            $this->apiSuccess();
        } else {
            $this->apiResponse($res->code, $res->desc, $res);
        }
    }

    public function _POST_delete()
    {
        $cursor = $this->apiPOST('cursor');
        if (servLessonPrepare::sole($this->platform)->delete($this->lessonSn, $cursor)) {
            $this->apiSuccess();
        } else {
            $this->apiFailure(self::ERR_UNDEFINED, ["Failed to delete $this->lessonSn:$cursor"]);
        }
    }

    public function _POST_jump()
    {
        $cursor = $this->apiPOST('cursor');
        $before = $this->apiPOST('before');
        if ($seqno = servLessonPrepare::sole($this->platform)->jump($this->lessonSn, $cursor, $before)) {
            $this->apiSuccess($seqno);
        } else {
            $this->apiFailure(self::ERR_UNABLE_TO_EXECUTE);
        }
    }

    public function _POST_swap()
    {
        $cursorA = $this->apiPOST('cursor_a');
        $cursorB = $this->apiPOST('cursor_b');
        if (servLessonPrepare::sole($this->platform)->swap($this->lessonSn, $cursorA, $cursorB)) {
            $this->apiSuccess();
        } else {
            $this->apiFailure(self::ERR_UNABLE_TO_EXECUTE);
        }
    }

    public function _GET_draft()
    {
        $key = uniqid('draft/prepare/');
        $token = servQiniu::inst()->getUploadToken($key, ['deleteAfterDays' => 1, 'fsizeLimit' => 500 * servQiniu::SIZE_MB]);
        $data = [
            'upload' => config::load('qiniu', 'os', 'Upload'),
            'token' => $token,
            'key' => $key,
            'url' => \view::upload($key)
        ];
        $this->apiSuccess($data);

    }

//    public function _GET_update()
//    {
//        $lists = dataLessonPrepare::sole($this->platform)->fetchAll(['i_type' => dataLessonPrepare::TYPE_AUDIO], 'id,content');
//        foreach ($lists as &$list) {
////            $list = json_decode($list['content']);
//            $content['note'] = '';
//            $content['path'] = json_decode($list['content']);
//
//            dataLessonPrepare::sole($this->platform)->update(['content' => json_encode($content)], ['id' => $list['id']])->rowCount();
//        }
//        $this->apiSuccess($lists);
//    }

}