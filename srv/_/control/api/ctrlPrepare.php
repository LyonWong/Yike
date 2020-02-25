<?php


namespace _\api;


use _\config;
use _\data;
use _\dataLessonPrepare;
use _\servContentSecurity;
use _\servLesson;
use _\servLessonPrepare;
use _\servQiniu;
use _\weixin\serv;
use Core\library\Http;
use function Qiniu\base64_urlSafeEncode;

class ctrlPrepare extends ctrlSigned
{
    const ERR_EMPTY_CONTENT = ['1', '内容不能为空'];
    const ERR_CONTENT_SECURITY_CHECK = ['2', '含有违规内容'];

    protected $sn;

    public function runBefore()
    {
        if (!parent::runBefore()) {
            echo 'f';
            return false;
        }
        $this->sn = $this->apiRequest('sn');
        if (!servLesson::sole($this->platform)->checkSpeak($this->sn, $this->usn)) {
            $this->apiFailure(self::ERR_UNDEFINED, ['您没有编辑权限']);
        }
        return true;
    }

    public function _POST_update()
    {
        $sn = $this->apiPOST('sn');
        $cursor = $this->apiPOST('cursor', ''); // 更新点，为空则添加新纪录

        $pointer = $this->apiPOST('pointer', ''); // 插入点，默认为末尾
        $type = $this->apiPOST('type'); // 记录类型
        $note = $this->apiPOST('note', ''); // 文本内容
        $free = $this->apiPOST('free', false); // 默认需要付费解锁
        $draft = $this->apiPOST('draft', ''); // 媒体资源临时
        $length = $this->apiPOST('length', 0); // 文字长度
        $duration = $this->apiPOST('duration', 0); // 音视频时间
        $srv = servLessonPrepare::sole($this->platform);
        $qiniu = servQiniu::inst();

/*
// 检查文本内容是否合规
        if ($note) {
//            if (serv::sole($this->platform)->textSecCheck($note)) {
//                $this->apiFailure(self::ERR_CONTENT_SECURITY_CHECK, ['文本']);
//            }
            if ($illegal = servContentSecurity::sole($this->platform)->checkArticle($note)) {
                $this->apiFailure(self::ERR_CONTENT_SECURITY_CHECK, array_values($illegal));
            }
        }
		*/

        switch ($type) {
            case 'markdown': // 文本内容
            case 'bookmark': // 书签标记
                if (!$note) {
                    $this->apiFailure(self::ERR_EMPTY_CONTENT);
                }
                $content = [
                    'note' => $note
                ];
                break;
            case 'image': // 图片
            case 'audio': // 音频
                if ($draft) {
                    $basename = basename($draft);
                    $path = "lesson/record/$basename";
                    $qiniu->move($draft, $path);
                    $qiniu->deleteAfterDays($draft, 0);
                } else {
                    $path = null;
                }
                $content = [
                    'note' => $note,
                    'path' => $path
                ];
            break;
            case 'video': // 视频
                if ($draft) {
                    $videoBits = $this->apiPOST('videoBits', '240k'); // 普通:240k, 高清:1280k
                    $basename = basename($draft);
                    $path = "lesson/record/$basename";
                    // 视频预览图
                    $fops = 'vframe/jpg/offset/1|saveas/' . base64_urlSafeEncode($qiniu->BUCKET() . ":$path-preview.jpg");
                    $qiniu->persist($draft, $fops);
                    // 视频切片
                    $watermark = '/wmImage/' . base64_urlSafeEncode(\view::upload('watermark!watermark'));
                    $fops = "avthumb/m3u8/segtime/10/ab/128k/ar/44100/acodec/libfaac/r/30/vb/$videoBits/vcodec/libx264/stripmeta/0/noDomain/1" . $watermark . '|saveas/' . base64_urlSafeEncode($qiniu->BUCKET() . ":$path.m3u8");
                    $qiniu->persist($draft, $fops);
                    // 同时保留mp4
                    $fops = 'avthumb/mp4|saveas/' . base64_urlSafeEncode($qiniu->BUCKET() . ":$path");
                    $res = $qiniu->persist($draft, $fops);
                    if ($res[0]) {
                        $persistsId = $res[0];
                    } else {
                        $this->apiFailure(self::ERR_UNDEFINED, ['Qiniu API Error'], $res[1]);
                    }
                } else {
                    $path = null;
                }
                $content = [
                    'note' => $note,
                    'path' => $path,
                ];
                break;
            default:
                $this->apiFailure(self::ERR_UNDEFINED, ['Illegal Type']);
                exit;
        }
        $content['length'] = $length;
        $content['duration'] = $duration;
        $content = array_merge([ 'free' => $free , 'note' => $content['note']], array_filter($content));
        if ($cursor) {
            $srv->modify($sn, $cursor, $type, $content);
            $seqno = $cursor;
        } else {
            $seqno = $srv->create($sn, $type, $content);
            if ($pointer) {
                $seqno = $srv->jump($sn, $seqno, $pointer);
            }
        }
        $response = $srv->slicePreview($sn, $seqno, data::TOWARD_SELF, 1)[0] ?? [];
        $response['persistsId'] = $persistsId ?? null;
        $this->apiSuccess($response);
    }

    public function _POST_delete()
    {
        $cursor = $this->apiPOST('cursor');
        if (servLessonPrepare::sole($this->platform)->delete($this->sn, $cursor)) {
            $this->apiSuccess();
        } else {
            $this->apiFailure(self::ERR_UNDEFINED, ["Failed to delete $this->sn:$cursor"]);
        }
    }

    public function _GET_slice()
    {
        $cursor = $this->apiGET('cursor', 0);
        $toward = $this->apiGET('toward', data::TOWARD_NEXT);
        $limit = $this->apiGET('limit', 1);
        if (servLesson::sole($this->platform)->checkSpeak($this->sn, $this->usn)) {
            $data = servLessonPrepare::sole($this->platform)->slicePreview($this->sn, $cursor, $toward, $limit);
            $this->apiSuccess($data);
        } else {
            $this->apiFailure(self::ERR_UNDEFINED, ['No Access']);
        }
    }

    public function _POST_release()
    {}

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

}
